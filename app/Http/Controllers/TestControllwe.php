<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stevebauman\Location\Facades\Location;

class TestControllwe extends Controller
{
    //
    public function test()
    {
        if ($position = Location::get()) {
            // Successfully retrieved position.
            echo $position->countryName;
        } else {
            // Failed retrieving position.
        }

    }
}
