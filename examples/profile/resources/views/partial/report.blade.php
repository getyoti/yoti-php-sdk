<table>
    @foreach ($report as $key => $value)
        <tr>
            <td>{{ $key }}</td>
            <td>
                <pre>
                    {{ $value }}
                </pre>
            </td>
        </tr>
    @endforeach
</table>