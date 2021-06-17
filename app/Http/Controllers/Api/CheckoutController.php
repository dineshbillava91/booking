<?php

namespace App\Http\Controllers\Api;

use App\Address;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bookable;
use App\Models\Booking;

class CheckoutController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $data = $request->validate([
            'bookings' => 'required| array | min:1',
            //Ex: how to use closure validation on array
            // 'bookings.*' => ['required', function($attribute, $value, $fail) {
            //     $bookable = Bookable::findOrFail($value['bookable_id']);

            //     if (!$bookable->checkAvailability($value['from'], $value['to'])) {
            //         $fail('Bookable is not available for given dates');
            //     }
            // }],
            'bookings.*.bookable_id' => 'required|exists:bookables,id',
            'bookings.*.from' => 'required| date| after_or_equal:today',
            'bookings.*.to' => 'required| date| after_or_equal:bookings.*.from',
            'customer.first_name' => 'required| min:2',
            'customer.last_name' => 'required| min:2',
            'customer.email' => 'required| email',
            'customer.country' => 'required| min:4',
            'customer.state' => 'required| min:2',
        ]);
            
        $data = array_merge($data,$request->validate([
            'bookings.*' => ['required', function($attribute, $value, $fail) {
                $bookable = Bookable::findOrFail($value['bookable_id']);

                if (!$bookable->checkAvailability($value['from'], $value['to'])) {
                    $fail('Bookable is not available for given dates');
                }
            }]
        ]));


        $bookingsData = $data['bookings'];
        $customerData = $data['customer'];
        

        $bookings = collect($bookingsData)->map(function($bookingInfo) use ($customerData) {
            $bookable = Bookable::findOrFail($bookingInfo['bookable_id']);
            $booking = new Booking();
            $booking->from = $bookingInfo['from'];
            $booking->to = $bookingInfo['to'];
            $booking->price = $bookable->priceFor($bookingInfo['from'], $bookingInfo['to'])['total'];
            $booking->bookable()->associate($bookable);

            $booking->address()->associate(Address::create($customerData));

            $booking->save();

            return $booking;
        });

        return $bookings;
    }
}
