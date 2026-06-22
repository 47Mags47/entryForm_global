<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>503 — Сервис недоступен</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background:
                radial-gradient(circle at top, #1e3a8a 0%, #0f172a 40%),
                #020617;
            font-family: Inter, Arial, sans-serif;
            color: white;
            overflow: hidden;
        }

        .container {
            text-align: center;
            padding: 48px;
            max-width: 600px;
        }

        .code {
            font-size: 180px;
            font-weight: 800;
            line-height: 1;
            color: #93c5fd;
            text-shadow:
                0 0 20px rgba(147, 197, 253, .4),
                0 0 60px rgba(59, 130, 246, .3);
        }

        h1 {
            margin-top: 12px;
            font-size: 32px;
            font-weight: 700;
        }

        p {
            margin-top: 16px;
            font-size: 18px;
            line-height: 1.7;
            color: #cbd5e1;
        }

        .status {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-top: 30px;
            padding: 12px 20px;
            border-radius: 999px;
            background: rgba(255,255,255,.05);
            border: 1px solid rgba(255,255,255,.1);
            color: #fca5a5;
        }

        .dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #ef4444;
            animation: pulse 1.5s infinite;
        }

        .status .reload {
            color: #93c5fd;
            cursor: pointer;
            transition: 0.3s ease-in-out;
        }
        .status .reload:hover {
            color: #c9e2ff;
        }

        .status p {
            margin: 0;
            font-size: 16px;
        }

        @keyframes pulse {
            0% { opacity: .4; transform: scale(.9); }
            50% { opacity: 1; transform: scale(1.2); }
            100% { opacity: .4; transform: scale(.9); }
        }

        .footer {
            margin-top: 40px;
            color: #94a3b8;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .code {
                font-size: 120px;
            }

            h1 {
                font-size: 26px;
            }

            p {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="code">503</div>

    <h1>Сервис временно недоступен</h1>

    <div class="status">
        <span class="dot"></span>
        <p>Попробуйте <span class="reload" onclick="reloadPage()">обновить</span> страницу через несколько минут.</p>
    </div>

    <div class="footer">
        Мы уже работаем над устранением проблемы.
    </div>
</div>

</body>
</html>

<script>
function reloadPage() {
    window.location.reload();
}
</script>
