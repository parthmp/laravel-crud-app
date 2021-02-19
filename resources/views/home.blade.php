@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Property Listing</div>
                <div class="card-body">
                    <form action="{{ url('/') }}" method="GET">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <input type="text" value="{{ (request()->has('address')) ? trim(stripslashes(strip_tags(request()->input('address')))) : '' }}" id="address" class="form-control" name="address">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="bedrooms">Number of Bedrooms</label>
                                    <input type="number" id="bedrooms" min="0" value="{{ (request()->has('bedrooms')) ? trim(stripslashes(strip_tags(request()->input('bedrooms')))) : '' }}" class="form-control" name="bedrooms">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="type">Property Type</label>
                                    <select name="property_type" id="type" class="form-control">
                                        <option value="">Select</option>
                                        @foreach($property_types as $t)
                                        @php
                                            $selected = '';
                                            if(request()->has('property_type')){
                                                if(trim(stripslashes(strip_tags(request()->input('property_type')))) == $t->id){
                                                    $selected = 'selected';
                                                }
                                            }
                                        @endphp
                                            <option {{ $selected }} value="{{ $t->id }}">{{ $t->property_type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="sale_rent">For Sale / For Rent</label>
                                    <select name="type" id="sale_rent" class="form-control">
                                        <option value="">Select</option>
                                        @php
                                            $selected = '';
                                            if(request()->has('type')){
                                                if(trim(stripslashes(strip_tags(request()->input('type')))) == Config::get('app.sale')){
                                                    $selected = 'selected';
                                                }
                                            }
                                        @endphp
                                        <option {{ $selected }} value="{{ Config::get('app.sale') }}">Sale</option>
                                        @php
                                            $selected = '';
                                            if(request()->has('type')){
                                                if(trim(stripslashes(strip_tags(request()->input('type')))) == Config::get('app.rent')){
                                                    $selected = 'selected';
                                                }
                                            }
                                        @endphp
                                        <option {{ $selected }} value="{{ Config::get('app.rent') }}">Rent</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="price_from">Price From</label>
                                    <input type="number" value="{{ (request()->has('price_from')) ? trim(stripslashes(strip_tags(request()->input('price_from')))) : '' }}" id="price_from" min="0" class="form-control" name="price_from">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="price_to">Price To</label>
                                    <input type="number" value="{{ (request()->has('price_to')) ? trim(stripslashes(strip_tags(request()->input('price_to')))) : '' }}" id="price_to" min="0" class="form-control" name="price_to">
                                </div>
                            </div>
                        </div>
                        @if($clearfilter)
                        <a href="{{ url('/') }}" class="btn btn-success float-left">Clear Filter</a>
                        @endif
                        <button class="btn btn-primary float-right">Search</button>
                        <div class="clearfix"></div>
                    </form>

                   

                    <hr>
                    <a href="{{ url('/create') }}" class="btn btn-primary">Create</a>
                    <hr>

                    <table class="table">

                        <tr>
                            <td>Address</td>
                            <td>Price</td>
                            <td>Country</td>
                            <td>Bedrooms</td>
                            <td>Type</td>
                            <td>Thumbnail</td>
                            <td>Actions</td>
                        </tr>

                        @foreach($properties as $data)
                        <tr>
                            <td>{{ $data->address }}</td>
                            <td>${{ number_format($data->price, 2) }}</td>
                            <td>{{ $data->country_name }}</td>
                            <td>{{ $data->number_of_bedrooms }}</td>
                            @if($data->sale_or_rent_type == Config::get('app.sale'))
                                <td><span class="badge bg-success text-light">Sale</span></td>
                            @else
                                <td><span class="badge bg-warning">Rent</span></td>
                            @endif
                            <td><img src="{{ $data->thumbnail }}"></td>
                            <td>
                                <a href="{{ url('/edit') }}/{{ $data->id }}" class="btn btn-primary">Edit</a>
                                <a href="{{ url('/delete') }}?id={{ $data->id }}" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                        @endforeach

                    </table>
                    <div class="d-flex justify-content-center">
                        {!! $properties->appends(request()->input())->render() !!}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
