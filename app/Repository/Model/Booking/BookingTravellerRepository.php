<?php

namespace App\Repository\Model\Booking;

use App\Models\Booking\Booking;
use App\Models\Booking\BookingTraveller;
use App\Models\Customer\Customer;
use App\Models\Flight\FlightInventoryTour;
use App\Models\Location\Address;
use App\Models\Location\AddressParent;
use App\Repository\Abstracts\ModelRepository;

class BookingTravellerRepository extends ModelRepository
{
    private BookingTraveller $traveller;

    public function __construct(BookingTraveller $traveller)
    {
        $this->traveller = $traveller;
    }

    /**
     * @param Booking $booking
     * @param array $details
     * @return BookingTraveller
     */
    public static function create(Booking $booking, array $details): BookingTraveller
    {
        $traveller = BookingTravellerRepository::make($details);
        $booking->travellers()->save($traveller);
        return $traveller;
    }

    public static function make(array $details): BookingTraveller
    {
        $customer = array_key_exists('email_address', $details) ? Customer::whereEmailAddress($details['email_address']) : null;
        if (isset($customer)) {
            return BookingTraveller::make(['customer_id' => $customer->id,]);
        } else {
            $homeAddress = Address::create([
                'name' => ($details['first_name'] ?? '') . ($details['last_name'] ?? '') . ' - Home Address',
                'address_parent_id' => AddressParent::getParentId('customer'),
                'address_line_1' => $details['home_address_line_1'] ?? null,
                'address_line_2' => $details['home_address_line_2'] ?? null,
                'town' => $details['home_town'] ?? null,
                'region' => $details['home_region'] ?? null,
                'country_id' => $details['home_country_id'] ?? null,
                'postcode' => $details['home_postcode'] ?? null,
            ]);
            $billingAddress = Address::create([
                'name' => ($details['first_name'] ?? '') . ($details['last_name'] ?? '') . ' - Billing Address',
                'address_parent_id' => AddressParent::getParentId('customer'),
                'address_line_1' => $details['billing_address_line_1'] ?? null,
                'address_line_2' => $details['billing_address_line_2'] ?? null,
                'town' => $details['billing_town'] ?? null,
                'region' => $details['billing_region'] ?? null,
                'country_id' => $details['billing_country_id'] ?? null,
                'postcode' => $details['billing_postcode'] ?? null,
            ]);
            return BookingTraveller::make([
                'title' => $details['title'] ?? null,
                'first_name' => $details['first_name'] ?? null,
                'middle_names' => $details['middle_names'] ?? null,
                'last_name' => $details['last_name'] ?? null,
                'email_address' => $details['email_address'] ?? null,
                'date_of_birth' => $details['date_of_birth'] ?? null,
                'mobile_number' => $details['mobile_number'] ?? null,
                'home_address_id' => $homeAddress->id,
                'billing_address_id' => $billingAddress->id,
            ]);
        }
    }

    public function selectFlights(?FlightInventoryTour $inbound, ?FlightInventoryTour $outbound): void
    {
        $inbound?->repository->grantToTraveller($this->traveller);
        $outbound?->repository->grantToTraveller($this->traveller);
    }

    public function get(): BookingTraveller
    {
        return $this->traveller;
    }

    public function update(array $data): BookingTraveller
    {
        $this->traveller->update($data);
        $this->save();
        return $this->get();
    }

    public function save(): bool
    {
        return $this->traveller->save();
    }

    public function delete(): bool
    {
        return $this->traveller->delete();
    }

    public function isDeleted(): bool
    {
        return !isset($this->traveller);
    }

    public function __toString(): string
    {
        return "{$this->traveller->first_name} {$this->traveller->last_name} - {$this->traveller?->booking?->token}";
    }
}