<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{

    public function index()
    {
        return view('pages.models.countries.table', ['countries' => Country::all(),]);
    }

    public function create()
    {
        return view('pages.models.countries.create');
    }

    public function store(Request $request)
    {
        $country = Country::create([
            'name' => $request->input('name'),
            'code' => $request->input('code'),
            'currency' => $request->input('currency'),
        ]);
        return redirect()->route('countries.view', ['country' => $country,]);
    }

    public function view(Country $country)
    {
        return view('pages.models.countries.view', ['country' => $country,]);
    }

    public function edit(Country $country)
    {
        return view('pages.models.countries.update', ['country' => $country,]);
    }

    public function update(Request $request, Country $country)
    {
        $country->update([
            'name' => $request->input('name'),
            'code' => $request->input('code'),
            'currency' => $request->input('currency'),
        ]);
        return redirect()->route('countries.view', ['country' => $country,]);
    }

    public function destroy(Country $country)
    {
        $country->delete();
        return redirect()->route('countries.all');
    }
}
