
<section id="module-pnf" class="tab-content space-y-6">
  <!-- Top Banner / Routine Selection -->
  <div class="glass-card rounded-2xl p-5 border border-gray-800 flex flex-col gap-4">
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
      <div>
        <h2 class="text-lg font-bold text-white flex items-center gap-2">
          <i class="fa-solid fa-heart-pulse text-goldAccent"></i> PNF Stretching Protocol Assistant
          <span class="px-2 py-0.5 text-[10px] font-bold rounded-md bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
            AI POSE HOLD ACTIVE
          </span>
        </h2>
        <p class="text-xs text-gray-400 mt-1">
          Proprioceptive Neuromuscular Facilitation (Hold-Relax Method): AI tracks your joint angles in real-time and counts down only while proper stretch posture is held.
        </p>
      </div>
    </div>

    <!-- Muscle Presets (Dynamic from DB - 22 Protocols) -->
    <div class="flex items-center gap-2 overflow-x-auto pb-2 w-full">
      <span class="text-xs text-gray-400 font-medium whitespace-nowrap mr-1"><i class="fa-solid fa-database text-goldAccent mr-1"></i> Presets (22):</span>
      @if(isset($deviations) && $deviations->count() > 0)
        @foreach($deviations as $dIndex => $deviation)
          @foreach($deviation->recommendedStretches as $sIndex => $stretch)
            <button type="button"
              data-stretch-id="{{ $stretch->id }}"
              data-title="{{ $stretch->title_zh }}"
              data-steps='{{ json_encode($stretch->pnfSteps) }}'
              data-ai-rules='@json($stretch->ai_rules)'
              onclick="handlePnfPresetClick(this)"
              class="pnf-preset-btn ...">
              <i class="fa-solid fa-heart-pulse"></i> {{ $stretch->title_zh }}
            </button>
          @endforeach
        @endforeach
      @else
        <span class="text-xs text-gray-500">尚無預設動作</span>
      @endif
    </div>
  </div>

  <!-- Main Interactive Dashboard Grid -->
  <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

    <!-- Left: AI Pose Detection & Smart Hold Timer Display (8 cols) -->
    <div class="lg:col-span-8 glass-card rounded-2xl p-6 flex flex-col items-center justify-between relative overflow-hidden min-h-[500px]">
      
      <!-- Background Ambient Glow -->
      <div id="pnf-glow-bg" class="absolute inset-0 bg-goldAccent/5 blur-3xl pointer-events-none transition-colors duration-500"></div>

      <!-- Top Header: Target Muscle, Source Switch & Cycle Counter -->
      <div class="z-10 mb-3 flex flex-wrap items-center justify-between gap-2 w-full">
        <div class="flex items-center gap-2">
          <span id="pnf-target-label" class="text-xs uppercase tracking-widest font-bold px-3 py-1 rounded-full bg-surfaceDark text-goldAccent border border-goldAccent/30">
            Target: Chest Stretch
          </span>
          <!-- 來源切換：即時攝影機 / 上傳照片驗證 -->
          <button type="button" id="ai-btn-cam" onclick="switchPnfAiSource('cam')" class="px-2.5 py-1 rounded-lg text-[11px] font-bold bg-indigo-600 text-white transition">
            <i class="fa-solid fa-video mr-1"></i>Live Cam
          </button>
          <label for="ai-photo-input" id="ai-btn-photo" class="cursor-pointer px-2.5 py-1 rounded-lg text-[11px] font-bold bg-gray-800 hover:bg-gray-700 text-gray-300 border border-gray-700 transition">
            <i class="fa-solid fa-image mr-1"></i>Photo Test
          </label>
          <input type="file" id="ai-photo-input" accept="image/*" class="hidden">
        </div>

        <span class="text-xs text-gray-400 font-semibold">
          Cycle <span id="pnf-cycle-count" class="text-white font-bold">1</span> of 3
        </span>
      </div>

      <!-- Center: AI Camera/Canvas Viewport + Floating HUD Timer -->
      <div class="relative z-10 w-full aspect-video bg-black rounded-2xl overflow-hidden border-2 border-gray-800 flex items-center justify-center shadow-inner transition-colors duration-300" id="ai-viewport-frame">
        <video id="pnf-ai-webcam" autoplay playsinline class="w-full h-full object-cover -scale-x-100"></video>
        <img id="pnf-ai-photo" class="hidden max-w-full max-h-full object-contain" alt="Posture Check">
        <canvas id="pnf-ai-canvas" class="absolute inset-0 w-full h-full pointer-events-none -scale-x-100"></canvas>

        <!-- Top-Left HUD: Joint Angle & Hold Gate Status -->
        <div class="absolute top-3 left-3 flex flex-col gap-1.5 text-left">
          <div id="ai-hold-badge" class="px-3 py-1 rounded-full text-xs font-bold bg-black/80 text-gray-300 border border-gray-700 backdrop-blur-md flex items-center gap-2">
            <span id="ai-hold-dot" class="w-2 h-2 rounded-full bg-gray-500"></span>
            <span id="ai-hold-status-text">AI Standby (Click Start)</span>
          </div>
          <span id="ai-joint-readout" class="px-2.5 py-1 rounded-lg text-[11px] font-mono bg-black/75 text-goldAccent border border-gray-800 backdrop-blur-sm">
            Angle: --° | Target: --°
          </span>
        </div>

        <!-- Top-Right HUD: Compact Circular SVG Progress Timer (Preserves all original IDs!) -->
        <div class="absolute top-3 right-3 w-28 h-28 sm:w-32 sm:h-32 rounded-2xl bg-black/75 backdrop-blur-md border border-gray-700/80 p-2 flex items-center justify-center shadow-lg">
          <svg class="w-full h-full -rotate-90" viewBox="0 0 120 120">
            <circle class="text-gray-800" stroke-width="8" stroke="currentColor" fill="transparent" r="52" cx="60" cy="60" />
            <circle id="pnf-progress-circle" class="progress-ring__circle text-goldAccent transition-all duration-300" stroke-width="8" stroke-dasharray="326.72" stroke-dashoffset="0" stroke-linecap="round" stroke="currentColor" fill="transparent" r="52" cx="60" cy="60" />
          </svg>
          <div class="absolute inset-0 flex flex-col items-center justify-center text-center p-1">
            <span id="pnf-phase-badge" class="text-[8px] font-extrabold uppercase tracking-wider px-1.5 py-0.5 rounded bg-goldAccent/20 text-goldAccent border border-goldAccent/40 truncate max-w-[95px]">
              PREPARATION
            </span>
            <span id="pnf-timer-digits" class="text-2xl sm:text-3xl font-extrabold text-white font-mono leading-tight mt-0.5">
              10
            </span>
          </div>
        </div>

        <!-- Bottom HUD: Real-time Posture Instruction & Simulation Fallback -->
        <div class="absolute bottom-3 left-3 right-3 bg-black/85 backdrop-blur-md border border-gray-700/80 px-3.5 py-2.5 rounded-xl flex items-center justify-between gap-2 text-left">
          <div class="min-w-0 flex-1">
            <p id="pnf-instruction-text" class="text-xs font-bold text-white truncate">
              Position target muscle in stretch position
            </p>
            <p id="ai-feedback-subtext" class="text-[11px] text-gray-400 truncate mt-0.5">
              Press "Start Session" to activate AI posture tracking
            </p>
          </div>
          <!-- 無攝影機環境專用的模擬達標測試鈕 -->
          <button type="button" id="ai-btn-simulate" onclick="toggleSimulatePoseHold()" class="hidden shrink-0 px-2.5 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-[11px] font-bold text-white transition">
            模擬動作達標
          </button>
        </div>
      </div>

      <!-- Phase Progress Flow Bar (Visual Stepper) -->
      <div id="pnf-stepper-container" class="z-10 w-full max-w-lg my-4 grid grid-cols-3 sm:grid-cols-6 gap-2 text-center">
        <!-- Dynamically Rendered by JS -->
      </div>

      <!-- Interactive Controls Row -->
      <div class="z-10 flex items-center justify-center gap-3 flex-wrap w-full max-w-md">
        <button id="pnf-btn-start" onclick="startPnfTimer()" class="flex-1 min-w-[120px] py-3 px-5 rounded-xl font-bold text-sm bg-gradient-to-r from-goldAccent to-goldHover text-black shadow-lg shadow-goldAccent/20 hover:scale-[1.02] active:scale-95 transition">
          <i class="fa-solid fa-play mr-1.5"></i> Start Session
        </button>
        <button id="pnf-btn-pause" onclick="pausePnfTimer()" disabled class="py-3 px-4 rounded-xl font-bold text-sm bg-gray-800 text-gray-400 border border-gray-700 hover:bg-gray-700 disabled:opacity-40 disabled:pointer-events-none transition">
          <i class="fa-solid fa-pause"></i> Pause
        </button>
        <button id="pnf-btn-reset" onclick="resetPnfTimer()" class="py-3 px-4 rounded-xl font-bold text-sm bg-gray-800 text-gray-300 border border-gray-700 hover:bg-gray-700 transition">
          <i class="fa-solid fa-rotate-left"></i> Reset
        </button>
        <button onclick="skipPnfPhase()" class="py-3 px-4 rounded-xl font-bold text-sm bg-surfaceDark text-goldAccent border border-goldAccent/40 hover:bg-goldAccent/10 transition">
          <i class="fa-solid fa-forward-step"></i> Skip Step
        </button>
      </div>

    </div>

    <!-- Right: Instruction Guidelines Card (4 cols) -->
    <div class="lg:col-span-4 glass-card rounded-2xl p-6 border border-gray-800 flex flex-col justify-between space-y-4">
      <div>
        <div class="flex items-center gap-2 text-goldAccent font-bold text-sm mb-3">
          <i class="fa-solid fa-book-open"></i> PNF Protocol Execution Guide
        </div>
        
        <div id="pnf-guide-steps-list" class="space-y-2.5 text-xs text-gray-300 max-h-[380px] overflow-y-auto pr-1">
            <!-- Rendered via JS -->
        </div>
      </div>

      <!-- Scientific Note Footer -->
      <div class="p-3 rounded-xl bg-goldAccent/10 border border-goldAccent/30 text-[11px] text-gray-300">
        <i class="fa-solid fa-lightbulb text-goldAccent mr-1"></i>
        <strong>Neuro-Physiology Note:</strong> PNF utilizes Golgi Tendon Organ (GTO) autogenic inhibition to override stretch reflex resistance.
      </div>
    </div>

  </div>
