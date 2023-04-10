@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <!-- BEGIN SEARCH RESULT -->
            {{--            @dd($books->data);--}}
            <books-show :book="{{ json_encode($book) }}"></books-show>
        </div>
    </div>
@endsection
