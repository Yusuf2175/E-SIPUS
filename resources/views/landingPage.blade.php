@extends('layouts.landingPage')

@section('content')
    <x-herosection />
    @include('components.sections-below-hero')
    @include('components.section-below-about', ['totalUsers' => $totalUsers, 'totalBooks' => $totalBooks, 'totalBorrowings' => $totalBorrowings])
    @include('components.sections-below-categories')
    @include('components.section-below-card')
    @include('components.footer')
@endsection
