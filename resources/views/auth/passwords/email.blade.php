<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="{{ asset('css/email.css') }}">
</head>
<body>
    <div class="container">
        <button class="back-button" onclick="goBack()">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m12 19-7-7 7-7"/>
                <path d="M19 12H5"/>
            </svg>
            Back
        </button>
        <div>
            <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            </svg>
            <h2>Reset Your Password</h2>
            <p class="text-muted">
                Enter your email address to receive a password reset link.
            </p>
        </div>
        
        <!-- Success Alert Section -->
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" id="email" class="input @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>
                
                <!-- Validation Error Display -->
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <button type="submit" class="button">Send Reset Link</button>
        </form>
        <p class="info">
            We'll send you an email with instructions to reset your password.
        </p>
    </div>

    <script>
        function goBack() {
            window.history.back();
        }
    </script>
</body>
</html>
