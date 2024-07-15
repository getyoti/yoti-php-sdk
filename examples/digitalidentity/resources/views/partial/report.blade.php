@if (isset($key) && is_array($key))
@foreach ($report as $key => $value)
    <table>
        <thead>
        <tr>
            <td>
                <H2>{{ $key }}</H2>
            </td>
        </tr>
        </thead>
        <tbody>
        @foreach ($value as $name => $result)
            @if (is_array($result))
                @foreach ($result as $data => $view)
                    @if (is_array($view))
                        @foreach ($view as $key2 => $value2)
                            @if (is_array($value2))
                                {{json_encode($value2)}}
                            @else
                                <tr>
                                    <td><b>{{ $key2 }}</b><br>{{ $value2 }}</td>
                                </tr>
                            @endif
                        @endforeach
                    @else
                        <tr>
                            <td><b>{{ $data }}</b><br>{{ $view }}</td>
                        </tr>
                    @endif
                @endforeach
            @else
                <tr>
                        <td><b>{{ $name }}</b><br>{{ $result }}</td>
                </tr>
            @endif
        @endforeach

        </tbody>
    </table>
    @endforeach
@else
    <table>
        @foreach ($report as $key => $value)
            <tr>
                <td>
                    {{ $key }}<br/>
                    <pre>
                        {!! json_encode($value, JSON_PRETTY_PRINT) !!}
                    </pre>
                </td>
            </tr>
        @endforeach
    </table>
@endif
