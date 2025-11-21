<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? 'Notification de Conformité' }}</title>
       <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous"
    >
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #f5f5f5;
        }
        
        .email-wrapper {
            max-width: 800px;
            margin: 0 auto;
            background-color: #ffffff;
        }
        
        .header {
            background: #05436b;
            color: #ffffff;
            padding: 30px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .header p {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .content {
            padding: 40px 30px;
        }
        
        .greeting {
            font-size: 18px;
            font-weight: 500;
            margin-bottom: 20px;
            color: #1f2937;
        }
        
        .message {
            font-size: 15px;
            color: #4b5563;
            margin-bottom: 15px;
            line-height: 1.7;
        }
        
        .alert-box {
            border-left: 4px solid;
            padding: 15px 20px;
            margin: 25px 0;
            border-radius: 4px;
            background-color: #f9fafb;
        }
        
        .alert-box.success {
            border-color: #10b981;
            background-color: #ecfdf5;
        }
        
        .alert-box.warning {
            border-color: #f59e0b;
            background-color: #fffbeb;
        }
        
        .alert-box.danger {
            border-color: #ef4444;
            background-color: #fef2f2;
        }
        
        .alert-box.info {
            border-color: #3b82f6;
            background-color: #eff6ff;
        }
        
        .info-table {
            width: 100%;
            margin: 25px 0;
            border-collapse: collapse;
            background-color: #f9fafb;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .info-table tr {
            border-bottom: 1px solid #e5e7eb;
        }
        
        .info-table tr:last-child {
            border-bottom: none;
        }
        
        .info-table td {
            padding: 12px 15px;
            font-size: 14px;
        }
        
        .info-table td:first-child {
            font-weight: 600;
            color: #6b7280;
            width: 40%;
        }
        
        .info-table td:last-child {
            color: #1f2937;
        }
        
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            font-size: 15px;
            margin: 20px 0;
            text-align: center;
        }
        
        .button:hover {
            opacity: 0.9;
        }
        
        .footer {
            background-color: #f9fafb;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }
        
        .footer p {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 10px;
        }
        
        .footer a {
            color: #667eea;
            text-decoration: none;
        }
        
        .divider {
            height: 1px;
            background-color: #e5e7eb;
            margin: 30px 0;
        }
        
        @media only screen and (max-width: 600px) {
            .email-wrapper {
                width: 100% !important;
            }
            
            .content {
                padding: 25px 20px !important;
            }
            
            .header {
                padding: 25px 20px !important;
            }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <!-- Header -->
        <div class="header">
        <div class="d-flex flex-column align-items-center">

                            <!-- Logo de l'application -->
                            <img
                                src="https://nedcore.net/assets/img/authentication/logo_nedcore.JPG"
                                alt="Logo application"
                                class="mb-3 img-fluid"
                                style="max-height: 70px;border-radius:10px"
                            >

                            <div>
                            <h1 class="h4 mb-1">
                                {{ $headerTitle ?? 'Nedcore Conformité' }}
                            </h1>
                            <p class="mb-0 small">
                                {{ $headerSubtitle ?? 'Système de Gestion de Conformité' }}
                            </p>
                        </div>
                        </div>
        </div>
        
        <!-- Content -->
        <div class="content">
            @yield('content')
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p><strong>{{ config('app.name') }}</strong></p>
            <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
            <p>Pour toute question, contactez-nous à <a href="mailto:{{ config('mail.from.address') }}">{{ config('mail.from.address') }}</a></p>
            <p style="margin-top: 5px; font-size: 12px;">
                &copy; {{ date('Y') }} {{ config('app.name') }}. Tous droits réservés.
            </p>
        </div>
    </div>
</body>
</html>