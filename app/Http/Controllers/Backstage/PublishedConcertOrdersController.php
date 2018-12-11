<?php

namespace App\Http\Controllers\Backstage;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class PublishedConcertOrdersController extends Controller
{
    public function index($concertId)
    {
        $concert = Auth::user()->concerts()->published()->findOrFail($concertId);
        return view('backstage.published-concert-orders.index', [
            'concert' => $concert,
        ]);
    }
}
