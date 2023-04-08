@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <!-- BEGIN SEARCH RESULT -->
{{--            @dd($books->data);--}}
            <books-index></books-index>
        </div>
    </div>
@endsection
