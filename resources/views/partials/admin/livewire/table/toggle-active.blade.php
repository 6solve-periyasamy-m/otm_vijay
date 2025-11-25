<div class="form-check form-switch d-flex justify-content-center align-items-center">
    <input 
        class="form-check-input custom-switch-size" 
        type="checkbox" 
        id="toggleSwitch{{ $id }}" 
        wire:click="$emit('toggleActive', {{ $id }})"
        {{ $is_active ? 'checked' : '' }}
    >
    <label class="form-check-label ms-2" for="toggleSwitch{{ $id }}"></label>
</div>
