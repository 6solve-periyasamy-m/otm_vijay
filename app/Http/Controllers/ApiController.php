<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    //

    public function getBasicTourInformation(Tour $tour) {
        // TODO: I'm assuming there is going to be some sort of authentication check here somewhere
        return response()->json([
            "title" => $tour->title, 
            "description" => $tour->description,
            "base_price_per_person" => $tour->base_price_per_person,
            // tour_colour - is an ID so i'm assuming there would be a relationship, doesn't exist yet
            // tour_merchandise - is an ID so i'm assuming there would be a relationship, doesn't exist yet

            
            // This is just a basic start with the models that I have access to and the relationships I currently have
        ]);
    }
}
