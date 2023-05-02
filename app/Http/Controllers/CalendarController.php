<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index()
    {
        $bookings = Booking::all();
        $events = array();
        foreach($bookings as $booking) {
            $events[] = [
                'id' => $booking->id,
                'title' => $booking->title,
                'start' => $booking->start_date,
                'end' => $booking->end_date,
                'color' => $booking->color,
            ];
        }

        return view('calendar.index' , ['events' => $events]);
    }
    public function index1()
    {
        $bookings = Booking::all();
        $events = array();
        foreach($bookings as $booking) {
            $events[] = [
                'id' => $booking->id,
                'title' => $booking->title,
                'start' => $booking->start_date,
                'end' => $booking->end_date,
                'color' => $booking->color,
            ];
        }

        return view('calendar.index1' , ['events' => $events]);
    }


    public function store(Request $request)
    {
            $validated = $request->validate([
                'title' => 'required|string',
            ], [], []);
          $booking =   Booking::create([
                'title' => $request->title,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'color' => $request->color_val,
            ]);

            return response()->json($booking);

    }

    public function update(Request $request ,$id)
    {
        $booking = Booking::find($id);
        if(! $booking) {
            return response()->json(['error' => 'Unable to locate the event'] , 404);
        }else {
            $booking->update([
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ]);
            return response()->json(['message' => 'Event Update']);
        }
    }

    public function destroy(Request $request ,$id)
    {
        $booking = Booking::find($id);
        if(! $booking) {
            return response()->json(['error' => 'Unable to locate the event'] , 404);
        }else {
            $booking->delete();
            return response()->json(['data' => $id , 'message' => 'Event Delete']);
        }
    }



}
