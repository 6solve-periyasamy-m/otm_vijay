<table style="width: 100%;">
    <tr>
        <td style="width: 20%; text-align: center; border: 1px solid black"><h2>{{ $accommodation->title }}</h2></td>
        <td style="width: 20%; text-align: center; border: 1px solid black"><h2>{{ $accommodation->audit_date }}</h2></td>
        <td style="width: 60%; text-align: center; border: 1px solid black"><h2>{{ $accommodation->address }}</h2></td>
    </tr>
</table>
{{ $accommodation->description }}
