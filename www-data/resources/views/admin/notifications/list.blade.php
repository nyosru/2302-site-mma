@extends('layouts.contentLayoutMaster')

@section('title', 'Notification center')

@section('content')

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
                    <option value='' >All</option>


                    @foreach(\App\Models\App::all() as $app)
                        <option value="{{$app->id}}">{{$app->name}} [{{$app->id}}]</option>
                    @endforeach
                </select>

            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-12">
            <hr>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="mainTable" class="table table-striped table-bordered display" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th style="width: 100px">App id</th>
                                <th style="min-width: 200px">Message</th>
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

        function getSearchData(d){

            if(app_id_val !== undefined && app_id_val !== ""){
                d.app_id_val = app_id_val;
            }

            // console.log(date_range_val)

            if(date_range_val !== undefined && date_range_val !== ""){
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
        $('.pickatime').on('apply.daterangepicker', function(ev, picker) {
            let date = picker.startDate.format('MM-DD-YYYY h:mm:ss') + ' - ' + picker.endDate.format('MM-DD-YYYY h:mm:ss')
            $(this).val(date);

            date_range_val = date;

            table.ajax.reload();
        });

        $('.pickatime').on('cancel.daterangepicker', function(ev, picker) {
            date_range_val = undefined
            $(this).val('');

            table.ajax.reload();
        });

        var decodeEntities = (function() {
            // this prevents any overhead from creating the object each time
            var element = document.createElement('div');

            function decodeHTMLEntities (str) {
                if(str && typeof str === 'string') {
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
            pageLength: 10,
            ajax: {
                url: `{{route('admin.' . $route . '.listData')}}`,
                type: `GET`,
                data: getSearchData
            },
            columns: [
                { data: 'created_at', render: (_, p, row) => {

                        let bid = '';
                        try {
                            let data = JSON.parse(decodeEntities(row.request))
                            if(data.bid){
                                bid = data.bid;
                            }
                        } catch (e) {
                            console.log('bid parse error', e)
                        }

                    return '<small>' + moment(_).format('DD.MM.YYYY<br>HH:mm:ss') + '</small>';
                    } },
                { data: 'app_id', render: (_) => {
                    let app = APPS.find(e=>e.id === _)
                    if(!app){
                        return 'not found'
                    }
                    return `<p style="overflow-wrap: break-word; max-width: 150px;"><a target="_blank" href="/admin/apps/edit/${_}">[${_}]</a> ${app.app_id}</p>`
                }},
                { data: 'text', }
            ]
        })


        $('select#app_id').on('change', function(ev, picker) {

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
