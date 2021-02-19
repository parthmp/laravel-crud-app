<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \App\Models\Property;
use \App\Models\PropertyType;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Intervention\Image\ImageManagerStatic as Image;

class CRUDController extends Controller
{
    public function remove(Request $req){
        
        $id = $req->input('id');
        
        $id = trim(stripcslashes(strip_tags($id)));

        Property::where('id', '=', $id)->delete();

        return redirect('/');

    }

    public function edit(Request $req){

        $id = \Request::segment(2);
        
        $id = trim(stripcslashes(strip_tags($id)));

        $prop = Property::where('id', '=', $id)->first();

        if(!$prop){
            return redirect('/');
        }

        $bedrooms = Property::select('number_of_bedrooms')->distinct()->orderBy('number_of_bedrooms', 'ASC')->get();
        $bathrooms = Property::select('number_of_bathrooms')->distinct()->orderBy('number_of_bathrooms', 'ASC')->get();
        $ptypes = PropertyType::orderBy('property_type', 'ASC')->get();

        return view('edit', ['prop' => $prop, 'bedrooms' => $bedrooms, 'bathrooms' => $bathrooms, 'ptypes' => $ptypes]);

    }

    public function saveEdited(Request $req){

        $id = \Request::segment(2);
        
        $id = trim(stripcslashes(strip_tags($id)));

        $prop = Property::where('id', '=', $id)->first();

        if(!$prop){
            return redirect('/');
        }

        $v = Validator::make($req->all(), [
            'county' => 'required',
            'country' => 'required',
            'town' => 'required',
            'address' => 'required',
            'bedroom' => 'required',
            'bathroom' => 'required',
            'price' => 'required',
            'property_type' => 'required',
            'sale_rent' => 'required'
        ]);

        if($v->fails()){
            return redirect('/');
        }

        $county = trim(strip_tags(stripcslashes($req->input('county'))));
        $country = trim(strip_tags(stripcslashes($req->input('country'))));
        $town = trim(strip_tags(stripcslashes($req->input('town'))));
        $address = trim(strip_tags(stripcslashes($req->input('address'))));
        $bedroom = trim(strip_tags(stripcslashes($req->input('bedroom'))));
        $bathroom = trim(strip_tags(stripcslashes($req->input('bathroom'))));
        $price = trim(strip_tags(stripcslashes($req->input('price'))));
        $property_type = trim(strip_tags(stripcslashes($req->input('property_type'))));
        $sale_rent = trim(strip_tags(stripcslashes($req->input('sale_rent'))));
        $description = trim(strip_tags(stripcslashes($req->input('description'))));

        Property::where('id', '=', $id)->update([
            'uuid'                  =>      null,
            'county_name'           =>      $county,
            'country_name'          =>      $country,
            'town_name'             =>      $town,
            'address'               =>      $address,
            'description'           =>      $description,
            'number_of_bedrooms'    =>      $bedroom,
            'number_of_bathrooms'   =>      $bathroom,
            'price'                 =>      $price,
            'property_type_id'      =>      $property_type,
            'sale_or_rent_type'     =>      $sale_rent
        ]);

        
        $this->updateImage($req, $id);

        return redirect('/edit'.'/'.$id);

    }

    private function updateImage($req, $id){

        /* update image */
        if ($req->hasFile('image')) {
            
            if($req->file('image')->isValid()){

                $v = Validator::make($req->all(), [
                    'image' => 'mimes:jpeg,png|max:2024'
                ]);

                if(!$v->fails()){

                    $image = $req->file('image');

                    $extension = $req->image->extension();
                    $filename    = $image->getClientOriginalName();
                    $image_resize = Image::make($image->getRealPath());

                    $image_resize->resize(1000, 400, function($constraint){
                        $constraint->aspectRatio();
                    });

                    
                    $this->dirCreate($id);

                    $image_resize->save(public_path('/images/'.$id.'/'.$filename));
                    $big_image_url = url('/images/'.$id.'/'.$filename);


                    $image_resize->resize(100, 100, function($constraint){
                        $constraint->aspectRatio();
                    });

                    $image_resize->save(public_path('/images/'.$id.'/thumb_'.$filename));
                    $small_image_url = url('/images/'.$id.'/'.'thumb_'.$filename);

                    $prop = Property::where('id', '=', $id)->first();
                    $prop->image = $big_image_url;
                    $prop->thumbnail = $small_image_url;
                    $prop->save();
                    
                }

                
                
            }
        }

    }

    private function dirCreate($id){
        if(!file_exists(public_path('/images'))){
            mkdir(public_path('/images'));
        }

        if(!file_exists(public_path('/images/'.$id))){
            mkdir(public_path('/images/'.$id));
        }
    }

    public function create(Request $req){
        
        $bedrooms = Property::select('number_of_bedrooms')->distinct()->orderBy('number_of_bedrooms', 'ASC')->get();
        $bathrooms = Property::select('number_of_bathrooms')->distinct()->orderBy('number_of_bathrooms', 'ASC')->get();
        $ptypes = PropertyType::orderBy('property_type', 'ASC')->get();

        return view('create', ['bedrooms' => $bedrooms, 'bathrooms' => $bathrooms, 'ptypes' => $ptypes]);
        
    }

    public function saveNew(Request $req){

        $v = Validator::make($req->all(), [
            'county' => 'required',
            'country' => 'required',
            'town' => 'required',
            'address' => 'required',
            'bedroom' => 'required',
            'bathroom' => 'required',
            'price' => 'required',
            'property_type' => 'required',
            'sale_rent' => 'required'
        ]);

        if($v->fails()){
            return redirect('/');
        }

        $county = trim(strip_tags(stripcslashes($req->input('county'))));
        $country = trim(strip_tags(stripcslashes($req->input('country'))));
        $town = trim(strip_tags(stripcslashes($req->input('town'))));
        $address = trim(strip_tags(stripcslashes($req->input('address'))));
        $bedroom = trim(strip_tags(stripcslashes($req->input('bedroom'))));
        $bathroom = trim(strip_tags(stripcslashes($req->input('bathroom'))));
        $price = trim(strip_tags(stripcslashes($req->input('price'))));
        $property_type = trim(strip_tags(stripcslashes($req->input('property_type'))));
        $sale_rent = trim(strip_tags(stripcslashes($req->input('sale_rent'))));
        $description = trim(strip_tags(stripcslashes($req->input('description'))));

        $prop = new Property();
        
        $prop->uuid = null;
        $prop->county_name = $county;
        $prop->country_name = $country;
        $prop->town_name = $town;
        $prop->address = $address;
        $prop->description = $description;
        $prop->number_of_bedrooms = $bedroom;
        $prop->number_of_bathrooms = $bathroom;
        $prop->price = $price;
        $prop->image = '';
        $prop->thumbnail = '';
        $prop->latitude = 0;
        $prop->longitude = 0;
        $prop->property_type_id = $property_type;
        $prop->sale_or_rent_type = $sale_rent;
        $prop->created_at = now();
        $prop->updated_at = now();
        $prop->save();
        
        $id = $prop->id;

        /* update image */
        $this->updateImage($req, $id);
       

        return redirect('/edit'.'/'.$id);

    }

}
