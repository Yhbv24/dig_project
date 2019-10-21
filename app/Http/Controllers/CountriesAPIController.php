<?php

namespace App\Http\Controllers;

class CountriesAPIController extends Controller
{
    public function getCountries()
    {
        return ['countries' => 'test'];
    }
}
