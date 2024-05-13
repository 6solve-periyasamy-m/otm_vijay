<canvas id="{{ str_replace(' ', '', $name) }}"></canvas>
@push('footer-stack')
<script type="text/javascript" defer>
    new window.Chart(document.getElementById('{{ str_replace(' ', '', $name) }}').getContext('2d'), {
        type: 'line',
        data: {
            labels: [
                @foreach ($labels as $label) '{{$label}}', @endforeach
            ],
            datasets: [{
                label: '{{$name}}',
                data: [@foreach ($values as $value) '{{$value}}', @endforeach],
                fill: false,
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endpush
