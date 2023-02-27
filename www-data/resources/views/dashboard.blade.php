@extends('layouts.contentLayoutMaster')

@section('title', 'Dashboard')

@section('content')

    <form action="{{route('admin.dashboard')}}" method="get">
    <div class="row align-items-end">

        <div class="col-12 col-md-4">
            <label for="">Date</label>
            <fieldset class="form-group position-relative has-icon-left">
                <input type="text" class="form-control pickatime" id="date_range" name="date_range" placeholder="Select Time" value="{!! $date_start->format('d-m-Y H:mm:ss') !!} - {!! $date_end->format('d-m-Y H:mm:ss') !!}">
                <div class="form-control-position">
                    <i class='bx bx-history'></i>
                </div>
            </fieldset>
        </div>
        <div class="col-12 col-md-4">
            <div class="form-group select-status">
                <label for="">App</label>
                <select name="app_id" id="app_id" class="form-control select2">
                    <option value='all' >All</option>


                    @foreach(\App\Models\App::all() as $app)
                        <option value="{{$app->id}}" {{request()->input('app_id', 'all') == $app->id ? 'selected' : ''}}>{{$app->name}} [{{$app->id}}]</option>
                    @endforeach
                </select>

            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="form-group">
                <input type="submit" value="Show" class="btn btn-success btn-block">
            </div>
        </div>



    </div>

    </form>

    @if(request()->input('app_id', 'all') !== 'all')

        @php
            $resultItem = $result;
        @endphp
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <canvas id="myChart"></canvas>

                    <hr>
                </div>
                <div class="col-12">
                    <div class="card dashboard-app">
                        <div class="card-body">

                            <div class="row">
                                <div class="col-12">
                                    <p class="text-center">@include('parts.log-detail', ['app' => $resultItem['app']])</p>
                                </div>
                                <div class="col-6">
                                    <span class="t">Downloads</span>
                                    <span class="d">{{$resultItem['downloads_count']}}</span>
                                </div>
                                <div class="col-6">
                                    <span class="t">Launch</span>
                                    <span class="d">Result 1: {{$resultItem['launch_success_count']}}  ({{$resultItem['launch_count'] === 0 ? 0 : round((($resultItem['launch_success_count'] / $resultItem['launch_count'])) * 100, 2)}}%)</span>
                                    <span class="d">All: {{$resultItem['launch_count']}}</span>
                                </div>
                            </div>

                            <div class="float-load">
                                <i style="width: {{$resultItem['launch_count'] === 0 ? 0 : (1 - ($resultItem['launch_success_count'] / $resultItem['launch_count'])) * 100}}%"></i>
                                <b>Result 0 count: {{$resultItem['launch_count'] - $resultItem['launch_success_count']}} ( {{$resultItem['launch_count'] === 0 ? 0 : round((1 - ($resultItem['launch_success_count'] / $resultItem['launch_count'])) * 100, 2)}}%)</b>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @else
<div class="container">
    <div class="row">
        <div class="col-12">
            <hr>
        </div>

        @foreach($result as $resultItem)

            <div class="col-md-4">
                <div class="card dashboard-app">
                    <div class="card-body">
                        <span class="title">{{$resultItem['app']->name}} <a href="{{route('admin.apps.view', [$resultItem['app']])}}" target="_blank">[{{$resultItem['app']->id}}]</a></span>

                        <div class="row">
                            <div class="col-12">
                                <p class="text-center">@include('parts.log-detail', ['app' => $resultItem['app']])</p>
                            </div>

                            <div class="col-6">
                                <span class="t">Downloads</span>
                                <span class="d">{{$resultItem['downloads_count']}}</span>
                            </div>
                            <div class="col-6">
                                <span class="t">Launch</span>
                                <span class="d">Result 1: {{$resultItem['launch_success_count']}}  ({{$resultItem['launch_count'] === 0 ? 0 : round((($resultItem['launch_success_count'] / $resultItem['launch_count'])) * 100, 2)}}%)</span>
                                <span class="d">All: {{$resultItem['launch_count']}}</span>
                            </div>
                        </div>

                        <div class="float-load">
                            <i style="width: {{$resultItem['launch_count'] === 0 ? 0 : (1 - ($resultItem['launch_success_count'] / $resultItem['launch_count'])) * 100}}%"></i>
                            <b>Result 0 count: {{$resultItem['launch_count'] - $resultItem['launch_success_count']}} ( {{$resultItem['launch_count'] === 0 ? 0 : round((1 - ($resultItem['launch_success_count'] / $resultItem['launch_count'])) * 100, 2)}}%)</b>
                        </div>
                    </div>
                </div>
            </div>

        @endforeach

    </div>
</div>

    @endif
@endsection

@section('footer-admin')
    @if(request()->input('app_id', 'all') !== 'all')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>

        const accepts_data = JSON.parse(`{!! json_encode($result['analytics_accepts']) !!}`)
        const rejects_data = JSON.parse(`{!! json_encode($result['analytics_rejects']) !!}`)

        console.log(accepts_data, rejects_data)
        const ctx = document.getElementById('myChart');
        const myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: Object.keys(accepts_data),
                datasets: [{
                    label: 'Rejected',
                    data: Object.values(rejects_data),
                    backgroundColor: [
                        'red',
                    ],
                    borderColor: [
                        'rgba(255, 0, 0, 0.5)',
                    ],
                    borderWidth: 1
                },{
                    label: 'Accepted',
                    data: Object.values(accepts_data),
                    backgroundColor: [
                        'green',
                    ],
                    borderColor: [
                        'green',
                    ],
                    borderWidth: 1
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

    @endif

    <style>
        .dashboard-app {
            position: relative;
            overflow: hidden;
            padding-bottom: 20px;
        }
        .dashboard-app .float-load {
            position: absolute;
            background: green;
            left: 0;
            bottom: 0;
            width: 100%;
            height: 20px;


        }
        .dashboard-app .float-load i {
            position: absolute;
            background: red;
            left: 0;
            bottom: 0;
            height: 100%;
            z-index: 1;
        }
        .dashboard-app .float-load b {
            position: absolute;
            text-align: center;
            color: #fff;
            left: 0;
            bottom: 0;
            z-index: 2;
            width: 100%;
            height: 100%;
        }
        .dashboard-app .title {
            display: block;
            font-weight: 600;
            text-align: center;
            margin-bottom: 10px;

        }
        .dashboard-app .col-6 .t {
            text-align: center;
            display: block;
        }
        .dashboard-app .col-6 .d {
            display: block;
            text-align: center;
        }
    </style>

    <script>
        $('.pickatime').daterangepicker({
            timePicker: true,
            timePickerIncrement: 30,
            timePicker24Hour: true,
            timePickerSeconds: true,
            locale: {
                format: 'DD-MM-YYYY H:mm:ss',
                cancelLabel: 'Clear'
            }
        });
        // $('.pickatime').val('')
        $('.pickatime').on('apply.daterangepicker', function(ev, picker) {
            let date = picker.startDate.format('DD-MM-YYYY H:mm:ss') + ' - ' + picker.endDate.format('DD-MM-YYYY H:mm:ss')
            $(this).val(date);

            // date_range_val = date;

            // table.ajax.reload();
        });

        $('.pickatime').on('cancel.daterangepicker', function(ev, picker) {
            // date_range_val = undefined
            $(this).val('');

            // table.ajax.reload();
        });

    </script>
@endsection
