<style>
  #module-pnf .presets-scroll-track {
    scrollbar-width: thin;
    scrollbar-color: rgba(245, 158, 11, 0.35) rgba(31, 41, 55, 0.4);
    -webkit-overflow-scrolling: touch;
  }
  #module-pnf .presets-scroll-track::-webkit-scrollbar {
    height: 6px;
  }
  #module-pnf .presets-scroll-track::-webkit-scrollbar-track {
    background: rgba(31, 41, 55, 0.4);
    border-radius: 9999px;
  }
  #module-pnf .presets-scroll-track::-webkit-scrollbar-thumb {
    background: rgba(245, 158, 11, 0.45);
    border-radius: 9999px;
  }
  #module-pnf .pnf-media-box {
    aspect-ratio: 4 / 3;
  }
  @media (min-width: 768px) {
    #module-pnf .pnf-media-box {
      aspect-ratio: 16 / 9;
    }
  }
</style>

<section id="module-pnf" class="tab-content space-y-4 sm:space-y-6 w-full max-w-full min-w-0">
  <!-- Top Banner / Routine Selection -->
  <div class="glass-card rounded-2xl p-4 sm:p-5 border border-gray-800/90 flex flex-col gap-3.5 min-w-0">
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-3 min-w-0">
      <div class="min-w-0">
        <h2 class="text-base sm:text-lg font-bold text-white flex flex-wrap items-center gap-2">
          <span class="flex items-center gap-2">
            <i class="fa-solid fa-heart-pulse text-goldAccent"></i>
            <span data-i18n="pnf.title">PNF Stretching Protocol Assistant</span>
          </span>
          <span data-i18n="pnf.ai_badge" class="px-2 py-0.5 text-[10px] font-bold rounded-md bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 uppercase tracking-wide">
            AI POSE HOLD ACTIVE
          </span>
        </h2>
        <p data-i18n="pnf.subtitle" class="text-[11px] sm:text-xs text-gray-400 mt-1 leading-relaxed">
          Proprioceptive Neuromuscular Facilitation (Hold-Relax Method): AI tracks your joint angles in real-time and counts down only while proper stretch posture is held.
        </p>
      </div>
    </div>

    <!-- Muscle Presets (Dynamic from DB - 22 Protocols, Smooth Horizontal Scroll on Phone / iPad / Desktop) -->
    <div class="w-full min-w-0">
      <div id="pnf-presets-scroll" class="presets-scroll-track flex items-center gap-2 overflow-x-auto pb-2 w-full min-w-0 touch-pan-x">
        @if(isset($deviations) && $deviations->count() > 0)
          @foreach($deviations as $dIndex => $deviation)
            @foreach($deviation->recommendedStretches as $sIndex => $stretch)
              <button type="button"
                data-stretch-id="{{ $stretch->id }}"
                data-title="{{ $stretch->title_zh }}"
                data-title-en="{{ $stretch->title_en ?? $stretch->title_zh }}"
                data-steps='{{ json_encode($stretch->pnfSteps) }}'
                data-ai-rules='@json($stretch->ai_rules)'
                onclick="handlePnfPresetClick(this)"
                class="pnf-preset-btn shrink-0 min-h-[36px] whitespace-nowrap {{ ($dIndex === 0 && $sIndex === 0) ? 'active-preset bg-goldAccent/20 text-goldAccent border-goldAccent/50' : 'bg-[#171923] text-gray-300 border-gray-700/80' }} px-3 py-1.5 rounded-lg text-xs font-semibold hover:bg-goldAccent hover:text-black transition border inline-flex items-center gap-1.5">
                <i class="fa-solid fa-heart-pulse text-[11px]"></i>
                <span class="pnf-preset-label">{{ $stretch->title_zh }}</span>
              </button>
            @endforeach
          @endforeach
        @else
          <span data-i18n="pnf.no_presets" class="text-xs text-gray-500">尚無預設動作</span>
        @endif
      </div>
    </div>
  </div>

  <!-- Main Interactive Dashboard Grid -->
  <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 sm:gap-6 min-w-0 items-stretch">

    <!-- Left: AI Pose Detection & Smart Hold Timer Display (8 cols on lg+) -->
    <div class="lg:col-span-8 glass-card rounded-2xl p-3.5 sm:p-6 border border-gray-800/90 flex flex-col items-center justify-between relative overflow-hidden min-w-0">

      <!-- Background Ambient Glow -->
      <div id="pnf-glow-bg" class="absolute inset-0 bg-goldAccent/5 blur-3xl pointer-events-none transition-colors duration-500"></div>

      <!-- Top Header: Target Muscle, Source Switch & Cycle Counter -->
      <div class="z-10 mb-3 flex flex-wrap items-center justify-between gap-2 w-full">
        <div class="flex flex-wrap items-center gap-1.5 sm:gap-2 min-w-0">
          <span id="pnf-target-label" class="text-[11px] sm:text-xs uppercase tracking-wider font-bold px-3 py-1 rounded-full bg-amber-500/15 text-goldAccent border border-goldAccent/40 truncate max-w-[210px] sm:max-w-none">
            TARGET: 胸大肌門框 PNF 拉伸
          </span>
          <button type="button" id="ai-btn-cam" onclick="switchPnfAiSource('cam')" class="min-h-[32px] px-2.5 py-1 rounded-lg text-[11px] font-bold bg-indigo-600 hover:bg-indigo-500 text-white transition inline-flex items-center gap-1">
            <i class="fa-solid fa-video"></i> <span data-i18n="pnf.live_cam">Live Cam</span>
          </button>
          <label for="ai-photo-input" id="ai-btn-photo" class="min-h-[32px] cursor-pointer px-2.5 py-1 rounded-lg text-[11px] font-bold bg-[#1b1e2b] hover:bg-gray-700 text-gray-200 border border-gray-700 transition inline-flex items-center gap-1">
            <i class="fa-solid fa-image"></i> <span data-i18n="pnf.photo_test">Photo Test</span>
          </label>
          <input type="file" id="ai-photo-input" accept="image/*" class="hidden">
        </div>

        <span class="text-xs text-gray-400 font-semibold shrink-0">
          <span data-i18n="pnf.cycle">Cycle</span> <span id="pnf-cycle-count" class="text-white font-bold">1</span> <span data-i18n="pnf.of_3">of 3</span>
        </span>
      </div>

      <!-- Center: AI Camera/Canvas Viewport + Floating HUD Timer -->
      <div class="relative z-10 w-full pnf-media-box bg-[#050609] rounded-2xl overflow-hidden border border-gray-800 flex items-center justify-center shadow-inner transition-colors duration-300" id="ai-viewport-frame">
        <video id="pnf-ai-webcam" autoplay playsinline class="w-full h-full object-cover -scale-x-100"></video>
        <img id="pnf-ai-photo" class="hidden w-full h-full object-contain" alt="Posture Check">
        <canvas id="pnf-ai-canvas" class="absolute inset-0 w-full h-full pointer-events-none -scale-x-100"></canvas>

        <!-- Top-Left HUD: Joint Angle & Hold Gate Status -->
        <div class="absolute top-2.5 left-2.5 sm:top-3.5 sm:left-3.5 flex flex-col gap-1.5 text-left max-w-[60%]">
          <div id="ai-hold-badge" class="px-2.5 py-1 rounded-full text-[10px] sm:text-xs font-bold bg-black/85 text-gray-200 border border-gray-700/90 backdrop-blur-md flex items-center gap-1.5 truncate">
            <span id="ai-hold-dot" class="w-2 h-2 rounded-full bg-gray-400 shrink-0"></span>
            <span id="ai-hold-status-text" data-i18n="pnf.ai_standby" class="truncate">AI Standby (Click Start)</span>
          </div>
          <span id="ai-joint-readout" class="px-2.5 py-1 rounded-lg text-[10px] sm:text-[11px] font-mono bg-black/80 text-goldAccent border border-gray-800 backdrop-blur-sm truncate">
            Angle: --° | Target: &gt;=75°
          </span>
        </div>

        <!-- Top-Right HUD: Compact Circular SVG Progress Timer -->
        <div class="absolute top-2.5 right-2.5 sm:top-3.5 sm:right-3.5 w-24 h-24 sm:w-28 sm:h-28 md:w-32 md:h-32 rounded-2xl bg-[#0e1017]/85 backdrop-blur-md border border-gray-700/80 p-2 flex items-center justify-center shadow-lg">
          <svg class="w-full h-full -rotate-90" viewBox="0 0 120 120">
            <circle class="text-gray-800" stroke-width="7" stroke="currentColor" fill="transparent" r="52" cx="60" cy="60" />
            <circle id="pnf-progress-circle" class="progress-ring__circle text-goldAccent transition-all duration-300" stroke-width="7" stroke-dasharray="326.72" stroke-dashoffset="0" stroke-linecap="round" stroke-linejoin="round" stroke="currentColor" fill="transparent" r="52" cx="60" cy="60" />
          </svg>
          <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-1">
            <span id="pnf-phase-badge" class="text-[7px] sm:text-[8px] font-extrabold tracking-tight px-1.5 py-0.5 rounded bg-goldAccent/20 text-goldAccent border border-goldAccent/40 truncate max-w-[68px] sm:max-w-[88px]">
              STEP 1: 初始預備
            </span>
            <span id="pnf-timer-digits" class="text-xl sm:text-2xl md:text-3xl font-extrabold text-white font-mono leading-tight mt-0.5">
              10
            </span>
          </div>
        </div>

        <!-- Bottom HUD: Real-time Posture Instruction & Simulation Fallback -->
        <div class="absolute bottom-2.5 left-2.5 right-2.5 sm:bottom-3.5 sm:left-3.5 sm:right-3.5 bg-[#0e1017]/90 backdrop-blur-md border border-gray-800 px-3 py-2.5 sm:px-4 sm:py-3 rounded-xl flex items-center justify-between gap-2 text-left">
          <div class="min-w-0 flex-1">
            <p id="pnf-instruction-text" class="text-xs sm:text-sm font-bold text-white truncate">
              前臂貼齊門框，單腳微向前跨立，挺胸預備。
            </p>
            <p id="ai-feedback-subtext" class="text-[10px] sm:text-xs text-gray-400 truncate mt-0.5">
              已切換動作規則：抬起手臂扶住門框（大臂外展 ≥ 75°）並挺胸前跨
            </p>
          </div>
          <button type="button" id="ai-btn-simulate" onclick="toggleSimulatePoseHold()" data-i18n="pnf.sim_btn" class="hidden shrink-0 px-2.5 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-[11px] font-bold text-white transition">
            模擬動作達標
          </button>
        </div>
      </div>

      <!-- Phase Progress Flow Bar (6 Steps) -->
      <div id="pnf-stepper-container" class="z-10 w-full max-w-2xl my-3.5 sm:my-4 grid grid-cols-3 sm:grid-cols-6 gap-2 text-center"></div>

      <!-- Interactive Controls Row -->
      <div class="z-10 grid grid-cols-2 sm:flex sm:items-center sm:justify-center gap-2.5 sm:gap-3 w-full max-w-xl">
        <button id="pnf-btn-start" type="button" onclick="startPnfTimer()" class="col-span-2 sm:flex-1 min-h-[46px] py-2.5 px-5 rounded-xl font-extrabold text-xs sm:text-sm bg-gradient-to-r from-amber-400 to-goldAccent text-black shadow-lg shadow-goldAccent/20 hover:brightness-105 active:scale-95 transition">
          <i class="fa-solid fa-play mr-1.5"></i> <span data-i18n="pnf.start">Start Session</span>
        </button>
        <button id="pnf-btn-pause" type="button" onclick="pausePnfTimer()" disabled class="min-h-[42px] py-2.5 px-4 rounded-xl font-bold text-xs sm:text-sm bg-[#171923] text-gray-400 border border-gray-800 hover:bg-gray-800 disabled:opacity-45 disabled:pointer-events-none transition">
          <i class="fa-solid fa-pause mr-1"></i> <span data-i18n="pnf.pause">Pause</span>
        </button>
        <button id="pnf-btn-reset" type="button" onclick="resetPnfTimer()" class="min-h-[42px] py-2.5 px-4 rounded-xl font-bold text-xs sm:text-sm bg-[#171923] text-gray-200 border border-gray-800 hover:bg-gray-800 transition">
          <i class="fa-solid fa-rotate-left mr-1"></i> <span data-i18n="pnf.reset">Reset</span>
        </button>
        <button type="button" onclick="skipPnfPhase()" class="col-span-2 sm:col-span-1 min-h-[42px] py-2.5 px-4 rounded-xl font-bold text-xs sm:text-sm bg-[#171923] text-goldAccent border border-goldAccent/40 hover:bg-goldAccent/10 transition">
          <i class="fa-solid fa-forward-step mr-1"></i> <span data-i18n="pnf.skip">Skip Step</span>
        </button>
      </div>

    </div>

    <!-- Right: Instruction Guidelines Card (4 cols on lg+) -->
    <div class="lg:col-span-4 glass-card rounded-2xl p-4 sm:p-6 border border-gray-800/90 flex flex-col justify-between space-y-4 min-w-0">
      <div class="min-w-0">
        <div class="flex items-center gap-2 text-goldAccent font-bold text-sm mb-3.5">
          <i class="fa-solid fa-book-open"></i> <span data-i18n="pnf.guide_title">PNF Protocol Execution Guide</span>
        </div>

        <div id="pnf-guide-steps-list" class="space-y-2.5 text-xs text-gray-300 max-h-[300px] md:max-h-[380px] lg:max-h-[440px] overflow-y-auto pr-1"></div>
      </div>

      <!-- Scientific Note Footer -->
      <div class="p-3.5 rounded-xl bg-amber-500/10 border border-goldAccent/30 text-[11px] text-gray-300 leading-relaxed">
        <i class="fa-solid fa-lightbulb text-goldAccent mr-1"></i>
        <strong data-i18n="pnf.note_label">Neuro-Physiology Note:</strong>
        <span data-i18n="pnf.note_body">PNF utilizes Golgi Tendon Organ (GTO) autogenic inhibition to override stretch reflex resistance.</span>
      </div>
    </div>

  </div>
