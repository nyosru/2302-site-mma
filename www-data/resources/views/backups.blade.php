@extends('layouts.contentLayoutMaster')

@section('title', 'Backups')


@section('content')
<div class="row">
    <div class="col-12">
        <table class="table table-responsive table-bordered">
            <thead>
            <th>Name</th>
            <th>Disk</th>
            <th>Reachable</th>
            <th>Healthy</th>
            <th># of backups</th>
            <th>Newest backup</th>
            <th>Used storage</th>
            </thead>
            <tbody>
            @foreach($rows as $row)
                <tr>
                    @foreach($row as $item)
                        <td>{{$item}}</td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <table class="table table-responsive table-bordered">
            <thead>
            <th>Name</th>
            <th>Path</th>
            </thead>
            <tbody>
            @foreach(Storage::disk('local')->files('laravel-backup') as $row)
                <tr>
                    <td>{{explode('/', $row)[1]}}</td>
                    <td>{{Storage::disk('local')->path($row)}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
