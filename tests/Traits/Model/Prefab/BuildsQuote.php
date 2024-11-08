<?php

namespace Tests\Traits\Model\Prefab;

use App\Models\Accommodation\Accommodation;
use App\Models\Accommodation\BoardType;
use App\Models\Accommodation\RoomType;
use App\Models\Customer\Customer;
use App\Models\Helper\Enum\QuoteStatus;
use App\Models\Quote\Component\QuoteAccommodation;
use App\Models\Quote\Component\QuoteActivity;
use App\Models\Quote\Component\QuoteFlight;
use App\Models\Quote\Component\QuoteMerchandise;
use App\Models\Quote\Component\QuoteTransport;
use App\Models\Quote\Quote;
use App\Models\Quote\QuotePricePoint;
use App\Models\Quote\QuoteProspect;
use Carbon\Carbon;

/**
 * Builds the same quote each time, with specific data.
 * Useful if you need to test quote specifics rather than generalizations.
 * Quote Specifics:
 *
 *  Start: 30 days from now (or start param)
 *  Expires: 14 days before start
 *  Final Payment: 7 days before start
 *  End: 4 days from start
 *  Travellers: Lead (Paying/Travelling) + 2 paying, 0 free travelling
 *  Cost to company breakdown (Components: £50):
 *      5x Accommodation (1x Double, 4x Single)          = £ 250.00
 *      9x Activities    (3x Activities, 3x Travellers)  = £ 450.00
 *      6x Flights       (2x Flights, 3x Travellers)     = £ 300.00
 *      6x Transport     (2x Transport, 3x Travellers)   = £ 300.00
 *      3x Merchandise   (1x Merchandise, 3x Travellers) = £ 150.00
 *      Total                                            = £1450.00
 *  Per Person: £2500
 *  Deposit: 40% (£960)
 *  Installment: 40% (£960)
 *  Remaining: 20% (£480)
 *  Single Occupancy: £50
 */
trait BuildsQuote
{
    use BuildsInventory;

    public static array $presetQuoteFields = [
        'name' => 'Test Quote - Created by Builder',
        'description' => 'Test Quote - Description',
        'terms' => 'Test Quote - Terms and Conditions',
        'invoice_footer' => 'Test Quote - Invoice Footer',
        'internal_notes' => 'Test Quote - Internal Notes',
        'external_notes' => 'Test Quote - External Notes',
    ];

    /**
     * Returns the pre-fabricated bespoke quote
     *
     * @param Carbon|null $start
     * @return Quote
     */
    public function buildBespokeQuote(Carbon|null $start = null): Quote
    {
        // Build the default quote
        $start = $start ?? now()->setTime(10, 0, 0, 0)->addDays(30); // Unless there is a specific reason, we should probably always start the quote slightly in the future

        $prospect = QuoteProspect::create(['customer_id' => Customer::factory()->create()->id, 'travelling' => true, 'paying' => true, ]);

        $quote = Quote::create([
            'tour_id' => null,
            'order_id' => null,
            'tax_bracket_id' => null,
            'lead_traveller_id' => $prospect->id,
            'consultant_id' => null,
            'event_id' => null,
            'brand_id' => null,
            'revision' => 1,
            'reference' => 'TEST0101Q', // Will append the actual ID for testing reference lookup
            'deposit' => 20,
            'is_deposit_percentage' => true,
            'single_occupancy_surcharge' => 50,
            'final_payment' => $start->clone()->subDays(7), // Week before the start
            'date_from' => $start->clone(),
            'date_to' => $start->clone()->addDays(4),
            'paying' => 2,
            'travelling' => 0,
            'expires' => $start->clone()->subDays(14),
            'quote_status' => QuoteStatus::NOT_SENT,
            ...self::$presetQuoteFields,
        ]);
        QuotePricePoint::create(['quote_id' => $quote->id, 'quantity' => 1, 'price_per_person' => 2500,]);
        $quote->reference .= $quote->id;
        $quote->save();

        // Setup Accommodation (Double Room spanning whole tour, 4x Single rooms each night)
        $cStart = $start->clone()->setTime(12, 0, 0);
        $quote->accommodation()->save($this->buildQuoteAccommodation(2, $cStart->clone(), $cStart->clone()->addDays(4), 1));
        $singleRoom = $this->generateRoomType(1);
        $quote->accommodation()->save($this->buildQuoteAccommodation($singleRoom, $cStart->clone(), $cStart->clone()->addDays(1), 1));
        $quote->accommodation()->save($this->buildQuoteAccommodation($singleRoom, $cStart->clone()->addDays(1), $cStart->clone()->addDays(2), 1));
        $quote->accommodation()->save($this->buildQuoteAccommodation($singleRoom, $cStart->clone()->addDays(2), $cStart->clone()->addDays(3), 1));
        $quote->accommodation()->save($this->buildQuoteAccommodation($singleRoom, $cStart->clone()->addDays(3), $cStart->clone()->addDays(4), 1));

        // Setup Activities (3 activities, on 1st, 2nd and 3rd day, 1 hour long)
        $cStart = $start->clone()->setTime(17, 0, 0);
        $quote->activities()->save($this->buildQuoteActivity($cStart->clone(), $cStart->clone()->addHours(1)));
        $quote->activities()->save($this->buildQuoteActivity($cStart->clone()->addDays(1), $cStart->clone()->addDays(1)->addHours(1)));
        $quote->activities()->save($this->buildQuoteActivity($cStart->clone()->addDays(2), $cStart->clone()->addDays(2)->addHours(1)));

        // Setup Flights (2 flights, one at the start, one at the end)
        $cStart = $start->clone()->setTime(15, 0, 0);
        $quote->flights()->save($this->buildQuoteFlight($cStart->clone(), $cStart->clone()->addHours(1)));
        $quote->flights()->save($this->buildQuoteFlight($cStart->clone()->addDays(4), $cStart->clone()->addDays(2)->addHours(4)));

        // Setup Transports (2 transports, one at the start, one at the end)
        $cStart = $start->clone()->setTime(14, 0, 0);
        $quote->transport()->save($this->buildQuoteTransport($cStart->clone(), $cStart->clone()->addHours(1)));
        $quote->transport()->save($this->buildQuoteTransport($cStart->clone()->addDays(4), $cStart->clone()->addDays(2)->addHours(4)));

        // Setup Merchandise (1 merchandise)
        $quote->merchandise()->save($this->buildQuoteMerchandise());

        return $quote;
    }

