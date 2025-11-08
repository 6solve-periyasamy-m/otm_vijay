<?php

namespace App\Http\Livewire\Admin\Customer;

use App\Http\Livewire\Abstract\ActionColumn;
use App\Http\Livewire\Abstract\AddressColumn;
use App\Http\Livewire\SendsEvents;
use App\Models\Customer\Customer;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use App\Models\Customer\Organization;

class Table extends LivewireDatatable
{
    use SendsEvents;

    public $name = "customer-table";

    public function builder()
    {
        return Customer::query()
                ->leftJoin('addresses', 'customers.home_address_id', '=', 'addresses.id')
                ->leftJoin('countries', 'addresses.country_id', '=', 'countries.id')
                ->leftJoin('organizations', 'organizations.id', '=', 'customers.organization_id');
    }

    public function columns()
    {
        return [
            Column::raw('CONCAT(COALESCE(first_name, ""), " ", COALESCE(last_name, "")) AS name')
                ->label('Name')
                ->sortable()
                ->searchable(),
            Column::callback('customers.email_address', function ($email) {
                return !empty($email) ? $email : 'No Email Address';
            })
                ->label('Email')
                ->sortable()
                ->searchable(),
            Column::name('customers.mobile_number')
                ->label('Phone Number')
                ->sortable()
                ->searchable(),
            DateColumn::name('customers.date_of_birth')
                ->label('Date Of Birth')
                ->sortable()
                ->searchable(),
            BooleanColumn::raw('IF(ISNULL(customers.email_address), false, IF(ISNULL(customers.password), false, true)) AS registered')
                ->label('Status')
                ->sortable()
                ->searchable()
                ->filterable(),
            Column::name('organizations.name')
                ->label('Organization')
                ->sortable()
                ->searchable()
                ->filterable(Organization::pluck('name')->toArray()),
            ActionColumn::view('customer', 'customers.edit', 'customers.view'),
        ];
    }

    public function delete($id)
    {
        $customer = Customer::find($id);
        $success = $customer->repository->delete();
        if (!$success) {
            $this->toast('Customer Cannot Be Deleted', 'That customer has orders or quotes, so cannot be deleted', 'danger');
            return;
        }
        $this->toast('Customer Deleted Successfuly', 'Customer has been successfully deleted', 'success');
        $this->refreshTables();
        return;
    }
}
