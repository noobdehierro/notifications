<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>{{ config('app.name') }}</title>
    <style>
        /* Base styles */
        * {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f6f9fc;
            width: 100% !important;
            height: 100%;
            line-height: 1.4;
            color: #2b2b2b;
            -webkit-text-size-adjust: none;
        }
        
        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }
        
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .email-wrapper {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }
        
        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px;
            text-align: center;
            color: white;
        }
        
        .email-body {
            padding: 30px;
            line-height: 1.6;
        }
        
        .email-footer {
            background-color: #f6f9fc;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #65748b;
        }
        
        .button {
            display: inline-block;
            padding: 12px 24px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 600;
            margin: 15px 0;
        }
        
        .divider {
            height: 1px;
            background-color: #e8e8e8;
            margin: 25px 0;
        }
        
        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: #667eea;
            text-decoration: none;
        }
        
        /* Responsive styles */
        @media only screen and (max-width: 620px) {
            .container {
                width: 100% !important;
                padding: 10px;
            }
            
            .email-header, .email-body, .email-footer {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <center class="container">
        <div class="email-wrapper">
            <!-- Header -->
            <div class="email-header">
                <h1>{{ config('app.name') }}</h1>
            </div>
            
            <!-- Content -->
            <div class="email-body">
                @yield('content')
            </div>
            
            <!-- Footer -->
            <div class="email-footer">
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.</p>
                <div class="social-links">
                    <a href="https://www.facebook.com/figou.mx">Facebook</a> | 
                    <!-- <a href="#">Twitter</a> |  -->
                    <a href="https://www.instagram.com/figou.mx?igsh=bHk1MndscTR5ZTg4">Instagram</a>
                </div>
                <p>Si tienes alguna pregunta, contáctanos en <a href="mailto:soporte@ejemplo.com">soporte@ejemplo.com</a></p>
                <p><a href="https://www.facebook.com/figou.mx">Visitar sitio web</a></p>
            </div>
        </div>
    </center>
</body>
</html>