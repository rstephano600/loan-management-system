<header class="main-header">
    {{-- ── Toggle ── --}}
    <button class="toggle-btn" id="sidebarToggle" title="Toggle Sidebar">
        <i class="bi bi-list"></i>
    </button>

    {{-- ── Page title (injected per-page via @section('page-title')) ── --}}
    <span class="header-title d-none d-md-block">@yield('page-title', 'Dashboard')</span>

    {{-- ── Top-level module nav (desktop only) ── --}}
    <nav class="header-module-nav d-none d-lg-flex align-items-center gap-1 ms-4">
        @can('view-confirguration-side')
        <a href="{{ route('configurationside') }}"
           class="module-nav-link {{ request()->routeIs('configurationside') ? 'active' : '' }}">
            <i class="bi bi-sliders me-1"></i> Configuration
        </a>
        @endcan
        @can('view-working-side')
        <a href="{{ route('workingside') }}"
           class="module-nav-link {{ request()->routeIs('workingside') ? 'active' : '' }}">
            <i class="bi bi-briefcase me-1"></i> Working
        </a>
        @endcan
        @can('view-reporting-side')
        <a href="{{ route('reportingside') }}"
           class="module-nav-link {{ request()->routeIs('reportingside') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-line me-1"></i> Reports
        </a>
        @endcan
    </nav>

    <div class="header-spacer"></div>

    <div class="header-actions">

        {{-- ── Session timer ── --}}
        @auth
        <div id="session-timer-widget" class="session-timer-widget" title="Session time remaining">
            <svg viewBox="0 0 36 36" class="session-timer-svg">
                <path class="session-timer-bg"
                      d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                <path id="session-timer-arc" class="session-timer-arc"
                      stroke-dasharray="100, 100"
                      d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
            </svg>
            <span id="session-timer-label" class="session-timer-label">10:00</span>
        </div>
        @endauth

        {{-- ── Notifications ── --}}
        <div class="dropdown">
            <button class="header-icon-btn" type="button" data-bs-toggle="dropdown" title="Notifications">
                <i class="bi bi-bell"></i>
                {{-- Uncomment when you have real notifications --}}
                {{-- <span class="badge-dot"></span> --}}
            </button>
            <ul class="dropdown-menu dropdown-menu-end header-dropdown shadow mt-2">
                <li class="dropdown-header fw-semibold px-3 py-2">Notifications</li>
                <li><hr class="dropdown-divider m-0"></li>
                <li><span class="dropdown-item text-muted small py-3 text-center">No new notifications</span></li>
                <li><hr class="dropdown-divider m-0"></li>
                <li><a class="dropdown-item text-center small py-2" href="#"
                       style="color:var(--navy);font-weight:600;">View all</a></li>
            </ul>
        </div>

        {{-- ── User menu ── --}}
        <div class="dropdown">
            <button class="header-user-btn" type="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                <div class="header-avatar">
                    {{ strtoupper(substr(Auth::user()->username ?? 'U', 0, 2)) }}
                </div>
                <div class="d-none d-md-flex flex-column text-start lh-1 ms-2">
                    <span style="font-size:13px;font-weight:600;color:#1e293b;">
                        {{ Auth::user()->username }}
                    </span>
                    <span style="font-size:10px;color:#94a3b8;">
                        @php
                            $role = Auth::user()->role ?? '';
                        @endphp
                        {{ ucfirst(str_replace('_', ' ', $role)) ?: 'Staff' }}
                    </span>
                </div>
                <i class="bi bi-chevron-down d-none d-md-block ms-2"
                   style="font-size:11px;color:#94a3b8;"></i>
            </button>

            <ul class="dropdown-menu dropdown-menu-end header-dropdown shadow mt-2" style="min-width:220px;">
                {{-- User summary --}}
                <li class="px-3 pt-3 pb-2 text-center">
                    <div class="header-avatar mx-auto mb-2"
                         style="width:48px;height:48px;font-size:16px;">
                        {{ strtoupper(substr(Auth::user()->username ?? 'U', 0, 2)) }}
                    </div>
                    <div style="font-size:14px;font-weight:600;color:#1e293b;">
                        {{ Auth::user()->username }}
                    </div>
                    <div style="font-size:12px;color:#94a3b8;">
                        {{ Auth::user()->email }}
                    </div>
                </li>
                <li><hr class="dropdown-divider m-0"></li>
                <li>
                    <a class="dropdown-item py-2" href="{{ route('profile.show') }}">
                        <i class="bi bi-person me-2" style="color:var(--navy);"></i> My Profile
                    </a>
                </li>
                <li><hr class="dropdown-divider m-0"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}" id="header-logout-form">
                        @csrf
                        <button type="submit" class="dropdown-item py-2 text-danger w-100 text-start border-0 bg-transparent">
                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>

    </div>{{-- /.header-actions --}}
