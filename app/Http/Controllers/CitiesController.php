<?php

namespace App\Http\Controllers;

use App\Http\Resources\citiesResource;
use App\Models\City;

class CitiesController extends Controller
{
    public function index()
    {
        return citiesResource::collection(City::all());
    }
}
