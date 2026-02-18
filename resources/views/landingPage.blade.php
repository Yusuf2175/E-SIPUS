@extends('layouts.landingPage')

@section('content')
    @include('components.hero-section')
    @include('components.sections-below-hero')
    @include('components.sections-below-categories')
    @include('components.sections-below-recommended')
@endsection
