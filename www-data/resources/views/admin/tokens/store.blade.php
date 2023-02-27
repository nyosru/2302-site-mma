@extends('layouts.contentLayoutMaster')

@section('title', isset($object) ? 'Edit token' : 'Add token')


@section('content')
    <form action="{{route('admin.' . $route . '.store')}}" method="post">
        @csrf
        <div class="row">
            <div class="col-12 col-md-8 col-xl-9">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-12">
                                <x-adminlte-input
                                    name="token"
                                    id="token"
                                    type="text"
                                    label="Token"
                                    :value="isset($object) ? substr($object->token, 0, App\Http\Controllers\Admin\TokenController::VISIBLE_TOKEN_LENGTH) . str_repeat('*', App\Http\Controllers\Admin\TokenController::TOKEN_LENGTH - App\Http\Controllers\Admin\TokenController::VISIBLE_TOKEN_LENGTH) : ''"
                                    readonly
                                    required
                                />
                                @if(!isset($object))
                                <div class="input-group-prepend">
                                    <button onclick="generateToken()" class="btn btn-outline-secondary btn-sm" type="button">Generate</button>&nbsp;&nbsp;
                                    <button onclick="copy('#token')" class="btn btn-outline-secondary btn-sm" type="button" title="Copy"><i class="bx bx-copy"></i></button>
                                </div>
                                <br>
                                @endif
                            </div>
                            <div class="col-12 col-md-12">
                                <x-adminlte-input
                                    name="ip"
                                    label="Allowed IP"
                                    placeholder="..."
                                    :value="isset($object) ? $object->ip : ''"
                                />
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
                            @allowed(App\Services\PermissionService::O_TOKEN, App\Services\PermissionService::A_DELETE)
                            <a href="{{route('admin.' . $route . '.destroy', $object)}}" onclick="return confirm('You a sure?');" class="btn btn-outline-danger"><i class="bx bx-trash"></i></a>
                            @endallowed
                        </div>
                            @endif
                    @else
                        @allowed(App\Services\PermissionService::O_TOKEN, App\Services\PermissionService::A_CREATE)
                            <x-adminlte-button label="Create" theme="primary" type="submit"/>
                        @endallowed
                    @endisset
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop

@section('footer-admin')
    <script>
        function generateToken() {
            const characters ='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789_';
            const tokenLen = {{App\Http\Controllers\Admin\TokenController::TOKEN_LENGTH}};
            let token = '';
            const charactersLength = characters.length;
            for ( let i = 0; i < tokenLen; i++ ) {
                token += characters.charAt(Math.floor(Math.random() * charactersLength));
            }
            document.getElementById('token').value = token;
        }
    </script>
@stop
