<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Services</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #343a40;
            font-family: 'Arial', sans-serif;
            margin: 0;
        }
        .card {
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            width: 90%;
            max-width: 400px;
        }
        .btn-custom {
            background-color: #007bff;
            color: #fff;
            border-radius: 10px;
            padding: 15px 30px;
            font-size: 1.2rem;
            transition: background-color 0.3s;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
        .btn-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
            align-items: center;
        }
        .card-title {
            font-weight: bold;
            font-size: 2rem;
            color: #212529;
        }
        .card-text {
            color: #6c757d;
        }
    </style>
</head>
<body>

    <div class="container text-center">
        <div class="card mx-auto">
            <h1 class="card-title mb-4">Welcome to Campus Services</h1>
            <p class="card-text mb-5">Navigate our campus with ease. Choose one of the services below to get started.</p>
            <div class="btn-container">
                <button class="btn btn-custom w-100" onclick="location.href='survey.html'">Take a Survey</button>
                <button class="btn btn-custom w-100" onclick="location.href='priority.html'">Get a Priority Number</button>
                <button class="btn btn-custom w-100" onclick="location.href='campusmap.html'">View Campus Map</button>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>