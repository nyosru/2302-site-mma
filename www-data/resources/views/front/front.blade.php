@extends('layouts.contentLayoutMaster')

@section('title', $item->seo('title'))
@section('keywords', $item->seo('keywords'))
@section('description', $item->seo('description'))

@section('meta')
    <link rel="canonical" href="{{url('/')}}" />

    <meta property="og:title" content="Monte Kristcorporate" />
    <meta property="og:description" content="{{$item->seo('title')}}" />

    <meta name="twitter:title" content="{{$item->seo('title')}}" />
    <meta name="twitter:description" content="{{$item->seo('description')}}" />

    <meta itemprop="copyrightHolder" content="Monte Kristcorporate" />

    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">

@endsection

@section('content')
    @foreach ($item->sections as $section)
        @include("front.sections.{$section->type}", ['section' => $section])
    @endforeach
@endsection
