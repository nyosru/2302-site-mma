@extends('layouts.contentLayoutMaster')
@section('title', 'Roles list')


@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="main_table" class="table table-striped table-bordered display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Key</th>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer-admin')

    <script>
        $('#main_table').DataTable({
            language: {
                "url": "/assets/extra-libs/DataTables/dataTables.ru.lang.json"
            },
            processing: true,
            serverSide: true,
            fixedHeader: true,
            ajax: `{{route('admin.role_list_view_data')}}`,
            columns: [
                { data: 'key' },
                { data: 'name' },

                { data: 'action', sorting: false }
            ]
        });

    </script>

@endsection
