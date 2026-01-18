<nav class="sidebar">
    <div>
        <img src="{{ asset('img/laravel-logo.png') }}" alt="Laravel Logo" class="sidebar-logo">
        <h3 style="margin-top: 0;">Sommaire</h3>
        <ul>
            <li><a href="{{ url('/#intro') }}">Introduction</a></li>
            <li><a href="{{ url('/#partie-1') }}">Partie 1 : Le Routage</a></li>
            <li><a href="{{ url('/#partie-2') }}">Partie 2 : Middleware</a></li>
            <li><a href="{{ url('/#partie-3') }}">Partie 3 : Scénarios</a></li>
            <li><a href="{{ url('/#partie-4') }}">Partie 4 : Bonnes pratiques</a></li>
            <li><a href="{{ url('/#conclusion') }}">Conclusion</a></li>
            <li style="margin-top: 1.5rem;">
                <a href="{{ route('quiz') }}" class="quiz-link">
                    🚀 Démarrer le Quiz
                </a>
            </li>
        </ul>
    </div>

    <div class="sidebar-credits">
        <p>Développé et présenté par :</p>
        <p><strong>Rafiki Aymane</strong> <br> <strong>Moukrim Meriem</strong></p>
    </div>
</nav>