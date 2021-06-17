<?php

use App\Models\Bookable;
use App\Models\Booking;
use Illuminate\Database\Seeder;

class BookingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Bookable::all()->each(function($bookable) {
            $booking = factory(Booking::class)->make();

            $bookings = collect([$booking]);

            for ($i = 0; $i < random_int(1, 20) ; $i++) { 
                $from = (clone $booking->to)->addDays(random_int('1', '14'));
                $to = (clone $from)->addDays(random_int('0', '14'));

                $booking = Booking::make([
                    'from' => $from,
                    'to' => $to,
                    'price' => random_int(200,50000)
                ]);

                // $bookable->bookings()->save($booking);

                $bookings->push($booking);
            }

            $bookable->bookings()->saveMany($bookings);

        });
    }
}
