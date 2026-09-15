<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PARKING POINT - @yield('title', 'Dashboard')</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #0a2540;
            --primary-dark: #061a30;
            --primary-light: #1e3a5f;
            --accent: #4a90e2;
            --bg-light: #f4f6f9;
            --text-dark: #2c3e50;
        }

        * { font-family: 'Poppins', sans-serif; }

        body {
            background: var(--bg-light);
            color: var(--text-dark);
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            background: linear-gradient(180deg, var(--primary) 0%, var(--primary-dark) 100%);
            min-height: 100vh;
            width: 260px;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            transition: all 0.3s;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar-brand {
            padding: 25px 20px;
            color: #fff;
            font-size: 1.4rem;
            font-weight: 700;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-user {
            padding: 20px;
            color: #fff;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            text-align: center;
        }

        .sidebar-user .avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--accent);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0 auto 10px;
        }

        .sidebar-menu {
            list-style: none;
            padding: 15px 0;
            margin: 0;
        }

        .sidebar-menu .menu-label {
            color: rgba(255,255,255,0.5);
            font-size: 0.75rem;
            text-transform: uppercase;
            padding: 10px 20px 5px;
            letter-spacing: 1px;
        }

        .sidebar-menu a {
            color: rgba(255,255,255,0.85);
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: rgba(255,255,255,0.08);
            color: #fff;
            border-left-color: var(--accent);
        }

        .sidebar-menu a i { font-size: 1.1rem; width: 20px; }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            margin-left: 260px;
            min-height: 100vh;
            transition: all 0.3s;
        }

        /* ===== NAVBAR ===== */
        .top-navbar {
            background: #fff;
            padding: 15px 30px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .top-navbar .page-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--primary);
            margin: 0;
        }

        /* ===== CARDS ===== */
        .card-custom {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(10,37,64,0.08);
            transition: transform 0.2s;
        }

        .card-custom:hover { transform: translateY(-2px); }

        .stat-card {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: #fff;
            border-radius: 12px;
            padding: 25px;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: -30px;
            right: -30px;
            width: 120px;
            height: 120px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .stat-card .stat-icon {
            width: 50px;
            height: 50px;
            background: rgba(255,255,255,0.2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 15px;
        }

        .stat-card .stat-value {
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
        }

        .stat-card .stat-label {
            opacity: 0.85;
            font-size: 0.9rem;
        }

        /* ===== BUTTONS ===== */
        .btn-primary-custom {
            background: var(--primary);
            border-color: var(--primary);
            color: #fff;
        }

        .btn-primary-custom:hover {
            background: var(--primary-light);
            border-color: var(--primary-light);
            color: #fff;
        }

        /* ===== TABLE ===== */
        .table-custom thead {
            background: var(--primary);
            color: #fff;
        }

        .table-custom thead th {
            font-weight: 500;
            padding: 12px;
            border: none;
        }

        .table-custom tbody td {
            padding: 12px;
            vertical-align: middle;
        }

        /* ===== BADGES ===== */
        .badge-soft {
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 500;
            font-size: 0.8rem;
        }

        /* ===== LANDING ===== */
        .hero {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: #fff;
            padding: 100px 0;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0; right: 0;
            width: 500px; height: 500px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
            transform: translate(30%, -30%);
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
        }

        /* ===== PRINT ===== */
        @media print {
            .sidebar, .top-navbar, .no-print { display: none !important; }
            .main-content { margin-left:
