<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .email-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            padding-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            color: #4CAF50;
        }

        .content {
            padding: 20px 0;
        }

        .content p {
            line-height: 1.5;
            margin: 0 0 15px;
        }

        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #e4e4e4;
        }

        .footer p {
            margin: 0;
            color: #888888;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 20px 0;
            color: #ffffff;
            background-color: #4CAF50;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="content">
            @yield('content')
            <img src="{{ asset('img/logo-ct-dark.png') }}" alt="">
        </div>
        {{-- <div class="footer">
            <p>&copy; IGOU TELECOM S.A.P.I. de C.V..</p>
        </div> --}}
    </div>
</body>

</html>
