@extends('layouts.contentLayoutMaster')
@if(isset($p))

    @section('title', 'Edit permission')

@else
    @section('title', 'Add permission')
@endif


@section('content')
    <script type="text/javascript" src="/js/core/jquery.js"></script>
    <script type="text/javascript" src="/js/core/scripts.js"></script>
        <form id="permission" class="form form-submit" method="POST" action="{{ route('admin.store_role_permissions', [$key]) }}"
              autocomplete="off" section="Permission">
        <div class="row">
            <div class="col-12 col-md-8 col-xl-9">

                <div class="card">
                    <div class="card-body">
                        <div class="grid sm:grid-cols-4 gap-0">

                            @foreach($roles as $r => $role)
                                @include('include.chekgroup', [
                                    'title'=>  $r,
                                    'items' => $role,
                                    'bitvalue' => 0,
                                    'class'=> 'permission',
                                    'name' => 'feature_type_id',
                                     'disabled' => ($hasRole)
                                ])

                            @endforeach
                        </div>


{{--                        <h6 class="card-subtitle">Key</h6>--}}
{{--                        <div class="form-group">--}}
{{--                            <input type="text" class="form-control" name="key_name" value="{{ $p->key_name ?? '' }}" required>--}}
{{--                        </div>--}}
{{--                        <h6 class="card-subtitle">Name</h6>--}}
{{--                        <div class="form-group">--}}
{{--                            <input type="text" class="form-control" name="name" value="{{ $p->name ?? '' }}">--}}
{{--                        </div>--}}
{{--                        <h6 class="card-subtitle">Description</h6>--}}
{{--                        <div class="form-group">--}}
{{--                            <input type="text" class="form-control" name="description" value="{{ $p->description ?? '' }}">--}}
{{--                        </div>--}}


{{--                        <h6 class="card-subtitle">Parent</h6>--}}
{{--                        <div class="form-group">--}}


{{--                            <select name="parent_id" class="form-control select2">--}}

{{--                                <option value="" selected>Select</option>--}}

{{--                                @foreach(\App\Models\UserRolePermission::all() as $permission)--}}
{{--                                    <option value="{{$permission->id}}" {{ (isset($p) and $p->parent_id == $permission->id)  ? 'selected' : ''}}>{{$permission->key_name}} - {{$permission->name}}</option>--}}
{{--                                @endforeach--}}


{{--                            </select>--}}
{{--                        </div>--}}



                    </div>
                </div>

            </div>


        </div>

    </form>

    <script>
        $(document).ready(function(){
            $("input.permission").on("change", function(){
                $("form#permission").submit();
            });
        });
    </script>
@endsection
