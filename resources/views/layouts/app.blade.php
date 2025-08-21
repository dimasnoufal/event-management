<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Panel')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --sidebar-w: 260px;
            --surface: #ffffff;
            --bg: #f6f7fb;
            --border: #eef0f4;
            --primary: #2f6bff;
            --radius: 16px;
        }

        html,
        body {
            height: 100%;
        }

        body {
            background: var(--bg);
        }

        .app-shell {
            min-height: 100vh;
            display: grid;
            grid-template-columns: var(--sidebar-w) 1fr;
            transition: grid-template-columns 0.3s ease;
        }

        .app-shell.sidebar-collapsed {
            grid-template-columns: 70px 1fr;
        }

        .app-shell.sidebar-collapsed .app-sidebar {
            width: 70px;
            overflow: hidden;
            transition: width 0.3s ease;
        }

        .app-shell.sidebar-collapsed .app-sidebar .brand .fw-bold,
        .app-shell.sidebar-collapsed .app-sidebar .side-link span:not(.side-icon),
        .app-shell.sidebar-collapsed .app-sidebar .side-sep,
        .app-shell.sidebar-collapsed .app-sidebar .sidebar-spacer {
            display: none;
        }

        .app-shell.sidebar-collapsed .app-sidebar .brand {
            justify-content: center;
            padding: 10px;
        }

        .app-shell.sidebar-collapsed .app-sidebar .side-link {
            justify-content: center;
            padding: 10px;
            position: relative;
        }

        .app-shell.sidebar-collapsed .app-sidebar .nav-group {
            gap: 8px;
        }

        .app-shell.sidebar-collapsed .app-sidebar #btnCollapse {
            padding: 8px;
            font-size: 0;
        }

        .app-shell.sidebar-collapsed .app-sidebar #btnCollapse i {
            font-size: 1rem;
        }

        @media (max-width: 992px) {
            .app-shell {
                grid-template-columns: 1fr;
            }

            .app-shell.sidebar-collapsed {
                grid-template-columns: 1fr;
            }

            .app-shell.sidebar-collapsed .app-sidebar {
                transform: translateX(-100%);
                width: 60%;
            }

            .app-sidebar {
                position: fixed;
                z-index: 1040;
                inset: 0 40% 0 0;
                transform: translateX(-100%);
                transition: transform 0.25s ease;
                width: 60%;
            }

            .app-sidebar.show {
                transform: none;
            }

            .sidebar-backdrop {
                display: none;
            }

            .sidebar-backdrop.show {
                display: block;
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, .25);
                z-index: 1039;
            }

            .app-shell.sidebar-collapsed .app-sidebar .brand .fw-bold,
            .app-shell.sidebar-collapsed .app-sidebar .side-link span:not(.side-icon),
            .app-shell.sidebar-collapsed .app-sidebar .side-sep,
            .app-shell.sidebar-collapsed .app-sidebar .sidebar-spacer {
                display: block;
            }

            .app-shell.sidebar-collapsed .app-sidebar .brand {
                justify-content: flex-start;
                padding: 10px 12px;
            }

            .app-shell.sidebar-collapsed .app-sidebar .side-link {
                justify-content: flex-start;
                padding: 10px 12px;
            }

            .app-shell.sidebar-collapsed .app-sidebar #btnCollapse {
                padding: 8px 16px;
                font-size: inherit;
            }
        }

        .app-sidebar {
            background: var(--surface);
            border-right: 1px solid var(--border);
            padding: 16px 14px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            transition: width 0.3s ease;
            overflow: visible;
        }

        .app-shell.sidebar-collapsed .side-link {
            position: relative;
        }

        .app-shell.sidebar-collapsed .side-link:hover::after {
            content: attr(data-tooltip);
            position: absolute;
            left: 70px;
            top: 50%;
            transform: translateY(-50%);
            background: #333;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            white-space: nowrap;
            z-index: 1000;
            font-size: 14px;
            opacity: 1;
            pointer-events: none;
        }

        .app-shell.sidebar-collapsed .side-link:hover::before {
            content: '';
            position: absolute;
            left: 62px;
            top: 50%;
            transform: translateY(-50%);
            border: 8px solid transparent;
            border-right-color: #333;
            z-index: 1000;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            margin-bottom: 4px;
        }

        .brand .logo {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, #e8f0ff, #fff);
            border: 1px solid #e7ecff;
            color: var(--primary);
            font-weight: 800;
        }

        .nav-group {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .side-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 12px;
            color: #111827;
            text-decoration: none;
        }

        .side-link:hover {
            background: #f2f4ff;
        }

        .side-link.active {
            background: #ffe9a6;
        }

        .side-icon {
            width: 22px;
            display: inline-grid;
            place-items: center;
        }

        .side-sep {
            border-top: 1px solid var(--border);
            margin: 10px 0;
        }

        .sidebar-spacer {
            flex: 1;
        }

        .topbar {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
        }

        .topbar .avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
        }

        .page {
            padding: 20px 22px 28px;
        }

        footer {
            background: var(--surface);
            border-top: 1px solid var(--border);
        }

        .card-lite {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: 0 6px 20px rgba(20, 33, 61, .06);
        }
    </style>

    @stack('styles')
