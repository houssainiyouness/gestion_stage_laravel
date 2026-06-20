<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Gestion des stages')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        :root{
            --navy-950:#06162d;
            --navy-900:#082044;
            --navy-850:#0b2a5b;
            --navy-800:#123873;
            --blue-700:#1e40af;
            --blue-600:#2563eb;
            --blue-500:#3b82f6;
            --cyan-500:#0ea5e9;
            --cyan-400:#38bdf8;
            --ink:#0b1f3f;
            --text:#1f2f4a;
            --muted:#64748b;
            --soft:#f5f8fc;
            --soft-2:#eef4fb;
            --card:#ffffff;
            --border:#dbe5f1;
            --line:#e6edf6;
            --shadow:0 18px 44px rgba(8,32,68,.075);
            --shadow-sm:0 10px 24px rgba(8,32,68,.055);
            --radius:18px;
            --radius-lg:24px;
            --gradient:linear-gradient(135deg,#1d4ed8 0%,#0ea5e9 100%);
            --gradient-dark:linear-gradient(135deg,#06162d 0%,#0b2a5b 55%,#123873 100%);
            --danger:#b4234a;
            --warning:#7c5a20;
        }

        *{box-sizing:border-box}
        html{scroll-behavior:smooth}
        body{
            margin:0;
            font-family:"Inter","Segoe UI",Roboto,Arial,sans-serif;
            background:radial-gradient(circle at 12% 8%,rgba(37,99,235,.09),transparent 28%),var(--soft);
            color:var(--ink);
            min-height:100vh;
            -webkit-font-smoothing:antialiased;
        }
        a{text-decoration:none;color:inherit}
        button,input,select,textarea{font-family:inherit}
        svg{display:block;flex-shrink:0}

        .guest-content{min-height:100vh}

        .institution-shell{min-height:100vh;background:var(--soft)}
        .institution-topline{
            height:46px;
            display:flex;
            align-items:center;
            justify-content:space-between;
            padding:0 32px;
            background:rgba(255,255,255,.88);
            border-bottom:1px solid var(--line);
            color:#263a5c;
            font-size:14px;
            backdrop-filter:blur(10px);
        }
        .institution-topline strong{color:var(--ink)}
        .topline-right{display:flex;gap:22px;align-items:center;white-space:nowrap}
        .topline-sep{width:1px;height:18px;background:#cad6e6}

        .brandbar{
            min-height:84px;
            display:flex;
            align-items:center;
            justify-content:space-between;
            padding:16px 32px;
            background:rgba(255,255,255,.94);
            border-bottom:1px solid var(--line);
        }
        .brand-block{display:flex;align-items:center;gap:14px}
        .brand-logo,.login-logo{
            width:54px;height:54px;border-radius:15px;
            display:grid;place-items:center;color:white;
            background:var(--gradient);
            box-shadow:0 12px 24px rgba(30,64,175,.20);
        }
        .brand-title{font-size:24px;font-weight:900;letter-spacing:-.03em;color:var(--ink);line-height:1.05}
        .brand-subtitle{margin-top:5px;font-size:12px;font-weight:800;letter-spacing:.15em;text-transform:uppercase;color:#64748b}
        .account-area{display:flex;align-items:center;gap:14px}
        .user-chip{display:flex;align-items:center;gap:10px;color:var(--ink);font-weight:800}
        .avatar{
            width:44px;height:44px;border-radius:50%;display:grid;place-items:center;
            color:var(--blue-700);background:#eef4ff;border:1px solid #dbeafe;font-weight:900;
        }
        .logout-form{margin:0}
        .logout-btn{
            display:inline-flex;align-items:center;gap:9px;
            height:44px;padding:0 20px;border-radius:10px;border:1px solid var(--border);
            background:#fff;color:var(--ink);font-weight:900;font-size:14px;cursor:pointer;
            box-shadow:0 5px 16px rgba(9,30,66,.04);
            transition:.2s ease;
        }
        .logout-btn:hover{transform:translateY(-1px);border-color:#bad2f5;color:var(--blue-700)}

        .main-nav{
            display:flex;align-items:center;gap:6px;
            padding:11px 32px;
            background:#fff;
            border-bottom:1px solid var(--line);
            box-shadow:0 12px 25px rgba(9,30,66,.04);
            overflow:auto;
            scrollbar-width:thin;
        }
        .nav-link{
            display:inline-flex;align-items:center;
            min-height:36px;padding:0 13px;border-radius:10px;
            color:#334866;font-size:12.5px;font-weight:850;white-space:nowrap;
            border:1px solid transparent;
            letter-spacing:.01em;
            transition:.2s ease;
        }
        .nav-link:hover{background:#f2f7fd;color:var(--blue-700);border-color:#e0ebfa}
        .nav-link.active{background:var(--gradient);color:white;box-shadow:0 14px 26px rgba(30,64,175,.16)}

        .page-container{padding:34px 38px 46px;max-width:1540px;margin:0 auto}
        .page-intro{display:flex;align-items:flex-start;justify-content:space-between;gap:24px;margin-bottom:22px}
        .eyebrow{font-size:12px;letter-spacing:.12em;text-transform:uppercase;font-weight:900;color:#6b7d99;margin-bottom:8px}
        .page-title{margin:0;font-size:30px;line-height:1.1;color:var(--ink);font-weight:950;letter-spacing:-.04em}
        .role-badge{
            min-width:218px;display:flex;align-items:center;gap:12px;padding:15px 18px;border-radius:22px;background:white;
            border:1px solid var(--border);box-shadow:var(--shadow-sm);color:var(--blue-700);font-weight:900;
        }
        .role-badge .shield{width:40px;height:40px;border-radius:12px;display:grid;place-items:center;background:#f1f6ff;color:var(--blue-700)}
        .content-area{position:relative}

        .card{
            background:rgba(255,255,255,.96);
            border:1px solid var(--border);
            border-radius:var(--radius);
            padding:22px;
            box-shadow:var(--shadow-sm);
            margin-bottom:20px;
            overflow:auto;
        }
        .card h3{margin:0 0 18px;font-size:20px;color:var(--ink);letter-spacing:-.02em}
        .card h2{margin-top:0;color:var(--ink)}
        .grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:18px}

        .stat{
            display:flex;align-items:center;gap:18px;min-height:92px;padding:20px 22px;border-radius:17px;
            background:#fff;border:1px solid var(--line);box-shadow:var(--shadow-sm);
            transition:.2s ease;
        }
        .stat:hover{transform:translateY(-2px);box-shadow:var(--shadow)}
        .stat span{display:block;color:#586b88;font-size:14px;font-weight:750;margin-bottom:6px}
        .stat strong{display:block;color:var(--ink);font-size:30px;line-height:1;font-weight:950;letter-spacing:-.04em}
        .stat-icon{width:50px;height:50px;border-radius:14px;display:grid;place-items:center;background:#eef5ff;color:var(--blue-700)}
        .stat-icon.blue{background:#eef5ff;color:#1d4ed8}
        .stat-icon.cyan{background:#eaf7ff;color:#0369a1}
        .stat-icon.purple{background:#eef2ff;color:#4f46e5}
        .stat-icon.orange{background:#eef6ff;color:#2563eb}
        .stat-icon.gold{background:#f1f5f9;color:#334155}
        .stat-icon svg{width:24px;height:24px}

        .btn{
            display:inline-flex;align-items:center;justify-content:center;gap:9px;
            min-height:40px;padding:10px 16px;border-radius:10px;border:0;
            background:var(--gradient);color:white;cursor:pointer;font-size:14px;font-weight:850;
            box-shadow:0 12px 22px rgba(30,64,175,.16);transition:.2s ease;
        }
        .btn:hover{transform:translateY(-1px);filter:saturate(1.04)}
        .btn svg{width:18px;height:18px}
        .btn.secondary{background:#f8fbff;color:var(--ink);border:1px solid var(--border);box-shadow:none}
        .btn.green{background:linear-gradient(135deg,#1d4ed8,#0ea5e9)}
        .btn.red{background:#fff1f4;color:#b4234a;border:1px solid #f3c7d0;box-shadow:none}
        .btn.orange{background:#eef5ff;color:#1d4ed8;border:1px solid #dbeafe;box-shadow:none}
        .btn.small{min-height:32px;padding:7px 11px;font-size:12px;border-radius:9px;box-shadow:none}
        .actions{display:flex;gap:9px;align-items:center;flex-wrap:wrap}
        .actions h3{margin:0}

        table{width:100%;border-collapse:separate;border-spacing:0;background:white;border:1px solid var(--line);border-radius:16px;overflow:hidden}
        th,td{padding:14px 15px;border-bottom:1px solid var(--line);text-align:left;font-size:14px;vertical-align:middle;color:#263a5c}
        tr:last-child td{border-bottom:0}
        th{background:#f7fbff;font-size:12px;text-transform:uppercase;letter-spacing:.06em;color:#64748b;font-weight:950}
        tbody tr:hover td{background:#fbfdff}

        .badge{display:inline-flex;align-items:center;gap:6px;padding:6px 10px;border-radius:999px;font-size:12px;font-weight:900;background:#eef2f7;color:#475569}
        .badge.green{background:#eaf7ff;color:#075985}
        .badge.red{background:#fff1f4;color:#b4234a}
        .badge.orange{background:#eef5ff;color:#315682}
        .badge.blue{background:#eaf2ff;color:#1d4ed8}

        .form-group{margin-bottom:16px}
        label{display:block;margin-bottom:7px;font-weight:850;font-size:14px;color:#263a5c}
        input,select,textarea{
            width:100%;padding:12px 13px;border:1px solid #d8e3f1;border-radius:12px;font-size:14px;background:#fff;color:var(--ink);
            outline:none;transition:.18s ease;
        }
        input:focus,select:focus,textarea:focus{border-color:#60a5fa;box-shadow:0 0 0 4px rgba(37,99,235,.10)}
        textarea{min-height:116px;resize:vertical}
        input[type="checkbox"]{accent-color:var(--blue-600)}
        .muted{color:var(--muted);font-size:13px;line-height:1.55}

        .alert{padding:13px 15px;border-radius:14px;margin-bottom:16px;border:1px solid;font-size:14px;font-weight:700}
        .alert.success{background:#eef6ff;color:#1e40af;border-color:#bfdbfe}
        .alert.error{background:#fff1f4;color:#b4234a;border-color:#f3c7d0}

        .custom-pagination,.pagination{display:flex;gap:8px;align-items:center;justify-content:center;margin-top:20px;flex-wrap:wrap}
        .custom-pagination a,.custom-pagination span,.pagination a,.pagination span{
            min-width:36px;height:36px;display:inline-flex;align-items:center;justify-content:center;padding:0 12px;border-radius:10px;
            border:1px solid var(--border);background:white;color:#314666;font-weight:850;font-size:13px;
        }
        .custom-pagination .page-active,.pagination .active span{background:var(--gradient);color:white;border-color:transparent}
        .custom-pagination .page-disabled{opacity:.55;background:#f8fafc}

        .dashboard-layout{display:grid;grid-template-columns:minmax(0,1.85fr) minmax(320px,.95fr);gap:20px;margin-bottom:20px}
        .welcome-card{
            min-height:270px;border-radius:22px;padding:34px 36px;position:relative;overflow:hidden;color:white;background:var(--gradient-dark);box-shadow:0 22px 45px rgba(6,19,38,.16);
            display:flex;align-items:center;
        }
        .welcome-card:before{content:"";position:absolute;right:-60px;top:-70px;width:260px;height:260px;border-radius:50%;background:rgba(255,255,255,.06)}
        .welcome-card:after{content:"";position:absolute;right:210px;bottom:-45px;width:120px;height:120px;border-radius:50%;background:rgba(59,130,246,.10)}
        .welcome-content{position:relative;z-index:2;max-width:560px}
        .welcome-role{font-size:13px;letter-spacing:.12em;text-transform:uppercase;color:#7dd3fc;font-weight:950;margin-bottom:14px}
        .welcome-title{font-size:34px;line-height:1.1;font-weight:950;letter-spacing:-.04em;margin:0 0 16px;color:white}
        .welcome-text{font-size:17px;line-height:1.55;color:#e7eef9;margin:0 0 26px;max-width:520px}
        .hero-actions{display:flex;gap:14px;flex-wrap:wrap}

        .quick-actions-panel{background:#fff;border:1px solid var(--border);border-radius:22px;padding:24px;box-shadow:var(--shadow-sm);margin-bottom:20px}
        .quick-actions-head{display:flex;align-items:flex-start;justify-content:space-between;gap:18px;margin-bottom:16px}
        .quick-actions-head h3{margin:0;font-size:21px;letter-spacing:-.03em;color:var(--ink)}
        .quick-actions-head p{margin:6px 0 0;color:var(--muted);font-size:13px;line-height:1.5}
        .quick-action-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px}
        .quick-action{
            display:flex;align-items:center;gap:14px;padding:16px;border-radius:16px;background:#f8fbff;border:1px solid #e1eaf6;
            transition:.2s ease;color:var(--ink);min-height:78px;
        }
        .quick-action:hover{transform:translateY(-2px);background:#f3f8ff;border-color:#cfe0f6;box-shadow:0 12px 24px rgba(8,32,68,.06)}
        .quick-action-icon{width:44px;height:44px;border-radius:13px;display:grid;place-items:center;background:#eef5ff;color:var(--blue-700)}
        .quick-action-icon svg{width:21px;height:21px}
        .quick-action strong{display:block;font-size:14px;font-weight:950;color:var(--ink);margin-bottom:3px}
        .quick-action span{display:block;font-size:12.5px;line-height:1.35;color:var(--muted);font-weight:650}
        .btn-hero-outline{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.65);box-shadow:none;color:white}
        .hero-visual{position:absolute;right:24px;top:28px;bottom:20px;width:38%;min-width:300px;z-index:1;opacity:.98}
        .workflow-card{background:#fff;border:1px solid var(--border);border-radius:22px;padding:28px 30px;box-shadow:var(--shadow-sm)}
        .workflow-card h3{margin:0 0 18px;font-size:22px;letter-spacing:-.03em}
        .workflow-step{display:grid;grid-template-columns:40px 1fr;gap:14px;align-items:start;padding:14px 0;border-bottom:1px solid var(--line)}
        .workflow-step:last-child{border-bottom:0;padding-bottom:0}
        .step-number{width:34px;height:34px;border-radius:10px;display:grid;place-items:center;background:#edf5ff;color:var(--blue-700);font-weight:950}
        .workflow-step strong{display:block;margin-bottom:4px;font-size:14px;color:var(--ink)}
        .workflow-step span{font-size:13px;color:var(--muted);line-height:1.45}
        .stats-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:18px}

        .login-page{
            min-height:100vh;position:relative;overflow:hidden;background:linear-gradient(180deg,#f7fbff 0%,#eef5fd 100%);
        }
        .login-topbar{
            height:88px;background:var(--gradient-dark);border-bottom-left-radius:16px;border-bottom-right-radius:16px;
            display:flex;align-items:center;justify-content:space-between;padding:0 30px;color:white;box-shadow:0 12px 26px rgba(6,19,38,.18);
        }
        .login-brand{display:flex;align-items:center;gap:14px}
        .login-brand-title{font-size:22px;font-weight:950;letter-spacing:-.03em;line-height:1.08}
        .login-brand-sub{font-size:13px;color:#dbeafe;margin-top:4px}
        .secure-chip{display:flex;align-items:center;gap:10px;font-weight:900;color:#f8fbff;border-left:1px solid rgba(255,255,255,.13);padding-left:26px}
        .login-bg-icon{position:absolute;color:#b8c8dc;opacity:.23;pointer-events:none}
        .login-bg-icon.cap{left:7%;top:31%;width:190px;height:140px}
        .login-bg-icon.path{right:0;top:24%;width:430px;height:500px;opacity:.18}
        .login-page:before{content:"";position:absolute;left:-130px;bottom:-210px;width:520px;height:520px;border-radius:50%;background:rgba(37,99,235,.06)}
        .login-page:after{content:"";position:absolute;right:-120px;bottom:-160px;width:460px;height:260px;border-radius:50%;background:rgba(37,99,235,.06)}
        .login-center{min-height:calc(100vh - 88px);display:grid;place-items:center;padding:34px 20px;position:relative;z-index:2}
        .login-card{
            width:min(440px,100%);background:rgba(255,255,255,.94);border:1px solid rgba(219,229,241,.9);border-radius:20px;padding:24px 26px 24px;
            box-shadow:0 24px 60px rgba(9,30,66,.10);backdrop-filter:blur(12px);
        }
        .login-card-head{text-align:center;margin-bottom:20px}
        .login-card .login-logo{margin:0 auto 12px;width:52px;height:52px;border-radius:15px}
        .login-title{margin:0;font-size:28px;font-weight:950;letter-spacing:-.045em;color:var(--ink)}
        .input-wrap{position:relative}
        .input-wrap svg{position:absolute;left:16px;top:50%;transform:translateY(-50%);width:20px;height:20px;color:#7b8da8}
        .input-wrap input{height:46px;border-radius:12px;padding-left:48px;font-size:15px;color:#263a5c}
        .input-action{position:absolute;right:16px;top:50%;transform:translateY(-50%);color:#7b8da8}
        .login-options{display:flex;align-items:center;justify-content:space-between;gap:14px;margin:14px 0 18px;font-size:13px;color:#405270}
        .remember-label{display:flex;align-items:center;gap:8px;font-weight:650;margin:0}.remember-label input{width:16px;height:16px}
        .forgot-link{color:#2563eb;font-weight:800}
        .login-submit{width:100%;height:46px;font-size:15px;border-radius:12px;background:linear-gradient(135deg,#06162d 0%,#0b2a5b 52%,#0ea5e9 100%)}
        .login-divider{display:flex;align-items:center;gap:20px;margin:22px 0;color:#60718d;font-weight:700;font-size:12px}.login-divider:before,.login-divider:after{content:"";height:1px;background:var(--line);flex:1}
        .institution-btn{width:100%;height:48px;border-radius:12px;background:#fff;border:1px solid #d8e3f1;color:var(--ink);font-weight:850;font-size:14px;display:flex;align-items:center;justify-content:center;gap:10px;cursor:pointer}

        @media(max-width:1100px){
            .institution-topline{display:none}
            .brandbar{padding:15px 20px}.main-nav{padding:14px 20px}.page-container{padding:26px 22px 36px}
            .dashboard-layout{grid-template-columns:1fr}.hero-visual{opacity:.28;right:-20px}.stats-grid,.grid{grid-template-columns:repeat(2,minmax(0,1fr))}.quick-action-grid{grid-template-columns:repeat(2,minmax(0,1fr))}
        }
        @media(max-width:720px){
            .brandbar{align-items:flex-start;gap:16px;flex-direction:column}.account-area{width:100%;justify-content:space-between}.brand-title{font-size:21px}
            .page-intro{flex-direction:column}.role-badge{min-width:0;width:100%}
            .welcome-card{padding:28px 24px}.welcome-title{font-size:28px}.welcome-text{font-size:15px}.hero-visual{display:none}
            .workflow-card{padding:22px}.stats-grid,.grid,.quick-action-grid{grid-template-columns:1fr}.quick-actions-panel{padding:20px}.stat{min-height:84px}
            .login-topbar{height:auto;min-height:96px;padding:20px;align-items:flex-start;gap:14px;flex-direction:column}.secure-chip{border-left:0;padding-left:0}.login-center{min-height:calc(100vh - 120px);padding:26px 16px}.login-card{padding:24px 20px}.login-title{font-size:28px}.login-bg-icon{display:none}
        }
    </style>
</head>
<body>
@php
    $icon = [
        'cap' => '<svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10L12 5 2 10l10 5 10-5Z"/><path d="M6 12v5c3.5 2.5 8.5 2.5 12 0v-5"/><path d="M22 10v6"/></svg>',
        'dashboard' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>',
        'building' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 21V5a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v16"/><path d="M9 21v-5h3v5"/><path d="M8 7h1M12 7h1M8 11h1M12 11h1M19 21V10h1a2 2 0 0 1 2 2v9"/></svg>',
        'user' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21a8 8 0 0 0-16 0"/><circle cx="12" cy="7" r="4"/></svg>',
        'briefcase' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="7" width="18" height="13" rx="2"/><path d="M9 7V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2"/><path d="M3 12h18"/></svg>',
        'inbox' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-6l-2 3h-4l-2-3H2"/><path d="m5.45 5.11-3.25 7.27A2 2 0 0 0 4.03 15H20a2 2 0 0 0 1.83-2.62l-3.25-7.27A2 2 0 0 0 16.75 4H7.25a2 2 0 0 0-1.8 1.11Z"/></svg>',
        'ai' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 3a3 3 0 0 0-3 3v12a3 3 0 0 0 6 0V6a3 3 0 0 0-3-3Z"/><path d="M15 3a3 3 0 0 1 3 3v12a3 3 0 0 1-6 0V6a3 3 0 0 1 3-3Z"/><path d="M6 9H4M20 9h-2M6 15H4M20 15h-2"/></svg>',
        'folder' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7a2 2 0 0 1 2-2h5l2 2h7a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2Z"/></svg>',
        'clipboard' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="4" width="14" height="17" rx="2"/><path d="M9 4a3 3 0 0 1 6 0"/><path d="m9 14 2 2 4-4"/></svg>',
        'star' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3 2.8 5.7 6.2.9-4.5 4.4 1.1 6.2L12 17.3l-5.6 2.9 1.1-6.2L3 9.6l6.2-.9Z"/></svg>',
        'calendar' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>',
        'shield' => '<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/></svg>',
        'logout' => '<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/></svg>',
        'check' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>',
        'lock' => '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="10" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>',
        'mail' => '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2Z"/><path d="m22 6-10 7L2 6"/></svg>',
        'eye' => '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/></svg>',
        'bank' => '<svg viewBox="0 0 24 24" width="21" height="21" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 10 9-6 9 6"/><path d="M5 10v8M9 10v8M15 10v8M19 10v8M3 18h18M2 21h20"/></svg>',
    ];
@endphp

@guest
    <main class="guest-content">
        @yield('content')
    </main>
@endguest

@auth
    @php
        $role = auth()->user()->role;
        $roleLabels = [
            'super_admin' => 'Super administrateur',
            'admin_organisme' => 'Administrateur organisme',
            'encadrant' => 'Encadrant',
            'etudiant' => 'Étudiant',
        ];
        $navItems = [];
        if ($role === 'super_admin') {
            $navItems = [
                ['route' => 'dashboard', 'match' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'dashboard'],
                ['route' => 'organizations.index', 'match' => 'organizations.*', 'label' => 'Organismes', 'icon' => 'building'],
                ['route' => 'users.index', 'match' => 'users.*', 'label' => 'Utilisateurs', 'icon' => 'user'],
                ['route' => 'offers.index', 'match' => 'offers.*', 'label' => 'Offres', 'icon' => 'briefcase'],
                ['route' => 'applications.index', 'match' => 'applications.*', 'label' => 'Candidatures', 'icon' => 'inbox'],
                ['route' => 'ia.index', 'match' => 'ia.*', 'label' => 'IA', 'icon' => 'ai'],
                ['route' => 'internships.index', 'match' => 'internships.*', 'label' => 'Stages', 'icon' => 'folder'],
                ['route' => 'suivis.create', 'match' => 'suivis.*', 'label' => 'Suivi', 'icon' => 'clipboard'],
                ['route' => 'evaluations.create', 'match' => 'evaluations.*', 'label' => 'Évaluation', 'icon' => 'star'],
                ['route' => 'soutenances.index', 'match' => 'soutenances.*', 'label' => 'Soutenances', 'icon' => 'calendar'],
            ];
        } elseif ($role === 'admin_organisme') {
            $navItems = [
                ['route' => 'dashboard', 'match' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'dashboard'],
                ['route' => 'offers.index', 'match' => 'offers.*', 'label' => 'Offres', 'icon' => 'briefcase'],
                ['route' => 'applications.index', 'match' => 'applications.*', 'label' => 'Candidatures', 'icon' => 'inbox'],
                ['route' => 'ia.index', 'match' => 'ia.*', 'label' => 'IA', 'icon' => 'ai'],
                ['route' => 'internships.index', 'match' => 'internships.*', 'label' => 'Stages', 'icon' => 'folder'],
                ['route' => 'suivis.create', 'match' => 'suivis.*', 'label' => 'Suivi', 'icon' => 'clipboard'],
                ['route' => 'evaluations.create', 'match' => 'evaluations.*', 'label' => 'Évaluation', 'icon' => 'star'],
                ['route' => 'soutenances.index', 'match' => 'soutenances.*', 'label' => 'Soutenances', 'icon' => 'calendar'],
            ];
        } elseif ($role === 'encadrant') {
            $navItems = [
                ['route' => 'dashboard', 'match' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'dashboard'],
                ['route' => 'internships.index', 'match' => 'internships.*', 'label' => 'Stages', 'icon' => 'folder'],
                ['route' => 'suivis.create', 'match' => 'suivis.*', 'label' => 'Suivi', 'icon' => 'clipboard'],
                ['route' => 'evaluations.create', 'match' => 'evaluations.*', 'label' => 'Évaluation', 'icon' => 'star'],
                ['route' => 'soutenances.index', 'match' => 'soutenances.*', 'label' => 'Soutenances', 'icon' => 'calendar'],
            ];
        } else {
            $navItems = [
                ['route' => 'dashboard', 'match' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'dashboard'],
                ['route' => 'offers.index', 'match' => 'offers.*', 'label' => 'Offres', 'icon' => 'briefcase'],
                ['route' => 'internships.index', 'match' => 'internships.*', 'label' => 'Stage', 'icon' => 'folder'],
                ['route' => 'soutenances.index', 'match' => 'soutenances.*', 'label' => 'Soutenance', 'icon' => 'calendar'],
            ];
        }
    @endphp



        <header class="brandbar">
            <a class="brand-block" href="{{ route('dashboard') }}">
                <span class="brand-logo">{!! $icon['cap'] !!}</span>
                <span>
                    <span class="brand-title">Gestion des stages</span>
                    <span class="brand-subtitle">Portail institutionnel</span>
                </span>
            </a>

            <div class="account-area">
                <div class="user-chip">
                    <span class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                    <span>{{ auth()->user()->name }}</span>
                </div>
                <form class="logout-form" method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="logout-btn" type="submit">{!! $icon['logout'] !!} Déconnexion</button>
                </form>
            </div>
        </header>

        <nav class="main-nav" aria-label="Navigation principale">
            @foreach($navItems as $item)
                <a class="nav-link {{ request()->routeIs($item['match']) ? 'active' : '' }}" href="{{ route($item['route']) }}">
                    <span>{{ $item['label'] }}</span>
                </a>
            @endforeach
        </nav>

        <main class="page-container">
            <div class="page-intro">
                <div>
                    <div class="eyebrow">Plateforme de gestion</div>
                    <h1 class="page-title">@yield('title', 'Gestion des stages')</h1>
                </div>
                <div class="role-badge">
                    <span class="shield">{!! $icon['shield'] !!}</span>
                    <span>{{ $roleLabels[$role] ?? $role }}</span>
                </div>
            </div>

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

            <div class="content-area">
                @yield('content')
            </div>
        </main>
    </div>
@endauth
</body>
</html>
