@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1>Test Page</h1>
    <p>This is a simple test page to verify the layout works.</p>
    <p>Current time: {{ now() }}</p>
</div>
@endsection
