@extends('layouts.contentLayoutMaster')

@section('title', 'Clients')

@section('content')
    <style type="text/css">
        @keyframes rotating {
            from {
                -ms-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -webkit-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            to {
                -ms-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -webkit-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

        .refresh {
            background: #FFFFFF;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.20);
            padding: 8px;
            border: 0;
            border-radius: 4px;
            width: 40px;
            height: 40px;
            margin: 0 auto;
            display: block;
        }

        .refresh .icon {
        }

        .refresh:hover {
            cursor: pointer;
        }

        .refresh:active .icon {
        }

        .refresh.loading {
            cursor: wait;
        }

        .refresh.loading .icon {
            -webkit-animation: rotating 1.2s linear infinite;
            -moz-animation: rotating 1.2s linear infinite;
            -ms-animation: rotating 1.2s linear infinite;
            -o-animation: rotating 1.2s linear infinite;
            animation: rotating 1.2s linear infinite;
        }

    </style>
    <script type="text/javascript" src="/js/core/jquery.js"></script>
    <div class="row">

        <div class="col-12 col-md-4">
            <label for="">Date</label>
            <fieldset class="form-group position-relative has-icon-left">
                <input type="text" class="form-control pickatime" id="date_range" placeholder="Select Time" value="">
                <div class="form-control-position">
                    <i class='bx bx-history'></i>
                </div>
            </fieldset>
        </div>
        <div class="col-12 col-md-4">
            <div class="form-group select-status">
                <label for="">App</label>
                <select name="app_id" id="app_id" class="form-control select2">
                    <option value=''>All</option>


                    @foreach(\App\Models\App::all() as $app)
                        <option value="{{$app->id}}">{{$app->name}} [{{$app->id}}]</option>
                    @endforeach
                </select>

            </div>
        </div>

    </div>
    <script>
        $(document).ready(function(){
            $('.refresh').on('click', function () {
                $(this).addClass('loading');
                $(this).attr('disabled','disabled');

                $('#mainTable').dataTable().on('xhr.dt', function (e, settings, json) {
                    $('.refresh').removeClass('loading');
                    $('.refresh').removeAttr('disabled', 'disabled');
                } );

                table.ajax.reload();
            });
        });

    </script>
    <div class="row">
        <div class="col-12">
            <hr>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div style="display: flex;align-items: center;position: absolute;margin-left: 180px;margin-top: 15px;z-index: 999">
                        <button class="refresh" style="width: 30px;height:30px;display: flex;align-items: center">
                            <svg class="icon" height="18" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="m23.8995816 10.3992354c0 .1000066-.1004184.1000066-.1004184.2000132 0 0 0 .1000066-.1004184.1000066-.1004184.1000066-.2008369.2000132-.3012553.2000132-.1004184.1000066-.3012552.1000066-.4016736.1000066h-6.0251046c-.6025105 0-1.0041841-.4000264-1.0041841-1.00006592 0-.60003954.4016736-1.00006591 1.0041841-1.00006591h3.5146443l-2.8117154-2.60017136c-.9037657-.90005932-1.9079498-1.50009886-3.0125523-1.90012523-2.0083682-.70004614-4.2175733-.60003954-6.12552305.30001977-2.0083682.90005932-3.41422594 2.50016478-4.11715481 4.5002966-.20083682.50003295-.80334728.80005275-1.30543933.60003954-.50209205-.10000659-.80334728-.70004613-.60251046-1.20007909.90376569-2.60017136 2.71129707-4.60030318 5.12133891-5.70037568 2.41004184-1.20007909 5.12133894-1.30008569 7.63179914-.40002637 1.4058578.50003296 2.7112971 1.30008569 3.7154812 2.40015819l3.0125523 2.70017795v-3.70024386c0-.60003955.4016736-1.00006591 1.0041841-1.00006591s1.0041841.40002636 1.0041841 1.00006591v6.00039545.10000662c0 .1000066 0 .2000132-.1004184.3000197zm-3.1129707 3.7002439c-.5020921-.2000132-1.1046025.1000066-1.3054394.6000396-.4016736 1.1000725-1.0041841 2.200145-1.9079497 3.0001977-1.4058578 1.5000989-3.5146444 2.3001516-5.623431 2.3001516-2.10878662 0-4.11715482-.8000527-5.72384938-2.4001582l-2.81171548-2.6001714h3.51464435c.60251046 0 1.0041841-.4000263 1.0041841-1.0000659 0-.6000395-.40167364-1.0000659-1.0041841-1.0000659h-6.0251046c-.10041841 0-.10041841 0-.20083682 0s-.10041841 0-.20083682 0c0 0-.10041841 0-.10041841.1000066-.10041841 0-.20083682.1000066-.20083682.2000132s0 .1000066-.10041841.1000066c0 .1000066-.10041841.1000066-.10041841.2000132v.2000131.1000066 6.0003955c0 .6000395.40167364 1.0000659 1.0041841 1.0000659s1.0041841-.4000264 1.0041841-1.0000659v-3.7002439l2.91213389 2.8001846c1.80753138 2.0001318 4.31799163 3.0001977 7.02928871 3.0001977 2.7112971 0 5.2217573-1.0000659 7.1297071-2.9001911 1.0041841-1.0000659 1.9079498-2.3001516 2.4100418-3.7002439.1004185-.6000395-.2008368-1.2000791-.7029288-1.3000857z"
                                    transform=""/>
                            </svg>
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table id="mainTable" class="table table-striped table-bordered display" cellspacing="0"
                               width="100%">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th style="width: 100px">App id</th>
                                <th>TID</th>
                                <th style="width: 100px">IP</th>
                                <th>Request</th>
                                <th>Type</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop


@section('footer-admin')

    <script>
        let APPS = JSON.parse(`{!! json_encode(\App\Models\App::withTrashed()->get()) !!}`)
    </script>

    <script>

        let app_id_val = undefined;
        let date_range_val = undefined;


        function getSearchData(d) {

            if (app_id_val !== undefined && app_id_val !== "") {
                d.app_id_val = app_id_val;
            }

            // console.log(date_range_val)

            if (date_range_val !== undefined && date_range_val !== "") {
                d.date_range_val = date_range_val;
            }
            // if(search_tag !== undefined && search_tag !== ""){
            //     d.search_tag = search_tag;
            // }

            return d;
        }

        $('.pickatime').daterangepicker({
            timePicker: true,
            timePickerIncrement: 30,
            timePicker24Hour: true,
            timePickerSeconds: true,
            locale: {
                format: 'DD-MM-YYYY h:mm:ss',
                cancelLabel: 'Clear'
            }
        });
        $('.pickatime').val('')
        $('.pickatime').on('apply.daterangepicker', function (ev, picker) {
            let date = picker.startDate.format('MM-DD-YYYY h:mm:ss') + ' - ' + picker.endDate.format('MM-DD-YYYY h:mm:ss')
            $(this).val(date);

            date_range_val = date;

            table.ajax.reload();
        });

        $('.pickatime').on('cancel.daterangepicker', function (ev, picker) {
            date_range_val = undefined
            $(this).val('');

            table.ajax.reload();
        });


        var decodeEntities = (function () {
            // this prevents any overhead from creating the object each time
            var element = document.createElement('div');

            function decodeHTMLEntities(str) {
                if (str && typeof str === 'string') {
                    // strip script/html tags
                    str = str.replace(/<script[^>]*>([\S\s]*?)<\/script>/gmi, '');
                    str = str.replace(/<\/?\w(?:[^"'>]|"[^"]*"|'[^']*')*>/gmi, '');
                    element.innerHTML = str;
                    str = element.textContent;
                    element.textContent = '';
                }

                return str;
            }

            return decodeHTMLEntities;
        })();

        const table = $('#mainTable').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 100,
            ajax: {
                url: `{{route('admin.' . $route . '.listData')}}`,
                type: `GET`,
                data: getSearchData
            },
            columns: [
                {
                    data: 'created_at', render: (_, p, row) => {

                        let bid = '';
                        try {
                            let data = JSON.parse(decodeEntities(row.request))
                            if (data.bid) {
                                bid = data.bid;
                            }
                        } catch (e) {
                            console.log('bid parse error', e)
                        }

                        return '<small>' + moment(_).format('DD.MM.YYYY<br>HH:mm:ss') + '</small>' +
                            (bid ? `<br /><a href="/admin/clients/logs/${bid}" target="_blank" class="btn btn-sm">History</a>` : '')
                    }
                },
                {
                    data: 'app_id', render: (_) => {
                        let app = APPS.find(e => e.id === _)
                        if (!app) {
                            return 'not found'
                        }
                        return `<p style="overflow-wrap: break-word; max-width: 150px;"><a target="_blank" href="/admin/apps/edit/${_}">[${_}]</a> ${app.app_id}</p>`
                    }
                },
                {data: 'tid',},
                {
                    data: 'ip', render: (_, p, row) => {
                        return `<p style="width: 110px;">
<img src="/images/flags/4x3/${row.country.toLocaleLowerCase()}.svg" width="25">
${row.country}
<p>${_}</p>
</p>`
                    }
                },
                {
                    data: 'request', render: (_, p, row) => {
                        let country = '';
                        try {
                            let data = JSON.parse(decodeEntities(_))
                            if (data.country) {
                                country = data.country.toLocaleLowerCase();
                            }
                        } catch (e) {
                            console.log('country parse error', e)
                        }

                        return `<p style="width: 300px;">
${country ? `<img src="/images/flags/4x3/${country}.svg" width="25"> ${country.toLocaleUpperCase()} SIM` : ``}

<p style="display: block; width: 300px">${_}</p>
Result: <p>${row.result}</p>
</p>`
                    }
                },
                {
                    data: 'click_type', render: (_, p, row) => {
                        let type = '';

                        switch (_) {
                            case 'l': {
                                type = 'launch';
                                break;
                            }
                            case 'd': {
                                type = 'download';
                                break;
                            }
                            case 'e': {
                                type = 'event';
                                break;
                            }
                        }
                        return `<p>
${type}
</p>`
                    }
                },
            ]
        })


        $('select#app_id').on('change', function (ev, picker) {

            app_id_val = $(this).val();

            table.ajax.reload();
        });

        // document.querySelector('input[name=complex]').addEventListener('change', e=>{
        //     search_complex = e.target.value;
        //
        //     $table.ajax.reload();
        // })


    </script>

    <style>
        td p.all {
            /*word-break: break-word;*/
        }
    </style>
@stop
