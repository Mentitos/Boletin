<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Álbum de Figuras</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(to bottom, #004080 20%, #6699cc 90%, #f0f0f0 100%);
            background-size: contain;
            background-attachment: fixed;
            font-family: Arial, sans-serif;
            overflow-x: hidden;
        }

        .container {
            text-align: center;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            max-width: 90%;
            margin: auto;
        }

        h1 {
            color: #004080;
            margin-bottom: 20px;
            font-size: 28px;
        }

        .btn {
            background-color: #004080;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 10px;
            text-decoration: none;
            font-size: 16px;
            display: inline-block;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #00264d;
        }

        @media (max-width: 600px) {
            h1 {
                font-size: 24px;
            }
            .btn {
                padding: 8px 16px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bienvenid@ a la prueba<br> de boletín</h1>
        <a href="login.php" class="btn">Iniciar Sesión</a>
        <a href="register.php" class="btn">Registrarse</a>
    </div>
</body>
</html>
