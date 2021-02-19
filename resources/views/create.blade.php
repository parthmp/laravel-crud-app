@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Create Property</div>
                <div class="card-body">
                    <a href="{{ url('/') }}" class="btn btn-primary">All Listing</a>
                    <br>
                    <br>
                    <add-edit-form action="{{ url('/create') }}" csrf="{{ csrf_token() }}" values="{{ json_encode([]) }}" bedrooms="{{ json_encode($bedrooms) }}" bathrooms="{{ json_encode($bathrooms) }}" ptypes="{{ json_encode($ptypes) }}"></add-edit-form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
