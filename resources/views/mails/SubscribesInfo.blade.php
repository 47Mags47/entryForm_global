<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Запись на прием</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: #f7f8fa;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 30px auto;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        .header {
            background-color: #83adf0;
            color: #fff;
            text-align: center;
            padding: 20px;
        }
        .header h1 {
            font-size: 22px;
            margin: 0;
        }
        .content {
            padding: 25px 30px;
        }
        .content h3 {
            font-size: 18px;
            color: #111;
            margin-top: 0;
            margin-bottom: 15px;
        }
        .info p {
            margin: 6px 0;
            font-size: 15px;
            line-height: 1.5;
        }
        .info p strong {
            color: #111;
        }
        .footer {
            background-color: #f3f4f6;
            text-align: center;
            font-size: 13px;
            color: #666;
            padding: 15px;
        }
        @media only screen and (max-width: 600px) {
            .email-container {
                margin: 10px;
            }
            .content {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>Запись на приём в {{ $subscribe->division->name }}</h1>
        </div>
        <div class="content">
            <h3>Информация о записи:</h3>
            <div class="info">
                <p><strong>Фамилия:</strong> {{ $subscribe->last_name }}</p>
                <p><strong>Имя:</strong> {{ $subscribe->first_name }}</p>
                <p><strong>Отчество:</strong> {{ $subscribe->middle_name }}</p>
                <p><strong>Телефон:</strong> {{ $subscribe->phone }}</p>
                <p><strong>Услуга:</strong> {{ $subscribe->service->name }}</p>
                <p><strong>Специалист:</strong> {{ $subscribe->worker->last_name . ' ' . $subscribe->worker->first_name . ' ' . $subscribe->worker->middle_name }}</p>
                <p><strong>Дата:</strong> {{ $subscribe->start_at }}</p>
                @if ($subscribe->comment)
                    <p><strong>Комментарий к записи:</strong> {{ $subscribe->comment }} </p>
                @endif
            </div>
        </div>
        <div class="footer">
            Это письмо сформировано автоматически. Пожалуйста, не отвечайте на него.
            <p>&copy; Copyright {{ date('Y') }} {{ config('app.name') }}. Все права защищены.</p>
        </div>
    </div>
</body>
</html>
