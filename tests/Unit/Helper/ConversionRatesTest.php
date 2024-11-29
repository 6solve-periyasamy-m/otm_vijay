<?php

namespace Tests\Unit\Helper;

use App\Models\Location\Currency;
use App\Models\System\ConversionRate;
use Settings;
use Tests\Bases\DatabaseTestCase;

class ConversionRatesTest extends DatabaseTestCase
{
    public function test_fx_convert(): void
    {
        $currency1 = Currency::factory()->create();
        $currency2 = Currency::factory()->create();
        $systemCurrency = Currency::factory()->create();
        Settings::set('system.currency', $systemCurrency->code);
        // Validate returns null if null passed
        $this->assertNull(fx_convert(null));

        // Validate returns same if nothing else is passed
        $this->assertEquals(10, fx_convert(10));

        // Validate returns same if no conversion rate is set
        $this->assertEquals(10, fx_convert(10, $currency1, $currency2));

        // Validate returns correctly if rate passed manually
        $this->assertEquals(20, fx_convert(10, $currency1, $currency2, 2));

        // Validate looks up rates correctly
        ConversionRate::create(['from_currency_id' => $currency1->id, 'to_currency_id' => $currency2->id, 'rate' => 2, ]);
        ConversionRate::create(['from_currency_id' => $currency2->id, 'to_currency_id' => $currency1->id, 'rate' => 0.5, ]);
        ConversionRate::create(['from_currency_id' => $currency1->id, 'to_currency_id' => $systemCurrency->id, 'rate' => 3, ]);
        ConversionRate::create(['from_currency_id' => $currency2->id, 'to_currency_id' => $systemCurrency->id, 'rate' => 4, ]);

        $this->assertEquals(20, fx_convert(10, $currency1, $currency2));
        $this->assertEquals(5, fx_convert(10, $currency2, $currency1));

        // Manually passed rates should override lookup
        $this->assertEquals(30, fx_convert(10, $currency1, $currency2, 3));
        $this->assertEquals(30, fx_convert(10, $currency2, $currency1, 3));

        // Passing codes instead of currencies should also work
        $this->assertEquals(20, fx_convert(10, $currency1->code, $currency2->code));
        $this->assertEquals(5, fx_convert(10, $currency2->code, $currency1->code));

        // If no second currency is passed, default to system
        $this->assertEquals(30, fx_convert(10, $currency1->code));
        $this->assertEquals(40, fx_convert(10, $currency2->code));
    }
}
