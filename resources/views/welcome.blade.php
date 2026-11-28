<!DOCTYPE html>
<html lang="zh-Hant" class="dark h-full">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Flex & Form - PNF Stretching & TUT Web Prototype</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

  <script>
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {
          colors: {
            bgDark: '#0D0D0D',
            surfaceDark: '#161618',
            cardDark: '#1E1E22',
            goldAccent: '#E0AC69',
            goldHover: '#C89352',
            brownRich: '#6B3E11',
            darkAccent: '#402710',
            emeraldSuccess: '#10B981',
            cyanAccent: '#06B6D4',
            purpleAccent: '#8B5CF6'
          },
          fontFamily: {
            sans: ['Inter', 'sans-serif'],
          }
        }
      }
    }
  </script>

  <style>
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: #121212; }
    ::-webkit-scrollbar-thumb { background: #2A2A2E; border-radius: 3px; }
    ::-webkit-scrollbar-thumb:hover { background: #E0AC69; }
    .progress-ring__circle { transition: stroke-dashoffset 0.35s linear, stroke 0.3s ease; transform: rotate(-90deg); transform-origin: 50% 50%; }
    @keyframes pulse-ring { 0% { transform: scale(0.98); opacity: 0.8; } 50% { transform: scale(1.02); opacity: 1; } 100% { transform: scale(0.98); opacity: 0.8; } }
    .animate-pulse-ring { animation: pulse-ring 2s infinite ease-in-out; }
    .glass-card { background: rgba(30, 30, 34, 0.75); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.08); }
  </style>
</head>
<body class="bg-bgDark text-gray-100 font-sans min-h-screen flex flex-col justify-between selection:bg-goldAccent selection:text-black">

  <!-- Header / Navigation Bar -->
  <header class="sticky top-0 z-50 glass-card border-b border-gray-800/80 px-4 lg:px-8 py-3">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-3">
      <div class="flex items-center justify-between w-full md:w-auto gap-3">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-goldAccent to-brownRich flex items-center justify-center text-black font-extrabold text-xl shadow-lg shadow-goldAccent/20">
            <i class="fa-solid fa-bolt"></i>
          </div>
          <div>
            <div class="flex items-center gap-2">
              <h1 class="text-xl font-bold tracking-tight text-white">Flex <span class="text-goldAccent">&amp;</span> Form</h1>
              <span class="text-[10px] uppercase font-bold tracking-widest px-2 py-0.5 rounded bg-goldAccent/20 text-goldAccent border border-goldAccent/30">Prototype v1.0</span>
            </div>
            <p class="text-xs text-gray-400" data-i18n="header.tagline">PNF Stretching, TUT Cadence &amp; Posture Correction Assistant</p>
          </div>
        </div>

        <!-- Mobile Language & Audio Controls -->
        <div class="flex md:hidden items-center gap-2">
          <button type="button" onclick="toggleLanguage()" class="px-2.5 py-1.5 rounded-lg bg-cardDark border border-goldAccent/40 text-xs font-bold text-goldAccent">
            <span class="lang-label">中文 / EN</span>
          </button>
          <button type="button" onclick="toggleMute()" class="px-2.5 py-1.5 rounded-lg bg-cardDark border border-gray-700/60 text-xs text-goldAccent">
            <i class="fa-solid fa-volume-high audio-icon-el"></i>
          </button>
        </div>
      </div>

      <!-- Navigation Tabs -->
      <nav class="flex items-center gap-1 bg-surfaceDark p-1.5 rounded-xl border border-gray-800/80 overflow-x-auto max-w-full">
        <button id="tab-btn-pnf" type="button" onclick="switchTab('pnf')" class="tab-btn active-tab flex items-center gap-2 px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all duration-200 bg-goldAccent text-black shadow-md whitespace-nowrap">
          <i class="fa-solid fa-stopwatch"></i> <span data-i18n="nav.pnf">PNF Assistant</span>
        </button>
        <button id="tab-btn-tut" type="button" onclick="switchTab('tut')" class="tab-btn flex items-center gap-2 px-3.5 py-1.5 rounded-lg text-xs font-semibold text-gray-400 hover:text-white hover:bg-cardDark transition-all duration-200 whitespace-nowrap">
          <i class="fa-solid fa-dumbbell"></i> <span data-i18n="nav.tut">TUT Timer</span>
        </button>
        <button id="tab-btn-posture" type="button" onclick="switchTab('posture')" class="tab-btn flex items-center gap-2 px-3.5 py-1.5 rounded-lg text-xs font-semibold text-gray-400 hover:text-white hover:bg-cardDark transition-all duration-200 whitespace-nowrap">
          <i class="fa-solid fa-child-reaching"></i> <span data-i18n="nav.posture">Posture</span>
        </button>
        <button id="tab-btn-analytics" type="button" onclick="switchTab('analytics')" class="tab-btn flex items-center gap-2 px-3.5 py-1.5 rounded-lg text-xs font-semibold text-gray-400 hover:text-white hover:bg-cardDark transition-all duration-200 whitespace-nowrap">
          <i class="fa-solid fa-chart-line"></i> <span data-i18n="nav.analytics">Analytics &amp; SUS</span>
        </button>
        <a href="/ai-assessment" id="tab-btn-ai" class="tab-btn flex items-center gap-2 px-3.5 py-1.5 rounded-lg text-xs font-semibold text-gray-400 hover:text-white hover:bg-cardDark transition-all duration-200 whitespace-nowrap">
          <i class="fa-solid fa-robot"></i> <span data-i18n="nav.ai">AI Assessment</span>
        </a>
      </nav>

      <!-- Desktop Language & Audio Controls -->
      <div class="hidden md:flex items-center gap-2">
        <button id="lang-toggle-btn" type="button" onclick="toggleLanguage()" class="px-3 py-1.5 rounded-lg bg-cardDark hover:bg-gray-800 border border-goldAccent/40 text-xs font-bold flex items-center gap-1.5 text-goldAccent transition">
          <i class="fa-solid fa-language"></i>
          <span class="lang-label">中文 / EN</span>
        </button>
        <button id="audio-toggle-btn" type="button" onclick="toggleMute()" class="px-3 py-1.5 rounded-lg bg-cardDark hover:bg-gray-800 border border-gray-700/60 text-xs font-medium flex items-center gap-2 text-goldAccent transition">
          <i id="audio-icon" class="fa-solid fa-volume-high audio-icon-el"></i>
          <span id="audio-status-text" data-i18n="audio.on">Audio On</span>
        </button>
      </div>
    </div>
  </header>

  <!-- Main Content Area -->
  <main class="flex-1 max-w-7xl w-full mx-auto p-4 sm:p-6 lg:p-8 space-y-6">
    @includeIf('partials.pnf')
    @includeIf('partials.tut')
    @includeIf('partials.posture')
    @includeIf('partials.analytics')
  </main>

  <!-- Footer -->
  <footer class="glass-card border-t border-gray-800/80 py-4 px-6 text-center text-xs text-gray-500">
    <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-2">
      <p data-i18n="footer.copy">© 2026 Flex &amp; Form Academic Web Prototype. Designed for Integrated Study SEHS4510.</p>
      <p class="text-gray-400" data-i18n="footer.sub">Software Evaluation &amp; Prototyping Research Project</p>
    </div>
  </footer>

  <!-- MODAL: POSTURE EXERCISE GUIDED HOLD TIMER -->
  <div id="posture-modal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="glass-card rounded-2xl p-6 max-w-md w-full border border-gray-800 text-center space-y-5 animate-pulse-ring">
      <div class="flex items-center justify-between border-b border-gray-800 pb-3">
        <span id="modal-category" class="text-[10px] font-bold uppercase tracking-widest px-2.5 py-1 rounded bg-goldAccent/20 text-goldAccent">
          Posture Correction
        </span>
        <button type="button" onclick="closeExerciseModal()" class="text-gray-400 hover:text-white text-lg">
          <i class="fa-solid fa-xmark"></i>
        </button>
      </div>

      <div>
        <h3 id="modal-exercise-title" class="text-xl font-bold text-white">Exercise Title</h3>
        <p class="text-xs text-gray-400 mt-1" data-i18n="modal.hint">Maintain continuous contraction &amp; proper alignment</p>
      </div>

      <div class="w-36 h-36 rounded-full bg-surfaceDark border-4 border-goldAccent mx-auto flex items-center justify-center">
        <span id="modal-timer-digits" class="text-4xl font-extrabold font-mono text-goldAccent">30</span>
      </div>

      <div class="flex items-center justify-center gap-3 pt-2">
        <button id="modal-btn-start" type="button" onclick="startModalExerciseTimer()" class="py-2.5 px-5 rounded-xl font-bold text-xs bg-goldAccent text-black hover:bg-goldHover transition">
          <i class="fa-solid fa-play mr-1"></i> <span data-i18n="modal.start">Start</span>
        </button>
        <button type="button" onclick="closeExerciseModal()" class="py-2.5 px-5 rounded-xl font-bold text-xs bg-gray-800 text-gray-300 hover:bg-gray-700 transition" data-i18n="modal.close">
          Done / Close
        </button>
      </div>
    </div>
  </div>

  <!-- JavaScript Logic -->
  <script>
    const I18N_DICT = {
      zh: {
        "header.tagline": "PNF 伸展、TUT 節奏與體態矯正輔助系統",
        "nav.pnf": "PNF 伸展助手",
        "nav.tut": "TUT 節奏計時",
        "nav.posture": "體態矯正庫",
        "nav.analytics": "數據與 SUS 量表",
        "nav.ai": "AI 體態實驗室",
        "audio.on": "語音開啟",
        "audio.muted": "已靜音",
        "footer.copy": "© 2026 Flex & Form 學術網頁原型 · SEHS4510 專題研究",
        "footer.sub": "軟體評估與原型設計研究專案",
        "modal.hint": "維持穩定收縮與正確關節排列",
        "modal.start": "開始",
        "modal.close": "完成 / 關閉"
      },
      en: {
        "header.tagline": "PNF Stretching, TUT Cadence & Posture Correction Assistant",
        "nav.pnf": "PNF Assistant",
        "nav.tut": "TUT Timer",
        "nav.posture": "Posture",
        "nav.analytics": "Analytics & SUS",
        "nav.ai": "AI Assessment",
        "audio.on": "Audio On",
        "audio.muted": "Muted",
        "footer.copy": "© 2026 Flex & Form Academic Web Prototype. Designed for Integrated Study SEHS4510.",
        "footer.sub": "Software Evaluation & Prototyping Research Project",
        "modal.hint": "Maintain continuous contraction & proper alignment",
        "modal.start": "Start",
        "modal.close": "Done / Close"
      }
    };

    window.currentLang = localStorage.getItem("flexform_lang") || "zh";

    window.applyLanguage = function (lang) {
      window.currentLang = (lang === "en") ? "en" : "zh";
      localStorage.setItem("flexform_lang", window.currentLang);
      document.documentElement.lang = window.currentLang === "en" ? "en" : "zh-Hant";

      const dict = I18N_DICT[window.currentLang] || I18N_DICT.zh;
      document.querySelectorAll("[data-i18n]").forEach((el) => {
        const key = el.getAttribute("data-i18n");
        if (dict[key]) el.textContent = dict[key];
      });

      document.querySelectorAll(".lang-label").forEach((el) => {
        el.textContent = window.currentLang === "en" ? "EN / 中文" : "中文 / EN";
      });

      const audioText = document.getElementById("audio-status-text");
      if (audioText) {
        audioText.textContent = isMuted ? dict["audio.muted"] : dict["audio.on"];
      }

      if (typeof renderPnfGuideList === "function" && typeof pnfActiveSteps !== "undefined" && pnfActiveSteps.length) {
        renderPnfGuideList();
        updatePnfUI();
      }
      if (typeof updateTutUI === "function") updateTutUI();
      if (typeof window.rerenderSusWithCurrentLang === "function") window.rerenderSusWithCurrentLang();
    };

    window.toggleLanguage = function () {
      window.applyLanguage(window.currentLang === "zh" ? "en" : "zh");
    };

    let isMuted = false;
    let totalPnfCyclesCompleted = 0;
    let totalTutRepsCompleted = 0;
    let activeSecondsLogged = 0;

    function speakCue(text) {
      if (isMuted || !('speechSynthesis' in window)) return;
      window.speechSynthesis.cancel();
      const utterance = new SpeechSynthesisUtterance(text);
      utterance.rate = 1.0;
      utterance.pitch = 1.0;
      window.speechSynthesis.speak(utterance);
    }

    function toggleMute() {
      isMuted = !isMuted;
      const dict = I18N_DICT[window.currentLang] || I18N_DICT.zh;
      const btnText = document.getElementById('audio-status-text');
      document.querySelectorAll('.audio-icon-el').forEach(icon => {
        icon.className = isMuted ? 'fa-solid fa-volume-xmark audio-icon-el' : 'fa-solid fa-volume-high audio-icon-el';
      });
      if (btnText) btnText.textContent = isMuted ? dict["audio.muted"] : dict["audio.on"];
      if (!isMuted) speakCue(window.currentLang === "en" ? "Audio enabled" : "語音已開啟");
    }

    setInterval(() => {
      if (pnfTimerInterval || tutTimerInterval || modalTimerInterval) {
        activeSecondsLogged++;
        const mins = Math.floor(activeSecondsLogged / 60);
        const secs = activeSecondsLogged % 60;
        const el = document.getElementById('stat-active-time');
        if (el) el.textContent = `${mins}m ${secs}s`;
      }
    }, 1000);

    function switchTab(tabId) {
      document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
      document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.className = 'tab-btn flex items-center gap-2 px-3.5 py-1.5 rounded-lg text-xs font-semibold text-gray-400 hover:text-white hover:bg-cardDark transition-all duration-200 whitespace-nowrap';
      });

      const targetSection = document.getElementById(`module-${tabId}`);
      if (targetSection) targetSection.classList.remove('hidden');

      const activeBtn = document.getElementById(`tab-btn-${tabId}`);
      if (activeBtn) {
        activeBtn.className = 'tab-btn active-tab flex items-center gap-2 px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all duration-200 bg-goldAccent text-black shadow-md whitespace-nowrap';
      }

      if (tabId === 'ai') {
        if (window.startAiCamera) window.startAiCamera();
      } else {
        if (window.stopAiCamera) window.stopAiCamera();
      }
    }

    function handlePostureCardClick(button) {
      const title = button.dataset.title;
      const duration = parseInt(button.dataset.duration);
      const categoryName = button.dataset.categoryName;
      openExerciseModal(title, duration, categoryName);
    }

    /* MODULE 1: PNF STRETCHING TIMER LOGIC */
    let pnfActiveSteps = [];
    let pnfPhaseIndex = 0;
    let pnfTimeRemaining = 10;
    let pnfTimerInterval = null;
    let pnfCycle = 1;
    let pnfCurrentRoutineName = '';

    function handlePnfPresetClick(button) {
      const title = button.dataset.title;
      const steps = JSON.parse(button.dataset.steps || '[]');

      document.querySelectorAll('.pnf-preset-btn').forEach(btn => {
        btn.className = 'pnf-preset-btn whitespace-nowrap px-3 py-1.5 rounded-lg text-xs font-semibold bg-cardDark text-gray-300 border border-gray-700 hover:bg-gray-800 transition';
      });
      button.className = 'pnf-preset-btn whitespace-nowrap active-preset px-3 py-1.5 rounded-lg text-xs font-semibold bg-goldAccent/20 text-goldAccent border border-goldAccent/40 hover:bg-goldAccent hover:text-black transition border';

      selectPnfRoutine(title, steps);
    }

    function selectPnfRoutine(routineName, steps) {
      pnfCurrentRoutineName = routineName;
      pnfActiveSteps = steps && steps.length > 0 ? steps : [
        { step_number: 1, step_name_zh: '預備動作', step_name_en: 'Prep', duration_seconds: 10, instruction: '設定預備姿勢', instruction_en: 'Set starting posture' },
        { step_number: 2, step_name_zh: '初次拉伸', step_name_en: 'Initial Stretch', duration_seconds: 10, instruction: '維持輕微張力', instruction_en: 'Hold gentle stretch' },
        { step_number: 3, step_name_zh: '等長收縮', step_name_en: 'Isometric Hold', duration_seconds: 6, instruction: '發力推抵門框', instruction_en: 'Contract against resistance' },
        { step_number: 4, step_name_zh: '短暫放鬆', step_name_en: 'Relax', duration_seconds: 3, instruction: '完全放鬆肌肉', instruction_en: 'Relax muscle completely' },
        { step_number: 5, step_name_zh: '二次推進', step_name_en: 'Deep Stretch', duration_seconds: 15, instruction: '深入新伸展終點', instruction_en: 'Advance into deeper stretch' },
        { step_number: 6, step_name_zh: '復位放鬆', step_name_en: 'Recovery', duration_seconds: 5, instruction: '緩慢收回放鬆', instruction_en: 'Return slowly and relax' }
      ];

      const targetLabel = document.getElementById('pnf-target-label');
      if (targetLabel) targetLabel.textContent = `Target: ${routineName}`;
      renderPnfGuideList();
      renderPnfStepper();
      resetPnfTimer();
    }

    function renderPnfGuideList() {
      const guideContainer = document.getElementById('pnf-guide-steps-list');
      if (!guideContainer) return;
      guideContainer.innerHTML = '';
      const isEn = window.currentLang === 'en';
      pnfActiveSteps.forEach(step => {
        const stepName = isEn ? (step.step_name_en || step.step_name_zh) : step.step_name_zh;
        const stepDesc = isEn ? (step.instruction_en || step.instruction) : step.instruction;
        const item = document.createElement('div');
        item.className = 'p-3 rounded-xl bg-surfaceDark border border-gray-800/80';
        item.innerHTML = `
          <span class="font-bold text-goldAccent block mb-0.5">Step ${step.step_number}: ${stepName} (${step.duration_seconds}s)</span>
          <span class="text-gray-300">${stepDesc}</span>
        `;
        guideContainer.appendChild(item);
      });
    }

    function renderPnfStepper() {
      const container = document.getElementById('pnf-stepper-container');
      if (!container) return;
      container.innerHTML = '';
      const isEn = window.currentLang === 'en';
      pnfActiveSteps.forEach((step, idx) => {
        const stepName = isEn ? (step.step_name_en || step.step_name_zh) : step.step_name_zh;
        const div = document.createElement('div');
        div.id = `pnf-step-box-${idx}`;
        div.className = 'p-2 rounded-xl bg-cardDark border border-gray-800 text-gray-400 transition-all duration-300';
        div.innerHTML = `
          <p class="text-[9px] font-bold uppercase tracking-wider">Step ${step.step_number}</p>
          <p class="text-[11px] font-semibold font-mono truncate">${stepName}</p>
        `;
        container.appendChild(div);
      });
    }

    function updatePnfUI() {
      if (pnfActiveSteps.length === 0) return;
      const step = pnfActiveSteps[pnfPhaseIndex];
      const isEn = window.currentLang === 'en';
      const stepName = isEn ? (step.step_name_en || step.step_name_zh) : step.step_name_zh;
      const stepDesc = isEn ? (step.instruction_en || step.instruction) : step.instruction;

      const phaseBadge = document.getElementById('pnf-phase-badge');
      const timerDigits = document.getElementById('pnf-timer-digits');
      const instText = document.getElementById('pnf-instruction-text');
      if (phaseBadge) phaseBadge.textContent = `STEP ${step.step_number}: ${stepName}`;
      if (timerDigits) timerDigits.textContent = pnfTimeRemaining;
      if (instText) instText.textContent = stepDesc;

      const circle = document.getElementById('pnf-progress-circle');
      if (circle) {
        const maxDuration = step.duration_seconds || 10;
        const strokeDashoffset = 326.72 - (pnfTimeRemaining / maxDuration) * 326.72;
        circle.style.strokeDashoffset = strokeDashoffset;
      }

      pnfActiveSteps.forEach((_, idx) => {
        const el = document.getElementById(`pnf-step-box-${idx}`);
        if (el) {
          if (idx === pnfPhaseIndex) {
            el.className = 'p-2 rounded-xl bg-goldAccent/20 border border-goldAccent text-goldAccent font-bold transition-all duration-300 scale-105';
          } else {
            el.className = 'p-2 rounded-xl bg-cardDark border border-gray-800 text-gray-400 transition-all duration-300 opacity-60';
          }
        }
      });
    }

    function startPnfTimer() {
      if (pnfTimerInterval) return;
      document.getElementById('pnf-btn-start').disabled = true;
      document.getElementById('pnf-btn-start').classList.add('opacity-50');
      document.getElementById('pnf-btn-pause').disabled = false;

      speakCue(pnfActiveSteps[pnfPhaseIndex].step_name_zh);

      pnfTimerInterval = setInterval(() => {
        pnfTimeRemaining--;
        if (pnfTimeRemaining < 0) {
          advancePnfPhase();
        } else {
          updatePnfUI();
        }
      }, 1000);
    }

    function pausePnfTimer() {
      clearInterval(pnfTimerInterval);
      pnfTimerInterval = null;
      const startBtn = document.getElementById('pnf-btn-start');
      const pauseBtn = document.getElementById('pnf-btn-pause');
      if (startBtn) {
        startBtn.disabled = false;
        startBtn.classList.remove('opacity-50');
      }
      if (pauseBtn) pauseBtn.disabled = true;
    }

    function resetPnfTimer() {
      pausePnfTimer();
      pnfPhaseIndex = 0;
      pnfTimeRemaining = pnfActiveSteps[0] ? pnfActiveSteps[0].duration_seconds : 10;
      pnfCycle = 1;
      const cycleEl = document.getElementById('pnf-cycle-count');
      if (cycleEl) cycleEl.textContent = pnfCycle;
      updatePnfUI();
    }

    function skipPnfPhase() {
      pausePnfTimer();
      advancePnfPhase();
    }

    function advancePnfPhase() {
      pnfPhaseIndex++;
      if (pnfPhaseIndex >= pnfActiveSteps.length) {
        pnfPhaseIndex = 0;
        pnfCycle++;
        totalPnfCyclesCompleted++;
        const statEl = document.getElementById('stat-pnf-cycles');
        if (statEl) statEl.textContent = totalPnfCyclesCompleted;
        logActivity(`Completed PNF Cycle (${pnfCurrentRoutineName})`);

        if (pnfCycle > 3) {
          resetPnfTimer();
          speakCue("PNF stretch protocol completed successfully");
          alert("Great job! You completed all 3 PNF stretch cycles.");
          return;
        }
        const cycleEl = document.getElementById('pnf-cycle-count');
        if (cycleEl) cycleEl.textContent = pnfCycle;
      }
      pnfTimeRemaining = pnfActiveSteps[pnfPhaseIndex].duration_seconds;
      updatePnfUI();
      if (pnfTimerInterval) {
        speakCue(pnfActiveSteps[pnfPhaseIndex].step_name_zh);
      } else {
        startPnfTimer();
      }
    }

    /* MODULE 2: TUT TEMPO TIMER LOGIC */
    let tutTempo = [4, 1, 1, 0];
    let tutCurrentStage = 0;
    let tutStageTime = tutTempo[0];
    let tutTimerInterval = null;
    let tutRepsCompleted = 0;

    function setTutTempo(e, p1, c, p2) {
      tutTempo = [e, p1, c, p2];
      document.getElementById('tut-input-e').value = e;
      document.getElementById('tut-input-p1').value = p1;
      document.getElementById('tut-input-c').value = c;
      document.getElementById('tut-input-p2').value = p2;
      updateTutFromInputs();
    }

    function updateTutFromInputs() {
      const eEl = document.getElementById('tut-input-e');
      if (!eEl) return;
      const e = parseInt(eEl.value) || 1;
      const p1 = parseInt(document.getElementById('tut-input-p1').value) || 0;
      const c = parseInt(document.getElementById('tut-input-c').value) || 1;
      const p2 = parseInt(document.getElementById('tut-input-p2').value) || 0;
      tutTempo = [e, p1, c, p2];

      const totalDuration = e + p1 + c + p2;
      const durEl = document.getElementById('tut-rep-duration');
      if (durEl) durEl.textContent = `${totalDuration.toFixed(1)} seconds`;
      resetTutTimer();
    }

    function updateTutUI() {
      const badge = document.getElementById('tut-phase-badge');
      const digits = document.getElementById('tut-seconds-display');
      const bar = document.getElementById('tut-rhythm-bar');
      if (!badge || !digits || !bar) return;

      const isEn = window.currentLang === 'en';
      const stageLabels = isEn
        ? ['ECCENTRIC (LOWERING)', 'BOTTOM PAUSE', 'CONCENTRIC (LIFTING)', 'TOP HOLD']
        : ['離心下放 (ECCENTRIC)', '底部停頓 (PAUSE)', '向心收縮 (CONCENTRIC)', '頂部停頓 (HOLD)'];
      const stageBadgeBg = ['bg-goldAccent/20 text-goldAccent', 'bg-amber-500/20 text-amber-400', 'bg-emerald-500/20 text-emeraldSuccess', 'bg-cyan-500/20 text-cyanAccent'];

      badge.textContent = stageLabels[tutCurrentStage];
      badge.className = `px-3 py-1 rounded-full text-xs font-extrabold tracking-widest uppercase border border-gray-700 ${stageBadgeBg[tutCurrentStage]}`;
      digits.textContent = tutStageTime;

      const stageMax = tutTempo[tutCurrentStage] || 1;
      const barPercent = Math.min(100, Math.max(0, ((stageMax - tutStageTime) / stageMax) * 100));
      bar.style.width = `${barPercent}%`;
    }

    function startTutTimer() {
      if (tutTimerInterval) return;
      document.getElementById('tut-btn-start').disabled = true;
      document.getElementById('tut-btn-pause').disabled = false;

      const stageSpeech = ['Lower slowly', 'Hold at bottom', 'Push up', 'Hold at top'];
      speakCue(stageSpeech[tutCurrentStage]);

      tutTimerInterval = setInterval(() => {
        tutStageTime--;
        if (tutStageTime < 0) {
          advanceTutStage();
        } else {
          updateTutUI();
        }
      }, 1000);
    }

    function pauseTutTimer() {
      clearInterval(tutTimerInterval);
      tutTimerInterval = null;
      const startBtn = document.getElementById('tut-btn-start');
      const pauseBtn = document.getElementById('tut-btn-pause');
      if (startBtn) startBtn.disabled = false;
      if (pauseBtn) pauseBtn.disabled = true;
    }

    function resetTutTimer() {
      pauseTutTimer();
      tutCurrentStage = 0;
      tutStageTime = tutTempo[0];
      tutRepsCompleted = 0;
      const repsEl = document.getElementById('tut-current-reps');
      if (repsEl) repsEl.textContent = 0;
      updateTutUI();
    }

    function advanceTutStage() {
      tutCurrentStage++;
      if (tutCurrentStage >= 4) {
        tutCurrentStage = 0;
        tutRepsCompleted++;
        totalTutRepsCompleted++;
        const repsEl = document.getElementById('tut-current-reps');
        if (repsEl) repsEl.textContent = tutRepsCompleted;
        const statEl = document.getElementById('stat-tut-reps');
        if (statEl) statEl.textContent = totalTutRepsCompleted;
        logActivity(`Completed TUT Repetition #${tutRepsCompleted}`);

        const targetReps = parseInt(document.getElementById('tut-target-reps').value) || 10;
        if (tutRepsCompleted >= targetReps) {
          resetTutTimer();
          speakCue("Target set completed! Great tempo control.");
          alert("Target TUT Set Completed!");
          return;
        }
      }

      if (tutTempo[tutCurrentStage] === 0) {
        advanceTutStage();
        return;
      }

      tutStageTime = tutTempo[tutCurrentStage];
      updateTutUI();

      const stageSpeech = ['Lowering', 'Pause', 'Lifting', 'Top Hold'];
      speakCue(stageSpeech[tutCurrentStage]);
    }

    /* MODULE 3: POSTURAL CORRECTION GUIDANCE */
    function filterPostureCategory(cat, btnElement) {
      document.querySelectorAll('.posture-filter-btn').forEach(btn => {
        btn.className = 'posture-filter-btn px-3 py-1.5 rounded-lg text-xs font-bold bg-cardDark text-gray-300 border border-gray-700 hover:bg-gray-800 transition';
      });
      if (btnElement) {
        btnElement.className = 'posture-filter-btn active-filter px-3 py-1.5 rounded-lg text-xs font-bold bg-goldAccent text-black shadow transition';
      }
      document.querySelectorAll('.posture-card').forEach(card => {
        if (cat === 'all' || card.dataset.category === cat) {
          card.classList.remove('hidden');
        } else {
          card.classList.add('hidden');
        }
      });
    }

    let modalTimerInterval = null;
    let modalTimeLeft = 30;

    function openExerciseModal(title, duration, category) {
      document.getElementById('modal-exercise-title').textContent = title;
      document.getElementById('modal-category').textContent = category;
      modalTimeLeft = duration;
      document.getElementById('modal-timer-digits').textContent = modalTimeLeft;
      document.getElementById('posture-modal').classList.remove('hidden');
    }

    function closeExerciseModal() {
      clearInterval(modalTimerInterval);
      modalTimerInterval = null;
      document.getElementById('posture-modal').classList.add('hidden');
    }

    function startModalExerciseTimer() {
      if (modalTimerInterval) return;
      speakCue("Hold exercise posture now");

      modalTimerInterval = setInterval(() => {
        modalTimeLeft--;
        document.getElementById('modal-timer-digits').textContent = modalTimeLeft;
        if (modalTimeLeft <= 0) {
          clearInterval(modalTimerInterval);
          modalTimerInterval = null;
          speakCue("Exercise hold completed");
          logActivity(`Completed Posture Hold: ${document.getElementById('modal-exercise-title').textContent}`);
          alert("Hold Completed!");
        }
      }, 1000);
    }

    /* MODULE 4: ANALYTICS & SUS SURVEY ENGINE */
    function logActivity(text) {
      const list = document.getElementById('activity-log-list');
      if (!list) return;
      if (list.querySelector('.italic')) list.innerHTML = '';

      const timeStr = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
      const item = document.createElement('div');
      item.className = 'p-2 rounded-lg bg-surfaceDark border border-gray-800 flex items-center justify-between';
      item.innerHTML = `<span class="text-gray-200">${text}</span><span class="text-[10px] text-gray-500 font-mono">${timeStr}</span>`;
      list.prepend(item);
    }

    function initApp() {
      try {
        switchTab('pnf');
        const firstPreset = document.querySelector('.pnf-preset-btn');
        if (firstPreset) {
          handlePnfPresetClick(firstPreset);
        } else {
          selectPnfRoutine('胸大肌門框 PNF 拉伸', []);
        }
        updateTutFromInputs();
        if (typeof renderSusQuestions === 'function') {
          renderSusQuestions();
          calculateSusScore();
        }
        window.applyLanguage(window.currentLang || 'zh');
      } catch (err) {
        console.error('Init error:', err);
      }
    }

    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', initApp);
    } else {
      initApp();
    }
  </script>
</body>
</html>