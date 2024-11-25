<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Barber;
use App\Models\Service;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $selectedDate = $request->input('date', Carbon::today()->toDateString());

        $barbers = Barber::all();
        $services = Service::all();

        $time_slots = ['09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00'];

        $bookedAppointments = Appointment::whereDate('appointment_date', $selectedDate)
            ->get(['barber_id', 'appointment_time'])
            ->map(function ($appointment) {
                $appointment->appointment_time = Carbon::parse($appointment->appointment_time)->format('H:i');
                return $appointment;
            })
            ->groupBy('barber_id');

        $timeAvailability = [];

        foreach ($barbers as $barber) {
            $barberAvailability = [];
            foreach ($time_slots as $time) {
                $isTimeBooked = isset($bookedAppointments[$barber->id]) &&
                    $bookedAppointments[$barber->id]->contains('appointment_time', $time);

                $barberAvailability[$time] = $isTimeBooked;
            }
            $timeAvailability[$barber->id] = $barberAvailability;
        }

        return view('appointments.appointment',
            compact('barbers', 'services', 'time_slots', 'timeAvailability', 'selectedDate'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barber_id' => 'required|exists:barbers,id',
            'service_id' => 'required|exists:services,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|date_format:H:i',
        ]);

        $barberId = $request->barber_id;
        $serviceId = $request->service_id;
        $appointmentTime = $request->appointment_time;
        $appointmentDate = $request->appointment_date;

        $existingAppointment = Appointment::where('barber_id', $barberId)
            ->where('appointment_date', $appointmentDate)
            ->where('appointment_time', $appointmentTime)
            ->exists();

        if ($existingAppointment) {
            return back()->withErrors(['appointment_time' => 'This time is already taken for the selected barber. Choose another.']);
        }

        Appointment::create([
            'barber_id' => $barberId,
            'service_id' => $serviceId,
            'appointment_date' => $appointmentDate,
            'appointment_time' => $appointmentTime,
        ]);

        return redirect()->route('appointments.index')->with('success', 'Your appointment is booked!');
    }

    public function checkTimeAvailability(Request $request, Barber $barber)
    {
        $date = $request->input('date');

        if (!$date) {
            return response()->json(['error' => 'No date specified'], 400);
        }

        if (!$barber) {
            return response()->json(['error' => 'No barber selected'], 400);
        }

        $bookedTimes = Appointment::where('appointment_date', $date)
            ->where('barber_id', $barber->id)
            ->pluck('appointment_time')
            ->map(function ($time) {
                return Carbon::createFromFormat('H:i:s', $time)->format('H:i');
            })
            ->toArray();

        return response()->json($bookedTimes);

    }

}
