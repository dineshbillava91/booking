<?php

namespace App\Http\Controllers\Api;

use App\Models\Bookable;
use App\Http\Controllers\Controller;
use App\Http\Requests\BookingRequest;
use Illuminate\Http\Request;

class BookableAvailability extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Bookable $bookable,BookingRequest $request)
    {
        //error code will be 422
        // $data = $request->validate([
        //     'from' => 'required|date_format:Y-m-d|after_or_equal:now',
        //     'to' => 'required|date_format:Y-m-d|after_or_equal:from'
        // ]);

        $data = $request->validated();

        return $bookable->checkAvailability($data['from'], $data['to']) ? response()->json([]) : response()->json([], 404);       

    }
}
