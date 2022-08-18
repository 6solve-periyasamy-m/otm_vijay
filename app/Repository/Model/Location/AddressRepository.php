<?php

namespace App\Repository\Model\Location;

use App\Models\Location\Address;
use App\Repository\Abstracts\ModelRepository;
use Illuminate\Http\Request;

class AddressRepository extends ModelRepository
{
    private Address $address;

    public function __construct(Address $address)
    {
        $this->address = $address;
    }

    public static function getArrayFromGenericRequest(Request $request, string $name, int $addressParent, string $prefix = ''): array
    {
        return [
            'name' => $name,
            'address_parent_id' => $addressParent,
            'location_type_id' => $request->input($prefix . 'location_type_id'),
            'address_line_1' => $request->input($prefix . 'address_line_1'),
            'address_line_2' => $request->input($prefix . 'address_line_2'),
            'address_line_3' => $request->input($prefix . 'address_line_3'),
            'town' => $request->input($prefix . 'town'),
            'region' => $request->input($prefix . 'region'),
            'country_id' => $request->input($prefix . 'country_id'),
            'postcode' => $request->input($prefix . 'postcode'),
        ];
    }

    public function cloneToNew(int $parent, ?Address $to = null): Address
    {
        if (isset($toAddress)) {
            $data = $this->address->toArray();
            unset($data['id']);
            $data['address_parent_id'] = $parent;
            $to->update($data);
        } else {
            $to = $this->address->replicate();
            $to->address_parent_id = $parent;
        }
        $to->save();
        return $to;
    }

    public function update(array $data): Address
    {
        $this->address->update($data);
        $this->save();
        return $this->get();
    }

    public function save(): bool
    {
        return $this->address->save();
    }

    public function get(): Address
    {
        return $this->address;
    }

    public function delete(): bool
    {
        return $this->address->delete();
    }

    public function isDeleted(): bool
    {
        return $this->address->trashed();
    }

    public function __toString(): string
    {
        $address = "";
        if (isset($this->address->address_line_1)) $address .= $this->address->address_line_1;
        if (isset($this->address->address_line_2)) $address .= ", " . $this->address->address_line_2;
        if (isset($this->address->address_line_3)) $address .= ", " . $this->address->address_line_3;
        if (isset($this->address->town)) $address .= ", " . $this->address->town;
        if (isset($this->address->region)) $address .= ", " . $this->address->region;
        if (isset($this->address->country)) $address .= ", " . $this->address->country->name;
        if (isset($this->address->postcode)) $address .= ", " . $this->address->postcode;
        return empty($address) ? "Address details empty" : $address;
    }
}
