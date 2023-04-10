@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <!-- BEGIN SEARCH RESULT -->
{{--            @dd($books->data);--}}
            <books-home :genres="{{ json_encode($genres) }}"></books-home>
        </div>
    </div>
@endsection
