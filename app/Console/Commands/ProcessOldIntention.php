<?php

namespace App\Console\Commands;

use App\Models\Order\Order;
use App\Models\Order\OrderCustomer;
use App\Models\Order\Payment\PaymentIntention;
use App\Repository\Abstracts\InventoryTourRepository;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CMD;

class ProcessOldIntention extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'intention:process-old {intention}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process an old intention';
    /**
     * @var PaymentIntention|null
     */
    private PaymentIntention|null $intention;
    private Order|null $order;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->intention = PaymentIntention::find($this->argument('intention'));
        if ($this->intention === null) {
            $this->error('Intention not found');
            return CMD::INVALID;
        }

        $this->order = Order::where('booking_reference', $this->intention->reference)->first();

        if ($this->order === null || $this->order->cancelled) {
            $this->error('Order not found or cancelled');
            return CMD::INVALID;
        }

        $this->info("Order found with reference {$this->order->booking_reference}. " . route('orders.view', ['order' => $this->order,]));

        foreach ($this->intention->data['additions'] ?? [] as $datum) {
            $this->processAdd($datum);
        }

        foreach ($this->intention->data['upgrades'] ?? [] as $datum) {
            $this->processUpgrade($datum);
        }

        return CMD::SUCCESS;
    }

    private function processAdd(array $data): bool
    {
        $owner = $this->getOrderCustomer($data['customer']);
        if ($owner === null) {
            $this->error('Owner not found in CID or OCID for add');
            return false;
        }

        $this->info('Owner is ' . $owner->customer->full_name);

        $component = InventoryTourRepository::getComponent($data['component'], $data['id']);
        if ($component === null) {
            $this->error('Component not found for add');
            return false;
        }
        if ($component->getOrderComponent($owner) !== null) {
            $this->error('Component Already owned');
            return true;
        }
        $component->grantToCustomer($owner);
        return true;
    }

    private function processUpgrade(array $data): bool
    {
        $owner = $this->getOrderCustomer($data['customer']);
        if ($owner === null) {
            $this->error('Owner not found in CID or OCID for add');
            return false;
        }

        $this->info('Owner is ' . $owner->customer->full_name);

        $from = InventoryTourRepository::getComponent($data['component'], $data['from']);
        $to = InventoryTourRepository::getComponent($data['component'], $data['to']);
        if ($from === null) {
            $this->error('Component not found for upgrade base');
            return false;
        }
        if ($to === null) {
            $this->error('Component not found for upgrade target');
            return false;
        }
        if ($from->getOrderComponent($owner) === null && $to->getOrderComponent($owner) !== null) {
            $this->error('Upgrade already applied');
            return true;
        }
        if ($from->getOrderComponent($owner) === null) {
            $this->error('Base no longer owned');
            return false;
        }
        if ($to->getOrderComponent($owner) !== null) {
            $this->error('Upgrade already owned');
            return false;
        }

        $from->getOrderComponent($owner)->delete();
        $to->grantToCustomer($owner);
        return true;
    }

    private function getOrderCustomer($id): OrderCustomer|null
    {
        $customer = $this->order->orderCustomers()->where('id', $id)->first();
        if ($customer !== null) {
            $this->info("Customer Found with OCID $id");
            return $customer;
        }
        $customer = $this->order->orderCustomers()->where('customer_id', $id)->first();
        if ($customer !== null) {
            $this->info("Customer Found with CID $id");
            return $customer;
        }
        return null;
    }
}
