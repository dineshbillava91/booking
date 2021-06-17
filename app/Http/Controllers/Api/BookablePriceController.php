<?php

namespace App\Http\Controllers\Api;

use App\Models\Bookable;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BookablePriceController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke($id, Request $request)
    {
        $data = $request->validate([
            'from' => 'required| ',
            'to' => 'required| after_or_equal:from'
        ]);
        
        $bookable = Bookable::findOrFail($id);

        return response()->json([
            'data' => $bookable->priceFor($data['from'], $data['to'])
        ]);
    }
}
