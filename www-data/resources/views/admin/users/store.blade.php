@extends('layouts.contentLayoutMaster')

@section('title', isset($object) ? 'Edit user' : 'Add user')


@section('content')
    <?php
    $userRole = isset($object) ? $object->getRole() : null;
    ?>
    <form action="{{route('admin.' . $route . '.store')}}" method="post">
        @csrf
        <div class="row">
            <div class="col-12 col-md-8 col-xl-9">
                <div class="card">

                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-12">
                                <x-adminlte-input
                                    name="email"
                                    type="email"
                                    label="Email"
                                    placeholder="..."
                                    :value="old('email',isset($object) ? $object->email : '')"
                                    required
                                />
                            </div>
                            <div class="col-12 col-md-12">
                                <x-adminlte-input
                                    name="name"
                                    label="Name"
                                    placeholder="..."
                                    :value="old('name',isset($object) ? $object->name : '')"
                                    required
                                />
                            </div>
                            <div class="col-12 col-md-12">
                                <x-adminlte-input
                                    name="password"
                                    label="Password"
                                    :value="old('password', '')"
                                    placeholder="..."
                                />
                            </div>

                            <div class="col-12">
                                <h6 class="card-subtitle">Role</h6>
                                <div class="form-group">
                                    <select name="role_key" class="form-control" placeholder="Pick" required>

                                        @foreach(\App\Services\PermissionService::getRoles() as $key => $role)
                                            <option value="{{$key}}" {{ (isset($object) ? ($userRole == $key) : (old('role_key')==$key))  ? 'selected' : ''}}>{{$role}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <div class="col-12 col-md-4 col-xl-3">
                <div class="card">
                    <div class="card-body">
                    @isset($object)
                        <input type="hidden" name="id" value="{{$object->getKey()}}">
                        @if($object)
                        <div class="btn-group btn-block">
                            <x-adminlte-button label="Save" theme="success" type="submit"/>
                            @allowed(App\Services\PermissionService::O_USER, App\Services\PermissionService::A_DELETE)
                            <a href="{{route('admin.' . $route . '.delete', $object)}}" onclick="return confirm('You a sure?');" class="btn btn-outline-danger"><i class="bx bx-trash"></i></a>
                            @endallowed
                        </div>
                            @endif
                    @else
                        @allowed(App\Services\PermissionService::O_USER, App\Services\PermissionService::A_CREATE)
                            <x-adminlte-button label="Create" theme="primary" type="submit"/>
                        @endallowed
                    @endisset
                    </div>
                </div>

            </div>
        </div>
    </form>
@stop

