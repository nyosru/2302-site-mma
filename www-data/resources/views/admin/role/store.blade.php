@extends('layouts.contentLayoutMaster')
@if(isset($p))

    @section('title', 'Edit role')

@else
    @section('title', 'Create role')
@endif


@section('content')
    <form action="{{route('admin.store_role')}}" enctype="multipart/form-data" method="post">
        @csrf
        <div class="row">
            <div class="col-12 col-md-8 col-xl-9">

                <div class="card">
                    <div class="card-body">

                        <h6 class="card-subtitle">Key</h6>
                        <div class="form-group">
                            <input type="text" class="form-control" name="key" value="{{ $p->key ?? '' }}" required>
                        </div>
                        <h6 class="card-subtitle">Name</h6>
                        <div class="form-group">
                            <input type="text" class="form-control" name="name" value="{{ $p->name ?? '' }}">
                        </div>
                        <h6 class="card-subtitle">Description</h6>
                        <div class="form-group">
                            <input type="text" class="form-control" name="description" value="{{ $p->description ?? '' }}">
                        </div>






                    </div>
                </div>

            </div>



        </div>


        <div class="col-12">
            <div class="row">
                <div class="col-12">
                    <h4 class="title">Allows</h4>
                </div>
            </div>


            <div class="row">

                <x-admin-permission-picker
                    :current="isset($p) ? $p->allows : null"
                />

            </div>
        </div>

    </form>
@endsection