</section>

<script type="module">
  import { PoseLandmarker, FilesetResolver, DrawingUtils } from "https://cdn.jsdelivr.net/npm/@mediapipe/tasks-vision@0.10.14";

  // 1. 前端備援規則庫（當資料庫 ai_rules 尚未讀取時的預設防護）
  const PNF_RULES_BY_KEYWORD = [
    {
      keywords: ["前臂", "手腕", "屈肌", "伸肌"],
      left: [11, 13, 15], right: [12, 14, 16],
      target: 158, comparator: ">=",
      requireArmStraight: true,
      tip: "手臂完全伸直貼牆（手肘角度 ≥ 158°），不可彎曲手肘"
    },
    {
      keywords: ["抓腳", "股四頭", "股直肌", "大腿前"],
      left: [23, 25, 27], right: [24, 26, 28],
      target: 75, comparator: "<=",
      requireLowerBody: true,
      tip: "單手抓腳踝將腳跟拉向臀部，保持屈膝角度 ≤ 75°"
    },
    {
      keywords: ["胸大肌", "胸小肌", "門框", "擴胸", "圓肩"],
      left: [13, 11, 23], right: [14, 12, 24],
      target: 75, comparator: ">=",
      tip: "抬起手臂扶住門框（大臂外展 ≥ 75°）並挺胸前跨"
    },
    {
      keywords: ["闊背", "背闊", "三頭", "過頭", "菱形"],
      left: [13, 11, 23], right: [14, 12, 24],
      target: 145, comparator: ">=",
      requireOverhead: true,
      tip: "單手高舉過頭（手臂貼近耳朵 ≥ 145°）並向對側側屈"
    },
    {
      keywords: ["腹外斜", "腹內斜", "腰方肌", "側彎", "側屈", "軀幹"],
      left: [11, 23, 25], right: [12, 24, 26],
      target: 155, comparator: "<=",
      tip: "軀幹明顯向對側側彎（軀幹側屈角 ≤ 155°），不可直立站著"
    },
    {
      keywords: ["斜方肌", "提肩胛", "胸鎖乳突", "頸", "頭"],
      left: [7, 11, 12], right: [8, 12, 11],
      target: 112, comparator: ">=",
      tip: "保持雙肩下沉，頭部向對側緩慢側傾 ≥ 112°"
    },
    {
      keywords: ["大腿後肌", "膕繩", "腿後", "直腿"],
      left: [23, 25, 27], right: [24, 26, 28],
      target: 162, comparator: ">=",
      requireLowerBody: true,
      tip: "抬起單腿並保持膝蓋伸直（膝角 ≥ 162°）"
    },
    {
      keywords: ["髂腰肌", "髖屈", "高跪", "弓箭步"],
      left: [11, 23, 25], right: [12, 24, 26],
      target: 165, comparator: ">=",
      requireLowerBody: true,
      requireKneeling: true,
      tip: "採單膝跪地高跪姿，重心微前移展開後側髖部 ≥ 165°"
    },
    {
      keywords: ["臀大肌", "梨狀肌", "抱膝", "臀"],
      left: [11, 23, 25], right: [12, 24, 26],
      target: 65, comparator: "<=",
      requireLowerBody: true,
      tip: "雙手抱膝拉向胸口，保持屈髖角度 ≤ 65°"
    },
    {
      keywords: ["內收", "蝴蝶", "胯"],
      left: [23, 25, 27], right: [24, 26, 28],
      target: 95, comparator: "<=",
      requireLowerBody: true,
      tip: "屈膝外展拉伸大腿內側，保持姿勢穩定"
    },
    {
      keywords: ["腓腸肌", "比目魚", "小腿", "跟腱", "推牆"],
      left: [25, 27, 31], right: [26, 28, 32],
      target: 85, comparator: "<=",
      requireLowerBody: true,
      tip: "後腳跟踩實地面身體前傾，保持小腿拉伸角度"
    }
  ];

  const DEFAULT_RULE = {
    left: [11, 23, 25], right: [12, 24, 26],
    target: 150, comparator: ">=",
    requireLowerBody: false,
    tip: "請將全身側面置於鏡頭內並保持目標拉伸姿勢"
  };

  // DOM 元素
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

  let poseLandmarker = null;
  let webcamStream = null;
  let animId = null;
  let runningMode = "VIDEO";
  let isAiSessionActive = false;
  let isPoseHeldValid = false;
  let isSimulatedHold = false;
  let lastDetectedLandmarks = null;

  // 取得當前預設按鈕與其帶有的資料庫 ai_rules
  let activePresetBtn = document.querySelector(".pnf-preset-btn.active-preset");
  let activeStretchTitle = activePresetBtn?.dataset.title || "胸大肌門框 PNF 拉伸";
  let activeDbRule = activePresetBtn?.dataset.aiRules ? JSON.parse(activePresetBtn.dataset.aiRules) : null;

  // 核心：優先採用資料庫規則，若無則依關鍵字從備援庫搜尋
  function getCurrentRule() {
    if (activeDbRule && activeDbRule.left && activeDbRule.right) {
      return activeDbRule;
    }
    return PNF_RULES_BY_KEYWORD.find(r => r.keywords.some(k => activeStretchTitle.includes(k))) || DEFAULT_RULE;
  }

  // 讀取當前課程右側指南的完整文字
  function getActiveGuideText() {
    return document.getElementById("pnf-guide-steps-list")?.innerText || "";
  }

  // 初始化 MediaPipe Pose Landmarker
  async function ensureLandmarker() {
    if (poseLandmarker) return;
    feedbackSubtext.innerText = "正在載入 AI 骨骼辨識模型...";
    const vision = await FilesetResolver.forVisionTasks(
      "https://cdn.jsdelivr.net/npm/@mediapipe/tasks-vision@0.10.14/wasm"
    );
    poseLandmarker = await PoseLandmarker.createFromOptions(vision, {
      baseOptions: {
        modelAssetPath: "https://storage.googleapis.com/mediapipe-models/pose_landmarker/pose_landmarker_lite/float16/1/pose_landmarker_lite.task",
        delegate: "GPU"
      },
      runningMode: runningMode,
      numPoses: 1
    });
  }

  function calcAngle(a, b, c) {
    const rad = Math.atan2(c.y - b.y, c.x - b.x) - Math.atan2(a.y - b.y, a.x - b.x);
    let deg = Math.abs((rad * 180.0) / Math.PI);
    if (deg > 180.0) deg = 360 - deg;
    return Math.round(deg);
  }

  // 嚴格評估當前幀是否符合該 PNF 動作與執行指南的條件
  function evaluateFrame(landmarks) {
    const rule = getCurrentRule();
    const guideText = getActiveGuideText();
    const combinedText = `${activeStretchTitle} ${guideText}`;

    const lPts = rule.left.map(i => landmarks[i]);
    const rPts = rule.right.map(i => landmarks[i]);
    const lScore = lPts.reduce((s, p) => s + (p?.visibility || 0), 0);
    const rScore = rPts.reduce((s, p) => s + (p?.visibility || 0), 0);
    const pts = lScore >= rScore ? lPts : rPts;

    // 1. 檢查目標關節是否清晰入鏡
    if (pts.some(p => !p || p.visibility < 0.6)) {
      jointReadout.innerText = `Angle: --° | Target: ${rule.comparator}${rule.target}°`;
      return {
        valid: false,
        msg: rule.requireLowerBody
          ? "❌ 姿勢錯誤：此為下半身動作，請確保髖, 膝, 腳踝完整入鏡"
          : "❌ 姿勢錯誤：未偵測到該動作所需的目標關節"
      };
    }

    // 2. 檢查「坐姿 / 仰臥 vs 站姿」
    const hipAngle = calcAngle(landmarks[11], landmarks[23], landmarks[25]);
    if ((combinedText.includes("坐姿") || combinedText.includes("仰臥")) && hipAngle > 155) {
      jointReadout.innerText = `Wrong Posture (Standing) | Need Seated/Supine`;
      return { valid: false, msg: `❌ 姿勢錯誤：指南要求「坐姿/仰臥」，但偵測到站立姿勢` };
    }

    // 3. 檢查「高舉過頭」條件
    if (rule.requireOverhead || combinedText.includes("高舉") || combinedText.includes("過頭")) {
      const lArmRaise = calcAngle(landmarks[13], landmarks[11], landmarks[23]);
      const rArmRaise = calcAngle(landmarks[14], landmarks[12], landmarks[24]);
      const maxRaise = Math.max(lArmRaise, rArmRaise);
      if (maxRaise < 135) {
        jointReadout.innerText = `Arm Raise: ${maxRaise}° | Need >= 145°`;
        return { valid: false, msg: `❌ 姿勢錯誤：指南要求「單手高舉過頭」，目前手臂僅抬高 ${maxRaise}°` };
      }
    }

    // 4. 檢查「手臂伸直」條件
    if (rule.requireArmStraight || combinedText.includes("手臂伸直")) {
      const lElbow = calcAngle(landmarks[11], landmarks[13], landmarks[15]);
      const rElbow = calcAngle(landmarks[12], landmarks[14], landmarks[16]);
      const bestElbow = lScore >= rScore ? lElbow : rElbow;
      if (bestElbow < 155) {
        jointReadout.innerText = `Elbow Angle: ${bestElbow}° | Need >= 158°`;
        return { valid: false, msg: `❌ 姿勢錯誤：手臂必須完全伸直貼牆（目前手肘彎曲 ${bestElbow}°）` };
      }
    }

    // 5. 檢查「高跪姿」條件
    if (rule.requireKneeling || combinedText.includes("高跪")) {
      const lKnee = calcAngle(landmarks[23], landmarks[25], landmarks[27]);
      const rKnee = calcAngle(landmarks[24], landmarks[26], landmarks[28]);
      if (lKnee > 140 && rKnee > 140) {
        jointReadout.innerText = `Wrong Posture (Standing) | Need Kneeling`;
        return { valid: false, msg: "❌ 姿勢錯誤：偵測到雙腿站立，此動作必須採「單膝跪地高跪姿」" };
      }
    }

    // 6. 計算目標關節夾角並比對
    const angle = calcAngle(pts[0], pts[1], pts[2]);
    jointReadout.innerText = `Angle: ${angle}° | Target: ${rule.comparator}${rule.target}°`;

    const passed = rule.comparator === ">=" ? (angle >= rule.target) : (angle <= rule.target);

    // 預備階段（STEP 1）允許 ±12° 緩衝
    const phaseText = (document.getElementById("pnf-phase-badge")?.innerText || "").toUpperCase();
    const isPrep = phaseText.includes("PREP") || phaseText.includes("預備");
    if (isPrep) {
      const prepOk = rule.comparator === ">=" ? (angle >= rule.target - 12) : (angle <= rule.target + 12);
      if (!prepOk) {
        return { valid: false, msg: `❌ 預備姿勢錯誤：${rule.tip}（目前 ${angle}°）` };
      }
      return { valid: true, msg: `✅ 預備姿勢正確（${angle}°），準備進入拉伸` };
    }

    // 7. 拉伸與保持階段：嚴格達標才倒數
    if (passed) {
      return { valid: true, msg: `🔥 姿勢正確並保持中（${angle}°），計時進行中！` };
    } else {
      return { valid: false, msg: `❌ 姿勢未達標 · 已暫停：${rule.tip}（目前 ${angle}°）` };
    }
  }

  function updateUiHoldState(valid, msg) {
    const finalValid = Boolean(valid || isSimulatedHold);
    isPoseHeldValid = finalValid;
    feedbackSubtext.innerText = msg;

    if (finalValid) {
      holdDot.className = "w-2 h-2 rounded-full bg-emerald-400 animate-ping";
      holdStatusText.innerText = "HOLDING · 計時進行中";
      holdBadge.className = "px-3 py-1 rounded-full text-xs font-bold bg-emerald-950/90 text-emerald-400 border border-emerald-500/50 backdrop-blur-md flex items-center gap-2";
      viewportFrame.classList.remove("border-gray-800", "border-red-500/80");
      viewportFrame.classList.add("border-emerald-500/80");
    } else {
      holdDot.className = "w-2 h-2 rounded-full bg-red-500";
      holdStatusText.innerText = "WRONG POSTURE · 計時暫停";
      holdBadge.className = "px-3 py-1 rounded-full text-xs font-bold bg-red-950/90 text-red-400 border border-red-500/60 backdrop-blur-md flex items-center gap-2";
      viewportFrame.classList.remove("border-gray-800", "border-emerald-500/80");
      viewportFrame.classList.add("border-red-500/80");
    }
  }

  function renderPhotoLandmarks(lm, isValid) {
    const boxW = viewportFrame.clientWidth || 640;
    const boxH = viewportFrame.clientHeight || 360;
    canvas.width = boxW;
    canvas.height = boxH;
    ctx.clearRect(0, 0, boxW, boxH);

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

    const mapped = lm.map(pt => ({
      ...pt,
      x: (offX + pt.x * drawW) / boxW,
      y: (offY + pt.y * drawH) / boxH
    }));

    const drawingUtils = new DrawingUtils(ctx);
    drawingUtils.drawConnectors(mapped, PoseLandmarker.POSE_CONNECTIONS, {
      color: isValid ? "#10B981" : "#EF4444",
      lineWidth: 3
    });
    drawingUtils.drawLandmarks(mapped, { color: "#FFFFFF", radius: 4 });
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
      updateUiHoldState(false, "❌ 未偵測到人體，請站入攝影機畫面中");
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
      feedbackSubtext.innerText = "⚠️ 無法存取攝影機，請使用 Photo Test 上傳照片或點擊右側模擬鈕";
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
      btnSimulate.innerText = "模擬動作達標";
      photoImg.classList.add("hidden");
      video.classList.remove("hidden");
      canvas.classList.add("-scale-x-100");
      document.getElementById("ai-btn-cam").className = "px-2.5 py-1 rounded-lg text-[11px] font-bold bg-indigo-600 text-white transition";
      document.getElementById("ai-btn-photo").className = "cursor-pointer px-2.5 py-1 rounded-lg text-[11px] font-bold bg-gray-800 hover:bg-gray-700 text-gray-300 border border-gray-700 transition";
      await startCamera();
    }
  };

  photoInput.addEventListener("change", (e) => {
    const file = e.target.files?.[0];
    if (!file) return;

    isSimulatedHold = false;
    btnSimulate.innerText = "模擬動作達標";
    btnSimulate.classList.add("hidden");

    stopCamera();
    video.classList.add("hidden");
    photoImg.classList.remove("hidden");
    canvas.classList.remove("-scale-x-100");
    document.getElementById("ai-btn-cam").className = "px-2.5 py-1 rounded-lg text-[11px] font-bold bg-gray-800 hover:bg-gray-700 text-gray-300 border border-gray-700 transition";
    document.getElementById("ai-btn-photo").className = "cursor-pointer px-2.5 py-1 rounded-lg text-[11px] font-bold bg-indigo-600 text-white transition";
    feedbackSubtext.innerText = `正在讀取照片「${file.name}」並載入 AI 模型...`;

    const reader = new FileReader();
    reader.onload = async (event) => {
      try {
        photoImg.src = event.target.result;
        await photoImg.decode();

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
          updateUiHoldState(false, "❌ 照片中未偵測到人體骨架，請換一張光線充足的全身照");
        }
      } catch (err) {
        console.error("Photo Test 辨識失敗:", err);
        updateUiHoldState(false, `❌ 照片分析錯誤：${err.message}`);
      } finally {
        photoInput.value = "";
      }
    };
    reader.readAsDataURL(file);
  });

  window.toggleSimulatePoseHold = function () {
    isSimulatedHold = !isSimulatedHold;
    btnSimulate.innerText = isSimulatedHold ? "取消模擬" : "模擬動作達標";
    updateUiHoldState(isSimulatedHold, isSimulatedHold ? "✅ 模擬動作保持中，計時正常運行" : "❌ 已暫停模擬");
  };

  const origSetInterval = window.setInterval;
  window.setInterval = function (fn, delay, ...args) {
    if (delay === 1000 && typeof fn === "function") {
      const wrappedFn = function () {
        if (!photoImg.classList.contains("hidden") && lastDetectedLandmarks) {
          const res = evaluateFrame(lastDetectedLandmarks);
          renderPhotoLandmarks(lastDetectedLandmarks, res.valid);
          updateUiHoldState(res.valid, res.msg);
        }

        if (isAiSessionActive && !isPoseHeldValid && !isSimulatedHold) {
          return;
        }
        return fn.apply(this, arguments);
      };
      return origSetInterval.call(window, wrappedFn, delay, ...args);
    }
    return origSetInterval.call(window, fn, delay, ...args);
  };

  const origStart = window.startPnfTimer;
  window.startPnfTimer = function () {
    isAiSessionActive = true;
    if (photoImg.classList.contains("hidden")) {
      startCamera();
    } else if (lastDetectedLandmarks) {
      const res = evaluateFrame(lastDetectedLandmarks);
      renderPhotoLandmarks(lastDetectedLandmarks, res.valid);
      updateUiHoldState(res.valid, res.msg);
    }
    if (typeof origStart === "function") origStart();
  };

  const origPause = window.pausePnfTimer;
  window.pausePnfTimer = function () {
    isAiSessionActive = false;
    if (typeof origPause === "function") origPause();
  };

  const origReset = window.resetPnfTimer;
  window.resetPnfTimer = function () {
    isAiSessionActive = false;
    isSimulatedHold = false;
    stopCamera();
    holdDot.className = "w-2 h-2 rounded-full bg-gray-500";
    holdStatusText.innerText = "AI Standby (Click Start)";
    holdBadge.className = "px-3 py-1 rounded-full text-xs font-bold bg-black/80 text-gray-300 border border-gray-700 backdrop-blur-md flex items-center gap-2";
    viewportFrame.className = "relative z-10 w-full aspect-video bg-black rounded-2xl overflow-hidden border-2 border-gray-800 flex items-center justify-center shadow-inner transition-colors duration-300";
    if (typeof origReset === "function") origReset();
  };

  const origPresetClick = window.handlePnfPresetClick;
  window.handlePnfPresetClick = function (btn) {
    activeStretchTitle = btn.dataset.title || "";
    activeDbRule = btn.dataset.aiRules ? JSON.parse(btn.dataset.aiRules) : null;

    const rule = getCurrentRule();
    jointReadout.innerText = `Angle: --° | Target: ${rule.comparator}${rule.target}°`;
    feedbackSubtext.innerText = `已切換動作規則：${rule.tip}`;

    if (typeof origPresetClick === "function") origPresetClick(btn);

    if (!photoImg.classList.contains("hidden") && lastDetectedLandmarks) {
      const res = evaluateFrame(lastDetectedLandmarks);
      renderPhotoLandmarks(lastDetectedLandmarks, res.valid);
      updateUiHoldState(res.valid, res.msg);
    }
  };
</script>