<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('page-title') | Konichiwa Admin</title>
    
    <!-- Fonts -->
    <!-- <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"> -->
    
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/fontawesome-5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/vendor/bootstrap.min.css') }}">
    @stack('styles')
    
    <style>
        :root {
            --primary: #f26522;
            --primary-soft: rgba(242, 101, 34, 0.1);
            --sidebar-bg: #0f172a;
            --sidebar-active: #1e293b;
            --bg-body: #f8fafc;
            --text-main: #334155;
            --text-muted: #64748b;
            --card-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --sidebar-width: 280px;
        }

        /* Preloader */
        .loader-wrapper {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
            transition: all 0.5s ease;
        }
        .loader-wrapper .loader {
            text-align: center;
        }
        .loader-wrapper img {
            max-width: 150px;
            height: auto;
            animation: pulse 2s infinite ease-in-out;
        }
        @keyframes pulse {
            0% { transform: scale(1); opacity: 0.8; }
            50% { transform: scale(1.05); opacity: 1; }
            100% { transform: scale(1); opacity: 0.8; }
        }
        body.loaded .loader-wrapper {
            opacity: 0;
            visibility: hidden;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            overflow-x: hidden;
        }

        /* Sidebar */
        .admin-sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background-color: var(--sidebar-bg);
            color: #fff;
            z-index: 1050;
            transition: all 0.3s ease;
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar-brand {
            padding: 30px 25px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .sidebar-brand img {
            height: 35px;
            filter: brightness(0) invert(1);
        }

        .sidebar-nav {
            padding: 20px 15px;
        }

        .nav-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255,255,255,0.4);
            margin-bottom: 15px;
            margin-top: 25px;
            padding-left: 10px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 5px;
            transition: all 0.2s ease;
            font-weight: 500;
        }

        .sidebar-link i {
            width: 24px;
            font-size: 18px;
            margin-right: 12px;
            transition: all 0.2s ease;
        }

        .sidebar-link:hover {
            color: #fff;
            background-color: rgba(255,255,255,0.05);
        }

        .sidebar-link.active {
            color: #fff;
            background-color: var(--primary);
            box-shadow: 0 4px 12px rgba(242, 101, 34, 0.3);
        }

        .sidebar-link.active i {
            color: #fff;
        }

        /* Main Content */
        .admin-main {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        .admin-header {
            height: 70px;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .header-title h4 {
            margin: 0;
            font-weight: 700;
            color: #1e293b;
            font-size: 1.25rem;
        }

        .user-nav {
            display: flex;
            align-items: center;
        }

        .user-badge {
            background: var(--bg-body);
            padding: 5px 15px;
            border-radius: 50px;
            display: flex;
            align-items: center;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            border: 1px solid #e2e8f0;
        }

        .user-badge img {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            margin-left: 10px;
        }

        .content-inner {
            padding: 30px;
        }

        /* Cards & Components */
        .premium-card {
            background: #fff;
            border-radius: 16px;
            border: none;
            box-shadow: var(--card-shadow);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .premium-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1);
        }

        .stat-widget {
            padding: 25px;
            position: relative;
            overflow: hidden;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .table-premium {
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        .table-premium tr {
            background: #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
            transition: all 0.2s ease;
        }

        .table-premium tr:hover {
            background: #f8fafc;
            transform: scale(1.005);
        }

        .table-premium td, .table-premium th {
            padding: 15px 20px;
            border: none;
        }

        .table-premium td:first-child { border-radius: 12px 0 0 12px; }
        .table-premium td:last-child { border-radius: 0 12px 12px 0; }

        .btn-premium {
            padding: 10px 24px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-premium-primary {
            background: var(--primary);
            color: #fff;
            border: none;
        }

        .btn-premium-primary:hover {
            background: #d4561b;
            box-shadow: 0 4px 12px rgba(242, 101, 34, 0.4);
            transform: translateY(-1px);
        }

        .badge-premium {
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 12px;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade {
            animation: fadeIn 0.4s ease forwards;
        }

        @media (max-width: 991px) {
            .admin-sidebar { left: -var(--sidebar-width); }
            .admin-sidebar.show { left: 0; }
            .admin-main { margin-left: 0; }
        }

        button.sidebar-link { font: inherit; cursor: pointer; }
.user-avatar {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #f26522;
    color: #ffffff;
    font-weight: 600;
}
    </style>
</head>
<body>
    <!-- start loader -->
    <div class="loader-wrapper">
        <div class="loader">
            <img src="{{ asset('assets/images/logo/logo10.png') }}" alt="Logo">
        </div>
    </div>
    <!-- End loader -->

    <aside class="admin-sidebar">
        <div class="sidebar-brand">
            <img src="{{ asset('assets/images/logo/logo10.png') }}" alt="Konichiwa">
            <span class="ms-3 fw-bold fs-5">ADMIN</span>
        </div>
        
        <div class="sidebar-nav">
            <div class="nav-label">Main Menu</div>
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-th-large"></i> Dashboard
            </a>
            
            <div class="nav-label">Content</div>
            <a href="{{ route('admin.blogs.index') }}" class="sidebar-link {{ request()->routeIs('admin.blogs.*') ? 'active' : '' }}">
                <i class="fas fa-edit"></i> Manage Blogs
            </a>
            <a href="{{ route('admin.farmer-stories.index') }}" class="sidebar-link {{ request()->routeIs('admin.farmer-stories.*') ? 'active' : '' }}">
                <i class="fas fa-seedling"></i> Farmer Stories
            </a>
            <a href="{{ route('admin.contacts.index') }}" class="sidebar-link {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}">
                <i class="fas fa-paper-plane"></i> Contact Requests
            </a>
            <a href="{{ route('admin.settings.edit') }}" class="sidebar-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <i class="fas fa-address-book"></i> Contact Details
            </a>
            
            <div class="nav-label">System</div>
            <a href="{{ route('home') }}" target="_blank" class="sidebar-link">
                <i class="fas fa-globe"></i> View Website
            </a>
            
            <div style="margin-top: 50px;">
                <form method="POST" action="{{ route('logout') }}" id="logout-form">
    @csrf
    <button type="submit" class="sidebar-link text-danger border-0 bg-transparent w-100 text-start">
        <i class="fas fa-power-off"></i> Sign Out
    </button>
</form>
            </div>
        </div>
    </aside>

    <main class="admin-main">
        <header class="admin-header">
            <div class="header-title">
                <h4>@yield('page-title')</h4>
            </div>
            <div class="user-nav">
                <div class="user-badge">
                    <span>{{ auth()->user()->name }}</span>
                    <span class="user-avatar" aria-hidden="true">{{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
                </div>
            </div>
        </header>

        <div class="content-inner animate-fade">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 12px; background: #ecfdf5; color: #065f46;">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <script src="{{ asset('assets/js/vendor/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/bootstrap.min.js') }}"></script>
  <script src="{{ asset('assets/redesign/js/admin.js') }}"></script>
    @stack('scripts')
</body>
</html>

