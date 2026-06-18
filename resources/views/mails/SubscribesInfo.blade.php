<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Запись на прием</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: #f4f6f9;
            color: #2c3e50;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        .email-container {
            max-width: 550px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            border: 1px solid #e8ebf0;
        }
        .header {
            background-color: #83adf0;
            color: #ffffff;
            text-align: center;
            padding: 30px 20px;
        }
        .header h1 {
            font-size: 20px;
            font-weight: 600;
            margin: 0;
            line-height: 1.4;
        }
        .content {
            padding: 30px 30px 15px 30px;
        }
        .alert-container {
            background-color: #fff9e6;
            border-left: 4px solid #ffb000;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 30px;
        }
        .alert-container p {
            margin: 0;
            font-size: 14px;
            line-height: 1.5;
            color: #5c4300;
        }
        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: #1a202c;
            margin: 0 0 15px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .info-group {
            padding-bottom: 2px;
        }
        .info-row {
            display: flex;
            margin-bottom: 10px;
            font-size: 15px;
            line-height: 1.4;
        }
        .info-label {
            width: 120px;
            color: #718096;
            flex-shrink: 0;
        }
        .info-value {
            color: #2d3748;
            font-weight: 500;
        }
        .divider {
            height: 1px;
            background-color: #edf2f7;
            margin: 15px 0;
        }
        .footer {
            background-color: #f8f8f8;
            text-align: center;
            font-size: 12px;
            color: #9c9c9c;
            padding: 20px;
            border-top: 1px solid #edf2f7;
        }
        @media only screen and (max-width: 600px) {
            .email-container {
                margin: 0;
                border-radius: 0;
                border: none;
            }
            .content {
                padding: 20px;
            }
            .info-row {
                flex-direction: column;
                margin-bottom: 12px;
            }
            .info-label {
                width: 100%;
                margin-bottom: 2px;
                font-size: 13px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>Запись на приём в<br>{{ $subscribe->division->name }}</h1>
        </div>

        <div class="content">
            <!-- Важное уведомление -->
            <div class="alert-container">
                <p>
                    Если Вы не сможете прийти на прием в выбранное время, запись необходимо отменить по
                    @if($subscribe->worker->phone !== null)
                        телефону {{ $subscribe->worker->phone }}.
                    @else
                        телефону.
                    @endif
                </p>
            </div>

            <!-- Данные заявителя -->
            <div class="info-group">
                <h3 class="section-title">Заявитель</h3>
                <div class="info-row">
                    <div class="info-label">Фамилия</div>
                    <div class="info-value">{{ $subscribe->last_name }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Имя</div>
                    <div class="info-value">{{ $subscribe->first_name }}</div>
                </div>
                @if($subscribe->middle_name)
                <div class="info-row">
                    <div class="info-label">Отчество</div>
                    <div class="info-value">{{ $subscribe->middle_name }}</div>
                </div>
                @endif
            </div>

            <div class="divider"></div>

            <!-- Детали приема -->
            <div class="info-group">
                <h3 class="section-title">Детали приема</h3>
                <div class="info-row">
                    <div class="info-label">Услуга</div>
                    <div class="info-value">{{ $subscribe->service->name }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Специалист</div>
                    <div class="info-value">{{ $subscribe->worker->last_name . ' ' . $subscribe->worker->first_name . ' ' . $subscribe->worker->middle_name }}</div>
                </div>
                @if($subscribe->worker->office)
                <div class="info-row">
                    <div class="info-label">Кабинет</div>
                    <div class="info-value">{{ $subscribe->worker->office }}</div>
                </div>
                @endif
                <div class="info-row">
                    <div class="info-label">Дата и время</div>
                    <div class="info-value">{{ $subscribe->start_at->format('d.m.Y') }} в {{ $subscribe->start_at->format('H:i') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Адрес</div>
                    <div class="info-value">{{ $subscribe->division->address }}</div>
                </div>
            </div>
        </div>

        <div class="footer">
            Это автоматическое письмо, отвечать на него не нужно.
        </div>
    </div>
</body>
</html>
