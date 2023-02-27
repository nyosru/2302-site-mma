@extends('layouts.contentLayoutMaster')

@section('title', 'Apps')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="mainTable" class="table table-striped table-bordered display" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Bundle</th>
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
            ajax: `{{route('admin.' . $route . '.listTrashedData')}}`,
            columns: [
                { data: 'id' },
                { data: 'name', },
                { data: 'app_id', },
                { data: 'actions', name: 'actions', sortable: false }
            ]
        })
    </script>
@stop
