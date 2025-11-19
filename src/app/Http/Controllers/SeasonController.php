<?php

namespace App\Http\Controllers;

use App\Models\Season;

class SeasonController extends Controller
{
    public function index()
    {
        $seasons = Season::with('products')->get();
        return view('seasons.index', compact('seasons'));
    }
}
