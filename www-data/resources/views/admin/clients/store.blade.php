@extends('layouts.contentLayoutMaster')

@section('title', isset($object) ? 'Edit app' : 'Add app')


@section('content')
    <form action="{{route('admin.' . $route . '.store')}}" method="post">
        @csrf
        <div class="row">
            <div class="col-12 col-md-8 col-xl-9">
                <div class="card">

                    <div class="card-body">
                        <div class="card-title">Main app settings</div>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <x-adminlte-input
                                    name="name"
                                    label="Name"
                                    placeholder="..."
                                    :value="isset($object) ? $object->name : ''"
                                    required
                                />
                            </div>
                            <div class="col-12 col-md-3">
                                <x-adminlte-select name="status" label="Status">
                                    <option value="1" {{(isset($object) and $object->status) ? 'selected' : ''}}>Active</option>
                                    <option value="0" {{(isset($object) and !$object->status) ? 'selected' : ''}}>Disabled</option>
                                </x-adminlte-select>
                            </div>
                            <div class="col-12 col-md-3">
                                <x-adminlte-select name="country_detection_type" label="Country Detection Type">
                                    <option value="sim" {{(isset($object) and $object->country_detection_type == 'sim') ? 'selected' : ''}}>SIM</option>
                                    <option value="ip" {{(isset($object) and $object->country_detection_type == 'ip') ? 'selected' : ''}}>IP-address</option>
                                </x-adminlte-select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <x-adminlte-input
                                    name="app_id"
                                    label="APP (bundle) id"
                                    placeholder="..."
                                    :value="isset($object) ? $object->app_id : ''"
                                    required
                                />
                            </div>
                            <div class="col-12 col-md-6">
                                <x-adminlte-input
                                    name="game_id"
                                    label="Game id"
                                    placeholder="..."
                                    :value="isset($object) ? $object->game_id : ''"

                                />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">

                                <div class="form-group">
                                    <label for="download_url">
                                        Download URL
                                    </label>
                                    <div class="input-group">
                                        <input id="download_url" name="download_url" value="{!! isset($object) ? $object->download_url : '' !!}" class="form-control" placeholder="...">
                                    </div>
                                </div>
                                <p class="text-muted">
                                    <small>By default: <code>market://details?id=tree.green.moneyfun&amp;referrer=tid%3D#tid#</code>, <code>#tid#</code> - is a track ID of client</small>
                                </p>
                            </div>
                        </div>
                        <hr>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="banner_url">
                                        Banner URL
                                    </label>
                                    <div class="input-group">
                                        <input id="banner_url" name="banner_url" value="{!! isset($object) ? $object->banner_url : '' !!}" class="form-control" placeholder="...">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="url">
                                        Product URL
                                    </label>
                                    <div class="input-group">
                                        <input id="url" name="url" value="{!! isset($object) ? $object->url : '' !!}" class="form-control" placeholder="...">
                                    </div>
                                </div>
                                <p class="text-muted">
                                    <small>For example: <code>https://google.com/?country=#country#</code> (<code>#something#</code> is a GET value of GET parameter)</small>
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="card">

                    <div class="card-body">
                        <div class="card-title">Integration settings</div>
                        <div class="row">
                            <div class="col-12 col-md-12">
                                <x-adminlte-input
                                    name="af_dev_key"
                                    label="Appsflyer dev key"
                                    placeholder="..."
                                    :value="isset($object) ? $object->af_dev_key : ''"
                                />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <x-adminlte-input
                                    name="unity_dev_key"
                                    label="Unity dev key"
                                    placeholder="..."
                                    :value="isset($object) ? $object->unity_dev_key : ''"
                                />
                            </div>
                            <div class="col-12 col-md-6">
                                <x-adminlte-input
                                    name="unity_campaign_id"
                                    label="Unity campaign id"
                                    placeholder="..."
                                    :value="isset($object) ? $object->unity_campaign_id : ''"
                                />
                            </div>
                        </div>

                    </div>
                </div>

                <div class="card">

                    <div class="card-body">
                        <div class="card-title">Filtration settings</div>
                        <div class="row">
                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label><input type="checkbox" name="ban_by_tid" value="on" {{(isset($object) and $object->ban_by_tid) ? 'checked' : ''}}>
                                        Ban By Tracking Id
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label><input type="checkbox" name="ban_if_countries_not_matched" value="on" {{(isset($object) and $object->ban_if_countries_not_matched) ? 'checked' : ''}}>
                                        Ban if SIM country doesn't match the IP country
                                    </label>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label><input type="checkbox" name="ban_if_no_country" value="on" {{(isset($object) and $object->ban_if_no_country) ? 'checked' : ''}}>
                                        Ban if country not provided and device is suspected
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label><input type="checkbox" name="allowed_countries_filter" value="on" {{(isset($object) and $object->allowed_countries_filter) ? 'checked' : ''}}>
                                        Allowed countries
                                    </label>
                                    <textarea name="allowed_countries" id="allowed_countries" class="form-control">{!! isset($object) ? $object->allowed_countries : '' !!}</textarea>
                                    <p class="text-muted">
                                        <small>For example: <code>RU,RUS</code></small>
                                    </p>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label><input type="checkbox" name="banned_devices_filter" value="on" {{(isset($object) and $object->banned_devices_filter) ? 'checked' : ''}}>
                                        Banned Devices
                                    </label>
                                    <textarea name="banned_devices" id="banned_devices" class="form-control">{!! isset($object) ? $object->banned_devices : '' !!}</textarea>
                                    <p class="text-muted">
                                        <small>For example: <code>android,ios</code></small>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label><input type="checkbox" name="banned_partners_filter" value="on" {{(isset($object) and $object->banned_partners_filter) ? 'checked' : ''}}>
                                        Banned Partners
                                    </label>
                                    <textarea name="banned_partners" id="banned_partners" class="form-control">{!! isset($object) ? $object->banned_partners : '' !!}</textarea>
                                    <p class="text-muted">
                                        <small>For example: <code>monte-media,otherpartner</code></small>
                                    </p>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label><input type="checkbox" name="banned_time_filter" value="on" {{(isset($object) and $object->banned_time_filter) ? 'checked' : ''}}>
                                        Banned Time
                                    </label>
                                    <div><label>
                                            From
                                            <input type="time" name="banned_time" value="{!! isset($object) ? $object->banned_time : '' !!}"></label> <label>
                                            To
                                            <input type="time" name="banned_time_end" value="{!! isset($object) ? $object->banned_time_end : '' !!}"></label>
                                    </div>
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

                        <div class="btn-group btn-block">
                            <x-adminlte-button label="Save" theme="success" type="submit"/>
                            @allowed(App\Services\PermissionService::O_CLIENT, App\Services\PermissionService::A_DELETE)
                                <a href="{{route('admin.' . $route . '.delete', $object)}}" onclick="return confirm('You a sure?');" class="btn btn-outline-danger"><i class="bx bx-trash"></i></a>
                            @endallowed
                        </div>
                    @else
                        @allowed(App\Services\PermissionService::O_CLIENT, App\Services\PermissionService::A_CREATE)
                            <x-adminlte-button label="Create" theme="primary" type="submit"/>
                        @endallowed
                    @endisset
                    </div>
                </div>

                @isset($object)
                    <div class="card">
                        <div class="card-body">
                            <label for="_d" class="form-label">
                                Download
                            </label>
                            <div class="input-group mb-1">
                                <div class="input-group-prepend">
                                    <button onclick="copy('#_d'); return false;" class="btn btn-outline-secondary btn-sm" type="button"><i class="bx bx-copy"></i></button>
                                </div>
                                <input type="text" id="_d" class="form-control" value="{!! route('api.download', [$object->app_id])  !!}" disabled>
                            </div>
                            <label for="_d" class="form-label">
                                APP launcher
                            </label>
                            <div class="input-group mb-1">
                                <div class="input-group-prepend">
                                    <button onclick="copy('#_l'); return false;" class="btn btn-outline-secondary btn-sm" type="button"><i class="bx bx-copy"></i></button>
                                </div>
                                <input type="text" id="_l" class="form-control" value="{!! route('api.launch', [$object->app_id])  !!}" disabled>
                            </div>
                            <label for="_d" class="form-label">
                                TWA launcher
                            </label>
                            <div class="input-group mb-1">
                                <div class="input-group-prepend">
                                    <button onclick="copy('#_twa'); return false;" class="btn btn-outline-secondary btn-sm" type="button"><i class="bx bx-copy"></i></button>
                                </div>
                                <input type="text" id="_twa" class="form-control" value="{!! route('api.twa', [$object->app_id])  !!}" disabled>
                            </div>
                            <label for="_d" class="form-label">
                                Postback
                            </label>
                            <div class="input-group mb-0">
                                <div class="input-group-prepend">
                                    <button onclick="copy('#_pb'); return false;" class="btn btn-outline-secondary btn-sm" type="button"><i class="bx bx-copy"></i></button>
                                </div>
                                <input type="text" id="_pb" class="form-control" value="{!! route('api.event', [$object->app_id, 'af_purchase?appsflyer_id=#id#&eventValue=#value#'])  !!}" disabled>
                            </div>
                        </div>
                    </div>
                @endisset
            </div>
        </div>
    </form>
@stop

