<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - User Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/verify-email.css') }}">
</head>
<body>
    <div class="verify-container">
        <div class="verify-content">
            <i class='bx bx-envelope-open' style="font-size: 64px;"></i>
            <h1>Verify Your Email Address</h1>
            <p>Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.</p>

            @if (session('resent'))
                <div class="alert alert-success">
                    A fresh verification link has been sent to your email address.
                </div>
            @endif

            <div class="timer">
                <p>Please verify your email within <span id="countdown">1:00</span> minutes.</p>
            </div>

            <form method="POST" action="{{ route('verification.send') }}" id="resend-form">
                @csrf
                <button type="submit" class="resend-button" id="resend-button" disabled>Resend Verification Email</button>
            </form>
        </div>
    </div>

    <script>
        let countdown = 60; // 1 minute in seconds
        let resendAttempts = 0;
        const maxResendAttempts = 3;
        const resendCooldown = 300; // 5 minutes in seconds

        const countdownElement = document.getElementById('countdown');
        const resendButton = document.getElementById('resend-button');

        function updateCountdown() {
            const minutes = Math.floor(countdown / 60);
            const seconds = countdown % 60;
            countdownElement.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;

            if (countdown > 0) {
                countdown--;
                setTimeout(updateCountdown, 1000);
            } else {
                resendButton.disabled = false;
            }
        }

        document.getElementById('resend-form').addEventListener('submit', function(event) {
            event.preventDefault();

            if (resendAttempts < maxResendAttempts) {
                resendAttempts++;
                this.submit();
                resendButton.disabled = true;
                countdown = resendCooldown;
                updateCountdown();
            } else {
                alert('You have reached the maximum number of resend attempts. Please try again later.');
            }
        });

        updateCountdown();

        // Check if the email is verified every 5 seconds
        setInterval(function() {
            fetch('{{ route('verification.status') }}')
                .then(response => response.json())
                .then(data => {
                    if (data.verified) {
                        window.location.href = '/users/home';
                    }
                });
        }, 5000);
    </script>
</body>
</html>
