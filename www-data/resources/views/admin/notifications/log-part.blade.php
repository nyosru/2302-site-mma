<table class="table table-responsive table-bordered">

    @foreach($logs as $logLine)
        @php
            $arr = explode(': ', $logLine);
        @endphp

        @switch($arr[0])
            @case('bannedID')
            <tr>
                <td>{{$arr[0]}}</td>
                <td style="max-width: 200px; word-break: break-all;"><code>{{$arr[1]}}</code></td>
            </tr>
            @break

            @default
            <tr>
                @if(count($arr) == 1)
                    <td colspan="2" style="max-width: 200px; word-break: break-all;">{{$logLine}}</td>
                @else
                    <td>{{$arr[0]}}</td>
                    <td style="max-width: 200px; word-break: break-all;">{{$arr[1]}}</td>
                @endif
            </tr>
        @endswitch
    @endforeach


</table>
