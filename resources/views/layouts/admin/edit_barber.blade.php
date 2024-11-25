@extends('layouts.admin.app')

@section('title', 'Edit Barber')

@section('content')
    <div class="container py-4">
        <h1 class="text-primary mb-4">Edit Barber</h1>
        <form action="{{ route('barber.update', $barber->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">First Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $barber->name }}" required>
            </div>
            <div class="mb-3">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name" value="{{ $barber->last_name }}"
                       required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" class="form-control" id="phone" name="phone" value="{{ $barber->phone }}" required>
            </div>
            <div class="mb-3">
                <label for="photo" class="form-label">Profile Photo</label>
                <input type="file" class="form-control" id="photo" name="photo">
                @if($barber->photo)
                    <div class="mt-3">
                        <img src="{{ asset('/storage/barber_photos/' . $barber->photo) }}" alt="Current Photo"
                             class="img-fluid" style="max-height: 200px;">
                    </div>
                @endif
            </div>
            <button type="submit" class="btn btn-primary w-100 mt-3">Update Barber</button>
        </form>
    </div>
@endsection