</header>

{{-- ══════════════════════════════════════════
     Session timeout — script + styles
══════════════════════════════════════════ --}}
@auth
<script>
(function () {
    'use strict';

    var TIMEOUT  = 600;   // seconds before logout
    var WARN_AT  = 150;   // seconds remaining when warning shows
    var PING_INT = 100;   // ping server every N seconds if active

    var lastActivity = Date.now();
    var warningShown = false;
    var warningEl    = null;

    var LOGOUT_ROUTE = "{{ route('logout') }}";
    var CSRF         = "{{ csrf_token() }}";

    /* ── Activity tracking ── */
    ['mousemove','mousedown','keydown','touchstart','scroll','click']
        .forEach(function(e){ document.addEventListener(e, onActivity, { passive: true }); });

    function onActivity() {
        lastActivity = Date.now();
        if (warningShown) hideWarning();
    }

    /* ── Ping server while active ── */
    setInterval(function () {
        if ((Date.now() - lastActivity) / 1000 < PING_INT) {
            fetch('/session/ping', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json' }
            }).catch(function(){});
        }
    }, PING_INT * 1000);

    /* ── Main tick (every 1s) ── */
    setInterval(function () {
        var idle      = (Date.now() - lastActivity) / 1000;
        var remaining = Math.max(0, TIMEOUT - idle);

        updateWidget(remaining);

        if (remaining <= 0) {
            doLogout();
        } else if (remaining <= WARN_AT && !warningShown) {
            showWarning(Math.ceil(remaining));
        } else if (remaining <= WARN_AT && warningShown) {
            var el = document.getElementById('timeout-countdown');
            if (el) el.textContent = Math.ceil(remaining);
        }
    }, 1000);

    /* ── Widget ── */
    function updateWidget(remaining) {
        var arc    = document.getElementById('session-timer-arc');
        var label  = document.getElementById('session-timer-label');
        var widget = document.getElementById('session-timer-widget');
        if (!arc || !label) return;

        var pct   = (remaining / TIMEOUT) * 100;
        var mins  = Math.floor(remaining / 60);
        var secs  = Math.floor(remaining % 60);
        arc.setAttribute('stroke-dasharray', pct.toFixed(1) + ', 100');
        label.textContent = mins + ':' + (secs < 10 ? '0' : '') + secs;

        var color = remaining > WARN_AT * 2 ? '#5DCAA5'      /* brand green */
                  : remaining > WARN_AT     ? '#f59e0b'      /* amber */
                                            : '#ef4444';     /* red */
        arc.style.stroke   = color;
        label.style.color  = color;

        widget.classList.toggle('timer-danger', remaining <= WARN_AT);
    }

    /* ── Logout ── */
    function doLogout() {
        hideWarning();
        var form   = document.createElement('form');
        form.method = 'POST';
        form.action = LOGOUT_ROUTE;
        var t = document.createElement('input');
        t.type = 'hidden'; t.name = '_token'; t.value = CSRF;
        form.appendChild(t);
        document.body.appendChild(form);
        form.submit();
    }

    /* ── Warning banner ── */
    function showWarning(secs) {
        warningShown = true;
        warningEl = document.createElement('div');
        warningEl.id = 'session-timeout-warning';
        warningEl.innerHTML =
            '<div style="position:fixed;top:0;left:0;right:0;z-index:99999;' +
            'background:#ef4444;color:#fff;text-align:center;padding:12px 20px;' +
            'font-size:13px;font-weight:600;box-shadow:0 2px 8px rgba(0,0,0,.25);' +
            'display:flex;align-items:center;justify-content:center;gap:16px;">' +
            '<i class="bi bi-exclamation-triangle-fill"></i>' +
            '<span>Session expiring in <strong><span id="timeout-countdown">' + secs + '</span>s</strong> due to inactivity.</span>' +
            '<button onclick="sessionKeepalive()" style="background:#fff;color:#ef4444;border:none;' +
            'padding:4px 14px;border-radius:6px;cursor:pointer;font-weight:700;font-size:12px;">' +
            'Stay Logged In</button></div>';
        document.body.prepend(warningEl);
    }

    function hideWarning() {
        warningShown = false;
        if (warningEl) { warningEl.remove(); warningEl = null; }
    }

    window.sessionKeepalive = function () {
        onActivity();
        fetch('/session/ping', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json' }
        }).catch(function(){});
    };

})();
</script>
@endauth

