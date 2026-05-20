@extends('layouts.auth-app')
@section('title', 'Login')

@section('content')
<div style="
    display: flex;
    min-height: 100vh;
    width: 100%;
    align-items: stretch;
">

    {{-- ══════════════════ LEFT PANEL ══════════════════ --}}
    <div style="
        width: 38%;
        min-width: 280px;
        background: #0C447C;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 48px 40px;
        position: relative;
        overflow: hidden;
        flex-shrink: 0;
    ">
        {{-- Decorative circles --}}
        <div style="position:absolute;top:-80px;left:-80px;width:260px;height:260px;
                    border-radius:50%;background:rgba(255,255,255,0.05);pointer-events:none;"></div>
        <div style="position:absolute;bottom:-60px;right:-60px;width:200px;height:200px;
                    border-radius:50%;background:rgba(255,255,255,0.04);pointer-events:none;"></div>

        {{-- Logo icon --}}
        <div style="
            width:64px;height:64px;
            background:rgba(255,255,255,0.12);
            border-radius:16px;
            display:flex;align-items:center;justify-content:center;
            border:0.5px solid rgba(255,255,255,0.2);
            margin-bottom:20px;
        ">
            <img src="{{ asset('images/arbifA.png') }}" style="width:40px;height:40px;object-fit:contain;">
        </div>

        <h2 style="color:#fff;font-size:20px;font-weight:500;text-align:center;margin-bottom:8px;line-height:1.4;">
            ArBif Management
        </h2>
        <div style="width:40px;height:2px;background:rgba(255,255,255,0.2);margin:12px auto 16px;"></div>
        <p style="color:rgba(255,255,255,0.55);font-size:12px;text-align:center;line-height:1.7;max-width:200px;">
            Secure loan management system for modern financial operations
        </p>

        <ul style="list-style:none;margin-top:32px;width:100%;padding:0;max-width:220px;display:none;">
            @foreach(['Role-based access control','Session timeout protection','Encrypted audit trails','Multi-branch reporting'] as $f)
            <li style="display:flex;align-items:center;gap:10px;
                        color:rgba(255,255,255,0.65);font-size:12px;padding:6px 0;">
                <span style="width:6px;height:6px;border-radius:50%;background:#5DCAA5;flex-shrink:0;"></span>
                {{ $f }}
            </li>
            @endforeach
        </ul>
    </div>

    {{-- ══════════════════ RIGHT PANEL ══════════════════ --}}
    <div style="
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 48px 24px;
        background: #fff;
        overflow-y: auto;
    ">
        <div style="width:100%;max-width:420px;">

            <h1 style="font-size:24px;font-weight:600;color:#111;margin-bottom:6px;">Welcome back</h1>
            <p style="font-size:13px;color:#6b7280;margin-bottom:28px;">Sign in to your account to continue</p>

            {{-- Error alert --}}
            @if($errors->any())
            <div style="display:flex;align-items:flex-start;gap:10px;padding:10px 14px;border-radius:8px;
                        background:#fef2f2;border:0.5px solid #fca5a5;color:#b91c1c;
                        font-size:13px;margin-bottom:20px;">
                <i class="bi bi-exclamation-circle" style="margin-top:1px;flex-shrink:0;"></i>
                <span>{{ $errors->first() }}</span>
            </div>
            @endif

            {{-- Status alert --}}
            @if(session('status'))
            <div style="display:flex;align-items:flex-start;gap:10px;padding:10px 14px;border-radius:8px;
                        background:#f0fdf4;border:0.5px solid #86efac;color:#15803d;
                        font-size:13px;margin-bottom:20px;">
                <i class="bi bi-check-circle" style="margin-top:1px;flex-shrink:0;"></i>
                <span>{{ session('status') }}</span>
            </div>
            @endif

            <form action="{{ route('login.submit') }}" method="POST">
                @csrf

                {{-- Email / Username --}}
                <div style="margin-bottom:18px;">
                    <label style="display:block;font-size:11px;font-weight:600;color:#6b7280;
                                  text-transform:uppercase;letter-spacing:0.06em;margin-bottom:6px;">
                        Email or Username
                    </label>
                    <div style="position:relative;">
                        <i class="bi bi-person" style="position:absolute;left:12px;top:50%;
                           transform:translateY(-50%);color:#9ca3af;font-size:15px;pointer-events:none;"></i>
                        <input
                            type="text"
                            name="login"
                            value="{{ old('login') }}"
                            autofocus
                            required
                            placeholder="Enter your email or username"
                            style="
                                width:100%;
                                padding:10px 12px 10px 36px;
                                font-size:13px;
                                border:1px solid #d1d5db;
                                border-radius:8px;
                                outline:none;
                                color:#111;
                                background:#fff;
                                transition:border-color 0.15s,box-shadow 0.15s;
                            "
                            onfocus="this.style.borderColor='#378ADD';this.style.boxShadow='0 0 0 3px rgba(55,138,221,0.12)'"
                            onblur="this.style.borderColor='#d1d5db';this.style.boxShadow='none'"
                        >
                    </div>
                </div>

                {{-- Password --}}
                <div style="margin-bottom:18px;">
                    <label style="display:block;font-size:11px;font-weight:600;color:#6b7280;
                                  text-transform:uppercase;letter-spacing:0.06em;margin-bottom:6px;">
                        Password
                    </label>
                    <div style="position:relative;">
                        <i class="bi bi-lock" style="position:absolute;left:12px;top:50%;
                           transform:translateY(-50%);color:#9ca3af;font-size:15px;pointer-events:none;"></i>
                        <input
                            type="password"
                            name="password"
                            required
                            id="pw-field"
                            placeholder="Enter your password"
                            style="
                                width:100%;
                                padding:10px 40px 10px 36px;
                                font-size:13px;
                                border:1px solid #d1d5db;
                                border-radius:8px;
                                outline:none;
                                color:#111;
                                background:#fff;
                                transition:border-color 0.15s,box-shadow 0.15s;
                            "
                            onfocus="this.style.borderColor='#378ADD';this.style.boxShadow='0 0 0 3px rgba(55,138,221,0.12)'"
                            onblur="this.style.borderColor='#d1d5db';this.style.boxShadow='none'"
                        >
                        <button type="button" onclick="togglePw()"
                                style="position:absolute;right:12px;top:50%;transform:translateY(-50%);
                                       background:none;border:none;cursor:pointer;color:#9ca3af;padding:0;line-height:1;">
                            <i class="bi bi-eye" id="pw-eye" style="font-size:15px;"></i>
                        </button>
                    </div>
                </div>

                {{-- Remember me + Forgot password --}}
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;flex-wrap:wrap;gap:8px;">
                    <label style="display:flex;align-items:center;gap:8px;font-size:13px;color:#6b7280;cursor:pointer;">
                        <input type="checkbox" name="remember"
                               style="width:14px;height:14px;accent-color:#0C447C;cursor:pointer;">
                        Remember me for 30 days
                    </label>
                    @if(Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                       style="font-size:12px;color:#378ADD;text-decoration:none;white-space:nowrap;">
                        Forgot password?
                    </a>
                    @endif
                </div>

                {{-- Submit --}}
                <button
                    type="submit"
                    style="
                        width:100%;
                        padding:11px;
                        background:#0C447C;
                        color:#fff;
                        border:none;
                        border-radius:8px;
                        font-size:14px;
                        font-weight:500;
                        cursor:pointer;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        gap:8px;
                        transition:background 0.2s;
                    "
                    onmouseover="this.style.background='#185FA5'"
                    onmouseout="this.style.background='#0C447C'"
                >
                    <i class="bi bi-box-arrow-in-right"></i> Sign in
                </button>
            </form>

            <p style="text-align:center;font-size:12px;color:#9ca3af;margin-top:28px;">
                Having trouble? Contact your
                <a href="#" style="color:#378ADD;text-decoration:none;">system administrator</a>
            </p>

        </div>{{-- /.max-width wrapper --}}
    </div>{{-- /.right panel --}}

</div>{{-- /.flex container --}}

{{-- Mobile responsiveness --}}
<style>
    @media (max-width: 640px) {
        div[style*="display: flex"][style*="min-height: 100vh"] {
            flex-direction: column !important;
        }
        div[style*="width: 38%"] {
            width: 100% !important;
            min-width: unset !important;
            padding: 36px 24px !important;
            min-height: auto !important;
        }
        div[style*="flex: 1"][style*="padding: 48px 24px"] {
            padding: 36px 20px !important;
        }
    }
</style>

<script>
function togglePw() {
    var f = document.getElementById('pw-field');
    var e = document.getElementById('pw-eye');
    f.type = f.type === 'password' ? 'text' : 'password';
    e.className = f.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
}
</script>
@endsection