    public function validateQuote(Quote $quote): void
    {
        $start = now()->setTime(10, 0,0 ,0);
        // Validate basic information
        foreach (self::$presetQuoteFields as $key => $field) { $this->assertEquals($field, $quote->$key); }
        // Validate basic dates
        $this->assertEquals($start->clone()->addDays(30)->unix(), $quote->date_from->unix());
        $this->assertEquals($start->clone()->addDays(30 + 4)->unix(), $quote->date_to->unix());
        $this->assertEquals($start->clone()->addDays(30 - 7)->unix(), $quote->final_payment->unix());
        $this->assertEquals($start->clone()->addDays(30 - 14)->unix(), $quote->expires->unix());
        // Validate Travellers
        $this->assertTrue($quote->leadTraveller->paying);
        $this->assertTrue($quote->leadTraveller->travelling);
        $this->assertEquals(2, $quote->paying);
        $this->assertEquals(0, $quote->travelling);
        // Validate Costing
        $this->assertEquals(2500, $quote->repository->getPricePerPerson(1 + 2)->price_per_person);
        $this->assertEquals(250, $quote->repository->getAccommodationCost(1 + 2));
        $this->assertEquals(450, $quote->repository->getActivityCost(1 + 2));
        $this->assertEquals(300, $quote->repository->getFlightCost(1 + 2));
        $this->assertEquals(300, $quote->repository->getTransportCost(1 + 2));
        $this->assertEquals(150, $quote->repository->getMerchandiseCost(1 + 2));
        $this->assertEquals(1450, $quote->repository->getTotalCostToCompany(1 + 2));
    }

    protected function buildQuoteAccommodation(RoomType|int $size, Carbon $start, Carbon $end, int|null $quantity = null): QuoteAccommodation
    {
        $inventory = $this->buildAccommodationInventory($size, $start, $end);
        return new QuoteAccommodation([
            'accommodation_inventory_id' => $inventory->id,
            'is_template' => true,
            'quantity' => $quantity,
            'price_shown' => true,
            'tour_component_type' => 'Included',
        ]);
    }

    protected function buildQuoteActivity(Carbon $start, Carbon $end, int|null $quantity = null): QuoteActivity
    {
        $inventory = $this->buildActivityInventory($start, $end);
        return new QuoteActivity([
            'activity_inventory_id' => $inventory->id,
            'quantity' => $quantity,
            'price_shown' => true,
            'tour_component_type' => 'Included',
        ]);
    }

    protected function buildQuoteFlight(Carbon $start, Carbon $end, int|null $quantity = null): QuoteFlight
    {
        $inventory = $this->buildFlightInventory($start, $end);
        return new QuoteFlight([
            'flight_inventory_id' => $inventory->id,
            'quantity' => $quantity,
            'price_shown' => true,
            'tour_component_type' => 'Included',
        ]);
    }

    protected function buildQuoteTransport(Carbon $start, Carbon $end, int|null $quantity = null): QuoteTransport
    {
        $inventory = $this->buildTransportInventory($start, $end);
        return new QuoteTransport([
            'transport_inventory_id' => $inventory->id,
            'quantity' => $quantity,
            'price_shown' => true,
            'tour_component_type' => 'Included',
        ]);
    }

    protected function buildQuoteMerchandise(int|null $quantity = null): QuoteMerchandise
    {
        $inventory = $this->buildMerchandiseInventory();
        return new QuoteMerchandise([
            'merchandise_inventory_id' => $inventory->id,
            'quantity' => $quantity,
            'price_shown' => true,
            'tour_component_type' => 'Included',
        ]);
    }
}
