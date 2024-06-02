@php $id = $attributes->get('id', 'a' . \Str::uuid()); @endphp
<div class="{{ $attributes->has('width') ? 'col-xl-' . $attributes->get('width', 12) : '' }}" wire:ignore>
    @if($attributes->get('label') !== null)
        <label>{{ $attributes->get('label', "") ?? $slot }} @if($attributes->has('required')) <x-admin.required /> @endif</label>
    @endif
    <textarea id="{{$id}}" {{ $attributes->except(['id', 'width', 'value', 'label']) }}>{!! $attributes->get('value', "") !!}</textarea>
    <script type="text/javascript">
        ClassicEditor
            .create(document.querySelector('#{{$id}}'))
            .then(editor => {
                editor.model.document.on('change:data', () => {
                    @this.set('{{$attributes->get('name')}}', editor.getData());
                });
                window.addEventListener('updateValue', (data) => {
                    if (data.detail.key === '{{ $attributes->get('name') }}') {
                        editor.setData(data.detail.value);
                    }
                });
            })
            .catch(error => console.error(error));
    </script>
</div>