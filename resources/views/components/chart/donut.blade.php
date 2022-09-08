<canvas id="{{ str_replace(' ', '', $name) }}" width="400" height="400"></canvas>
<script type="text/javascript" defer>
    new Chart(document.getElementById('{{ str_replace(' ', '', $name) }}').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: [@foreach ($labels as $label) '{{$label}}', @endforeach],
            datasets: [{
                label: 'Revenue by date',
                data: [@foreach ($values as $value) '{{$value}}', @endforeach],
                backgroundColor: [@foreach (random_colors(sizeof($labels)) as $color) '{{$color}}', @endforeach],
            }]
        },
    });
</script>
