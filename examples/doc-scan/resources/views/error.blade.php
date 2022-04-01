@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row pt-4">
        <div class="col">
            <p class="alert alert-danger">{{ $error }}</p>
        </div>
    </div>
</div>
@endsection
