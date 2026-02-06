<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Simple styling (no Tailwind dependency needed) --}}
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: system-ui, -apple-system, BlinkMacSystemFont;
            background: #0f172a;
            color: #e5e7eb;
        }

        .card {
            width: 100%;
            max-width: 420px;
            background: #020617;
            border-radius: 12px;
            padding: 32px;
            box-shadow: 0 20px 40px rgba(0,0,0,.4);
        }

        h1 {
            margin-bottom: 24px;
            text-align: center;
            font-size: 24px;
        }

        .field {
            margin-bottom: 16px;
        }

        label {
            display: block;
            font-size: 14px;
            margin-bottom: 6px;
            color: #94a3b8;
        }

        input {
            width: 100%;
            padding: 10px 12px;
            border-radius: 8px;
            border: 1px solid #1e293b;
            background: #020617;
            color: #e5e7eb;
        }

        input:focus {
            outline: none;
            border-color: #6366f1;
        }

        .error {
            color: #f87171;
            font-size: 13px;
            margin-top: 6px;
        }

        button {
            width: 100%;
            padding: 12px;
            margin-top: 12px;
            border: none;
            border-radius: 8px;
            background: #6366f1;
            color: white;
            font-weight: 600;
            cursor: pointer;
        }

        button:hover {
            background: #4f46e5;
        }
    </style>
</head>
<body>

<div class="card">
    <h1>Admin Login</h1>

    <form method="POST" action="{{ route('blade.admin.login.submit') }}">
        @csrf

        <div class="field">
            <label>Email or Username</label>
            <input
                type="text"
                name="login"
                value="{{ old('login') }}"
                required
            >
            @error('login')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="field">
            <label>Password</label>
            <input
                type="password"
                name="password"
                required
            >
            @error('password')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit">
            Sign in
        </button>
    </form>
</div>

</body>
</html>
