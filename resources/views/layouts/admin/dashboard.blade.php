@extends('layouts.admin.app')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="container py-4">

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success:</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @elseif(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error:</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Add Barber Section -->
        <h2 class="text-primary mb-3">Add Barber</h2>
        <div class="card border-0 shadow-sm p-4 mb-4">
            <form action="{{ route('barber.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label text-secondary">First Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="last_name" class="form-label text-secondary">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" required>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label text-secondary">Phone Number</label>
                    <input type="text" class="form-control" id="phone" name="phone" required>
                </div>
                <div class="mb-3">
                    <label for="photo" class="form-label text-secondary">Photo</label>
                    <input type="file" class="form-control" id="photo" name="photo">
                </div>
                <button type="submit" class="btn btn-primary w-100">Add Barber</button>
            </form>
        </div>

        <!-- Barber List -->
        <h2 class="text-primary mb-3">Barbers</h2>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            @foreach($barbers as $barber)
                <div class="col">
                    <div class="card border-0 shadow-sm">
                        <img src="{{ asset('/storage/barber_photos/' . $barber->photo) }}" class="card-img-top"
                             alt="Barber Photo" style="object-fit: cover; height: 200px; border-radius: 8px;">
                        <div class="card-body">
                            <h5 class="card-title text-dark">{{ $barber->name }} {{ $barber->last_name }}</h5>
                            <p class="card-text text-muted">{{ $barber->phone }}</p>
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('barber.edit', $barber->id) }}"
                                   class="btn btn-outline-warning btn-sm">Edit</a>
                                <form action="{{ route('barber.delete', $barber->id) }}" method="POST"
                                      style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Add Service Section -->
        <h2 class="text-primary mt-4 mb-3">Add Service</h2>
        <div class="card border-0 shadow-sm p-4 mb-4">
            <form action="{{ route('service.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="service_name" class="form-label text-secondary">Service Name</label>
                    <input type="text" class="form-control" id="service_name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label text-secondary">Service Price</label>
                    <input type="number" id="price" name="price" class="form-control" step="0.01" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Add Service</button>
            </form>
        </div>

        <!-- Service List -->
        <h2 class="text-primary mb-3">Services</h2>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            @foreach($services as $service)
                <div class="col">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-dark">{{ $service->name }}</h5>
                            <p class="card-text text-muted">Price: {{ $service->price }} ₴</p>
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('service.edit', $service->id) }}"
                                   class="btn btn-outline-warning btn-sm">Edit</a>
                                <form action="{{ route('service.delete', $service->id) }}" method="POST"
                                      style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
