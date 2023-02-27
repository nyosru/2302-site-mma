<table class="table table-responsive table-bordered table-sm" style="min-width: 200px">

    @foreach($logs as $logLine)
        @php
            $arr = explode(': ', $logLine);
        @endphp

        @switch($arr[0])
            @case('bannedID')
            <tr>
                <td>{{$arr[0]}}</td>
                <td style="max-width: 200px; word-break: break-all;">
                    @if($arr[1] == "false")
                        <span class="text-success">Not found in DB</span>
                    @else
                        <span class="text-danger">Found in DB</span>
                        <pre>{{$arr[1]}}</pre>
                    @endif

                </td>
            </tr>
            @break
            @case('created bannedId')
            <td colspan="2" style="max-width: 200px; word-break: break-all;"><span class="text-danger">created bannedId, this bid now in banned list</span></td>
            @break
            @case('banned previously by bannedId')
            <td colspan="2" style="max-width: 200px; word-break: break-all;"><span class="text-danger">bid founded in banned list, and not allowed to launch</span></td>
            @break
            @case('result productionUrl')
            @case('result bannerUrl')
            <tr>
                <td>{{$arr[0]}}</td>
                <td style="max-width: 200px; word-break: break-all;">
                    <pre>{{$arr[1]}}</pre>

                </td>
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
