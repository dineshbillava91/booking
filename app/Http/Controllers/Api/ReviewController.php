<?php

namespace App\Http\Controllers\Api;

use App\Models\Booking;
use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewResource;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function show(Review $review)
    {
        return new ReviewResource($review);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id'        => 'required | size:36',
            'content'   => 'required | min:2',
            'rating'    => 'required | in: 1,2,3,4,5'
        ]);


        $booking = Booking::findByReviewKey($data['id']);

        //check if booking exist for review key
        if ($booking == null) {
            return abort(404);
        }

        // make reviewkey empty
        $booking->review_key = "";
        $booking->save();

        //save review
        $review = Review::make($data);
        $review->booking_id  =  $booking->id;
        $review->bookable_id =  $booking->bookable_id;
        $review->save();

        return new ReviewResource($review);

    }
}
