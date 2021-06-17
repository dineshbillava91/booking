<?php

namespace App\Http\Controllers\Api;

use App\Models\Bookable;
use App\Http\Controllers\Controller;
use App\Http\Resources\BookableIndexResource;
use App\Http\Resources\BookableShowResource;
use Illuminate\Http\Request;

class BookableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return Bookable::all();

        return  BookableIndexResource::collection(Bookable::paginate(10));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Bookable $bookable)
    {
        // return $bookable; 

        return new BookableShowResource($bookable);
    }
}
