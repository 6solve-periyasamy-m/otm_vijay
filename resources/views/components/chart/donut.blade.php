<div style="height: {{$size??400}}px;">
    <canvas id="{{ str_replace(' ', '', $name) }}"></canvas>
</div>
@push('footer-stack')
<script type="text/javascript" defer>
    new window.Chart(document.getElementById('{{ str_replace(' ', '', $name) }}').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: [@foreach ($labels as $label) '{{$label}}', @endforeach],
            datasets: [{
                label: 'Revenue by date',
                data: [@foreach ($values as $value) '{{$value}}', @endforeach],
                backgroundColor: [@foreach ($colors as $color) '{{$color}}', @endforeach],
            }]
        },
        options: {
            circumference: {{$half??false ? 180 : 360}},
            rotation: {{$half??false ? 90 : 0}},
            borderWidth: 5,
            responsive: true,
            maintainAspectRatio: false,
        }
    });
</script>
@endpush