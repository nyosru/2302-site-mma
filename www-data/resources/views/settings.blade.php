@extends('layouts.contentLayoutMaster')

@section('title', 'Settings')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <form action="{{route('admin.settings.store')}}" method="post">
                        @csrf

                        @foreach(\App\Models\Setting::all() as $item)
                            @if ($item->key == 'timezone')
                                <div class="form-group">
                                    <label for="{{$item->key}}">{{$item->key}}</label>
                                    <input name="{{$item->key}}" id="{{$item->key}}" class="form-control" value="{!!$item->value !!}" type="number"/>
                                </div>
                                @else
                        <div class="form-group">
                            <label for="{{$item->key}}">{{$item->key}}</label>
                            <textarea name="{{$item->key}}" id="{{$item->key}}" class="form-control">{!!$item->value !!}</textarea>
                        </div>
                            @endif
                        @endforeach


                        <div class="d-flex flex-row justify-content-center align-items-center">
                            <button class="btn btn-success">Save</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
