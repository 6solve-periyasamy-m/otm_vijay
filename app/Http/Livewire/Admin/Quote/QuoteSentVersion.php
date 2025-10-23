<?php

namespace App\Http\Livewire\Admin\Quote;

use App\Http\Livewire\Abstract\LivewireForm;
use Livewire\Component;
use App\Models\Quote\SentQuote;

class QuoteSentVersion extends Component
{
    use LivewireForm;

    public $sentId;
    public $sentQuote;
    public $showModal = false;

    protected $listeners = ['showSentQuoteInfo' => 'loadSentQuote'];

    public function loadSentQuote($id)
    {
        $this->sentId = $id;
        $this->sentQuote = SentQuote::with(['quote'])->findOrFail($id);

        // $attachment = $this->sentQuote->additional_attachments;

        // $filename = basename($attachment);
        // $path = 'livewire-tmp/' . $filename;

        // dd($attachment, $filename, $path);
        // Storage::exists($path);


        $this->showModal = true;
        
        // Dispatch browser event to open the modal
        $this->dispatchBrowserEvent('open-sent-info-modal');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->sentQuote = null;
        $this->sentId = null;
    }

    public function render()
    {
        return view('livewire.admin.quote.quote-sent-version');
    }
}