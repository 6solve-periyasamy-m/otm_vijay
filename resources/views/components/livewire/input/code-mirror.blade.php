@php $id = "b" . \Str::uuid(); @endphp
@pushonce('footer-stack')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/css/css.min.js"></script>
    {{-- Addons: --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/edit/closebrackets.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/edit/closetag.min.js"></script>
    {{-- Addons (fold): --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/fold/foldcode.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/fold/foldgutter.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/display/autorefresh.js"></script>
@endpushonce
<div>
    <textarea {{ $attributes->merge(['id' => $id,]) }}>{{ $slot }}</textarea>
    <script>
        $(document).ready(function () {
            let editor = CodeMirror.fromTextArea(document.getElementById('{{ $id }}'), {
                lineNumbers: true,
                mode: 'css',
                autoRefresh: true,
            });
            editor.refresh();
        });
    </script>
</div>
