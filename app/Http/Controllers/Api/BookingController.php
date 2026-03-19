<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookingResource;
use App\Models\Appointment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $todayStart = now()->startOfDay();

        $bookings = Appointment::query()
            ->with(['salon.media', 'service', 'serviceProvider'])
            ->where('user_id', $request->user()->id)
            ->get()
            ->sort(function (Appointment $left, Appointment $right) use ($todayStart) {
                $leftUpcoming = $left->slot_start->greaterThanOrEqualTo($todayStart);
                $rightUpcoming = $right->slot_start->greaterThanOrEqualTo($todayStart);

                if ($leftUpcoming !== $rightUpcoming) {
                    return $leftUpcoming ? -1 : 1;
                }

                if ($leftUpcoming) {
                    return $left->slot_start <=> $right->slot_start;
                }

                return $right->slot_start <=> $left->slot_start;
            })
            ->values();

        return response()->json([
            'message' => 'OK',
            'status' => 'success',
            'data' => BookingResource::collection($bookings)->resolve(),
        ]);
    }
}
