@extends('layouts.auth')

@section('content')
    @auth
        <div class="auth-box welcome-card">
            <div class="welcome-header">
                <div class="avatar-wrapper">
                    @if(Auth::user()->avatar)
                        <img src="{{ Auth::user()->avatar }}" alt="Avatar" class="user-avatar">
                    @else
                        <div class="avatar-placeholder">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    @endif
                    <div class="status-badge"></div>
                </div>
                <h2>Bienvenue, {{ Auth::user()->name }}</h2>
                <p class="welcome-subtitle">Heureux de vous revoir ! Prêt pour un nouveau défi ?</p>
            </div>

            <div class="welcome-actions">
                <a href="{{ route('quiz.play') }}" class="btn-start-quiz">
                    <span class="icon">🚀</span>
                    <span class="text">Commencer le Quiz</span>
                </a>

                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit" class="btn-logout">Se déconnecter</button>
                </form>

                <a href="{{ url('/') }}" class="link-back">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    <span>Retour au cours</span>
                </a>
            </div>
        </div>

    @else
        <div class="auth-box" id="login-box">
            <div class="auth-logo">
                <img src="{{ asset('img/laravel-logo.png') }}" alt="Laravel Logo">
            </div>
            <h2>Connexion</h2>
            <p class="auth-subtitle">Connectez-vous pour accéder au Quiz</p>

            <form action="#" method="POST" onsubmit="event.preventDefault(); alert('Authentification simulée !');">
                <div class="form-group">
                    <label for="login-email">Adresse Email</label>
                    <input type="email" id="login-email" name="email" placeholder="exemple@email.com" required>
                </div>

                <div class="form-group">
                    <label for="login-password">Mot de passe</label>
                    <input type="password" id="login-password" name="password" placeholder="••••••••" required>
                </div>

                <button type="submit" class="btn-primary">Se connecter</button>

                <div class="auth-separator">ou</div>

                <a href="{{ route('auth.google') }}" class="btn-google" style="text-decoration: none;">
                    <svg class="google-icon" viewBox="0 0 48 48">
                        <path fill="#EA4335"
                            d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z" />
                        <path fill="#4285F4"
                            d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z" />
                        <path fill="#FBBC05"
                            d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z" />
                        <path fill="#34A853"
                            d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z" />
                        <path fill="none" d="M0 0h48v48H0z" />
                    </svg>
                    Se connecter avec Google
                </a>
            </form>

            <p class="auth-footer">
                Pas encore de compte ? <a href="#" onclick="toggleAuth('register')">S'inscrire</a>
            </p>
        </div>

        <div class="auth-box" id="register-box" style="display: none;">
            <div class="auth-logo">
                <img src="{{ asset('img/laravel-logo.png') }}" alt="Laravel Logo">
            </div>
            <h2>Inscription</h2>
            <p class="auth-subtitle">Créez un compte pour sauvegarder vos scores</p>

            <form action="#" method="POST" onsubmit="event.preventDefault(); alert('Inscription simulée !');">
                <div class="form-group">
                    <label for="register-name">Nom complet</label>
                    <input type="text" id="register-name" name="name" placeholder="John Doe" required>
                </div>

                <div class="form-group">
                    <label for="register-email">Adresse Email</label>
                    <input type="email" id="register-email" name="email" placeholder="exemple@email.com" required>
                </div>

                <div class="form-group">
                    <label for="register-password">Mot de passe</label>
                    <input type="password" id="register-password" name="password" placeholder="••••••••" required>
                </div>

                <div class="form-group">
                    <label for="register-confirm">Confirmer le mot de passe</label>
                    <input type="password" id="register-confirm" name="password_confirmation" placeholder="••••••••" required>
                </div>

                <button type="submit" class="btn-primary">S'inscrire</button>

                <div class="auth-separator">ou</div>

                <a href="{{ route('auth.google') }}" class="btn-google" style="text-decoration: none;">
                    <svg class="google-icon" viewBox="0 0 48 48">
                        <path fill="#EA4335"
                            d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z" />
                        <path fill="#4285F4"
                            d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z" />
                        <path fill="#FBBC05"
                            d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z" />
                        <path fill="#34A853"
                            d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z" />
                        <path fill="none" d="M0 0h48v48H0z" />
                    </svg>
                    S'inscrire avec Google
                </a>
            </form>

            <p class="auth-footer">
                Déjà un compte ? <a href="#" onclick="toggleAuth('login')">Se connecter</a>
            </p>
        </div>
        </div>
    @endauth

    <style>
        .welcome-card {
            text-align: center;
            padding: 3rem 2.5rem !important;
            max-width: 440px !important;
            border-radius: 20px !important;
            background: var(--surface-color);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15) !important;
            border: 1px solid rgba(255, 255, 255, 0.1);
            animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .welcome-header {
            margin-bottom: 2rem;
        }

        .avatar-wrapper {
            position: relative;
            width: 80px;
            height: 80px;
            margin: 0 auto 1.2rem;
        }

        .user-avatar,
        .avatar-placeholder {
            width: 100%;
            height: 100%;
            border-radius: 30px;
            object-fit: cover;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            border: 3px solid var(--surface-color);
        }

        .avatar-placeholder {
            background: linear-gradient(135deg, var(--accent-color), #f43f5e);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: 800;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .status-badge {
            position: absolute;
            bottom: 1px;
            right: 1px;
            width: 18px;
            height: 18px;
            background: #10B981;
            border: 3px solid var(--surface-color);
            border-radius: 50%;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .welcome-card h2 {
            font-size: 1.8rem;
            font-weight: 800;
            margin-bottom: 0.4rem;
            letter-spacing: -0.02em;
            color: var(--heading-color);
        }

        .welcome-subtitle {
            color: var(--subheading-color);
            font-size: 1rem;
            font-weight: 500;
        }

        .welcome-actions {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .btn-start-quiz {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            background: linear-gradient(135deg, var(--accent-color), #e11d48);
            color: white !important;
            padding: 1rem;
            border-radius: 16px;
            font-size: 1.05rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 8px 16px rgba(255, 45, 32, 0.25);
        }

        .btn-start-quiz:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 30px rgba(255, 45, 32, 0.4);
            filter: brightness(1.1);
        }

        .btn-start-quiz:active {
            transform: translateY(-1px);
        }

        .btn-logout {
            width: 100%;
            background: transparent;
            border: 2px solid var(--border-color);
            color: var(--text-color);
            padding: 1rem;
            border-radius: 16px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-logout:hover {
            background: #fef2f2;
            border-color: #fecaca;
            color: #ef4444;
        }

        [data-theme="dark"] .btn-logout:hover {
            background: rgba(239, 68, 68, 0.1);
            border-color: rgba(239, 68, 68, 0.3);
        }

        .link-back {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: var(--subheading-color);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            margin-top: 1rem;
            transition: color 0.2s;
        }

        .link-back:hover {
            color: var(--accent-color);
        }

        /* Premium Auth Box Styles - Compact Version */
        .auth-box:not(.welcome-card) {
            background: var(--surface-color);
            padding: 1.8rem 1.6rem !important;
            border-radius: 20px !important;
            max-width: 350px !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1) !important;
            border: 1px solid var(--border-color);
            animation: slideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1);
            margin: 0 auto;
            position: relative;
            overflow: hidden;
        }

        [data-theme="dark"] .auth-box:not(.welcome-card) {
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4) !important;
            border: 1px solid rgba(255, 255, 255, 0.05);
            background: linear-gradient(145deg, var(--surface-color), #1e293b);
        }

        .auth-logo {
            display: flex;
            justify-content: center;
            margin-bottom: 1.2rem;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        .auth-logo img {
            width: 55px;
            height: auto;
            filter: drop-shadow(0 6px 12px rgba(255, 45, 32, 0.25));
        }

        .auth-box h2 {
            font-size: 1.6rem !important;
            font-weight: 800;
            margin-bottom: 0.2rem !important;
            letter-spacing: -0.03em;
            color: var(--heading-color);
            text-align: center;
        }

        .auth-subtitle {
            margin-bottom: 1.5rem !important;
            font-size: 0.85rem !important;
            color: var(--subheading-color);
            text-align: center;
            opacity: 0.8;
        }

        .form-group {
            margin-bottom: 0.9rem !important;
            text-align: left;
        }

        .form-group label {
            font-weight: 700;
            font-size: 0.8rem !important;
            margin-bottom: 0.4rem !important;
            color: var(--heading-color);
            display: block;
            margin-left: 2px;
        }

        .form-group input {
            width: 100%;
            padding: 0.7rem 0.9rem !important;
            border-radius: 10px !important;
            border: 1.5px solid var(--border-color);
            background: var(--bg-color);
            color: var(--heading-color);
            font-size: 0.9rem !important;
            transition: all 0.3s;
            box-sizing: border-box;
        }

        .form-group input:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(255, 45, 32, 0.1);
            background: var(--surface-color);
            outline: none;
            transform: scale(1.01);
        }

        .btn-primary,
        .btn-google {
            width: 100%;
            height: 44px !important;
            padding: 0 1.2rem !important;
            border-radius: 12px !important;
            font-size: 0.95rem !important;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: flex !important;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-sizing: border-box !important;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--accent-color) 0%, #e11d48 100%);
            color: white !important;
            border: none;
            box-shadow: 0 6px 15px rgba(255, 45, 32, 0.25);
            margin-top: 0.4rem;
        }

        .btn-primary:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 10px 20px rgba(255, 45, 32, 0.3);
            filter: brightness(1.1);
        }

        .btn-primary:active {
            transform: translateY(0) scale(0.98);
        }

        .auth-separator {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            margin: 1.2rem 0 !important;
            color: var(--subheading-color);
            font-size: 0.75rem !important;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .auth-separator::before,
        .auth-separator::after {
            content: '';
            flex: 1;
            height: 1px;
            background: linear-gradient(to var(--direction, right), var(--border-color), transparent);
        }

        .auth-separator::before { --direction: left; }

        .btn-google {
            background: var(--surface-color);
            border: 1.5px solid var(--border-color);
            color: var(--heading-color);
            text-decoration: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        }

        .btn-google:hover {
            background: var(--bg-color);
            border-color: var(--accent-color);
            transform: translateY(-1px);
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.05);
        }

        .google-icon {
            width: 18px !important;
            height: 18px !important;
            flex-shrink: 0;
        }
            flex-shrink: 0;
        }

        .auth-footer {
            margin-top: 1.2rem !important;
            text-align: center;
            font-size: 0.85rem !important;
            color: var(--subheading-color);
        }

        .auth-footer a {
            color: var(--accent-color);
            font-weight: 700;
            text-decoration: none;
        }

        .auth-footer a:hover {
            text-decoration: underline;
        }
    </style>

    <script>
        function toggleAuth(type) {
            const loginBox = document.getElementById('login-box');
            const registerBox = document.getElementById('register-box');

            if (type === 'register') {
                loginBox.style.display = 'none';
                registerBox.style.display = 'block';
            } else {
                loginBox.style.display = 'block';
                registerBox.style.display = 'none';
            }
        }
    </script>
@endsection