@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <!-- BEGIN SEARCH RESULT -->
            {{--            @dd($books->data);--}}
            <books-edit :book="{{ json_encode($book) }}" :genres="{{ json_encode($genres) }}"></books-edit>
        </div>
    </div>
@endsection
