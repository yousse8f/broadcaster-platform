<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Broadcaster Platform</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f5f5f5;
            min-height: 100vh;
        }
        .navbar {
            background: white;
            padding: 20px 40px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .role-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .role-admin {
            background: #667eea;
            color: white;
        }
        .role-client {
            background: #3498db;
            color: white;
        }
        .logout-btn {
            padding: 10px 20px;
            background: #e74c3c;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            text-decoration: none;
            transition: background 0.3s;
        }
        .logout-btn:hover {
            background: #c0392b;
        }
        .content {
            padding: 40px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .welcome-card {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        }
        .welcome-card h1 {
            color: #333;
            margin-bottom: 10px;
        }
        .welcome-card p {
            color: #666;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="logo">Broadcaster Platform</div>
        <div class="user-info">
            <span class="role-badge role-{{ auth()->user()->role }}">
                {{ ucfirst(auth()->user()->role) }}
            </span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </nav>

    <div class="content">
        <div class="welcome-card">
            <h1>Welcome, {{ ucfirst(auth()->user()->role) }}!</h1>
            <p>You are logged in as <strong>{{ auth()->user()->name }}</strong> ({{ auth()->user()->email }})</p>
            <br>
            <p>Your role: <strong>{{ ucfirst(auth()->user()->role) }}</strong></p>
        </div>
    </div>
</body>
</html>