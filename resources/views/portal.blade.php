```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Lost & Found Portal</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #212529;
            font-family: Arial, sans-serif;
        }

        .portal-card {
            background: white;
            width: 100%;
            max-width: 495px;
            padding: 45px 40px;
            border-radius: 27px;
            text-align: center;
            box-shadow: 0 15px 35px rgba(0,0,0,.3);
        }

        .portal-card h1 {
            font-size: 32px;
            font-weight: 800;
            color: #212529;
        }

        .subtitle {
            color: #6c757d;
            margin: 12px 0 30px;
        }

        .login-option {
            display: block;
            text-decoration: none;
            padding: 20px;
            border-radius: 11px;
            margin-top: 18px;
            transition: .2s ease;
        }

        .login-option:hover {
            transform: translateY(-3px);
            opacity: .92;
        }

        .admin-option {
            background: #212529;
            color: white;
        }

        .user-option {
            background: #f1f3f5;
            color: #212529;
            border: 1px solid #dee2e6;
        }

        .login-option i {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .login-option h2 {
            font-size: 21px;
            font-weight: 800;
            margin-bottom: 6px;
        }

        .login-option p {
            margin: 0;
            font-size: 14px;
        }

        @media (max-width: 480px) {
            .portal-card {
                padding: 35px 22px;
            }

            .portal-card h1 {
                font-size: 27px;
            }
        }
    </style>
</head>

<body>

<div class="portal-card">

    <h1>LOST &amp; FOUND PORTAL</h1>

    <p class="subtitle">
        Lost and Found Management System
    </p>

    <a href="{{ route('admin.login') }}"
       class="login-option admin-option">

        <i class="fa-solid fa-shield-halved"></i>

        <h2>ADMIN LOGIN</h2>

        <p>For system administrators</p>
    </a>

    <a href="{{ route('user.login') }}"
       class="login-option user-option">

        <i class="fa-solid fa-user"></i>

        <h2>USER LOGIN</h2>

        <p>For registered users</p>
    </a>

    <p class="text-muted small mt-4 mb-0">
        Lost and Found Management System
    </p>

</div>

</body>
</html>
```