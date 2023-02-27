@extends('layouts.contentLayoutMaster')

@section('title', 'Apps')

@section('content')
    <style>

        .add-button {
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.20);
            padding: 2px;
            border: 0;
            border-radius: 4px;
            width: 30px;
            height: 30px;
            margin: 0 auto;
            display: block;
        }


    </style>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div style="display: flex;align-items: center;position: absolute;margin-left: 180px;margin-top: 15px;z-index: 999">
                        <a href="{{ route('admin.apps.create') }}" class="btn btn-dropbox float-right add-button">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#FFFFFF"><path d="M0 0h24v24H0z" fill="none"/><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table id="mainTable" class="table table-striped table-bordered display" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Bundle</th>
                                <th>Store Status</th>
                                <th>actions</th>
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
        const table = $('#mainTable').DataTable({
            ajax: `{{route('admin.' . $route . '.listData')}}`,
            columns: [
                { data: 'id' },
                { data: 'name', },
                { data: 'app_id', },
                { data: 'app_state_id', },
                { data: 'actions', name: 'actions', sortable: false }
            ]
        })
    </script>
@stop
