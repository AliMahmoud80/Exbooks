<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Http\Resources\countriesResource;

class CountriesController extends Controller
{
    public function index()
    {
        return countriesResource::collection(Country::all());
    }
}
