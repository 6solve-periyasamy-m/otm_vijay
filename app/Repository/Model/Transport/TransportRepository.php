<?php

namespace App\Repository\Model\Transport;

use App\Models\Transport\Transport;
use App\Repository\Abstracts\ModelRepository;
use App\Repository\Interfaces\Manifest\HasTransportManifest;
use App\Repository\Reporting\Manifest\TransportManifestRepository;
use Illuminate\Support\Collection;

class TransportRepository extends ModelRepository implements HasTransportManifest
{
    private Transport $transport;

    public function __construct(Transport $transport)
    {
        $this->transport = $transport;
    }

    public function get(): Transport
    {
        return $this->transport;
    }

    public function update(array $data): Transport
    {
        $this->transport->update($data);
        $this->save();
        return $this->get();
    }

    public function save(): bool
    {
        return $this->transport->save();
    }

    public function delete(): bool
    {
        return $this->transport->delete();
    }

    public function isDeleted(): bool
    {
        return $this->transport->trashed();
    }

    public function __toString(): string
    {
        return "{$this->transport->name} ({$this->transport->transportType}) ({$this->transport->departureAddress->name} to {$this->transport->arrivalAddress->name}) ({$this->transport->operator})";
    }

    public function getTransportManifest(): Collection|array
    {
        return $this->transport->orders()->with(TransportManifestRepository::getRelations())->get();
    }
}
