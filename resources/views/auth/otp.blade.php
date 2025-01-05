<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <style>
        /* Include the same or similar styles as in your register blade */
        :root {
            --bg-primary: #1a1a2e;
            --bg-secondary: #16213e;
            --color-accent: #0f3460;
            --color-text: #e94560;
            --color-text-secondary: #a9a9a9;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            background: linear-gradient(135deg, var(--color-text) 0%, var(--color-accent) 100%);
            position: relative;
            overflow: hidden;
        }

        .signup-container {
            display: flex;
            width: 100%;
            justify-content: space-between;
            align-items: center;
            padding: 40px;
        }

        .signup-form {
            background: white;
            padding: 40px;
            border-radius: 10px;
            width: 400px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-right: 100px;
        }

        .form-title {
            font-size: 24px;
            color: #333;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        .form-submit {
            width: 100%;
            padding: 12px;
            background-color: var(--color-text);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <div class="signup-form">
            <h2 class="form-title">OTP Verification</h2>
            <form action="{{ route('verify.otp') }}" method="POST">
                @csrf
                <div class="form-group">
                    <input type="text" name="otp" placeholder="OTP" required>
                </div>
                <button type="submit" class="form-submit">VERIFY</button>
            </form>
        </div>
    </div>
</body>
</html>