<style>
    /* ── Brand tokens (mirror app.blade.php) ── */
    :root {
        --navy:   #0C447C;
        --accent: #5DCAA5;
    }

    /* ── Header shell ── */
    .main-header {
        height: 60px;
        background: #fff;
        border-bottom: 1px solid #e5eaf0;
        display: flex;
        align-items: center;
        padding: 0 20px;
        gap: 10px;
        position: sticky;
        top: 0;
        z-index: 1040;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
    }

    .header-spacer { flex: 1; }

    .header-actions {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* ── Module nav pills ── */
    .header-module-nav { gap: 4px; }

    .module-nav-link {
        display: inline-flex;
        align-items: center;
        padding: 6px 13px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        color: #64748b;
        text-decoration: none;
        transition: background 0.15s ease, color 0.15s ease;
        border: 1px solid transparent;
    }

    .module-nav-link:hover {
        background: #f1f5f9;
        color: var(--navy);
    }

    .module-nav-link.active {
        background: rgba(12,68,124,0.08);
        color: var(--navy);
        border-color: rgba(12,68,124,0.15);
        font-weight: 600;
    }

    /* ── Icon buttons ── */
    .header-icon-btn {
        width: 36px;
        height: 36px;
        border: none;
        background: transparent;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: #64748b;
        font-size: 17px;
        text-decoration: none;
        transition: background 0.15s ease, color 0.15s ease;
        position: relative;
    }

    .header-icon-btn:hover { background: #f1f5f9; color: var(--navy); }

    .badge-dot {
        position: absolute;
        top: 7px; right: 7px;
        width: 7px; height: 7px;
        background: #ef4444;
        border-radius: 50%;
        border: 1.5px solid #fff;
    }

    /* ── User button ── */
    .header-user-btn {
        display: flex;
        align-items: center;
        background: transparent;
        border: 1px solid #e5eaf0;
        border-radius: 8px;
        padding: 4px 10px 4px 5px;
        cursor: pointer;
        transition: background 0.15s ease, border-color 0.15s ease;
    }

    .header-user-btn:hover {
        background: #f8fafc;
        border-color: rgba(12,68,124,0.2);
    }

    /* ── Avatar ── */
    .header-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: var(--navy);
        color: #fff;
        font-size: 12px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border: 2px solid rgba(93,202,165,0.5);
    }

    /* ── Dropdown ── */
    .header-dropdown {
        border: 1px solid #e5eaf0 !important;
        border-radius: 10px !important;
        animation: dropIn 0.18s ease;
    }

    @keyframes dropIn {
        from { transform: translateY(6px); opacity: 0; }
        to   { transform: translateY(0);   opacity: 1; }
    }

    .header-dropdown .dropdown-item {
        font-size: 13px;
        border-radius: 6px;
        margin: 1px 6px;
        padding-left: 10px;
        width: calc(100% - 12px);
        transition: background 0.12s ease;
    }

    .header-dropdown .dropdown-item:hover { background: #f1f5f9; }

    /* ── Session timer ── */
    .session-timer-widget {
        position: relative;
        width: 40px; height: 40px;
        flex-shrink: 0;
    }

    .session-timer-svg {
        width: 40px; height: 40px;
        transform: rotate(-90deg);
    }

    .session-timer-bg {
        fill: none;
        stroke: #e5eaf0;
        stroke-width: 3.5;
    }

    .session-timer-arc {
        fill: none;
        stroke: var(--accent);
        stroke-width: 3.5;
        stroke-linecap: round;
        transition: stroke-dasharray 0.9s linear, stroke 0.5s ease;
    }

    .session-timer-label {
        position: absolute;
        top: 50%; left: 50%;
        transform: translate(-50%, -50%);
        font-size: 9px;
        font-weight: 700;
        color: var(--accent);
        white-space: nowrap;
        transition: color 0.5s ease;
    }

    .timer-danger .session-timer-svg {
        animation: timerPulse 0.8s ease-in-out infinite alternate;
    }

    @keyframes timerPulse {
        from { opacity: 1; }
        to   { opacity: 0.45; }
    }
</style>