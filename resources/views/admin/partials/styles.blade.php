<style>
    :root {
        --admin-navy: #10284a;
        --admin-navy-2: #183a68;
        --admin-accent: #f5a623;
        --admin-bg: #f4f6fa;
        --admin-border: #e5e8ef;
        --admin-sidebar-w: 260px;
        --bs-primary: #1d4ed8;
        --bs-primary-rgb: 29, 78, 216;
        --bs-body-font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', sans-serif;
        --bs-body-font-size: .9rem;
    }
    body.admin-body { background: var(--admin-bg); }
    .admin-shell { display: flex; min-height: 100vh; }

    /* Sidebar */
    .admin-sidebar { width: var(--admin-sidebar-w); color: #c9d4e6; flex-shrink: 0; }
    /* Bootstrap forces .offcanvas-lg transparent on desktop; keep the navy panel. */
    .admin-sidebar.offcanvas-lg { background-color: var(--admin-navy) !important; --bs-offcanvas-width: var(--admin-sidebar-w); }
    @media (min-width: 992px) {
        .admin-sidebar { position: sticky; top: 0; height: 100vh; overflow-y: auto; visibility: visible !important; transform: none !important; }
    }
    .admin-sidebar .offcanvas-header, .admin-brand { padding: 1.1rem 1.25rem; border-bottom: 1px solid rgba(255,255,255,.08); }
    .admin-brand { display: flex; align-items: center; gap: .6rem; color: #fff; text-decoration: none; font-weight: 700; font-size: 1.1rem; }
    .admin-brand .badge { background: var(--admin-accent); color: var(--admin-navy); font-size: .65rem; }
    .admin-nav { padding: .75rem .75rem 2rem; }
    .admin-nav .nav-section { text-transform: uppercase; font-size: .68rem; letter-spacing: .08em; color: #7f93b5; padding: 1rem .75rem .35rem; }
    .admin-nav .nav-link { color: #c9d4e6; border-radius: .5rem; padding: .5rem .75rem; display: flex; align-items: center; gap: .65rem; font-weight: 500; }
    .admin-nav .nav-link i { font-size: 1rem; width: 1.1rem; text-align: center; }
    .admin-nav .nav-link:hover { background: rgba(255,255,255,.06); color: #fff; }
    .admin-nav .nav-link.active { background: var(--admin-navy-2); color: #fff; box-shadow: inset 3px 0 0 var(--admin-accent); }
    .admin-nav .nav-link .count { margin-left: auto; background: var(--admin-accent); color: var(--admin-navy); font-size: .7rem; font-weight: 700; border-radius: 1rem; padding: .05rem .45rem; }
    .admin-nav .sub { padding-left: 1.6rem; }
    .admin-nav .sub .nav-link { font-weight: 400; padding: .35rem .75rem; font-size: .85rem; }

    /* Main */
    .admin-main { flex: 1; min-width: 0; display: flex; flex-direction: column; }
    .admin-topbar { display: flex; align-items: center; background: #fff; border-bottom: 1px solid var(--admin-border); padding: .75rem 1.5rem; position: sticky; top: 0; z-index: 1020; }
    .admin-page-title { font-size: 1.2rem; font-weight: 700; margin: 0; color: var(--admin-navy); }
    .admin-avatar { width: 34px; height: 34px; border-radius: 50%; background: var(--admin-navy); color: #fff; display: inline-flex; align-items: center; justify-content: center; font-weight: 600; }
    .admin-content { padding: 1.5rem; flex: 1; }
    .admin-footer { padding: 1rem 1.5rem; color: #8a94a6; font-size: .8rem; border-top: 1px solid var(--admin-border); background: #fff; }
    @media (max-width: 575.98px) { .admin-content { padding: 1rem; } .admin-topbar { padding: .75rem 1rem; } }

    /* Cards & tables */
    .card { border: 1px solid var(--admin-border); border-radius: .75rem; box-shadow: 0 1px 2px rgba(16,40,74,.04); }
    .card-header { background: #fff; border-bottom: 1px solid var(--admin-border); font-weight: 600; padding: .85rem 1.25rem; }
    .stat-card .icon { width: 44px; height: 44px; border-radius: .65rem; display: inline-flex; align-items: center; justify-content: center; font-size: 1.25rem; }
    .stat-card .value { font-size: 1.5rem; font-weight: 700; color: var(--admin-navy); line-height: 1.2; }
    .table { --bs-table-hover-bg: #f7f9fc; margin-bottom: 0; }
    .table thead th { font-size: .72rem; text-transform: uppercase; letter-spacing: .05em; color: #6b7a90; background: #f9fafc; border-bottom-width: 1px; white-space: nowrap; font-weight: 600; }
    .table td { vertical-align: middle; }
    .table-actions { white-space: nowrap; text-align: right; }
    .table-actions form { display: inline; }
    .filter-bar .form-control, .filter-bar .form-select { min-width: 140px; }
    .nav-tabs-status .nav-link { color: #5b6880; font-weight: 500; }
    .nav-tabs-status .nav-link.active { color: var(--admin-navy); font-weight: 600; }
    .detail-list dt { color: #6b7a90; font-weight: 500; font-size: .8rem; }
    .detail-list dd { margin-bottom: .85rem; word-break: break-word; }
    .thumb { width: 48px; height: 48px; object-fit: cover; border-radius: .5rem; background: #eef1f6; }
    .pagination { margin-bottom: 0; }
    .text-navy { color: var(--admin-navy) !important; }
    .btn-navy { background: var(--admin-navy); color: #fff; }
    .btn-navy:hover { background: var(--admin-navy-2); color: #fff; }
    .ql-container { min-height: 260px; font-size: .95rem; }
</style>
