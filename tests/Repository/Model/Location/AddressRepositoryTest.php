<?php

namespace Tests\Repository\Model\Location;

use App\Models\Location\Address;
use Tests\Bases\DatabaseTestCase;

class AddressRepositoryTest extends DatabaseTestCase
{

    public function testCloneToNew()
    {
        $address = Address::factory()->create();
        $this->compareAddresses($address, $address->repository->cloneToNew($address->parent), false);
        $address2 = Address::factory()->create();
        $cloned = $address->repository->cloneToNew($address->parent, $address2);
        $this->compareAddresses($address, $cloned, false);
        $this->assertEquals($address2->id, $cloned->id);
    }

    private function compareAddresses(Address $a, Address $b, bool $matchIds = false): void
    {
        $this->assertEquals($a->id === $b->id, $matchIds);
        $this->assertEquals($a->parent, $b->parent);
        $this->assertEquals($a->address_line_1, $b->address_line_1);
        $this->assertEquals($a->address_line_2, $b->address_line_2);
        $this->assertEquals($a->address_line_3, $b->address_line_3);
        $this->assertEquals($a->town, $b->town);
        $this->assertEquals($a->region, $b->region);
        $this->assertEquals($a->country_id, $b->country_id);
        $this->assertEquals($a->postcode, $b->postcode);
        $this->assertEquals($a->location_type_id, $b->location_type_id);
    }
}
