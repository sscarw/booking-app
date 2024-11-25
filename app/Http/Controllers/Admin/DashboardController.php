<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\Barber;
use App\Models\Service;

class DashboardController extends Controller
{
    public function index()
    {
        $barbers = Barber::all();
        $services = Service::all();

        return view('layouts.admin.dashboard', compact('barbers', 'services'));
    }

    // Create Barber
    public function storeBarber(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|unique:barbers,phone|regex:/^[0-9]+$/',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        if (Barber::where('phone', $request->phone)->exists()) {
            return redirect()->route('admin.dashboard')->with('error', 'A barber with this number already exists');
        }

        $filename = null;
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = Str::uuid()->toString() . '.' . $file->extension();
            $filePath = 'barber_photos/' . $filename;
            Storage::disk('public')->put($filePath, file_get_contents($file));
        }

        try {
            Barber::create([
                'name' => $request->name,
                'last_name' => $request->last_name,
                'phone' => $request->phone,
                'photo' => $filename,
            ]);

            return redirect()->route('admin.dashboard')->with('success', 'Barber added');
        } catch (\Exception $e) {
            return redirect()->route('admin.dashboard')->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // Create Service
    public function storeService(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:services,name',
            'price' => 'required|numeric|min:0',
        ]);

        if (Service::where('name', $request->name)->exists()) {
            return redirect()->route('admin.dashboard')->with('error', 'A service with this name already exists');
        }

        $price = floor($request->price);

        Service::create([
            'name' => $request->name,
            'price' => $price,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Service added');
    }

    // Delete Barber
    public function deleteBarber($id)
    {
        Barber::findOrFail($id)->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Barber removed');
    }

    // Delete Service
    public function deleteService($id)
    {
        Service::findOrFail($id)->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Service removed');
    }

    // Edit Service
    public function editService($id)
    {
        $service = Service::findOrFail($id);

        return view('layouts.admin.edit_service', compact('service'));
    }

    // Update Service
    public function updateService(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:services,name,' . $id,
            'price' => 'required|numeric|min:0',
        ]);

        if (Service::where('name', $request->name)->where('id', '!=', $id)->exists()) {
            return redirect()->route('admin.dashboard')->with('error', 'A service with this name already exists');
        }

        $price = floor($request->price);

        $service = Service::findOrFail($id);
        $service->update([
            'name' => $request->name,
            'price' => $price,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Service updated');
    }

    // Edit Barber
    public function editBarber($id)
    {
        $barber = Barber::findOrFail($id);

        return view('layouts.admin.edit_barber', compact('barber'));
    }

    // Update Barber
    public function updateBarber(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|regex:/^[0-9]+$/',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        if (Barber::where('phone', $request->phone)->where('id', '!=', $id)->exists()) {
            return redirect()->route('admin.dashboard')->with('error', 'A barber with this number already exists');
        }

        $barber = Barber::findOrFail($id);

        $filename = $barber->photo;
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = Str::uuid()->toString() . '.' . $file->extension();
            $filePath = 'barber_photos/' . $filename;
            Storage::disk('public')->put($filePath, file_get_contents($file));
        }

        $barber->update([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'photo' => $filename,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Barber updated');
    }
}
