<?php

namespace Tests\Traits\Model;

use App\Models\Transport\Operator;
use App\Models\Transport\Transport;
use App\Models\Transport\TransportInventory;
use App\Models\Transport\TransportType;
use App\Models\TravelClass;

trait TestsTransport
{
    public function generateTransport(?Operator $operator = null, ?TransportType $transportType = null): Transport
    {
        if (!isset($operator)) $operator = $this->generateOperator();
        if (!isset($transportType)) $transportType = $this->generateTransportType();
        $transport = Transport::factory()->makeOne();
        $transport->operator_id = $operator->id;
        $transport->transport_type_id = $transportType->id;
        $transport->save();
        return $transport;
    }

    public function generateTransportInventory(Transport $transport = null, TravelClass $travelClass = null, array $attributes = []): TransportInventory
    {
        if (!isset($travelClass)) $travelClass = TravelClass::factory()->create();
        if (!isset($transport)) $transport = $this->generateTransport();
        $inventory = TransportInventory::factory()->makeOne($attributes);
        $inventory->travel_class_id = $travelClass->id;
        $transport->transportInventory()->save($inventory);
        return $inventory;
    }

    public function generateOperator(): Operator
    {
        return Operator::factory()->create();
    }

    public function generateTransportType(): TransportType
    {
        return TransportType::factory()->create();
    }
}
