<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Gestion de stage')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        :root{
            --primary:#2563eb;
            --dark:#111827;
            --muted:#6b7280;
            --bg:#f3f4f6;
            --card:#ffffff;
            --border:#e5e7eb;
            --green:#16a34a;
            --red:#dc2626;
            --orange:#ea580c;
        }
        *{box-sizing:border-box}
        body{margin:0;font-family:Arial,Helvetica,sans-serif;background:var(--bg);color:var(--dark)}
        a{text-decoration:none;color:inherit}
        .layout{display:flex;min-height:100vh}
        .sidebar{width:260px;background:#0f172a;color:white;padding:22px;position:fixed;top:0;bottom:0;left:0;overflow:auto}
        .brand{font-size:20px;font-weight:700;margin-bottom:22px}
        .user-box{background:#1e293b;border-radius:12px;padding:12px;margin-bottom:18px;font-size:13px}
        .nav a{display:block;padding:10px 12px;border-radius:10px;color:#e5e7eb;margin-bottom:6px}
        .nav a:hover{background:#1e293b}
        .content{margin-left:260px;width:calc(100% - 260px);padding:28px}
        .topbar{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px}
        .card{background:var(--card);border:1px solid var(--border);border-radius:14px;padding:18px;box-shadow:0 8px 20px rgba(15,23,42,.04);margin-bottom:18px}
        .grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px}
        .stat{padding:18px;border-radius:14px;background:white;border:1px solid var(--border)}
        .stat span{display:block;color:var(--muted);font-size:13px}
        .stat strong{display:block;font-size:28px;margin-top:8px}
        .btn{display:inline-block;padding:9px 14px;border-radius:9px;border:none;background:var(--primary);color:white;cursor:pointer;font-size:14px}
        .btn.secondary{background:#475569}
        .btn.green{background:var(--green)}
        .btn.red{background:var(--red)}
        .btn.orange{background:var(--orange)}
        .btn.small{padding:6px 10px;font-size:13px}
        table{width:100%;border-collapse:collapse;background:white;border-radius:14px;overflow:hidden}
        th,td{padding:12px;border-bottom:1px solid var(--border);text-align:left;font-size:14px;vertical-align:top}
        th{background:#f8fafc;font-size:13px;color:#475569}
        .badge{display:inline-block;padding:4px 8px;border-radius:20px;font-size:12px;background:#e5e7eb}
        .badge.green{background:#dcfce7;color:#166534}
        .badge.red{background:#fee2e2;color:#991b1b}
        .badge.orange{background:#ffedd5;color:#9a3412}
        .badge.blue{background:#dbeafe;color:#1d4ed8}
        .form-group{margin-bottom:14px}
        label{display:block;margin-bottom:6px;font-weight:600;font-size:14px}
        input,select,textarea{width:100%;padding:10px;border:1px solid var(--border);border-radius:10px;font-size:14px;background:white}
        textarea{min-height:110px}
        .alert{padding:12px;border-radius:10px;margin-bottom:14px}
        .alert.success{background:#dcfce7;color:#166534}
        .alert.error{background:#fee2e2;color:#991b1b}
        .actions{display:flex;gap:8px;align-items:center;flex-wrap:wrap}
        .muted{color:var(--muted);font-size:13px}
        @media(max-width:900px){
            .sidebar{position:relative;width:100%;height:auto}
            .layout{display:block}
            .content{margin-left:0;width:100%}
            .grid{grid-template-columns:repeat(2,1fr)}
        }
        @media(max-width:600px){.grid{grid-template-columns:1fr}}
    </style>
</head>
<body>
<div class="layout">

    @auth
        <aside class="sidebar">
            <div class="brand">Gestion Stage</div>

            <div class="user-box">
                <strong>{{ auth()->user()->name }}</strong><br>
                <span>{{ auth()->user()->role }}</span>
            </div>

            <nav class="nav">

                @if(auth()->user()->role === 'super_admin')
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                    <a href="{{ route('organizations.index') }}">Organismes</a>
                    <a href="{{ route('users.index') }}">Utilisateurs</a>
                    <a href="{{ route('offers.index') }}">Offres</a>
                    <a href="{{ route('applications.index') }}">Candidatures</a>
                    <a href="{{ route('ia.index') }}">Analyse IA</a>
                    <a href="{{ route('internships.index') }}">Stages</a>
                    <a href="{{ route('suivis.create') }}">Ajouter suivi</a>
                    <a href="{{ route('evaluations.create') }}">Ajouter évaluation</a>
                    <a href="{{ route('soutenances.index') }}">Soutenances</a>
              {{-- <a href="{{ route('soutenances.create') }}">Planifier soutenance</a> --}}                    
                @endif

                @if(auth()->user()->role === 'admin_organisme')
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                    <a href="{{ route('offers.index') }}">Offres</a>
                    <a href="{{ route('applications.index') }}">Candidatures</a>
                    <a href="{{ route('ia.index') }}">Analyse IA</a>
                    <a href="{{ route('internships.index') }}">Stages</a>
                    <a href="{{ route('suivis.create') }}">Ajouter suivi</a>
                    <a href="{{ route('evaluations.create') }}">Ajouter évaluation</a>
                    <a href="{{ route('soutenances.index') }}">Soutenances</a>
              {{-- <a href="{{ route('soutenances.create') }}">Planifier soutenance</a> --}}                    
                @endif

                @if(auth()->user()->role === 'encadrant')
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                    <a href="{{ route('internships.index') }}">Stages</a>
                    <a href="{{ route('suivis.create') }}">Ajouter suivi</a>
                    <a href="{{ route('evaluations.create') }}">Ajouter évaluation</a>
                    <a href="{{ route('soutenances.index') }}">Soutenances</a>
                    
              {{-- <a href="{{ route('soutenances.create') }}">Planifier soutenance</a> --}}                    
               @endif

                @if(auth()->user()->role === 'etudiant')
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                    <a href="{{ route('offers.index') }}">Offres</a>
                    <a href="{{ route('internships.index') }}">Mon stage</a>
                @endif

            </nav>
        </aside>
    @endauth

    <main class="content" @guest style="margin-left:0;width:100%;" @endguest>
        @auth
            <div class="topbar">
                <h2>@yield('title', 'Gestion de stage')</h2>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn secondary" type="submit">Déconnexion</button>
                </form>
            </div>
        @endauth

        @if(session('success'))
            <div class="alert success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert error">{{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="alert error">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @yield('content')
    </main>

</div>
</body>
</html>
