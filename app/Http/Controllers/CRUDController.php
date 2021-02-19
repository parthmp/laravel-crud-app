<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \App\Models\Property;

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

        //return json_encode($prop);

        return view('edit', ['prop' => $prop]);

    }

}
