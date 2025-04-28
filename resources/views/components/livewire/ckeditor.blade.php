@php $id = $attributes->get('id', 'a' . \Str::uuid()); @endphp
<div class="{{ $attributes->has('width') ? 'col-xl-' . $attributes->get('width', 12) : '' }}">
    <label>
        {{ $attributes->get('label', "") ?? $slot }} @if($attributes->has('required')) <x-admin.required /> @endif
        @error($attributes->get('name')) <span class="text-danger">({{ $message }})</span> @enderror
    </label>
    <div wire:ignore>
        <textarea id="{{$id}}" {{ $attributes->except(['id', 'width', 'value', 'label']) }}>{!! $attributes->get('value', "") !!}</textarea>
    </div>
    <script type="text/javascript">
        ClassicEditor
            .create(document.querySelector('#{{ $id }}'), ckConfig)
            .then(editor => {
                @if(isset($_instance))
                editor.model.document.on('change:data', () => {
                    @this.set('{{$attributes->get('name')}}', editor.getData());
                });
                @endif
                window.addEventListener('updateValue', (data) => {
                    if (data.detail.key === '{{ $attributes->get('name') }}') {
                        editor.setData(data.detail.value);
                    }
                });
            })
            .catch(error => console.error(error));
    </script>
</div>
