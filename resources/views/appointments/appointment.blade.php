@extends('site')

@section('title', 'Appointment')

@section('content')
    <div class="container my-5">
        <h1 class="text-center mb-4">Make an appointment</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('appointments.store') }}" method="POST"
              class="appointment-form mx-auto p-4 shadow rounded">
            @csrf

            <!-- Barber Selection -->
            <div class="form-group mb-4">
                <label for="barber_id" class="form-label">Select barber:</label>
                <div class="row row-cols-1 row-cols-md-3 g-3">
                    @foreach($barbers as $barber)
                        <div class="col">
                            <div class="card shadow barber-card" data-id="{{ $barber->id }}">
                                <img src="{{ asset('/storage/barber_photos/' . $barber->photo) }}" class="card-img-top"
                                     alt="Barber Photo" style="object-fit: cover; height: 180px;">
                                <div class="card-body text-center">
                                    <h5 class="card-title mb-2">{{ $barber->name }} {{ $barber->last_name }}</h5>
                                    <p class="card-text text-muted">{{ $barber->phone }}</p>
                                    <input type="radio" id="barber_{{ $barber->id }}" name="barber_id"
                                           value="{{ $barber->id }}" hidden>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Service Selection -->
            <div class="form-group mb-4">
                <label for="service_id" class="form-label">Select service:</label>
                <div class="row row-cols-1 row-cols-md-3 g-3">
                    @foreach($services as $service)
                        <div class="col">
                            <div class="card shadow service-card" data-id="{{ $service->id }}">
                                <div class="card-body text-center">
                                    <h5 class="card-title mb-2">{{ $service->name }}</h5>
                                    <p class="card-text text-muted">Price: {{ $service->price }} ₴</p>
                                    <input type="radio" id="service_{{ $service->id }}" name="service_id"
                                           value="{{ $service->id }}" hidden>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Date Selection -->
            <div class="form-group mb-4">
                <label for="appointment_date" class="form-label">Select date:</label>
                <input type="date" name="appointment_date" id="appointment_date" class="form-control shadow-sm"
                       required value="{{ \Carbon\Carbon::today()->toDateString() }}"
                       min="{{ \Carbon\Carbon::today()->toDateString() }}">
            </div>

            <!-- Time Selection -->
            <div class="form-group mb-4">
                <label for="appointment_time" class="form-label">Select time:</label>
                <div id="appointment-time-loader" class="spinner-border text-dark" role="status"
                     style="display: none">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div class="row g-2" ID="appointment-time-list" style="display: flex">
                    @foreach($time_slots as $time)
                        <div class="col-auto">
                            <button type="button"
                                    class="btn time-slot disabled"
                                    data-time="{{ $time }}" disabled>
                                {{ $time }}
                            </button>
                        </div>
                    @endforeach
                </div>
                <input type="hidden" name="appointment_time" id="appointment_time">
            </div>

            <button type="submit" class="btn btn-dark w-100 py-2">Book</button>
        </form>
    </div>

    <script>
        const dateInput = document.getElementById('appointment_date');
        var selectedBarberId = null;
        var selectedDate = dateInput.value;

        dateInput.addEventListener('change', function () {
            selectedDate = this.value;
            updateTimeSlots();
        });

        document.querySelectorAll('.barber-card').forEach(card => {
            card.addEventListener('click', function () {
                const barberId = this.getAttribute('data-id');
                const barberInput = document.getElementById(`barber_${barberId}`);

                if (this.classList.contains('selected')) {
                    this.classList.remove('selected');
                    if (barberInput) barberInput.checked = false;
                    selectedBarberId = null;
                } else {
                    document.querySelectorAll('.barber-card').forEach(c => c.classList.remove('selected'));
                    this.classList.add('selected');
                    if (barberInput) barberInput.checked = true;
                    selectedBarberId = barberId;
                }

                updateTimeSlots();
            });
        });

        document.querySelectorAll('.service-card').forEach(card => {
            card.addEventListener('click', function () {
                const serviceId = this.getAttribute('data-id');
                const serviceInput = document.getElementById(`service_${serviceId}`);

                if (this.classList.contains('selected')) {
                    this.classList.remove('selected');
                    if (serviceInput) {
                        serviceInput.checked = false;
                    }
                } else {
                    document.querySelectorAll('.service-card').forEach(c => c.classList.remove('selected'));
                    document.querySelectorAll('.service-card input[type="radio"]').forEach(input => input.checked = false);

                    this.classList.add('selected');
                    if (serviceInput) {
                        serviceInput.checked = true;
                    }
                }
            });
        });

        async function updateTimeSlots() {
            const loader = document.getElementById('appointment-time-loader');
            const timeList = document.getElementById('appointment-time-list');
            const timeSlots = document.querySelectorAll('.time-slot');
            const appointmentTimeInput = document.getElementById('appointment_time');

            appointmentTimeInput.value = '';
            timeSlots.forEach(slot => slot.classList.remove('selected'));

            if (!selectedBarberId || !selectedDate) {
                timeSlots.forEach(slot => {
                    slot.classList.remove('disabled');
                    slot.disabled = false;
                });
                return;
            }

            loader.style.display = 'block';
            timeList.style.display = 'none';

            try {
                const response = await fetch(`/check-time-availability/${selectedBarberId}/?date=${selectedDate}`);
                const data = await response.json();

                if (data) {
                    timeSlots.forEach(slot => {
                        const time = slot.dataset.time;

                        if (data.includes(time)) {
                            slot.classList.add('disabled');
                            slot.disabled = true;
                        } else {
                            slot.classList.remove('disabled');
                            slot.disabled = false;
                        }
                    });
                }
            } catch (error) {
                console.error('Error checking time availability:', error);
            } finally {
                loader.style.display = 'none';
                timeList.style.display = 'flex';
            }
        }

        document.querySelectorAll('.time-slot').forEach(slot => {
            slot.addEventListener('click', function () {
                if (this.classList.contains('disabled')) return;

                document.querySelectorAll('.time-slot').forEach(s => s.classList.remove('selected'));
                this.classList.add('selected');
                document.getElementById('appointment_time').value = this.dataset.time;
            });
        });
    </script>
@endsection
