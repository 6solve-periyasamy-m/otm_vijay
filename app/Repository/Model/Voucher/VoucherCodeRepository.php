<?php

namespace App\Repository\Model\Voucher;

use App\Models\Voucher\VoucherCode;
use App\Repository\Abstracts\ModelRepository;
use Illuminate\Database\Eloquent\Model;
use Str;

class VoucherCodeRepository extends ModelRepository
{
    public function __construct(private VoucherCode $voucher) { }

    public function get(): Model
    {
        return $this->voucher;
    }

    public function update(array $data): Model
    {
        $data['code'] = Str::upper($data['code']);
        $this->voucher->update($data);
        $this->save();
        return $this->get();
    }

    public function save(): bool
    {
        $this->voucher->code = Str::upper($this->voucher->code);
        return $this->voucher->save();
    }

    public function delete(): bool
    {
        if ($this->voucher->orderVouchers()->count() > 0) {
            return false;
        }
        $this->voucher->orderVouchers()->delete();
        $this->voucher->results()->delete();
        return $this->voucher->delete();
    }

    public function isDeleted(): bool
    {
        return $this->voucher->id === null;
    }

    public function __toString(): string
    {
        return "{$this->voucher->name} - {$this->voucher->code}";
    }
}
