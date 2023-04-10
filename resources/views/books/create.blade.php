@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <!-- BEGIN SEARCH RESULT -->
            {{--            @dd($books->data);--}}
            <books-create :genres="{{ json_encode($genres) }}"></books-create>
        </div>
    </div>
@endsection
