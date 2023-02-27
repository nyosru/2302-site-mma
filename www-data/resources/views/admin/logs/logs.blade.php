@extends('layouts.contentLayoutMaster')

@section('content')
<div class="row">
    <div class="col-12">
        <h3 class="title">bid <code>{{$bid}}</code> logs</h3>
    </div>
    <div class="col-12">
        <div class="btn-group">
            <a href="{{route('admin.clients.deleteFromBid', [$bid])}}" onclick="return confirm('You a sure?')" class="btn btn-default">Delete from BannedID</a>
        </div>
        <hr>
    </div>
    <div class="col-12">

        <table class="table table-striped table-bordered display">
            <thead>
            <tr>
                <th>date</th>
                <th>type</th>
                <th>addition</th>
            </tr>
            </thead>
            <tbody>
            @foreach($logs as $itemLog)
                @switch(get_class($itemLog))
                    @case("App\Models\ClientLog")

                        <tr>
                            <td>{{\App\Facades\Time::applyTimezone(\Illuminate\Support\Carbon::make($itemLog->created_at))->format('d.m.Y H:s:i')}}</td>
                            <td>{{$itemLog->typeName()}}
                                @if(isset($itemLog->app))
                                    <small>{{$itemLog->app->name}} <a href="{{route('admin.apps.view', [$itemLog->app])}}" target="_blank">[{{$itemLog->app->id}}]</a></small>
                                @else
                                    <small>app deleted</small>
                                @endif

                            </td>
                            <td>


                                <table class="table table-responsive table-bordered">

            @foreach($itemLog->log as $logLine)
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

                            </td>
                        </tr>
                        @break

                    @case("App\Models\BannedId")

                        <tr class="table-danger">
                            <td>{{\App\Facades\Time::applyTimezone(\Illuminate\Support\Carbon::make($itemLog->created_at))->format('d.m.Y H:s:i')}}</td>
                            <td colspan="2">
                                client bid inserted to <code>BannedIds</code>
                            </td>

                        </tr>
                        @break

                    @default

                @endswitch
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
