@extends('layouts.contentLayoutMaster')
@section('title', 'Roles list')


@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">


                        @allowed(App\Services\PermissionService::O_PERMISSION, App\Services\PermissionService::A_VIEW)
                        <div>
                            <table class="table table-compact w-full">
                                <thead class="text-primary">
                                <tr>
                                    <th>Name</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($map as $key => $value)
                                    <tr>
                                        <td><A class="link link-warning hover:underline" href="{{ route('admin.view_role_permissions', $key) }}">{{ $key }}</A></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            <div class="pt-0"></div>
                        </div>

                        {{--    <form method="GET" action="{{ route('admin.permission-add') }}">--}}
                        {{--        @include('include.button', ['title'=>'Добавить'])--}}
                        {{--    </form>--}}
                            @endallowed
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{--@section('footer-admin')--}}

{{--    <script>--}}
{{--        $('#main_table').DataTable({--}}
{{--            language: {--}}
{{--                "url": "/assets/extra-libs/DataTables/dataTables.ru.lang.json"--}}
{{--            },--}}
{{--            processing: true,--}}
{{--            serverSide: true,--}}
{{--            fixedHeader: true,--}}
{{--            ajax: `{{route('admin.role_list_view_data')}}`,--}}
{{--            columns: [--}}
{{--                { data: 'key' },--}}
{{--                { data: 'name' },--}}

{{--                { data: 'action', sorting: false }--}}
{{--            ]--}}
{{--        });--}}

{{--    </script>--}}
