@extends('layouts.admin.app')

@section('title', 'Edit Service')

@section('content')
    <div class="container py-4">
        <h1 class="text-primary mb-4">Edit Service</h1>
        <form action="{{ route('service.update', $service->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Service Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $service->name }}" required>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price (UAH)</label>
                <input type="number" class="form-control" id="price" name="price" value="{{ $service->price }}"
                       required>
            </div>
            <button type="submit" class="btn btn-primary w-100 mt-3">Update Service</button>
        </form>
    </div>
@endsection
