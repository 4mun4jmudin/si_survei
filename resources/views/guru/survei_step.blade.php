<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $kuesioner->nama_kuesioner }} — SiSurvei</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        
        :root {
            --indigo-50: #eef2ff; --indigo-100: #e0e7ff; --indigo-200: #c7d2fe;
            --indigo-500: #6366f1; --indigo-600: #4f46e5; --indigo-700: #4338ca;
            --emerald-50: #ecfdf5; --emerald-400: #34d399; --emerald-500: #10b981; --emerald-600: #059669;
            --slate-50: #f8fafc; --slate-100: #f1f5f9; --slate-200: #e2e8f0;
            --slate-300: #cbd5e1; --slate-400: #94a3b8; --slate-500: #64748b;
            --slate-600: #475569; --slate-700: #334155; --slate-800: #1e293b; --slate-900: #0f172a;
            --red-50: #fef2f2; --red-500: #ef4444; --red-600: #dc2626;
            --orange-50: #fff7ed; --orange-500: #f97316; --orange-600: #ea580c;
            --amber-50: #fffbeb; --amber-500: #f59e0b; --amber-600: #d97706;
            --blue-50: #eff6ff; --blue-500: #3b82f6; --blue-600: #2563eb;
        }
        
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #eef2ff 50%, #f0fdf4 100%);
            color: var(--slate-900);
            min-height: 100dvh;
            overflow: hidden;
            -webkit-font-smoothing: antialiased;
        }

        .survey-header {
            position: fixed; top: 0; left: 0; right: 0; z-index: 50;
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(0,0,0,0.04);
            padding: 0 1.25rem;
            height: 56px;
            display: flex; align-items: center;
        }
        .header-inner {
            width: 100%; max-width: 640px; margin: 0 auto;
            display: flex; align-items: center; justify-content: space-between;
        }
        .header-brand {
            display: flex; align-items: center; gap: 0.5rem;
            font-size: 0.8rem; font-weight: 700; color: var(--slate-600);
        }
        .header-brand-icon {
            width: 28px; height: 28px; border-radius: 8px;
            background: linear-gradient(135deg, var(--indigo-600), var(--indigo-500));
            display: flex; align-items: center; justify-content: center;
        }
        .header-brand-icon svg { width: 14px; height: 14px; color: white; }
        .header-counter {
            font-size: 0.75rem; font-weight: 700; color: var(--slate-400);
            letter-spacing: 0.05em;
        }
        .header-counter span { color: var(--indigo-600); }

        .progress-track {
            position: fixed; top: 56px; left: 0; right: 0; z-index: 49;
            height: 3px; background: var(--slate-100);
        }
        .progress-fill {
            height: 100%; background: linear-gradient(90deg, var(--indigo-500), var(--emerald-400));
            border-radius: 0 2px 2px 0;
            transition: width 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .survey-viewport {
            position: fixed; top: 59px; left: 0; right: 0; bottom: 0;
            overflow: hidden;
        }
        .survey-container {
            max-width: 640px; margin: 0 auto; padding: 0 1.5rem;
            height: 100%;
            display: flex; flex-direction: column; justify-content: center;
        }

        .slide {
            display: none;
            flex-direction: column;
            animation: slideIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        .slide.active { display: flex; }
        .slide.slide-out {
            animation: slideOut 0.25s cubic-bezier(0.4, 0, 1, 1) forwards;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(24px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideOut {
            from { opacity: 1; transform: translateY(0); }
            to { opacity: 0; transform: translateY(-16px); }
        }

        .indicator-badge {
            display: inline-flex; align-items: center; gap: 0.375rem;
            font-size: 0.65rem; font-weight: 800; text-transform: uppercase;
            letter-spacing: 0.1em; color: var(--indigo-600);
            background: var(--indigo-50); border: 1px solid var(--indigo-100);
            padding: 0.3rem 0.75rem; border-radius: 999px;
            margin-bottom: 1rem;
        }

        .question-text {
            font-size: clamp(1.35rem, 4vw, 1.75rem);
            font-weight: 800; line-height: 1.35;
            color: var(--slate-900);
            margin-bottom: 2rem;
        }

        .options-grid {
            display: flex; flex-direction: column; gap: 0.625rem;
        }
        .option-card {
            position: relative; cursor: pointer;
            display: flex; align-items: center; gap: 0.875rem;
            padding: 1rem 1.25rem;
            background: white;
            border: 2px solid var(--slate-200);
            border-radius: 1rem;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            -webkit-tap-highlight-color: transparent;
            user-select: none;
        }
        .option-card:hover {
            border-color: var(--indigo-200);
            background: var(--indigo-50);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(79,70,229,0.08);
        }
        .option-card:active { transform: scale(0.98); }
        .option-card.selected {
            border-color: var(--indigo-600);
            background: linear-gradient(135deg, var(--indigo-50), white);
            box-shadow: 0 0 0 3px rgba(79,70,229,0.12), 0 4px 16px rgba(79,70,229,0.1);
        }
        .option-card.selected .option-key {
            background: var(--indigo-600); color: white;
            box-shadow: 0 2px 8px rgba(79,70,229,0.3);
        }
        .option-card.selected .option-label { color: var(--indigo-700); font-weight: 700; }
        .option-card input { position: absolute; opacity: 0; pointer-events: none; }

        .option-key {
            flex-shrink: 0;
            width: 36px; height: 36px; border-radius: 10px;
            background: var(--slate-100); color: var(--slate-500);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.85rem; font-weight: 800;
            transition: all 0.2s ease;
        }
        .option-label {
            font-size: 0.9rem; font-weight: 600; color: var(--slate-700);
            transition: color 0.2s ease;
        }
        .option-check {
            margin-left: auto; opacity: 0;
            transition: opacity 0.2s ease, transform 0.2s ease;
            transform: scale(0.5);
            color: var(--indigo-600);
        }
        .option-card.selected .option-check { opacity: 1; transform: scale(1); }

        .nav-bottom {
            position: fixed; bottom: 0; left: 0; right: 0; z-index: 50;
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px);
            border-top: 1px solid rgba(0,0,0,0.04);
            padding: 0.75rem 1.25rem;
            padding-bottom: max(0.75rem, env(safe-area-inset-bottom));
        }
        .nav-inner {
            max-width: 640px; margin: 0 auto;
            display: flex; align-items: center; justify-content: space-between; gap: 0.75rem;
        }
        .nav-btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;
            padding: 0.75rem 1.5rem; border-radius: 0.875rem;
            font-size: 0.85rem; font-weight: 700;
            border: none; cursor: pointer;
            transition: all 0.2s ease;
            -webkit-tap-highlight-color: transparent;
        }
        .nav-btn svg { width: 18px; height: 18px; }
        .nav-btn-secondary { background: var(--slate-100); color: var(--slate-600); }
        .nav-btn-secondary:hover { background: var(--slate-200); }
        .nav-btn-primary {
            background: var(--indigo-600); color: white;
            box-shadow: 0 4px 14px rgba(79,70,229,0.25);
        }
        .nav-btn-primary:hover { background: var(--indigo-700); transform: translateY(-1px); }
        .nav-btn-submit {
            background: linear-gradient(135deg, var(--emerald-500), var(--emerald-600));
            color: white; flex: 1;
            box-shadow: 0 4px 14px rgba(5,150,105,0.3);
        }
        .nav-btn-submit:hover { transform: translateY(-1px); }
        .nav-btn:disabled { opacity: 0.4; cursor: not-allowed; transform: none !important; }

        .completion-screen {
            display: none;
            flex-direction: column; align-items: center; justify-content: center;
            text-align: center;
            animation: slideIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        .completion-screen.active { display: flex; }
        .completion-icon {
            width: 80px; height: 80px; border-radius: 50%;
            background: linear-gradient(135deg, var(--emerald-400), var(--emerald-500));
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 1.5rem;
            box-shadow: 0 8px 32px rgba(16,185,129,0.3);
            animation: popIn 0.5s 0.2s cubic-bezier(0.34, 1.56, 0.64, 1) both;
        }
        @keyframes popIn { from { transform: scale(0); } to { transform: scale(1); } }
        .completion-icon svg { width: 40px; height: 40px; color: white; }
        .completion-title { font-size: 1.75rem; font-weight: 800; color: var(--slate-900); margin-bottom: 0.5rem; }
        .completion-desc { font-size: 0.95rem; color: var(--slate-500); max-width: 360px; line-height: 1.6; }

        @keyframes selectPulse {
            0% { box-shadow: 0 0 0 0 rgba(79,70,229,0.3); }
            70% { box-shadow: 0 0 0 10px rgba(79,70,229,0); }
            100% { box-shadow: 0 0 0 0 rgba(79,70,229,0); }
        }
        .option-card.just-selected { animation: selectPulse 0.6s ease-out; }

        html, body { overscroll-behavior: none; }
        @supports(padding: env(safe-area-inset-bottom)) {
            .nav-bottom { padding-bottom: calc(0.75rem + env(safe-area-inset-bottom)); }
        }
        @media (min-width: 768px) {
            .options-grid { max-width: 520px; }
            .option-card { padding: 1.125rem 1.5rem; }
            .question-text { margin-bottom: 2.5rem; }
        }
    </style>
</head>
<body>
    <header class="survey-header">
        <div class="header-inner">
            <div class="header-brand">
                <div class="header-brand-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span style="max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $kuesioner->nama_kuesioner }}</span>
            </div>
            <div class="header-counter" id="headerCounter">
                <span id="currentNum">1</span> / {{ $allPertanyaan->count() }}
            </div>
        </div>
    </header>

    <div class="progress-track">
        <div class="progress-fill" id="progressFill" style="width: 0%"></div>
    </div>

    <div class="survey-viewport">
        <div class="survey-container">
            <form action="{{ route('guru.survei.store', $kuesioner) }}" method="POST" id="surveyForm">
                @csrf

                @foreach($allPertanyaan as $index => $item)
                <div class="slide {{ $index === 0 ? 'active' : '' }}" data-slide="{{ $index }}" data-question-id="{{ $item->id }}">
                    <div class="indicator-badge">
                        {{ $item->indikator ?: 'Umum' }}
                    </div>

                    <h2 class="question-text">{{ $item->isi_pertanyaan }}</h2>

                    <div class="options-grid">
                        @php
                            $options = [
                                1 => ['label' => 'Sangat Tidak Setuju', 'key' => '1', 'emoji' => '😞'],
                                2 => ['label' => 'Tidak Setuju', 'key' => '2', 'emoji' => '😕'],
                                3 => ['label' => 'Netral', 'key' => '3', 'emoji' => '😐'],
                                4 => ['label' => 'Setuju', 'key' => '4', 'emoji' => '🙂'],
                                5 => ['label' => 'Sangat Setuju', 'key' => '5', 'emoji' => '😄'],
                            ];
                        @endphp

                        @foreach($options as $val => $opt)
                        <label class="option-card" data-value="{{ $val }}">
                            <input type="radio" name="jawaban[{{ $item->id }}]" value="{{ $val }}" required>
                            <div class="option-key">{{ $opt['emoji'] }}</div>
                            <span class="option-label">{{ $opt['label'] }}</span>
                            <svg class="option-check" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endforeach

                <div class="completion-screen" id="completionScreen">
                    <div class="completion-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <h2 class="completion-title">Semua Terjawab! 🎉</h2>
                    <p class="completion-desc">Terima kasih atas partisipasi Anda. Klik tombol di bawah untuk mengirim semua jawaban.</p>
                </div>
            </form>
        </div>
    </div>

    <div class="nav-bottom">
        <div class="nav-inner">
            <button type="button" class="nav-btn nav-btn-secondary" id="btnPrev" style="visibility:hidden">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali
            </button>
            <button type="button" class="nav-btn nav-btn-primary" id="btnNext" disabled>
                Lanjut
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
            <button type="submit" form="surveyForm" class="nav-btn nav-btn-submit" id="btnSubmit" style="display:none">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                Kirim Jawaban
            </button>
        </div>
    </div>

    <script>
    (function() {
        const totalSlides = {{ $allPertanyaan->count() }};
        let current = 0;
        let answers = {};
        const slides = document.querySelectorAll('.slide');
        const progressFill = document.getElementById('progressFill');
        const currentNum = document.getElementById('currentNum');
        const btnPrev = document.getElementById('btnPrev');
        const btnNext = document.getElementById('btnNext');
        const btnSubmit = document.getElementById('btnSubmit');
        const completionScreen = document.getElementById('completionScreen');

        document.querySelectorAll('.option-card').forEach(card => {
            card.addEventListener('click', function() {
                const slide = this.closest('.slide');
                const qid = slide.dataset.questionId;
                const val = this.dataset.value;
                slide.querySelectorAll('.option-card').forEach(c => c.classList.remove('selected', 'just-selected'));
                this.classList.add('selected', 'just-selected');
                this.querySelector('input').checked = true;
                answers[qid] = val;
                setTimeout(() => this.classList.remove('just-selected'), 600);
                setTimeout(() => {
                    if (current < totalSlides - 1) goTo(current + 1);
                    else showCompletion();
                }, 450);
            });
        });

        function goTo(index) {
            if (index < 0 || index >= totalSlides) return;
            const oldSlide = slides[current];
            oldSlide.classList.add('slide-out');
            setTimeout(() => {
                oldSlide.classList.remove('active', 'slide-out');
                completionScreen.classList.remove('active');
                current = index;
                slides[current].classList.add('active');
                updateUI();
            }, 250);
        }

        function showCompletion() {
            const oldSlide = slides[current];
            oldSlide.classList.add('slide-out');
            setTimeout(() => {
                oldSlide.classList.remove('active', 'slide-out');
                completionScreen.classList.add('active');
                btnNext.style.display = 'none';
                btnSubmit.style.display = 'inline-flex';
                btnPrev.style.visibility = 'visible';
                progressFill.style.width = '100%';
                currentNum.textContent = '✓';
            }, 250);
        }

        function updateUI() {
            const pct = Math.round(((current + 1) / totalSlides) * 100);
            progressFill.style.width = pct + '%';
            currentNum.textContent = current + 1;
            btnPrev.style.visibility = current > 0 ? 'visible' : 'hidden';
            btnNext.style.display = 'inline-flex';
            btnSubmit.style.display = 'none';
            const qid = slides[current].dataset.questionId;
            btnNext.disabled = !answers[qid];
            if (answers[qid]) {
                slides[current].querySelectorAll('.option-card').forEach(c => {
                    if (c.dataset.value === answers[qid]) {
                        c.classList.add('selected');
                        c.querySelector('input').checked = true;
                    }
                });
            }
        }

        btnPrev.addEventListener('click', () => {
            if (completionScreen.classList.contains('active')) {
                completionScreen.classList.remove('active');
                slides[current].classList.add('active');
                updateUI();
            } else { goTo(current - 1); }
        });
        btnNext.addEventListener('click', () => goTo(current + 1));

        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowRight' || e.key === 'Enter') {
                if (!btnNext.disabled && btnNext.style.display !== 'none') btnNext.click();
            }
            if (e.key === 'ArrowLeft') btnPrev.click();
            if (e.key >= '1' && e.key <= '5') {
                const activeSlide = document.querySelector('.slide.active');
                if (activeSlide) {
                    const card = activeSlide.querySelector(`.option-card[data-value="${e.key}"]`);
                    if (card) card.click();
                }
            }
        });

        updateUI();
    })();
    </script>
</body>
</html>