</section>

<script type="module">
  import { PoseLandmarker, FilesetResolver, DrawingUtils } from "https://cdn.jsdelivr.net/npm/@mediapipe/tasks-vision@0.10.14";

  const PNF_I18N = {
    zh: {
      "pnf.title": "PNF 伸展協定助手",
      "pnf.ai_badge": "AI 姿勢保持偵測中",
      "pnf.subtitle": "本體感覺神經肌肉促進法（Hold-Relax）：AI 即時追蹤關節角度，僅在維持正確伸展姿勢時倒數計時。",
      "pnf.no_presets": "尚無預設動作",
      "pnf.live_cam": "即時鏡頭",
      "pnf.photo_test": "照片測試",
      "pnf.cycle": "第",
      "pnf.of_3": "組 / 共 3 組",
      "pnf.ai_standby": "AI 待命（點擊開始）",
      "pnf.sim_btn": "模擬動作達標",
      "pnf.start": "開始訓練",
      "pnf.pause": "暫停",
      "pnf.reset": "重設",
      "pnf.skip": "跳過步驟",
      "pnf.guide_title": "PNF 動作執行指南",
      "pnf.note_label": "神經生理學提示：",
      "pnf.note_body": "PNF 利用高爾肌腱器（GTO）自發性抑制機制，降低伸展反射阻力以提升關節活動度。"
    },
    en: {
      "pnf.title": "PNF Stretching Protocol Assistant",
      "pnf.ai_badge": "AI POSE HOLD ACTIVE",
      "pnf.subtitle": "Proprioceptive Neuromuscular Facilitation (Hold-Relax Method): AI tracks your joint angles in real-time and counts down only while proper stretch posture is held.",
      "pnf.no_presets": "No preset routines available",
      "pnf.live_cam": "Live Cam",
      "pnf.photo_test": "Photo Test",
      "pnf.cycle": "Cycle",
      "pnf.of_3": "of 3",
      "pnf.ai_standby": "AI Standby (Click Start)",
      "pnf.sim_btn": "Simulate Hold",
      "pnf.start": "Start Session",
      "pnf.pause": "Pause",
      "pnf.reset": "Reset",
      "pnf.skip": "Skip Step",
      "pnf.guide_title": "PNF Protocol Execution Guide",
      "pnf.note_label": "Neuro-Physiology Note:",
      "pnf.note_body": "PNF utilizes Golgi Tendon Organ (GTO) autogenic inhibition to override stretch reflex resistance."
    }
  };

  const PNF_TIP_EN_BY_STRETCH_ID = {
    1: "Raise your arm against the doorway (shoulder abduction ≥ 75°) and step forward with chest up.",
    2: "Lie supine, lift one leg, and keep the knee extended (knee angle ≥ 162°).",
    3: "Sit upright, stabilize your shoulder, and gently tilt your head toward the opposite side.",
    4: "Lie on your side and draw your heel toward your glutes while keeping posture steady.",
    5: "Keep a half-kneeling lunge with neutral pelvis and shift weight gently forward.",
    6: "Lie supine and hug one knee toward your chest without lifting lower back excessively.",
    7: "Sit upright, raise one arm overhead, and side-bend smoothly without twisting.",
    8: "Keep back leg straight with heel planted while leaning gently forward.",
    9: "Lie supine in a figure-4 position and draw the bottom thigh toward your chest.",
    10: "Keep arm straight with palm reversed against the wall and apply gentle pressure.",
    11: "Raise arm to ~120° on the doorway and press chest gently forward.",
    12: "Lie prone and press upper body up comfortably within a pain-free range.",
    13: "Sit upright and perform a controlled side-bend and gentle rotation.",
    14: "In half-kneeling, keep torso upright and glutes engaged during rear-leg stretch.",
    15: "Lie supine with one leg externally rotated and let knee settle gently.",
    16: "Sit in butterfly pose with soles together and hinge torso slightly forward.",
    17: "In a short wall lunge, keep back knee slightly bent with heel planted.",
    18: "In kneeling stance with insteps down, sit hips back smoothly.",
    19: "Stand with back to wall, arm straight behind, and turn body gently forward.",
    20: "Raise elbow overhead and support it while keeping neck relaxed.",
    21: "Turn head ~45° and look slightly down toward armpit with gentle guidance.",
    22: "Slightly side-bend/turn toward the same side, then guide gently to opposite side."
  };

  let currentRoutineSteps = [];
  let currentStepIdx = 0;
  let activePresetBtn = document.querySelector("#module-pnf .pnf-preset-btn.active-preset") || document.querySelector("#module-pnf .pnf-preset-btn");
  let activeStretchTitle = activePresetBtn?.dataset.title || "";
  let activeDbRule = null;

  function detectLang() {
    const raw = window.currentLang || document.documentElement.lang || localStorage.getItem("lang") || localStorage.getItem("locale") || "zh";
    return String(raw).toLowerCase().startsWith("en") ? "en" : "zh";
  }

  function isEnglishMode() {
    return detectLang() === "en";
  }

  function getStepTitle(step) {
    if (!step) return "";
    if (isEnglishMode()) {
      return step.step_name_en || step.step_nameEn || step.step_name_zh || step.step_name || "";
    }
    return step.step_name_zh || step.step_name || step.step_name_en || "";
  }

  function getStepDesc(step) {
    if (!step) return "";
    if (isEnglishMode()) {
      return step.instruction_en || step.instructionEn || step.instruction || step.instruction_zh || "";
    }
    return step.instruction || step.instruction_zh || step.instruction_en || "";
  }

  function getFeedbackTip() {
    const activeBtn = document.querySelector("#module-pnf .pnf-preset-btn.active-preset") || activePresetBtn;
    const stretchId = Number(activeBtn?.dataset?.stretchId || activeDbRule?.stretch_id || 0);
    const zhTip = activeDbRule?.tip || "";

    if (isEnglishMode()) {
      if (activeDbRule?.tip_en) return activeDbRule.tip_en;
      if (PNF_TIP_EN_BY_STRETCH_ID[stretchId]) return PNF_TIP_EN_BY_STRETCH_ID[stretchId];
      const degMatch = zhTip.match(/(≥|<=|>|<)\s*\d+\s*°/);
      return degMatch
        ? `Hold proper stretch posture (${degMatch[0].replace(">=", "≥").replace("<=", "≤")}).`
        : "Maintain proper PNF stretch alignment and hold steady.";
    }

    return zhTip;
  }

  function syncStepsFromButton(btn) {
    if (!btn) return;
    activePresetBtn = btn;

    if (btn.dataset.title) {
      activeStretchTitle = btn.dataset.title;
    }

    if (btn.dataset.aiRules && btn.dataset.aiRules !== "null") {
      try {
        activeDbRule = JSON.parse(btn.dataset.aiRules);
      } catch (_) {
        activeDbRule = null;
      }
    } else {
      activeDbRule = null;
    }

    if (btn.dataset.steps) {
      try {
        const parsed = JSON.parse(btn.dataset.steps);
        if (Array.isArray(parsed)) {
          currentRoutineSteps = parsed;
        }
      } catch (_) {
        currentRoutineSteps = [];
      }
    }
  }

  window.renderPnfGuideList = function () {
    const guideContainer = document.getElementById("pnf-guide-steps-list");
    if (!guideContainer) return;

    const activeBtn = document.querySelector("#module-pnf .pnf-preset-btn.active-preset") || activePresetBtn;
    if (activeBtn) {
      syncStepsFromButton(activeBtn);
    }

    if (!currentRoutineSteps || !currentRoutineSteps.length) {
      guideContainer.innerHTML = "";
      return;
    }

    guideContainer.innerHTML = currentRoutineSteps.map((step) => `
      <div class="p-3 rounded-xl bg-surfaceDark border border-gray-800/80">
        <span class="font-bold text-goldAccent block mb-0.5">Step ${step.step_number}: ${getStepTitle(step)} (${step.duration_seconds}s)</span>
        <span class="text-gray-300">${getStepDesc(step)}</span>
      </div>
    `).join("");
  };

  window.renderPnfStepper = function () {
    const container = document.getElementById("pnf-stepper-container");
    if (!container) return;
    if (!currentRoutineSteps.length) {
      container.innerHTML = "";
      return;
    }
    container.innerHTML = currentRoutineSteps.map((step, idx) => `
      <div id="pnf-step-box-${idx}" class="p-2 rounded-xl bg-cardDark border border-gray-800 text-gray-400 transition-all duration-300">
        <p class="text-[9px] font-bold uppercase tracking-wider">Step ${step.step_number}</p>
        <p class="text-[11px] font-semibold font-mono truncate">${getStepTitle(step)}</p>
      </div>
    `).join("");
  };

  const baseUpdatePnfUI = window.updatePnfUI;
  window.updatePnfUI = function () {
    if (typeof baseUpdatePnfUI === "function") baseUpdatePnfUI();

    const badgeEl = document.getElementById("pnf-phase-badge");
    const instEl = document.getElementById("pnf-instruction-text");
    if (badgeEl) {
      const m = badgeEl.textContent.match(/STEP\s*(\d+)/i);
      if (m) currentStepIdx = Math.max(0, parseInt(m[1], 10) - 1);
    }

    const step = currentRoutineSteps[currentStepIdx];
    if (step) {
      if (badgeEl) badgeEl.textContent = `STEP ${step.step_number}: ${getStepTitle(step)}`;
      if (instEl) instEl.textContent = getStepDesc(step);
    }
  };

  const video = document.getElementById("pnf-ai-webcam");
  const photoImg = document.getElementById("pnf-ai-photo");
  const photoInput = document.getElementById("ai-photo-input");
  const canvas = document.getElementById("pnf-ai-canvas");
  const ctx = canvas.getContext("2d");
  const viewportFrame = document.getElementById("ai-viewport-frame");
  const holdBadge = document.getElementById("ai-hold-badge");
  const holdDot = document.getElementById("ai-hold-dot");
  const holdStatusText = document.getElementById("ai-hold-status-text");
  const jointReadout = document.getElementById("ai-joint-readout");
  const feedbackSubtext = document.getElementById("ai-feedback-subtext");
  const btnSimulate = document.getElementById("ai-btn-simulate");
  const targetLabel = document.getElementById("pnf-target-label");

  let poseLandmarker = null;
  let webcamStream = null;
  let animId = null;
  let runningMode = "VIDEO";
  let isAiSessionActive = false;
  let isPoseHeldValid = false;
  let isSimulatedHold = false;
  let lastDetectedLandmarks = null;

  function getCurrentRule() {
    if (activeDbRule && activeDbRule.left && activeDbRule.right) return activeDbRule;
    return {
      left: [11, 23, 25],
      right: [12, 24, 26],
      target: 150,
      comparator: ">=",
      requireLowerBody: false,
      tip: ""
    };
  }

  function resetHoldHudToStandby() {
    const isEn = isEnglishMode();
    const rule = getCurrentRule();
    isAiSessionActive = false;
    isPoseHeldValid = false;
    isSimulatedHold = false;

    holdDot.className = "w-2 h-2 rounded-full bg-gray-400 shrink-0";
    holdStatusText.innerText = isEn ? "AI Standby (Click Start)" : "AI 待命（點擊開始）";
    holdBadge.className = "px-2.5 py-1 rounded-full text-[10px] sm:text-xs font-bold bg-black/85 text-gray-200 border border-gray-700/90 backdrop-blur-md flex items-center gap-1.5 truncate";

    if (rule?.target && rule?.comparator) {
      jointReadout.innerText = `Angle: --° | Target: ${rule.comparator}${rule.target}°`;
    } else {
      jointReadout.innerText = "Angle: --° | Target: >=75°";
    }

    const firstStep = currentRoutineSteps[0];
    const digitsEl = document.getElementById("pnf-timer-digits");
    const cycleEl = document.getElementById("pnf-cycle-count");
    const circleEl = document.getElementById("pnf-progress-circle");
    const btnStart = document.getElementById("pnf-btn-start");
    const btnPause = document.getElementById("pnf-btn-pause");

    if (firstStep && digitsEl) {
      digitsEl.innerText = String(firstStep.duration_seconds || 10);
    }
    if (cycleEl) cycleEl.innerText = "1";
    if (circleEl) circleEl.style.strokeDashoffset = "0";
    if (btnStart) btnStart.disabled = false;
    if (btnPause) btnPause.disabled = true;

    const tip = getFeedbackTip();
    if (tip) feedbackSubtext.innerText = tip;
  }

  function applyPnfLang() {
    const lang = detectLang();
    const dict = PNF_I18N[lang];

    document.querySelectorAll("#module-pnf [data-i18n]").forEach((el) => {
      const key = el.getAttribute("data-i18n");
      if (dict[key] && el.textContent !== dict[key]) el.textContent = dict[key];
    });

    document.querySelectorAll("#module-pnf .pnf-preset-btn").forEach((btn) => {
      const labelEl = btn.querySelector(".pnf-preset-label");
      if (labelEl) {
        const nextText = lang === "en" ? (btn.dataset.titleEn || btn.dataset.title) : btn.dataset.title;
        if (labelEl.textContent !== nextText) labelEl.textContent = nextText;
      }
    });

    if (targetLabel) {
      const curBtn = document.querySelector("#module-pnf .pnf-preset-btn.active-preset") || activePresetBtn;
      const displayName = curBtn
        ? (lang === "en" ? (curBtn.dataset.titleEn || curBtn.dataset.title) : curBtn.dataset.title)
        : activeStretchTitle;
      targetLabel.textContent = `TARGET: ${displayName}`;
    }

    if (jointReadout && activeDbRule?.target && activeDbRule?.comparator) {
      const curText = jointReadout.textContent || "";
      const angleMatch = curText.match(/Angle:\s*([^|]+)/i);
      const anglePart = angleMatch ? angleMatch[1].trim() : "--°";
      jointReadout.innerText = `Angle: ${anglePart} | Target: ${activeDbRule.comparator}${activeDbRule.target}°`;
    }

    if (feedbackSubtext && !isAiSessionActive && !lastDetectedLandmarks) {
      const nextTip = getFeedbackTip();
      if (nextTip && feedbackSubtext.innerText !== nextTip) {
        feedbackSubtext.innerText = nextTip;
      }
    }

    window.renderPnfGuideList();
    window.renderPnfStepper();
    window.updatePnfUI();
  }

  function schedulePnfSync() {
    applyPnfLang();
    requestAnimationFrame(() => applyPnfLang());
    setTimeout(() => applyPnfLang(), 50);
  }

  function wrapApplyLanguageWhenReady() {
    if (typeof window.applyLanguage === "function" && !window.applyLanguage.__pnfWrapped) {
      const prev = window.applyLanguage;
      const wrapped = function (lang) {
        if (typeof lang === "string" && lang.length > 0) {
          window.currentLang = lang;
        }
        const res = prev.apply(this, arguments);
        schedulePnfSync();
        return res;
      };
      wrapped.__pnfWrapped = true;
      window.applyLanguage = wrapped;
    }
  }

  wrapApplyLanguageWhenReady();
  document.addEventListener("DOMContentLoaded", () => {
    wrapApplyLanguageWhenReady();
    schedulePnfSync();
  });

  document.addEventListener("click", (e) => {
    const target = e.target.closest("button, [data-lang], #lang-toggle, .lang-toggle");
    if (!target) return;
    const txt = (target.textContent || "").trim().toUpperCase();
    if (txt.includes("EN") || txt.includes("中文") || target.hasAttribute("data-lang")) {
      setTimeout(() => schedulePnfSync(), 20);
    }
  });

  const presetsScrollEl = document.getElementById("pnf-presets-scroll");
  if (presetsScrollEl) {
    presetsScrollEl.addEventListener("wheel", (e) => {
      if (Math.abs(e.deltaY) > Math.abs(e.deltaX) && presetsScrollEl.scrollWidth > presetsScrollEl.clientWidth) {
        presetsScrollEl.scrollLeft += e.deltaY;
        e.preventDefault();
      }
    }, { passive: false });
  }

  async function ensureLandmarker() {
    if (poseLandmarker) return;
    const vision = await FilesetResolver.forVisionTasks("https://cdn.jsdelivr.net/npm/@mediapipe/tasks-vision@0.10.14/wasm");
    poseLandmarker = await PoseLandmarker.createFromOptions(vision, {
      baseOptions: {
        modelAssetPath: "https://storage.googleapis.com/mediapipe-models/pose_landmarker/pose_landmarker_lite/float16/1/pose_landmarker_lite.task",
        delegate: "GPU"
      },
      runningMode,
      numPoses: 1
    });
  }

  function calcAngle(a, b, c) {
    const rad = Math.atan2(c.y - b.y, c.x - b.x) - Math.atan2(a.y - b.y, a.x - b.x);
    let deg = Math.abs((rad * 180.0) / Math.PI);
    if (deg > 180.0) deg = 360 - deg;
    return Math.round(deg);
  }

  function evaluateFrame(landmarks) {
    const rule = getCurrentRule();
    const lPts = rule.left.map(i => landmarks[i]);
    const rPts = rule.right.map(i => landmarks[i]);
    const pts = (lPts.reduce((s, p) => s + (p?.visibility || 0), 0) >= rPts.reduce((s, p) => s + (p?.visibility || 0), 0)) ? lPts : rPts;

    // Only check leg/kneeling visibility when the joint indices use knee (25/26) or ankle (27/28)
    const usesLegJoints = [...rule.left, ...rule.right].some(idx => idx >= 25);

    if (usesLegJoints) {
      const hip = (landmarks[23]?.visibility || 0) >= (landmarks[24]?.visibility || 0) ? landmarks[23] : landmarks[24];
      const knee = (landmarks[25]?.visibility || 0) >= (landmarks[26]?.visibility || 0) ? landmarks[25] : landmarks[26];

      const hipValid = hip && (hip.visibility || 0) >= 0.65 && hip.y <= 0.95;
      const kneeValid = knee && (knee.visibility || 0) >= 0.65 && knee.y <= 0.97;
      const legLengthValid = hipValid && kneeValid && Math.abs(knee.y - hip.y) >= 0.10;

      if (!hipValid || !kneeValid || !legLengthValid) {
        jointReadout.innerText = `Angle: --° | Target: ${rule.comparator}${rule.target}°`;
        return {
          valid: false,
          msg: isEnglishMode()
            ? "Lower body / kneeling legs not visible in photo"
            : "照片未拍到完整下肢／跪姿腿部，無法通過此動作檢測"
        };
      }
    }

    if (pts.some(p => !p || (p.visibility || 0) < 0.5)) {
      jointReadout.innerText = `Angle: --° | Target: ${rule.comparator}${rule.target}°`;
      return {
        valid: false,
        msg: isEnglishMode() ? "Target joints not clearly visible" : "未清晰偵測到目標關節"
      };
    }

    const angle = calcAngle(pts[0], pts[1], pts[2]);
    jointReadout.innerText = `Angle: ${angle}° | Target: ${rule.comparator}${rule.target}°`;
    const passed = rule.comparator === ">=" ? (angle >= rule.target) : (angle <= rule.target);

    if (passed) {
      return {
        valid: true,
        msg: isEnglishMode()
          ? `Pose matched (${angle}°)${isAiSessionActive ? " · Timer active" : " · Click Start"}`
          : `姿勢符合目標（${angle}°）${isAiSessionActive ? " · 計時進行中" : " · 請點擊開始訓練"}`
      };
    }

    return {
      valid: false,
      msg: isEnglishMode()
        ? `Incorrect pose (${angle}°, target ${rule.comparator}${rule.target}°)`
        : `姿勢不符（目前 ${angle}°，需 ${rule.comparator}${rule.target}°）`
    };
  }

  function updateUiHoldState(valid, msg) {
    const finalValid = Boolean(valid || isSimulatedHold);
    isPoseHeldValid = finalValid;
    const isEn = isEnglishMode();
    feedbackSubtext.innerText = msg;

    if (finalValid) {
      holdDot.className = isAiSessionActive
        ? "w-2 h-2 rounded-full bg-emerald-400 animate-ping shrink-0"
        : "w-2 h-2 rounded-full bg-emerald-400 shrink-0";
      holdStatusText.innerText = isAiSessionActive
        ? (isEn ? "HOLDING · TIMER ACTIVE" : "HOLDING · 計時進行中")
        : (isEn ? "POSE MATCHED · STANDBY" : "姿勢達標 · 待命（點擊開始）");
      holdBadge.className = "px-2.5 py-1 rounded-full text-[10px] sm:text-xs font-bold bg-emerald-950/90 text-emerald-400 border border-emerald-500/50 backdrop-blur-md flex items-center gap-1.5 truncate";
      viewportFrame.classList.remove("border-gray-800", "border-red-500/80");
      viewportFrame.classList.add("border-emerald-500/80");
    } else {
      holdDot.className = "w-2 h-2 rounded-full bg-red-500 shrink-0";
      holdStatusText.innerText = isAiSessionActive
        ? (isEn ? "WRONG POSTURE · PAUSED" : "WRONG POSTURE · 計時暫停")
        : (isEn ? "INCORRECT POSE · NOT MATCHED" : "姿勢不符 · 未達標");
      holdBadge.className = "px-2.5 py-1 rounded-full text-[10px] sm:text-xs font-bold bg-red-950/90 text-red-400 border border-red-500/60 backdrop-blur-md flex items-center gap-1.5 truncate";
      viewportFrame.classList.remove("border-gray-800", "border-emerald-500/80");
      viewportFrame.classList.add("border-red-500/80");
    }
  }

  window.addEventListener("DOMContentLoaded", () => {
    const outerPresetHandler = window.handlePnfPresetClick;
    window.handlePnfPresetClick = async function (button) {
      wrapApplyLanguageWhenReady();
      if (!button) return;

      document.querySelectorAll("#module-pnf .pnf-preset-btn").forEach((b) => {
        b.classList.remove("active-preset", "bg-goldAccent/20", "text-goldAccent", "border-goldAccent/50");
        b.classList.add("bg-[#171923]", "text-gray-300", "border-gray-700/80");
      });
      button.classList.remove("bg-[#171923]", "text-gray-300", "border-gray-700/80");
      button.classList.add("active-preset", "bg-goldAccent/20", "text-goldAccent", "border-goldAccent/50");

      currentStepIdx = 0;
      syncStepsFromButton(button);

      if (typeof window.resetPnfTimer === "function") {
        window.resetPnfTimer();
      } else if (typeof outerPresetHandler === "function") {
        outerPresetHandler(button);
        if (typeof window.pausePnfTimer === "function") window.pausePnfTimer();
      }

      isAiSessionActive = false;
      isSimulatedHold = false;
      isPoseHeldValid = false;

      syncStepsFromButton(button);
      resetHoldHudToStandby();
      schedulePnfSync();
      await reEvaluateCurrentMedia();
    };
  });

  function renderPhotoLandmarks(lm, isValid) {
    const boxW = viewportFrame.clientWidth || 640;
    const boxH = viewportFrame.clientHeight || 360;
    canvas.width = boxW;
    canvas.height = boxH;
    ctx.clearRect(0, 0, boxW, boxH);
    if (!photoImg.naturalWidth || !photoImg.naturalHeight) return;

    const imgRatio = photoImg.naturalWidth / photoImg.naturalHeight;
    const boxRatio = boxW / boxH;
    let drawW = boxW, drawH = boxH, offX = 0, offY = 0;
    if (imgRatio > boxRatio) {
      drawH = boxW / imgRatio;
      offY = (boxH - drawH) / 2;
    } else {
      drawW = boxH * imgRatio;
      offX = (boxW - drawW) / 2;
    }

    const mapped = lm.map(pt => ({ ...pt, x: (offX + pt.x * drawW) / boxW, y: (offY + pt.y * drawH) / boxH }));
    const drawingUtils = new DrawingUtils(ctx);
    drawingUtils.drawConnectors(mapped, PoseLandmarker.POSE_CONNECTIONS, { color: isValid ? "#10B981" : "#EF4444", lineWidth: 3 });
    drawingUtils.drawLandmarks(mapped, { color: "#FFFFFF", radius: 4 });
  }

  async function reEvaluateCurrentMedia() {
    const isPhotoVisible = photoImg && !photoImg.classList.contains("hidden") && photoImg.src && photoImg.src.startsWith("data:image");
    if (!isPhotoVisible) return;

    await ensureLandmarker();
    if (runningMode !== "IMAGE") {
      await poseLandmarker.setOptions({ runningMode: "IMAGE" });
      runningMode = "IMAGE";
    }
    const results = poseLandmarker.detect(photoImg);
    if (results.landmarks && results.landmarks.length > 0) {
      const lm = results.landmarks[0];
      lastDetectedLandmarks = lm;
      const res = evaluateFrame(lm);
      renderPhotoLandmarks(lm, res.valid);
      updateUiHoldState(res.valid, res.msg);
    } else {
      lastDetectedLandmarks = null;
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      updateUiHoldState(false, isEnglishMode() ? "Target joints not visible" : "未偵測到目標關節");
    }
  }

  function videoDetectLoop() {
    if (!webcamStream) return;
    const drawingUtils = new DrawingUtils(ctx);
    const results = poseLandmarker.detectForVideo(video, performance.now());
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    if (results.landmarks && results.landmarks.length > 0) {
      const lm = results.landmarks[0];
      lastDetectedLandmarks = lm;
      const res = evaluateFrame(lm);
      const color = (res.valid || isSimulatedHold) ? "#10B981" : "#EF4444";
      drawingUtils.drawConnectors(lm, PoseLandmarker.POSE_CONNECTIONS, { color, lineWidth: 4 });
      drawingUtils.drawLandmarks(lm, { color: "#FFFFFF", radius: 4 });
      updateUiHoldState(res.valid, res.msg);
    } else {
      lastDetectedLandmarks = null;
      updateUiHoldState(false, isEnglishMode() ? "Full body not detected" : "未偵測到全身");
    }
    animId = requestAnimationFrame(videoDetectLoop);
  }

  async function startCamera() {
    await ensureLandmarker();
    if (runningMode !== "VIDEO") {
      await poseLandmarker.setOptions({ runningMode: "VIDEO" });
      runningMode = "VIDEO";
    }
    try {
      if (!webcamStream) {
        webcamStream = await navigator.mediaDevices.getUserMedia({ video: true });
        video.srcObject = webcamStream;
        video.onloadeddata = () => {
          canvas.width = video.videoWidth;
          canvas.height = video.videoHeight;
          videoDetectLoop();
        };
      }
    } catch (e) {
      btnSimulate.classList.remove("hidden");
    }
  }

  function stopCamera() {
    if (animId) cancelAnimationFrame(animId);
    animId = null;
    if (webcamStream) {
      webcamStream.getTracks().forEach(t => t.stop());
      webcamStream = null;
      video.srcObject = null;
    }
  }

  window.switchPnfAiSource = async function (source) {
    if (source === "cam") {
      isSimulatedHold = false;
      lastDetectedLandmarks = null;
      photoImg.classList.add("hidden");
      video.classList.remove("hidden");
      canvas.classList.add("-scale-x-100");
      await startCamera();
    }
  };

  photoInput.addEventListener("change", (e) => {
    const file = e.target.files?.[0];
    if (!file) return;
    isSimulatedHold = false;
    btnSimulate.classList.add("hidden");
    stopCamera();
    video.classList.add("hidden");
    photoImg.classList.remove("hidden");
    canvas.classList.remove("-scale-x-100");

    const reader = new FileReader();
    reader.onload = async (event) => {
      try {
        photoImg.src = event.target.result;
        await photoImg.decode();
        await reEvaluateCurrentMedia();
      } finally {
        photoInput.value = "";
      }
    };
    reader.readAsDataURL(file);
  });

  window.toggleSimulatePoseHold = function () {
    isSimulatedHold = !isSimulatedHold;
    updateUiHoldState(isSimulatedHold, isSimulatedHold ? "Simulation active" : "Simulation paused");
  };

  const origSetInterval = window.setInterval;
  window.setInterval = function (fn, delay, ...args) {
    if (delay === 1000 && typeof fn === "function") {
      return origSetInterval.call(window, function () {
        if (!isAiSessionActive) return;
        if (!isPoseHeldValid && !isSimulatedHold) return;
        return fn.apply(this, arguments);
      }, delay, ...args);
    }
    return origSetInterval.call(window, fn, delay, ...args);
  };

  const origStart = window.startPnfTimer;
  window.startPnfTimer = async function () {
    isAiSessionActive = true;
    const btnStart = document.getElementById("pnf-btn-start");
    const btnPause = document.getElementById("pnf-btn-pause");
    if (btnStart) btnStart.disabled = true;
    if (btnPause) btnPause.disabled = false;

    if (!photoImg.classList.contains("hidden")) {
      await reEvaluateCurrentMedia();
    } else {
      await startCamera();
    }
    if (typeof origStart === "function") origStart();
  };

  const origPause = window.pausePnfTimer;
  window.pausePnfTimer = function () {
    isAiSessionActive = false;
    const btnStart = document.getElementById("pnf-btn-start");
    const btnPause = document.getElementById("pnf-btn-pause");
    if (btnStart) btnStart.disabled = false;
    if (btnPause) btnPause.disabled = true;
    if (typeof origPause === "function") origPause();
    updateUiHoldState(false, isEnglishMode() ? "Session paused" : "已暫停計時");
  };

  const origReset = window.resetPnfTimer;
  window.resetPnfTimer = function () {
    currentStepIdx = 0;
    if (typeof origReset === "function") origReset();
    resetHoldHudToStandby();
    schedulePnfSync();
  };

  if (activePresetBtn) {
    syncStepsFromButton(activePresetBtn);
  }
  resetHoldHudToStandby();
  schedulePnfSync();
</script>