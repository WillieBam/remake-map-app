<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Continent;

class ContinentController extends Controller
{
    function getContinents(Request $request){
        $continents = Continent::all();

        return response($continents); // this is an array
    }
}
