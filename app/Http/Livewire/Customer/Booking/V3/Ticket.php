<?php

namespace App\Http\Livewire\Customer\Booking\V3;

use App\Http\Livewire\Abstract\V3BookingComponent;

class Ticket extends V3BookingComponent
{

    protected $listeners = ['currencyUpdated' => 'updateCurrency'];

    public function mount($tour = null, $booking = null, $quote = null)
    {
        parent::mount($tour, $booking, $quote);

        // foreach(\App\Repository\Model\Activity\ActivityInventoryRepository::getBetweenDates($tour->date_from, $tour->date_to, $tour->repository) as $inventory){
        //     if($inventory->component->activityType->name === 'Add On'){
        //         dd($inventory->component->activityType, $inventory->component->session);
        //         //dd($inventory->component->activityType)

        //     }
        //     //dd($inventory->component->activityType == 'Add On');
        // }
        // exit;
        //dd(\App\Repository\Model\Activity\ActivityInventoryRepository::getBetweenDates($tour->date_from, $tour->date_to, $tour->repository));
        //dd($this->quote->accommodationInventoryTours);
        //dd($this->quote->repository->getInclusions());

        // dd($this->quote->repository->getComponents());
        // foreach($this->quote->repository->getComponents() as $component){
        //     dd($component);
        // }
        // dd($this->quote->activities()->with('inventory')->get());
        // foreach($this->quote->activities()->with('inventory')->get() as $activityInventoryTour){
        //     //dd($activityInventoryTour->inventory->upgrades);
        //     foreach($activityInventoryTour->inventory->upgrades as $upgrade){
        //         echo "<pre>"; print_r($upgrade->upgrade->activityInventory->activity->name); echo "</prE>";
        //     }
        //     //dd($activityInventoryTour->inventory->component->name);
        //     //dd($activityInventoryTour->inventory->starts_at->format('d M Y'));
        //     //dd($activityInventoryTour->inventory->component->seating);
        //     // dd($activityInventoryTour->inventory->component->seating?->name);
        //     // dd($activityInventoryTour->tour_component_type);
        // }

        //dd($this->tour->activityInventoryTours()->with('inventory')->get());
        //12 /35dd($this->seatings);
        // Optionally set a default
        //$this->selectedSeating = $this->seatings->first()?->id;

        // //dd($this->tour->activityInventoryTours());
        
        // foreach ($tour->activityInventoryTours as $component) {
        //     foreach($component->upgrades as $upgrade){
        //         echo "<pre>"; print_r($upgrade->upgrade->activityInventory->activity->name); echo "</prE>";
        //     }
            
        //     //dd($component->tour_component_type);
        //     //dd($component->inventory->component->seating, $component->inventory->component->session);
        //     //dd($component->inventory->component->name, $component->inventory->starts_at->format('d M Y'), $component->inventory->ends_at->format('d M Y'));
        //     // dd($component->inventory->starts_at->format('d M Y'));
        //     // dd($component->inventory->ends_at->format('d M Y'));
        //     // dd($component->inventory->component->session);
        // }
        
        //dd($this->tour->activityInventoryTours()->where('is_bookable', '=', true)->get(), $this->tour->activityInventoryTours()->where('tour_component_type', '=', 'Included')->where('is_bookable', '=', true)->get());

        // foreach ($this->tour->activityInventoryTours()->where('tour_component_type', '=', 'Included')->where('is_bookable', '=', true)->get() as $inventoryTour) {
        //     dd($inventoryTour->inventory->component->seating, $inventoryTour->inventory->component->session);
        //     dd($inventoryTour);

        //     $name = $inventoryTour->inventory->component->name;
        //     $start_date = $inventoryTour->inventory->starts_at->format('d M Y');
        //     $end_date = $inventoryTour->inventory->ends_at->format('d M Y');
            
        //     $component = $inventoryTour->repository;
        //     dd($component->activityInventory);

        //     //$component->activityInventory->activity->name;
        //     // $inventoryTour->inventory->component->name;
        //     // $inventoryTour->inventory->starts_at->format('d M Y');
        //     // $inventoryTour->inventory->ends_at->format('d M Y');
            
        // }

    }

    public function back()
    {
        return redirect()->route('booking.v3.hotel', ['tour' => $this->tour->booking_form_url, 'booking' => $this->booking->token]);
    }

    public function advance()
    {
        return redirect()->route('booking.v3.inclusions', ['tour' => $this->tour->booking_form_url, 'booking' => $this->booking->token]);
    }

    public function render()
    {
        return view('livewire.customer.booking.v3.ticket');
    }
}