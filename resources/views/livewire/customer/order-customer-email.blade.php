<p class="guest_email">
    <input type="email" wire:model="email" wire:blur="updateEmail" placeholder="ENTER EMAIL ADDRESS" 
        class="@error('email') input-error @elseif(session()->has('success_'.$orderCustomer->id)) input-success @endif"
    required>
    @error('email')  <span class="input-error-message">{{ $message }}</span>  @enderror
</p>