@extends('layouts.app')
@section('title', 'Check In Success')

@section('content')
<div class="container mt-5">
    <div class="alert alert-success text-center p-4">
        <h2>{{ $visitor->name }} has successfully checked in!</h2>
        <p>Your visit has been logged. Thank you!</p>
    </div>
</div>
@endsection
