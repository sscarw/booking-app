<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Admin Login</title>
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-card {
            max-width: 400px;
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 10px;
            background: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .login-card .form-control:focus {
            border-color: #6c757d;
            box-shadow: 0 0 0 0.2rem rgba(108, 117, 125, 0.25);
        }

        .login-card button {
            width: 100%;
        }

        .error-message {
            color: #dc3545;
            font-size: 0.9rem;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="login-card">
    <h2 class="text-center mb-4">Admin Login</h2>
    <form action="{{ route('admin.login') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password:</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-dark">Login</button>
    </form>

    @if ($errors->any())
        <div class="error-message">
            <strong>{{ $errors->first() }}</strong>
        </div>
    @endif
</div>

</body>
</html>