</head>

<body>

    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    <div class="app-shell">

        <aside class="app-sidebar" id="sidebar">
            <div class="brand">
                <div class="logo">EM</div>
                <div class="fw-bold">Event Manager</div>
            </div>

            <nav class="nav-group">
                <a class="side-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                    href="{{ route('admin.dashboard') }}" data-tooltip="Dashboard">
                    <span class="side-icon"><i class="bi bi-speedometer2"></i></span> <span>Dashboard</span>
                </a>
                <a class="side-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                    href="{{ route('admin.users.index') }}" data-tooltip="Manage User">
                    <span class="side-icon"><i class="bi bi-person"></i></span> <span>Manage User</span>
                </a>
                <a class="side-link {{ request()->routeIs('admin.events.*') ? 'active' : '' }}"
                    href="{{ route('admin.events.index') }}" data-tooltip="Manage Events">
                    <span class="side-icon"><i class="bi bi-calendar-event"></i></span> <span>Manage Events</span>
                </a>
                <a class="side-link {{ request()->routeIs('admin.registrations.*') ? 'active' : '' }}"
                    href="{{ route('admin.registrations.index') }}" data-tooltip="Manage Registrations">
                    <span class="side-icon"><i class="bi bi-ui-checks"></i></span> <span>Manage Registrations</span>
                </a>
                <a class="side-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}"
                    href="{{ route('admin.payments.index') }}" data-tooltip="Manage Payments">
                    <span class="side-icon"><i class="bi bi-currency-dollar"></i></span> <span>Manage Payments</span>
                </a>
            </nav>

            <div class="side-sep"></div>

            <div class="sidebar-spacer"></div>

            <nav class="nav-group">
                <a class="side-link" href="{{ route('logout') }}" data-tooltip="Log Out"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <span class="side-icon"><i class="bi bi-box-arrow-right"></i></span> <span>Log Out</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
            </nav>

            <div class="mt-2">
                <button class="btn btn-outline-secondary w-100" id="btnCollapse" title="Collapse/Expand Sidebar">
                    <i class="bi bi-chevron-left me-1"></i> <span>Collapse Sidebar</span>
                </button>
            </div>
        </aside>

        <div class="d-flex flex-column min-vh-100">
            <header class="topbar py-2">
                <div class="container-fluid px-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <button class="btn btn-link d-lg-none" id="btnSidebar"><i class="bi bi-list fs-3"></i></button>
                        <div class="fw-semibold">@yield('title', 'Dashboard')</div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge text-bg-light">{{ auth()->user()->name ?? 'Admin' }}</span>
                            <img class="avatar" src="https://i.pravatar.cc/80?img=12" alt="avatar">
                        </div>
                    </div>
                </div>
            </header>

            <main class="page flex-grow-1">
                <div class="container-fluid px-3">
                    @yield('content')
                </div>
            </main>

            <footer class="py-3 mt-auto">
                <div class="container-fluid px-3 text-center text-muted small">
                    &copy; {{ date('Y') }} Event Management — All rights reserved.
                </div>
            </footer>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebar = document.getElementById('sidebar');
        const backdrop = document.getElementById('sidebarBackdrop');
        const btnOpen = document.getElementById('btnSidebar'); 
        const btnClose = document.getElementById('btnCollapse'); 
        const appShell = document.querySelector('.app-shell');

        function isMobile() {
            return window.innerWidth <= 992;
        }

        if (btnOpen) {
            btnOpen.addEventListener('click', () => {
                sidebar.classList.toggle('show');
                backdrop.classList.toggle('show');
            });
        }
        
        if (backdrop) {
            backdrop.addEventListener('click', () => {
                sidebar.classList.remove('show');
                backdrop.classList.remove('show');
            });
        }

        if (btnClose) {
            btnClose.addEventListener('click', () => {
                if (isMobile()) {
                    sidebar.classList.remove('show');
                    backdrop.classList.remove('show');
                } else {
                    appShell.classList.toggle('sidebar-collapsed');
                    
                    const isCollapsed = appShell.classList.contains('sidebar-collapsed');
                    
                    if (isCollapsed) {
                        btnClose.innerHTML = '<i class="bi bi-chevron-right"></i>';
                        btnClose.title = 'Expand Sidebar';
                    } else {
                        btnClose.innerHTML = '<i class="bi bi-chevron-left me-1"></i> <span>Collapse Sidebar</span>';
                        btnClose.title = 'Collapse Sidebar';
                    }
                }
            });
        }

        window.addEventListener('resize', () => {
            if (!isMobile()) {
                sidebar.classList.remove('show');
                backdrop.classList.remove('show');
            } else {
                appShell.classList.remove('sidebar-collapsed');
                btnClose.innerHTML = '<i class="bi bi-chevron-left me-1"></i> <span>Collapse Sidebar</span>';
                btnClose.title = 'Collapse Sidebar';
            }
        });
    </script>
    @stack('scripts')
</body>

</html>
