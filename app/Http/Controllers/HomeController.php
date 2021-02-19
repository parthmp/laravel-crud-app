<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $req)
    {

        $prop = \App\Models\Property::select('id', 'price', 'address','country_name', 'number_of_bedrooms', 'sale_or_rent_type', 'thumbnail');

        $clearfilter = false;

        if($req->has('address')){

            $address = trim(strip_tags(stripcslashes($req->input('address'))));

            if($address != ''){
                $prop->where('address', 'like', '%'.$address.'%');
                $clearfilter = true;
            }
            
        }

        if($req->has('bedrooms')){

            $bedrooms = trim(strip_tags(stripcslashes($req->input('bedrooms'))));

            if($bedrooms != ''){
                $prop->where('number_of_bedrooms', '=', $bedrooms);
                $clearfilter = true;
            }   
            
        }

        if($req->has('property_type')){
            
            $property_type = trim(strip_tags(stripcslashes($req->input('property_type'))));

            if($property_type != ''){
                $prop->where('property_type_id', '=', $property_type);
                $clearfilter = true;
            }   
            
        }

        if($req->has('type')){
            
            $type = trim(strip_tags(stripcslashes($req->input('type'))));

            if($type != ''){
                $prop->where('sale_or_rent_type', '=', $type);
                $clearfilter = true;
            }
            
        }

        if($req->has('price_from')){
            
            $price_from = trim(strip_tags(stripcslashes($req->input('price_from'))));

            if($price_from != ''){
                $prop->where('price', '>=', $price_from);
                $clearfilter = true;
            }
            
        }

        if($req->has('price_to')){
            
            $price_to = trim(strip_tags(stripcslashes($req->input('price_to'))));

            if($price_to != ''){
                $prop->where('price', '<=', $price_to);
                $clearfilter = true;
            }
            
        }

        $prop = $prop->paginate(15);


        return view('home', [
            'properties' => $prop,
            'clearfilter' => $clearfilter,
            'property_types' => \DB::table('property_types')->select('id', 'property_type')->get()
        ]);

    }
}
