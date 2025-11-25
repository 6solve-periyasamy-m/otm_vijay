<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\Booking\Booking;
use Exception;

class PurgeBookings extends Component
{
    public $totalCount = 0;
    public $progress = 0;
    public $deletedCount = 0;
    public $skippedCount = 0;
    public $isProcessing = false;
    public $forceStop = false;

    protected $listeners = ['purgeStep', 'startPurge', 'stopPurge'];

    public function render()
    {
        return view('livewire.admin.purge-bookings');
    }

    public function getNullLeadCount()
    {
        return DB::table('bookings')
            ->leftJoin('booking_travellers', 'booking_travellers.id', '=', 'bookings.lead_traveller_id')
            ->whereNull('booking_travellers.first_name')
            ->whereNull('booking_travellers.last_name')
            ->whereNull('booking_travellers.email_address')
            ->count();
    }

    public function startPurge()
    {
        $this->deletedCount = 0;
        $this->skippedCount = 0;
        $this->progress = 0;
        $this->forceStop = false;
        $this->totalCount = $this->getNullLeadCount();
        if ($this->totalCount === 0) {
            session()->flash('message', 'No bookings found to purge.');
            return;
        }
        $this->isProcessing = true;
        $this->dispatchBrowserEvent('purge-next-batch');
    }

    public function purgeStep()
    {
        if ($this->forceStop) {
            $this->isProcessing = false;
            session()->flash('message', "Process stopped. {$this->deletedCount} bookings deleted, {$this->skippedCount} skipped.");
            return;
        }

        $batch = DB::table('bookings')
            ->leftJoin('booking_travellers', 'booking_travellers.id', '=', 'bookings.lead_traveller_id')
            ->whereNull('booking_travellers.first_name')
            ->whereNull('booking_travellers.last_name')
            ->whereNull('booking_travellers.email_address')
            ->select('bookings.id')
            ->orderBy('bookings.id')
            ->limit(1000)
            ->get();

        if ($batch->isEmpty()) {
            $this->isProcessing = false;
            session()->flash(
                'message',
                "{$this->deletedCount} bookings deleted. {$this->skippedCount} skipped."
            );
            return;
        }

        foreach ($batch as $row) {
            try {
                Booking::find($row->id)?->delete();
                $this->deletedCount++;
            } catch (Exception $e) {
                $this->skippedCount++;
            }
            $this->progress++;
        }
        $this->dispatchBrowserEvent('purge-next-batch');
    }

    public function stopPurge()
    {
        $this->forceStop = true;
    }
}