<!DOCTYPE html>
<html>
<head>
    <title>iBarangay Link</title>
    <link rel="stylesheet" href="/iBarangayLink/assets/css/style.css">
    <style>
        .home-container {
            max-width: 600px;
            margin: 80px auto;
            text-align: center;
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 12px rgba(0,0,0,0.1);
        }
        .home-title {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .home-sub {
            color: #555;
            margin-bottom: 25px;
        }
        .home-buttons a {
            display: inline-block;
            margin: 10px 15px;
            padding: 12px 25px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
        }
        .btn-resident {
            background: #4A67FF;
            color: white;
        }
        .btn-staff {
            background: #f1f1f1;
            color: #333;
            border: 1px solid #ccc;
        }
        .btn-resident:hover {
            opacity: 0.9;
        }
        .btn-staff:hover {
            background: #e5e5e5;
        }
    </style>
</head>
<body>

<div class="home-container">
    <div class="home-title">Welcome to iBarangay Link</div>
    <div class="home-sub">Please choose how you want to access the system.</div>

    <div class="home-buttons">
        <a href="resident_dashboard.php" class="btn-resident">Resident</a>
        <a href="login.php" class="btn-staff">Staff / Admin</a>
    </div>
</div>

</body>
</html>
