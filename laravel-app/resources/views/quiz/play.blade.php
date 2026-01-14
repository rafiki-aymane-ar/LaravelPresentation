@extends('layouts.auth')

@section('content')
    <div class="quiz-fullscreen">
        <!-- Main Content Area -->
        <main class="quiz-main">
            <div class="quiz-content-wrapper">

                <!-- Header with Title & Back Button -->
                <div class="quiz-header-top">
                    <h2 class="quiz-title">Quiz Laravel 12</h2>
                    <a href="{{ route('home') }}" class="btn-back">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                        <span>Cours</span>
                    </a>
                </div>

                <!-- Progress Bar (Visible during quiz) -->
                <div id="quiz-progress-section" class="progress-bar-container">
                    <div class="progress-bar">
                        <div class="progress-fill" id="progress-fill"></div>
                    </div>
                    <p class="question-counter">Question <span id="current-question-num">1</span> <span
                            style="opacity: 0.6;">/ 10</span></p>
                </div>

                <!-- Questions Area -->
                <div id="questions-area">
                    @foreach($questions as $index => $question)
                        <div class="question-step" id="question-{{ $index }}"
                            style="{{ $index === 0 ? '' : 'display: none;' }}">
                            <h3 class="question-text">{{ $question['question'] }}</h3>

                            <div class="options-list">
                                @foreach($question['options'] as $option)
                                    <label class="option-item">
                                        <input type="radio" name="question_{{ $question['id'] }}" value="{{ $option }}"
                                            onchange="handleOptionSelect({{ $index }}, '{{ addslashes($option) }}', '{{ addslashes($question['answer']) }}')">
                                        <span class="option-indicator"></span>
                                        <span class="option-text">{{ $option }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Results Area (Hidden by default) -->
                <div id="quiz-result" class="result-section" style="display: none;">

                    <div class="score-card">
                        <div class="score-circle">
                            <div class="score-content">
                                <span class="score-big" id="score-value">0</span>
                                <span class="score-total">/ 10</span>
                            </div>
                        </div>
                        <h3 class="result-title">Quiz Terminé !</h3>
                        <p id="result-message" class="result-message"></p>
                    </div>

                    <!-- Mistakes Section -->
                    <div id="mistakes-section" style="display: none; margin-top: 3rem;">
                        <h4 class="section-title error-title">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z">
                                </path>
                                <line x1="12" y1="9" x2="12" y2="13"></line>
                                <line x1="12" y1="17" x2="12.01" y2="17"></line>
                            </svg>
                            À Revoir
                        </h4>
                        <ul id="mistakes-list" class="mistakes-list"></ul>
                    </div>

                    <div class="action-buttons">
                        <button onclick="retryQuiz()" class="btn-refresh">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="23 4 23 10 17 10"></polyline>
                                <polyline points="1 20 1 14 7 14"></polyline>
                                <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
                            </svg>
                            Réessayer
                        </button>
                        <a href="{{ url('/') }}" class="btn-home">Retour au cours</a>
                    </div>
                </div>

            </div>
        </main>

        <!-- Sidebar (Leaderboard) -->
        <aside class="quiz-sidebar">
            <div class="sidebar-content">
                <div class="leaderboard-header">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        style="color: #F59E0B;">
                        <circle cx="12" cy="8" r="7"></circle>
                        <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
                    </svg>
                    <h3>Top Classement</h3>
                </div>

                <div class="leaderboard-box">
                    <ul id="leaderboard-list">
                        <!-- Loading or Items -->
                        <li style="padding: 20px; text-align: center; color: var(--subheading-color);">Chargement...</li>
                    </ul>
                </div>

                <div class="sidebar-info">
                    <p>Mesurez-vous aux autres développeurs et testez vos connaissances sur Laravel 12.</p>
                </div>
            </div>
        </aside>
    </div>

    <style>
        /* OVERRIDE AUTH LAYOUT FOR FULLSCREEN QUIZ */
        .auth-layout {
            padding: 0 !important;
            display: block !important;
            background: none !important;
            min-height: 100vh;
        }

        .auth-toggle-wrapper {
            position: fixed;
            top: 1.5rem;
            right: 420px;
            z-index: 100;
        }

        /* FULLSCREEN LAYOUT */
        .quiz-fullscreen {
            display: grid;
            grid-template-columns: 1fr;
            /* Default: sidebar hidden */
            min-height: 100vh;
            width: 100%;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            align-items: stretch;
            transition: grid-template-columns 0.4s ease;
        }

        .quiz-fullscreen.has-sidebar {
            grid-template-columns: 1fr 400px;
        }

        /* Dark Mode Background */
        [data-theme="dark"] .quiz-fullscreen {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        }

        /* MAIN AREA */
        .quiz-main {
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
        }

        .quiz-content-wrapper {
            width: 100%;
            max-width: 100%;
            margin: 0;
            padding: 2rem 4rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        /* SIDEBAR */
        .quiz-sidebar {
            display: none;
            /* Hidden during quiz */
            background: #ffffff;
            border-left: 1px solid #e2e8f0;
            height: 100vh;
            overflow-y: auto;
            box-shadow: -4px 0 20px rgba(0, 0, 0, 0.02);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .quiz-fullscreen.has-sidebar .quiz-sidebar {
            display: block;
        }

        [data-theme="dark"] .quiz-sidebar {
            background: #1e293b;
            border-left-color: #334155;
        }

        .sidebar-content {
            padding: 2.5rem;
        }

        /* Header */
        .quiz-header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            width: 100%;
        }

        .quiz-title {
            font-size: 1.8rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            background: linear-gradient(to right, var(--heading-color), var(--subheading-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin: 0;
        }

        .btn-back {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: rgba(255, 255, 255, 0.8);
            color: var(--subheading-color);
            border-radius: 100px;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        [data-theme="dark"] .btn-back {
            background: #334155;
            color: #cbd5e1;
        }

        .btn-back:hover {
            transform: translateX(-3px);
            color: #1e293b;
            /* Toujours sombre sur fond blanc */
            background: white;
        }

        [data-theme="dark"] .btn-back:hover {
            color: #0f172a;
            /* Très sombre pour contraste maximum */
            background: #ffffff;
        }

        /* Questions & Results Areas Flex Centering */
        #questions-area,
        #quiz-result {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            width: 100%;
        }

        #questions-area {
            max-width: 800px;
            margin: 0 auto;
        }

        #quiz-result {
            max-width: 1000px;
            margin: 0 auto;
            align-items: center;
        }

        /* Progress Bar */
        .progress-bar-container {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 1.5rem;
            background: rgba(255, 255, 255, 0.5);
            padding: 10px 18px;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.5);
            width: 100%;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        [data-theme="dark"] .progress-bar-container {
            background: rgba(30, 41, 59, 0.5);
            border-color: rgba(255, 255, 255, 0.05);
        }

        .progress-bar {
            flex-grow: 1;
            height: 8px;
            background: #e2e8f0;
            border-radius: 100px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--accent-color), #f43f5e);
            border-radius: 100px;
            width: 0%;
            transition: width 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .question-counter {
            font-weight: 700;
            color: var(--heading-color);
            margin: 0;
            font-size: 1rem;
        }

        /* Questions */
        .question-text {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--heading-color);
            margin-bottom: 2rem;
            line-height: 1.3;
            letter-spacing: -0.01em;
            text-align: center;
        }

        /* Options Grid */
        .options-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 16px;
            width: 100%;
        }

        .option-item {
            position: relative;
            display: flex;
            align-items: center;
            padding: 1rem 1.5rem;
            border: 2px solid rgba(0, 0, 0, 0.05);
            border-radius: 14px;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            background: white;
            gap: 12px;
        }

        .option-item input[type="radio"] {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        [data-theme="dark"] .option-item {
            background: #1e293b;
            border-color: rgba(255, 255, 255, 0.05);
        }

        .option-item:hover {
            border-color: var(--accent-color);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.05);
        }

        .option-text {
            font-size: 1.05rem;
            font-weight: 500;
            color: var(--heading-color);
        }

        .option-indicator {
            width: 20px;
            height: 20px;
            border: 2px solid #cbd5e1;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .option-item.correct {
            border-color: #10B981 !important;
            background: #ecfdf5 !important;
        }

        [data-theme="dark"] .option-item.correct {
            background: rgba(16, 185, 129, 0.1) !important;
        }

        .option-item.wrong {
            border-color: #EF4444 !important;
            background: #fef2f2 !important;
        }

        [data-theme="dark"] .option-item.wrong {
            background: rgba(239, 68, 68, 0.1) !important;
        }

        .option-item.correct .option-indicator {
            background: #10B981;
            border-color: #10B981;
            box-shadow: 0 0 0 3px #d1fae5;
        }

        [data-theme="dark"] .option-item.correct .option-indicator {
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
        }

        .option-item.wrong .option-indicator {
            border-color: #EF4444;
            background: #EF4444;
            box-shadow: 0 0 0 3px #fee2e2;
        }

        [data-theme="dark"] .option-item.wrong .option-indicator {
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2);
        }

        /* LEADERBOARD SIDEBAR STYLES */
        .leaderboard-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f1f5f9;
        }

        [data-theme="dark"] .leaderboard-header {
            border-bottom-color: #334155;
        }

        .leaderboard-header h3 {
            margin: 0;
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--heading-color);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .leaderboard-item {
            display: flex;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        [data-theme="dark"] .leaderboard-item {
            border-bottom-color: #334155;
        }

        .leaderboard-rank {
            font-weight: 700;
            width: 28px;
            height: 28px;
            background: #f1f5f9;
            color: var(--subheading-color);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            margin-right: 12px;
        }

        /* Top 3 Colors */
        .leaderboard-item:nth-child(1) .leaderboard-rank {
            background: #FEF3C7;
            color: #D97706;
        }

        .leaderboard-item:nth-child(2) .leaderboard-rank {
            background: #F1F5F9;
            color: #64748B;
        }

        .leaderboard-item:nth-child(3) .leaderboard-rank {
            background: #FFEDD5;
            color: #C2410C;
        }

        .leaderboard-user {
            flex-grow: 1;
            font-weight: 600;
            color: var(--heading-color);
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .leaderboard-score {
            font-weight: 800;
            color: var(--accent-color);
            font-size: 0.95rem;
        }

        .sidebar-info {
            margin-top: 3rem;
            padding: 1.5rem;
            background: #f8fafc;
            border-radius: 12px;
            color: var(--subheading-color);
            font-size: 0.9rem;
            line-height: 1.6;
        }

        [data-theme="dark"] .sidebar-info {
            background: #0f172a;
            color: #94a3b8;
        }

        /* RESULT & MISTAKES GRID */
        .score-card {
            text-align: center;
            margin-bottom: 4rem;
            width: 100%;
        }

        .result-title {
            font-size: 2.5rem;
            margin: 1.2rem 0 0.4rem;
            color: var(--heading-color);
            font-weight: 800;
            letter-spacing: -0.02em;
        }

        .result-message {
            font-size: 1.1rem;
            color: var(--subheading-color);
            max-width: 500px;
            margin: 0 auto;
        }

        .score-circle {
            width: 180px;
            height: 180px;
            margin: 0 auto;
            border-radius: 50%;
            background: conic-gradient(var(--accent-color) 0%, var(--accent-color) var(--score-pct, 0%), #e2e8f0 0%, #e2e8f0 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.06);
        }

        [data-theme="dark"] .score-circle {
            background: conic-gradient(var(--accent-color) 0%, var(--accent-color) var(--score-pct, 0%), #334155 0%, #334155 100%);
        }

        .score-circle::before {
            content: '';
            position: absolute;
            background: white;
            width: 150px;
            height: 150px;
            border-radius: 50%;
        }

        [data-theme="dark"] .score-circle::before {
            background: #0f172a;
        }

        .score-content {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            bottom: 3px;
        }

        .score-big {
            font-size: 4rem;
            font-weight: 800;
            line-height: 1;
            color: var(--heading-color);
        }

        .score-total {
            font-size: 1rem;
            font-weight: 600;
            color: var(--subheading-color);
        }

        /* Mistakes Grid */
        #mistakes-section {
            width: 100%;
            margin-top: 3rem;
            padding-top: 3rem;
            border-top: 1px solid var(--border-color);
        }

        .section-title {
            font-size: 1.75rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .mistakes-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 24px;
            width: 100%;
        }

        .mistake-item {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
            transition: transform 0.2s;
        }

        [data-theme="dark"] .mistake-item {
            background: #1e293b;
            border-color: #334155;
        }

        .mistake-header {
            padding: 20px;
            background: #f1f5f9;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            gap: 14px;
        }

        [data-theme="dark"] .mistake-header {
            background: #0f172a;
            border-bottom-color: #334155;
        }

        .mistake-icon-wrapper {
            background: #FEE2E2;
            color: #EF4444;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .mistake-question {
            font-weight: 700;
            color: var(--heading-color);
            margin: 0;
            font-size: 1.05rem;
            line-height: 1.4;
        }

        .mistake-body {
            padding: 20px;
        }

        .mistake-explanation {
            margin: 0;
            font-size: 0.95rem;
            line-height: 1.6;
            color: var(--text-color);
            display: flex;
            gap: 10px;
        }

        .explanation-icon {
            color: #F59E0B;
            flex-shrink: 0;
            margin-top: 4px;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 1.2rem;
            justify-content: center;
            margin-top: 4rem;
            margin-bottom: 4rem;
        }

        .btn-refresh,
        .btn-home {
            padding: 14px 32px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.2s;
        }

        .btn-refresh {
            background: white;
            border: 2px solid #e2e8f0;
            color: var(--heading-color);
        }

        [data-theme="dark"] .btn-refresh {
            background: #334155;
            border-color: #475569;
            color: #f8fafc;
        }

        .btn-refresh:hover {
            background: #f8fafc;
            border-color: var(--subheading-color);
        }

        [data-theme="dark"] .btn-refresh:hover {
            background: #475569;
            border-color: #64748b;
        }

        .btn-home {
            background: var(--accent-color);
            color: white !important;
            border: none;
            box-shadow: 0 10px 20px rgba(255, 45, 32, 0.2);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-home:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(255, 45, 32, 0.3);
            color: white !important;
            filter: brightness(1.1);
        }

        @media(max-width: 1200px) {
            .quiz-fullscreen.has-sidebar {
                grid-template-columns: 1fr 320px;
            }

            .auth-toggle-wrapper {
                right: 340px;
            }
        }

        @media(max-width: 900px) {

            .quiz-fullscreen,
            .quiz-fullscreen.has-sidebar {
                grid-template-columns: 1fr;
            }

            .quiz-sidebar {
                display: none;
                /* Keep hidden on mobile until finished */
                height: auto;
                border-left: none;
                border-top: 1px solid #e2e8f0;
                position: relative;
            }

            .quiz-fullscreen.has-sidebar .quiz-sidebar {
                display: block;
            }

            .quiz-content-wrapper {
                padding: 2rem 1.5rem;
            }

            .auth-toggle-wrapper {
                right: 1.5rem;
                top: 1.5rem;
            }

            .question-text {
                font-size: 2rem;
            }

            .options-list {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <script>
        // Data passed from Laravel
        let score = 0;
        const totalQuestions = 10;
        let mistakes = [];
        const explanations = @json($questions);
        let isQuizActive = true;

        document.addEventListener('DOMContentLoaded', () => {
            // Check for explicit new quiz request
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('new')) {
                localStorage.removeItem('laravel_quiz_result');
            }

            // Load Leaderboard IMMEDIATELY (Sidebar)
            loadLeaderboard();

            // Check for saved state
            const savedState = localStorage.getItem('laravel_quiz_result');
            if (savedState) {
                isQuizActive = false;
                const state = JSON.parse(savedState);
                score = state.score;
                mistakes = state.mistakes;

                // Hide Quiz, Show Results
                document.getElementById('questions-area').style.display = 'none';
                document.getElementById('quiz-progress-section').style.display = 'none';

                showResult(); // Populate results
            }
        });

        // Anti-Cheat
        document.addEventListener('visibilitychange', () => {
            if (document.hidden && isQuizActive) {
                isQuizActive = false;
                score = 0; mistakes = [];
                document.getElementById('questions-area').style.display = 'none';
                finishQuiz();
                document.getElementById('result-message').innerHTML = "🚫 <span style='color: #EF4444; font-weight: bold;'>Disqualifié</span><br>Changement d'onglet détecté.";
            }
        });

        function handleOptionSelect(index, selectedValue, correctValue) {
            const questionDiv = document.getElementById(`question-${index}`);
            const options = questionDiv.querySelectorAll('.option-item');
            questionDiv.querySelectorAll('input').forEach(i => i.disabled = true);

            // Highlight selected
            let selectedLabel = [...options].find(l => l.querySelector('input').value === selectedValue);

            if (selectedValue === correctValue) {
                score++;
                selectedLabel.classList.add('correct');
            } else {
                selectedLabel.classList.add('wrong');
                mistakes.push({
                    question: explanations[index].question,
                    explanation: explanations[index].explanation
                });
                // Show right answer
                [...options].forEach(l => {
                    if (l.querySelector('input').value === correctValue) l.classList.add('correct');
                });
            }

            setTimeout(() => { goToNextQuestion(index); }, 1500);
        }

        function goToNextQuestion(currentIndex) {
            const currentDiv = document.getElementById(`question-${currentIndex}`);
            const nextDiv = document.getElementById(`question-${currentIndex + 1}`);
            const progressBar = document.getElementById('progress-fill');
            const counterSpan = document.getElementById('current-question-num');

            if (currentDiv) currentDiv.style.display = 'none';

            if (nextDiv) {
                nextDiv.style.display = 'block';
                const progress = ((currentIndex + 2) / totalQuestions) * 100;
                progressBar.style.width = `${progress}%`;
                counterSpan.textContent = currentIndex + 2;
            } else {
                finishQuiz();
            }
        }

        function finishQuiz() {
            isQuizActive = false;
            localStorage.setItem('laravel_quiz_result', JSON.stringify({ score, mistakes }));
            saveScore();
            showResult();

            // Reload leaderboard to show My New Score immediately?
            // saveScore is async, so better to wait or just let it update on next refresh.
            // But we can trigger reload:
            setTimeout(loadLeaderboard, 1000);
        }

        function showResult() {
            // Show sidebar and adjust grid
            document.querySelector('.quiz-fullscreen').classList.add('has-sidebar');

            // Hide quiz parts
            document.getElementById('quiz-result').style.display = 'block';
            document.getElementById('quiz-progress-section').style.display = 'none';
            document.getElementById('questions-area').style.display = 'none';

            // Populate Score
            document.getElementById('score-value').textContent = score;
            const pct = (score / 10) * 100;
            document.querySelector('.score-circle').style.setProperty('--score-pct', pct + '%');

            // Message
            const message = document.getElementById('result-message');
            // Reset message if empty (unless set by disqualification)
            if (!message.innerHTML.includes('Disqualifié')) {
                if (score === 10) message.textContent = "Exceptionnel ! Vous êtes un maître Laravel. 🏆";
                else if (score >= 8) message.textContent = "Excellent score ! Très solide. 🚀";
                else if (score >= 5) message.textContent = "Bien joué ! Encore un petit effort. 💪";
                else message.textContent = "Continuez d'apprendre, c'est le métier qui rentre ! 📚";
            }

            // Populate Mistakes
            const mistakesSection = document.getElementById('mistakes-section');
            const mistakesList = document.getElementById('mistakes-list');
            mistakesList.innerHTML = '';

            if (mistakes.length > 0) {
                mistakesSection.style.display = 'block';
                mistakes.forEach(m => {
                    const li = document.createElement('li');
                    li.className = 'mistake-item';
                    li.innerHTML = `
                                                                                <div class="mistake-header">
                                                                                    <div class="mistake-icon-wrapper">
                                                                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                                                                    </div>
                                                                                    <h5 class="mistake-question">${m.question}</h5>
                                                                                </div>
                                                                                <div class="mistake-body">
                                                                                    <p class="mistake-explanation">
                                                                                        <svg class="explanation-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                                                                                        ${m.explanation}
                                                                                    </p>
                                                                                </div>
                                                                            `;
                    mistakesList.appendChild(li);
                });
            } else {
                mistakesSection.style.display = 'none';
            }
        }

        function saveScore() {
            fetch('{{ route('quiz.score') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ score })
            }).catch(e => console.error(e));
        }

        function loadLeaderboard() {
            fetch('{{ route('quiz.leaderboard') }}')
                .then(r => r.json())
                .then(users => {
                    const list = document.getElementById('leaderboard-list');
                    list.innerHTML = '';
                    if (users.length === 0) {
                        list.innerHTML = '<li style="padding:20px;text-align:center">Aucun score pour le moment.</li>';
                        return;
                    }
                    users.forEach((u, i) => {
                        const li = document.createElement('li');
                        li.className = 'leaderboard-item';
                        const avatar = u.avatar ? `<img src="${u.avatar}" style="width:32px;height:32px;border-radius:50%;object-fit:cover;">` : `<div style="width:32px;height:32px;background:#cbd5e1;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;">${u.name[0]}</div>`;
                        li.innerHTML = `<span class="leaderboard-rank">${i + 1}</span>
                                                                                                <div class="leaderboard-user">${avatar} ${u.name}</div>
                                                                                                <span class="leaderboard-score">${u.score}/10</span>`;
                        list.appendChild(li);
                    });
                });
        }

        function retryQuiz() {
            localStorage.removeItem('laravel_quiz_result');
            window.location.reload();
        }
    </script>
@endsection