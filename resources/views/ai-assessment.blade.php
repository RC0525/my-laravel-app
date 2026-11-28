<!DOCTYPE html>
<html lang="zh-Hant">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title data-i18n="lab.page_title">PNF Posture Lab - 全身多維度 AI 體態快篩與處方推薦</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            cardDark: '#161822',
            goldAccent: '#F59E0B'
          }
        }
      }
    }
  </script>
  <style>
    html, body {
      max-width: 100vw;
      overflow-x: hidden;
      -webkit-tap-highlight-color: transparent;
    }
    .lab-media-box {
      position: relative;
      width: 100%;
      aspect-ratio: 4 / 3;
    }
    @media (min-width: 768px) {
      .lab-media-box {
        aspect-ratio: 16 / 9;
      }
    }
  </style>
</head>
<body class="bg-[#0B0D14] text-white px-3 py-4 sm:px-6 sm:py-6 min-h-screen">

  <!-- 頂部導覽列 -->
  <div class="max-w-6xl mx-auto mb-4 sm:mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 border-b border-gray-800 pb-4">
    <div class="flex items-center gap-3 min-w-0">
      <div class="w-9 h-9 shrink-0 rounded-xl bg-goldAccent/20 border border-goldAccent/40 flex items-center justify-center text-goldAccent">
        <i class="fa-solid fa-person-rays"></i>
      </div>
      <div class="min-w-0">
        <h1 data-i18n="lab.header_title" class="text-sm sm:text-base font-bold text-white truncate">PNF Posture Lab · 全身 AI 體態實驗室</h1>
        <p data-i18n="lab.header_subtitle" class="text-[11px] sm:text-xs text-gray-400 leading-snug sm:truncate">多維度關節角度快篩（頭頸、肩胸、骨盆、膝關節、左右對稱度）與 PNF 處方推薦</p>
      </div>
    </div>

    <div class="flex items-center gap-2 self-start sm:self-auto shrink-0">
      <button id="btn-toggle-lang" type="button" class="min-h-[38px] px-3.5 py-2 rounded-xl bg-cardDark hover:bg-gray-800 border border-goldAccent/40 text-xs font-bold text-goldAccent transition inline-flex items-center gap-1.5">
        <i class="fa-solid fa-language"></i> <span id="lab-lang-label">EN / 中文</span>
      </button>
      <a href="/" class="min-h-[38px] px-3.5 py-2 rounded-xl bg-cardDark hover:bg-gray-800 border border-gray-700 text-xs font-semibold text-gray-300 transition inline-flex items-center gap-2">
        <i class="fa-solid fa-arrow-left"></i> <span data-i18n="lab.back_home">返回主訓練系統</span>
      </a>
    </div>
  </div>

  <!-- 主版面：手機 1 欄、iPad 橫向與桌面 3 欄 -->
  <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 text-white">

    <!-- 左側：即時 AI 視覺辨識與照片上傳區 -->
    <div class="lg:col-span-2 bg-cardDark p-3 sm:p-4 rounded-2xl border border-gray-800 min-w-0">
      <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 mb-3">
        <!-- 來源切換控制列 -->
        <div class="flex flex-wrap items-center gap-2">
          <button id="btn-source-cam" type="button" class="min-h-[36px] px-3 py-1.5 rounded-lg text-xs font-bold bg-indigo-600 text-white transition inline-flex items-center gap-1.5">
            <i class="fa-solid fa-video"></i> <span data-i18n="lab.live_cam">即時攝影機</span>
          </button>
          <label for="photo-upload" id="btn-source-photo" class="min-h-[36px] cursor-pointer px-3 py-1.5 rounded-lg text-xs font-bold bg-gray-800 hover:bg-gray-700 text-gray-300 border border-gray-700 transition inline-flex items-center gap-1.5">
            <i class="fa-solid fa-image"></i> <span data-i18n="lab.upload_photo_btn">上傳全身照片檢測</span>
          </label>
          <input type="file" id="photo-upload" accept="image/*" class="hidden">
        </div>

        <span id="angle-readout" class="font-mono text-[10px] sm:text-[11px] text-gray-400">頭肩: --° | 骨盆: --° | 膝角: --°</span>
      </div>

      <div id="lab-media-container" class="lab-media-box bg-black rounded-xl overflow-hidden border border-gray-800 flex items-center justify-center">
        <!-- 即時攝影機畫面 -->
        <video id="webcam" autoplay playsinline class="w-full h-full object-cover -scale-x-100"></video>

        <!-- 靜態上傳照片預覽 -->
        <img id="uploaded-photo" class="hidden w-full h-full object-contain" alt="Uploaded Posture">

        <!-- 骨架繪製畫布 -->
        <canvas id="output_canvas" class="absolute inset-0 w-full h-full pointer-events-none -scale-x-100"></canvas>

        <!-- 畫面底部引導與進度條 -->
        <div id="overlay-banner" class="absolute bottom-2.5 left-2.5 right-2.5 sm:bottom-4 sm:left-4 sm:right-4 bg-black/80 backdrop-blur-md border border-gray-700 text-white p-2.5 sm:p-3.5 rounded-xl text-center">
          <p id="guide-text" data-i18n="lab.init_ai" class="text-xs sm:text-sm font-bold">正在初始化 AI 視覺引擎...</p>
          <div id="progress-bar-container" class="w-full bg-gray-800 h-2 rounded-full mt-2 hidden">
            <div id="scan-progress" class="bg-emerald-400 h-2 rounded-full transition-all duration-100" style="width: 0%"></div>
          </div>
        </div>
      </div>

      <!-- 明顯的上傳照片按鈕與拖放區 -->
      <div id="photo-dropzone" class="mt-3 rounded-xl border border-dashed border-gray-700 hover:border-goldAccent bg-gray-900/60 p-3.5 transition flex flex-col sm:flex-row items-center justify-between gap-3">
        <div class="flex items-center gap-3 text-left min-w-0">
          <div class="w-10 h-10 shrink-0 rounded-xl bg-indigo-500/20 border border-indigo-400/30 flex items-center justify-center text-indigo-300">
            <i class="fa-solid fa-cloud-arrow-up text-base"></i>
          </div>
          <div class="min-w-0">
            <p data-i18n="lab.dropzone_title" class="text-xs font-bold text-white">上傳全身姿勢照片進行 AI 檢測</p>
            <p data-i18n="lab.dropzone_desc" class="text-[11px] text-gray-400 leading-snug">支援 JPG / PNG / WEBP；建議雙手自然下垂，可直接點擊按鈕或拖曳照片至此</p>
          </div>
        </div>
        <button type="button" id="btn-trigger-photo-upload" class="shrink-0 w-full sm:w-auto min-h-[38px] px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow">
          <i class="fa-solid fa-folder-open"></i> <span data-i18n="lab.choose_photo">選擇照片上傳</span>
        </button>
      </div>
    </div>

    <!-- 右側：多維度診斷結果、全部問題清單與 PNF 頁面導流 -->
    <div class="bg-cardDark p-4 sm:p-6 rounded-2xl border border-gray-800 flex flex-col justify-between min-w-0">
      <div>
        <div class="flex items-center justify-between gap-2 mb-3 sm:mb-4">
          <h2 class="text-base sm:text-lg font-bold flex items-center gap-2">
            <i class="fa-solid fa-robot text-indigo-400"></i> <span data-i18n="lab.diag_title">AI 全身姿態診斷</span>
          </h2>
          <span id="mode-badge" data-i18n="lab.mode_badge" class="shrink-0 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-500/20 text-amber-400 border border-amber-500/30">
            全身快篩
          </span>
        </div>

        <!-- 預設提示說明 -->
        <div id="scanning-placeholder" class="p-3.5 sm:p-4 rounded-xl bg-gray-900/60 border border-gray-800 text-xs text-gray-400 leading-relaxed mb-4 space-y-2">
          <p><strong class="text-gray-200" data-i18n="lab.method1_label">方式 1（即時鏡頭）：</strong><span data-i18n="lab.method1_desc">全身站立於鏡頭前 1.5～2.5 公尺處，雙手自然下垂，系統將自動進行 5 秒多維度採樣。</span></p>
          <p><strong class="text-gray-200" data-i18n="lab.method2_label">方式 2（上傳照片）：</strong><span data-i18n="lab.method2_desc">使用上方或下方「選擇照片上傳」／拖放區上傳包含頭、肩、髖、膝、踝的全身照，系統將自動識別所有潛在體態問題。</span></p>
        </div>

        <!-- 快篩結果與全部問題識別卡片 -->
        <div id="assessment-result" class="hidden space-y-3 sm:space-y-4">
          <!-- 多維度量化數據 -->
          <div class="p-3 sm:p-3.5 rounded-xl bg-gray-900/80 border border-gray-800 grid grid-cols-3 gap-2 text-center">
            <div>
              <p data-i18n="lab.metric_neck" class="text-[10px] text-gray-400">頭肩夾角</p>
              <p id="avg-neck-angle" class="text-xs sm:text-sm font-bold text-white mt-0.5">--°</p>
            </div>
            <div>
              <p data-i18n="lab.metric_pelvic" class="text-[10px] text-gray-400">骨盆髖角</p>
              <p id="avg-pelvic-angle" class="text-xs sm:text-sm font-bold text-white mt-0.5">--°</p>
            </div>
            <div>
              <p data-i18n="lab.metric_knee" class="text-[10px] text-gray-400">膝關節角</p>
              <p id="avg-knee-angle" class="text-xs sm:text-sm font-bold text-white mt-0.5">--°</p>
            </div>
          </div>

          <!-- 角度可信度提示 -->
          <div id="reliability-banner" class="hidden p-2.5 sm:p-3 rounded-xl border text-[11px] leading-relaxed"></div>

          <!-- 識別出的所有問題與對應 PNF 處方清單 -->
          <div class="space-y-2.5 max-h-[260px] md:max-h-[340px] lg:max-h-[360px] overflow-y-auto pr-1" id="detected-issues-list">
            <!-- 由 JS 動態填入所有偵測到的問題卡片與前往 PNF 按鈕 -->
          </div>
        </div>
      </div>

      <button id="btn-reset" type="button" class="mt-4 sm:mt-6 min-h-[42px] w-full py-2.5 border border-gray-700 text-gray-300 text-xs font-semibold rounded-xl hover:bg-gray-800 transition">
        <span data-i18n="lab.reset_btn">重置並重新檢測</span>
      </button>
    </div>
  </div>

  <script type="module">
    import { PoseLandmarker, FilesetResolver, DrawingUtils } from "https://cdn.jsdelivr.net/npm/@mediapipe/tasks-vision@0.10.14";

    const LAB_I18N = {
      zh: {
        "lab.page_title": "PNF Posture Lab - 全身多維度 AI 體態快篩與處方推薦",
        "lab.header_title": "PNF Posture Lab · 全身 AI 體態實驗室",
        "lab.header_subtitle": "多維度關節角度快篩（頭頸、肩胸、骨盆、膝關節、左右對稱度）與 PNF 處方推薦",
        "lab.back_home": "返回主訓練系統",
        "lab.live_cam": "即時攝影機",
        "lab.upload_photo_btn": "上傳全身照片檢測",
        "lab.init_ai": "正在初始化 AI 視覺引擎...",
        "lab.dropzone_title": "上傳全身姿勢照片進行 AI 檢測",
        "lab.dropzone_desc": "支援 JPG / PNG / WEBP；建議雙手自然下垂，可直接點擊按鈕或拖曳照片至此",
        "lab.choose_photo": "選擇照片上傳",
        "lab.diag_title": "AI 全身姿態診斷",
        "lab.mode_badge": "全身快篩",
        "lab.method1_label": "方式 1（即時鏡頭）：",
        "lab.method1_desc": "全身站立於鏡頭前 1.5～2.5 公尺處，雙手自然下垂，系統將自動進行 5 秒多維度採樣。",
        "lab.method2_label": "方式 2（上傳照片）：",
        "lab.method2_desc": "使用上方或下方「選擇照片上傳」／拖放區上傳包含頭、肩、髖、膝、踝的全身照，系統將自動識別所有潛在體態問題。",
        "lab.metric_neck": "頭肩夾角",
        "lab.metric_pelvic": "骨盆髖角",
        "lab.metric_knee": "膝關節角",
        "lab.reset_btn": "重置並重新檢測"
      },
      en: {
        "lab.page_title": "PNF Posture Lab - Full-Body Multi-Angle AI Posture Screen & Prescription",
        "lab.header_title": "PNF Posture Lab · Full-Body AI Posture Studio",
        "lab.header_subtitle": "Multi-joint angle screening (head/neck, shoulder/chest, pelvis, knee, left-right symmetry) & PNF prescription",
        "lab.back_home": "Back to Main App",
        "lab.live_cam": "Live Cam",
        "lab.upload_photo_btn": "Upload Full-Body Photo",
        "lab.init_ai": "Initializing AI vision engine...",
        "lab.dropzone_title": "Upload a full-body posture photo for AI screening",
        "lab.dropzone_desc": "Supports JPG / PNG / WEBP; keep arms relaxed at your sides. Click the button or drag and drop a photo here.",
        "lab.choose_photo": "Choose Photo",
        "lab.diag_title": "AI Full-Body Posture Diagnosis",
        "lab.mode_badge": "Full-Body Screen",
        "lab.method1_label": "Method 1 (Live Camera): ",
        "lab.method1_desc": "Stand 1.5–2.5 m from the camera with arms relaxed at your sides for an automatic 5-second multi-angle scan.",
        "lab.method2_label": "Method 2 (Photo Upload): ",
        "lab.method2_desc": "Use 'Choose Photo' or the dropzone to upload a full-body photo showing head, shoulders, hips, knees, and ankles to detect all potential issues.",
        "lab.metric_neck": "Head-Shoulder",
        "lab.metric_pelvic": "Pelvic-Hip",
        "lab.metric_knee": "Knee Angle",
        "lab.reset_btn": "Reset & Rescan"
      }
    };

    const STRETCH_EN_MAP = {
      "胸大肌門框 PNF 拉伸": "Doorway Pectoralis Major PNF Stretch",
      "髂腰肌高跪姿 PNF 伸展": "Kneeling Iliopsoas PNF Stretch",
      "臀大肌仰臥抱膝 PNF 伸展": "Supine Gluteus Maximus PNF Stretch",
      "腓腸肌推牆直膝 PNF 伸展": "Wall Calf Gastrocnemius PNF Stretch",
      "斜方肌上束側傾 PNF 伸展": "Seated Upper Trapezius PNF Stretch",
      "腹外斜肌側彎 PNF 伸展": "Seated Trunk Oblique PNF Stretch"
    };

    function detectLabLang() {
      const raw = window.currentLang || document.documentElement.lang || localStorage.getItem("lang") || localStorage.getItem("locale") || "zh";
      return String(raw).toLowerCase().startsWith("en") ? "en" : "zh";
    }

    function isLabEn() {
      return detectLabLang() === "en";
    }

    function setLabLang(lang) {
      const normalized = String(lang).toLowerCase().startsWith("en") ? "en" : "zh";
      window.currentLang = normalized;
      document.documentElement.lang = normalized === "en" ? "en" : "zh-Hant";
      localStorage.setItem("lang", normalized);
      localStorage.setItem("locale", normalized);
      applyLabLang();
    }

    const video = document.getElementById("webcam");
    const uploadedPhoto = document.getElementById("uploaded-photo");
    const photoInput = document.getElementById("photo-upload");
    const btnTriggerPhoto = document.getElementById("btn-trigger-photo-upload");
    const photoDropzone = document.getElementById("photo-dropzone");
    const btnSourceCam = document.getElementById("btn-source-cam");
    const btnSourcePhoto = document.getElementById("btn-source-photo");
    const canvas = document.getElementById("output_canvas");
    const ctx = canvas.getContext("2d");
    const guideText = document.getElementById("guide-text");
    const angleReadout = document.getElementById("angle-readout");
    const scanProgress = document.getElementById("scan-progress");
    const progressContainer = document.getElementById("progress-bar-container");
    const reliabilityBanner = document.getElementById("reliability-banner");

    let poseLandmarker = null;
    let webcamStream = null;
    let animationFrameId = null;
    let lastVideoTime = -1;
    let currentRunningMode = "VIDEO";
    let lastPhotoLandmarks = null;
    let lastSummaryForRender = null;

    let currentMode = "ASSESSMENT";
    const SCAN_DURATION_MS = 5000;
    let scanStartTime = null;
    let samples = {
      neck: [],
      pelvic: [],
      knee: [],
      shoulderDiff: [],
      hipDiff: [],
      forwardHeadOffset: [],
      lowConfidenceCount: 0,
      totalCount: 0
    };

    function formatAngleReadout(neckAngle, pelvicAngle, kneeAngle, relTagZh, relTagEn) {
      const isEn = isLabEn();
      const nVal = neckAngle !== null && neckAngle !== undefined ? `${neckAngle}°` : "--°";
      const pVal = pelvicAngle !== null && pelvicAngle !== undefined ? `${pelvicAngle}°` : "--°";
      const kVal = kneeAngle !== null && kneeAngle !== undefined ? `${kneeAngle}°` : "--°";
      const tag = isEn ? (relTagEn || "") : (relTagZh || "");
      return isEn
        ? `Neck: ${nVal}${tag} | Pelvis: ${pVal} | Knee: ${kVal}`
        : `頭肩: ${nVal}${tag} | 骨盆: ${pVal} | 膝角: ${kVal}`;
    }

    function applyLabLang() {
      const isEn = isLabEn();
      const dict = LAB_I18N[isEn ? "en" : "zh"];

      document.querySelectorAll("[data-i18n]").forEach((el) => {
        const key = el.getAttribute("data-i18n");
        if (dict[key] && el.textContent !== dict[key]) {
          el.textContent = dict[key];
        }
      });

      const currentAngleText = angleReadout.innerText || "";
      const nMatch = currentAngleText.match(/(?:頭肩|Neck):\s*([0-9]+°|--°)/i);
      const pMatch = currentAngleText.match(/(?:骨盆|Pelvis):\s*([0-9]+°|--°)/i);
      const kMatch = currentAngleText.match(/(?:膝角|Knee):\s*([0-9]+°|--°)/i);
      const hasLowRel = currentAngleText.includes("可信度中") || currentAngleText.toLowerCase().includes("med conf");
      angleReadout.innerText = isEn
        ? `Neck: ${nMatch?.[1] || "--°"}${hasLowRel ? " (Arm offset·Med conf)" : ""} | Pelvis: ${pMatch?.[1] || "--°"} | Knee: ${kMatch?.[1] || "--°"}`
        : `頭肩: ${nMatch?.[1] || "--°"}${hasLowRel ? " (手臂偏移·可信度中)" : ""} | 骨盆: ${pMatch?.[1] || "--°"} | 膝角: ${kMatch?.[1] || "--°"}`;

      if (lastSummaryForRender) {
        renderAllIssuesAndRecommendations(lastSummaryForRender);
      }
    }

    document.getElementById("btn-toggle-lang").addEventListener("click", () => {
      setLabLang(isLabEn() ? "zh" : "en");
    });

    if (btnTriggerPhoto && photoInput) {
      btnTriggerPhoto.addEventListener("click", () => photoInput.click());
    }

    if (photoDropzone && photoInput) {
      ["dragenter", "dragover"].forEach((evt) => {
        photoDropzone.addEventListener(evt, (e) => {
          e.preventDefault();
          photoDropzone.classList.add("border-goldAccent", "bg-amber-500/10");
        });
      });

      ["dragleave", "drop"].forEach((evt) => {
        photoDropzone.addEventListener(evt, (e) => {
          e.preventDefault();
          photoDropzone.classList.remove("border-goldAccent", "bg-amber-500/10");
        });
      });

      photoDropzone.addEventListener("drop", (e) => {
        const file = e.dataTransfer?.files?.[0];
        if (file && file.type.startsWith("image/")) {
          const dt = new DataTransfer();
          dt.items.add(file);
          photoInput.files = dt.files;
          photoInput.dispatchEvent(new Event("change", { bubbles: true }));
        }
      });
    }

    async function initPoseLandmarker() {
      guideText.innerText = isLabEn() ? "Loading AI full-body skeleton model..." : "正在載入 AI 全身骨架模型...";
      const vision = await FilesetResolver.forVisionTasks(
        "https://cdn.jsdelivr.net/npm/@mediapipe/tasks-vision@0.10.14/wasm"
      );
      poseLandmarker = await PoseLandmarker.createFromOptions(vision, {
        baseOptions: {
          modelAssetPath: "https://storage.googleapis.com/mediapipe-models/pose_landmarker/pose_landmarker_lite/float16/1/pose_landmarker_lite.task",
          delegate: "GPU"
        },
        runningMode: currentRunningMode,
        numPoses: 1
      });
    }

    window.startAiCamera = async function () {
      try {
        if (!poseLandmarker) await initPoseLandmarker();
        if (currentRunningMode !== "VIDEO") {
          await poseLandmarker.setOptions({ runningMode: "VIDEO" });
          currentRunningMode = "VIDEO";
        }
        if (!webcamStream) {
          guideText.innerText = isLabEn() ? "Requesting camera permission..." : "正在請求攝影機權限...";
          webcamStream = await navigator.mediaDevices.getUserMedia({ video: true });
          video.srcObject = webcamStream;
          video.onloadeddata = () => {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            lastVideoTime = -1;
            renderLoop();
          };
        }
      } catch (err) {
        guideText.innerText = isLabEn()
          ? "⚠️ Unable to access camera. You can still use 'Choose Photo'."
          : "⚠️ 無法開啟攝影機，您仍可使用「選擇照片上傳」功能";
        console.error(err);
      }
    };

    window.stopAiCamera = function () {
      if (animationFrameId) {
        cancelAnimationFrame(animationFrameId);
        animationFrameId = null;
      }
      if (webcamStream) {
        webcamStream.getTracks().forEach((track) => track.stop());
        webcamStream = null;
        video.srcObject = null;
      }
    };

    function getAngle(a, b, c) {
      const rad = Math.atan2(c.y - b.y, c.x - b.x) - Math.atan2(a.y - b.y, a.x - b.x);
      let deg = Math.abs((rad * 180.0) / Math.PI);
      if (deg > 180.0) deg = 360 - deg;
      return Math.round(deg);
    }

    function hasValidPoint(pt, minVis = 0.25) {
      if (!pt) return false;
      const inBounds = pt.x >= 0 && pt.x <= 1 && pt.y >= 0.03 && pt.y <= 0.99;
      return inBounds && ((pt.visibility ?? 0.5) >= minVis);
    }

    function checkArmReliability(lm, isLeft) {
      const shoulder = lm[isLeft ? 11 : 12];
      const elbow = lm[isLeft ? 13 : 14];
      const wrist = lm[isLeft ? 15 : 16];

      if (!shoulder || !elbow || !wrist || (shoulder.visibility ?? 0) < 0.5) {
        return {
          reliable: false,
          reasonZh: "肩部關鍵點能見度偏低",
          reasonEn: "Shoulder landmark visibility is low."
        };
      }

      const elbowVis = (elbow.visibility ?? 0) >= 0.45;
      const wristVis = (wrist.visibility ?? 0) >= 0.45;
      const elbowAngle = (elbowVis && wristVis) ? getAngle(shoulder, elbow, wrist) : 175;
      const armRaisedHigh = elbowVis ? (elbow.y - shoulder.y) < -0.08 : false;

      if (elbowAngle < 135 || armRaisedHigh) {
        return {
          reliable: false,
          reasonZh: "偵測到手臂明顯彎曲或抬高，可能影響肩部關鍵點位置，建議雙手自然下垂再測",
          reasonEn: "Noticeable arm bending or elevation detected, which may shift shoulder landmarks. Retest with arms relaxed."
        };
      }

      return { reliable: true, reasonZh: "", reasonEn: "" };
    }

    function checkLiveFraming(lm) {
      const isEn = isLabEn();
      const visPts = lm.filter((pt) => pt && (pt.visibility ?? 0) >= 0.5);
      if (visPts.length < 10) {
        return {
          ok: false,
          msg: isEn ? "❌ Full body not detected: adjust camera so your full body is visible." : "❌ 未偵測到完整全身：請調整鏡頭角度，讓全身完整入鏡"
        };
      }
      const ys = visPts.map((pt) => pt.y);
      const bodyHeightRatio = Math.max(...ys) - Math.min(...ys);
      if (bodyHeightRatio < 0.35) {
        return {
          ok: false,
          msg: isEn ? "📏 Too far from camera: move closer (~1.5–2 m) so your body fills ~50%–75% of the frame." : "📏 距離鏡頭太遠：請往前走近約 1.5～2 公尺，讓身體佔畫面約 50%～75%"
        };
      }
      if (Math.min(...ys) < 0.03 || Math.max(...ys) > 0.97) {
        return {
          ok: false,
          msg: isEn ? "📐 Part of body is out of frame: adjust distance so head and feet stay inside." : "📐 部分身體超出畫面邊緣：請微調距離，確保頭部與雙腳完整入鏡"
        };
      }
      return { ok: true, msg: "" };
    }

    function extractPostureMetrics(lm) {
      const leftSide = [lm[7], lm[11], lm[23], lm[25], lm[27]];
      const rightSide = [lm[8], lm[12], lm[24], lm[26], lm[28]];
      const leftScore = leftSide.reduce((sum, pt) => sum + (pt?.visibility || 0), 0);
      const rightScore = rightSide.reduce((sum, pt) => sum + (pt?.visibility || 0), 0);
      const useLeft = leftScore >= rightScore;
      const chosen = useLeft ? leftSide : rightSide;

      const ear = chosen[0];
      const shoulder = chosen[1];
      const hip = chosen[2];
      const knee = chosen[3];
      const ankle = chosen[4];

      const upperCoreVisible = hasValidPoint(ear, 0.35) && hasValidPoint(shoulder, 0.35) && hasValidPoint(hip, 0.25);
      const lowerCoreVisible = upperCoreVisible && hasValidPoint(knee, 0.22);
      const ankleVisible = lowerCoreVisible && hasValidPoint(ankle, 0.2);

      const neckAngle = upperCoreVisible ? getAngle(ear, shoulder, hip) : null;
      const rawPelvicAngle = lowerCoreVisible ? getAngle(shoulder, hip, knee) : null;
      const pelvicAngle = (rawPelvicAngle !== null && rawPelvicAngle < 135) ? null : rawPelvicAngle;
      const kneeAngle = ankleVisible ? getAngle(hip, knee, ankle) : null;

      const armCheck = checkArmReliability(lm, useLeft);

      const shoulderDiff = (hasValidPoint(lm[11], 0.35) && hasValidPoint(lm[12], 0.35))
        ? Math.round(Math.abs(lm[11].y - lm[12].y) * 100)
        : 0;

      const hipDiff = (hasValidPoint(lm[23], 0.25) && hasValidPoint(lm[24], 0.25))
        ? Math.round(Math.abs(lm[23].y - lm[24].y) * 100)
        : 0;

      const forwardHeadOffset = (ear && shoulder)
        ? Math.round(Math.abs(ear.x - shoulder.x) * 100)
        : 0;

      return {
        coreVisible: upperCoreVisible,
        neckAngle,
        pelvicAngle,
        rawPelvicAngle,
        kneeAngle,
        shoulderDiff,
        hipDiff,
        forwardHeadOffset,
        neckReliable: armCheck.reliable,
        neckWarningZh: armCheck.reasonZh,
        neckWarningEn: armCheck.reasonEn
      };
    }

    function analyzeAllProblems(summary) {
      const isEn = isLabEn();
      const issues = [];
      const lowRelNote = summary.neckLowReliability
        ? (isEn
          ? " (Note: arm posture may shift shoulder landmarks; retest with arms relaxed.)"
          : "（註：手臂姿態可能影響肩部關鍵點，建議雙手自然下垂重測）")
        : "";

      if (summary.neck !== null && summary.neck < 158) {
        issues.push({
          title: isEn ? "Rounded Shoulders & Forward Head (Upper Crossed)" : "圓肩與頭部前傾（上交叉症候群）",
          badge: isEn ? "Neck/Thoracic" : "肩頸/胸椎",
          desc: isEn
            ? `Average head-shoulder angle is ${summary.neck}° (below ~158°); pectoralis major and neck muscles may be tight.${lowRelNote}`
            : `平均頭肩角為 ${summary.neck}°（低於約 158°），胸大肌與頸部肌群可能處於緊繃縮短狀態。${lowRelNote}`,
          stretchTitleZh: "胸大肌門框 PNF 拉伸",
          stretchTitleEn: STRETCH_EN_MAP["胸大肌門框 PNF 拉伸"]
        });
      }

      if (summary.pelvic !== null && summary.pelvic >= 135 && summary.pelvic < 163) {
        issues.push({
          title: isEn ? "Anterior Pelvic Tilt & Tight Hip Flexors" : "骨盆前傾與髖屈肌緊繃",
          badge: isEn ? "Hip/Pelvis" : "髖部/骨盆",
          desc: isEn
            ? `Average trunk-hip angle is ${summary.pelvic}° (135°–162° range); iliopsoas or rectus femoris may be tight.`
            : `平均軀幹髖角為 ${summary.pelvic}°（介於 135°～162°），髂腰肌或股直肌可能過度緊繃。`,
          stretchTitleZh: "髂腰肌高跪姿 PNF 伸展",
          stretchTitleEn: STRETCH_EN_MAP["髂腰肌高跪姿 PNF 伸展"]
        });
      }

      if (summary.pelvic !== null && summary.pelvic > 185) {
        issues.push({
          title: isEn ? "Posterior Pelvic Tilt & Lower Back/Glute Tension" : "骨盆後傾與下背/臀腿張力異常",
          badge: isEn ? "Lumbar/Glutes" : "腰椎/臀腿",
          desc: isEn
            ? `Average trunk-hip angle is ${summary.pelvic}° (above 185°); consider releasing lower back and glutes.`
            : `平均軀幹髖角為 ${summary.pelvic}°（高於 185°），建議放鬆下背與臀部肌群。`,
          stretchTitleZh: "臀大肌仰臥抱膝 PNF 伸展",
          stretchTitleEn: STRETCH_EN_MAP["臀大肌仰臥抱膝 PNF 伸展"]
        });
      }

      if (summary.knee !== null && summary.knee > 178) {
        issues.push({
          title: isEn ? "Knee Hyperextension (Genu Recurvatum)" : "膝關節超伸（Genu Recurvatum）",
          badge: isEn ? "Knee/Calf" : "膝關節/小腿",
          desc: isEn
            ? `Average knee angle is ${summary.knee}° (hyperextended), placing extra stress on knee and posterior calf.`
            : `平均膝關節夾角達 ${summary.knee}°（過度伸直），易造成膝蓋與小腿後側壓力。`,
          stretchTitleZh: "腓腸肌推牆直膝 PNF 伸展",
          stretchTitleEn: STRETCH_EN_MAP["腓腸肌推牆直膝 PNF 伸展"]
        });
      }

      if (summary.shoulderDiff >= 5) {
        issues.push({
          title: isEn ? "Uneven Shoulders & Lateral/Trapezius Asymmetry" : "高低肩與斜方肌/側軀幹張力不對稱",
          badge: isEn ? "L/R Symmetry" : "左右對稱",
          desc: isEn
            ? `Left-right shoulder height offset index is ${summary.shoulderDiff}; consider side-bending and upper-trap PNF stretches.`
            : `偵測到左右肩高度差指標為 ${summary.shoulderDiff}，建議針對緊繃側進行側屈與頸肩 PNF 伸展。`,
          stretchTitleZh: "斜方肌上束側傾 PNF 伸展",
          stretchTitleEn: STRETCH_EN_MAP["斜方肌上束側傾 PNF 伸展"]
        });
      }

      if (summary.hipDiff >= 5) {
        issues.push({
          title: isEn ? "Pelvic Height Asymmetry & Lateral Trunk Tightness" : "骨盆高低差與腰方肌/腹外斜肌緊繃",
          badge: isEn ? "Pelvic Symmetry" : "骨盆對稱",
          desc: isEn
            ? `Left-right hip height offset index is ${summary.hipDiff}; consider lateral trunk and hip PNF stretches.`
            : `偵測到左右髖部高度差指標為 ${summary.hipDiff}，建議進行軀幹側彎與髖部伸展。`,
          stretchTitleZh: "腹外斜肌側彎 PNF 伸展",
          stretchTitleEn: STRETCH_EN_MAP["腹外斜肌側彎 PNF 伸展"]
        });
      }

      if (issues.length === 0) {
        issues.push({
          title: isEn ? "Well-Aligned Posture (Daily Maintenance)" : "整體體態排列良好（日常維持）",
          badge: isEn ? "Good Alignment" : "狀態良好",
          desc: isEn
            ? "Your key joint angles and symmetry are within target ranges. Use PNF stretching to maintain mobility and elasticity."
            : "您的主要關節角度與對稱度皆在理想範圍內，建議透過 PNF 伸展維持關節活動度與肌肉彈性。",
          stretchTitleZh: "胸大肌門框 PNF 拉伸",
          stretchTitleEn: STRETCH_EN_MAP["胸大肌門框 PNF 拉伸"]
        });
      }

      return issues;
    }

    function avg(arr) {
      if (!arr || arr.length === 0) return null;
      return Math.round(arr.reduce((a, b) => a + b, 0) / arr.length);
    }

    function renderAllIssuesAndRecommendations(summary) {
      lastSummaryForRender = summary;
      const isEn = isLabEn();

      document.getElementById("scanning-placeholder").classList.add("hidden");
      document.getElementById("avg-neck-angle").innerText = summary.neck !== null ? `${summary.neck}°` : "--°";
      document.getElementById("avg-pelvic-angle").innerText = summary.pelvic !== null ? `${summary.pelvic}°` : "--°";
      document.getElementById("avg-knee-angle").innerText = summary.knee !== null ? `${summary.knee}°` : "--°";
      document.getElementById("assessment-result").classList.remove("hidden");

      if (summary.neckLowReliability) {
        const warnText = isEn
          ? (summary.neckWarningEn || "Bent/akimbo arms detected; shoulder landmarks may shift. Retest with arms relaxed.")
          : (summary.neckWarningZh || "偵測到手臂彎曲/叉腰，肩部關鍵點可能偏移，建議雙手自然下垂重測。");
        reliabilityBanner.className = "p-2.5 sm:p-3 rounded-xl border border-amber-500/40 bg-amber-500/10 text-amber-200 text-[11px] leading-relaxed";
        reliabilityBanner.innerHTML = `<i class="fa-solid fa-triangle-exclamation text-amber-400 mr-1"></i><strong>${isEn ? "Neck-Shoulder Confidence Note:" : "頭肩角可信度提醒："}</strong>${warnText}`;
        reliabilityBanner.classList.remove("hidden");
      } else {
        reliabilityBanner.className = "p-2.5 sm:p-3 rounded-xl border border-emerald-500/30 bg-emerald-500/10 text-emerald-300 text-[11px] leading-relaxed";
        reliabilityBanner.innerHTML = `<i class="fa-solid fa-circle-check text-emerald-400 mr-1"></i><strong>${isEn ? "High Joint Confidence:" : "關節可信度高："}</strong>${isEn ? "Arm position and key landmarks are clear and stable." : "雙手姿態與主要關節能見度良好，角度判讀穩定。"}`;
        reliabilityBanner.classList.remove("hidden");
      }

      const issues = analyzeAllProblems(summary);
      const listEl = document.getElementById("detected-issues-list");
      listEl.innerHTML = "";

      issues.forEach((item) => {
        const displayStretch = isEn ? item.stretchTitleEn : item.stretchTitleZh;
        const card = document.createElement("div");
        card.className = "p-3.5 rounded-xl border border-goldAccent/40 bg-goldAccent/10 space-y-2";
        card.innerHTML = `
          <div class="flex items-center justify-between gap-2 flex-wrap">
            <span class="text-[11px] font-bold px-2 py-0.5 rounded bg-goldAccent/20 text-goldAccent">${item.badge}</span>
            <span class="text-[11px] text-gray-300 font-semibold">${isEn ? "Recommended:" : "推薦："} ${displayStretch}</span>
          </div>
          <h3 class="text-sm font-bold text-white">${item.title}</h3>
          <p class="text-xs text-gray-300 leading-relaxed">${item.desc}</p>
          <button type="button" data-stretch="${item.stretchTitleZh}" class="btn-go-pnf-item mt-1 min-h-[40px] w-full py-2 px-3 bg-gradient-to-r from-goldAccent to-amber-600 hover:from-amber-500 hover:to-amber-700 text-black text-xs font-extrabold rounded-lg transition flex items-center justify-center gap-1.5 shadow">
            <i class="fa-solid fa-arrow-right-to-bracket"></i> <span>${isEn ? `Go to PNF (${displayStretch})` : `前往 PNF 頁面開始（${displayStretch}）`}</span>
          </button>
        `;

        card.querySelector(".btn-go-pnf-item").addEventListener("click", () => {
          localStorage.setItem("preferred_pnf_stretch", item.stretchTitleZh);
          window.location.href = "/";
        });

        listEl.appendChild(card);
      });
    }

    function drawPhotoSkeleton(lm) {
      const container = uploadedPhoto.parentElement;
      canvas.width = container.clientWidth;
      canvas.height = container.clientHeight;
      ctx.clearRect(0, 0, canvas.width, canvas.height);

      if (!uploadedPhoto.naturalWidth || !uploadedPhoto.naturalHeight || !lm) return;

      const imgRatio = uploadedPhoto.naturalWidth / uploadedPhoto.naturalHeight;
      const boxRatio = canvas.width / canvas.height;
      let drawW, drawH, offsetX, offsetY;

      if (imgRatio > boxRatio) {
        drawW = canvas.width;
        drawH = canvas.width / imgRatio;
        offsetX = 0;
        offsetY = (canvas.height - drawH) / 2;
      } else {
        drawH = canvas.height;
        drawW = canvas.height * imgRatio;
        offsetX = (canvas.width - drawW) / 2;
        offsetY = 0;
      }

      const mappedLandmarks = lm.map(pt => ({
        ...pt,
        x: (offsetX + pt.x * drawW) / canvas.width,
        y: (offsetY + pt.y * drawH) / canvas.height
      }));

      const drawingUtils = new DrawingUtils(ctx);
      drawingUtils.drawConnectors(mappedLandmarks, PoseLandmarker.POSE_CONNECTIONS, { color: "#10B981", lineWidth: 3 });
      drawingUtils.drawLandmarks(mappedLandmarks, { color: "#F43F5E", radius: 4 });
    }

    if (typeof ResizeObserver !== "undefined") {
      const ro = new ResizeObserver(() => {
        if (!uploadedPhoto.classList.contains("hidden") && lastPhotoLandmarks) {
          drawPhotoSkeleton(lastPhotoLandmarks);
        }
      });
      ro.observe(document.getElementById("lab-media-container"));
    }

    function renderLoop() {
      if (!webcamStream) return;
      const drawingUtils = new DrawingUtils(ctx);
      const isEn = isLabEn();

      if (lastVideoTime !== video.currentTime) {
        lastVideoTime = video.currentTime;
        const results = poseLandmarker.detectForVideo(video, performance.now());
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        if (results.landmarks && results.landmarks.length > 0) {
          const lm = results.landmarks[0];
          const framing = checkLiveFraming(lm);
          const metrics = extractPostureMetrics(lm);

          drawingUtils.drawConnectors(lm, PoseLandmarker.POSE_CONNECTIONS, {
            color: framing.ok ? "#6366F1" : "#F59E0B",
            lineWidth: 4
          });
          drawingUtils.drawLandmarks(lm, { color: "#F43F5E", radius: 5 });

          if (!framing.ok) {
            if (currentMode === "ASSESSMENT") {
              scanStartTime = null;
              samples = { neck: [], pelvic: [], knee: [], shoulderDiff: [], hipDiff: [], forwardHeadOffset: [], lowConfidenceCount: 0, totalCount: 0 };
              progressContainer.classList.add("hidden");
              angleReadout.innerText = formatAngleReadout(null, null, null);
              guideText.innerText = framing.msg;
            }
          } else if (metrics.coreVisible) {
            angleReadout.innerText = formatAngleReadout(
              metrics.neckAngle,
              metrics.pelvicAngle,
              metrics.kneeAngle,
              metrics.neckReliable ? "" : " (手臂偏移·可信度中)",
              metrics.neckReliable ? "" : " (Arm offset·Med conf)"
            );

            if (currentMode === "ASSESSMENT") {
              handlePreSessionAssessment(metrics);
            }
          } else if (currentMode === "ASSESSMENT") {
            scanStartTime = null;
            samples = { neck: [], pelvic: [], knee: [], shoulderDiff: [], hipDiff: [], forwardHeadOffset: [], lowConfidenceCount: 0, totalCount: 0 };
            progressContainer.classList.add("hidden");
            guideText.innerText = isEn
              ? "⚠️ Stand before camera with head, shoulders, hips, and knees in frame."
              : "⚠️ 請站立於鏡頭前，確保頭、肩、髖、膝完整入鏡";
          }
        } else if (currentMode === "ASSESSMENT") {
          scanStartTime = null;
          samples = { neck: [], pelvic: [], knee: [], shoulderDiff: [], hipDiff: [], forwardHeadOffset: [], lowConfidenceCount: 0, totalCount: 0 };
          progressContainer.classList.add("hidden");
          angleReadout.innerText = formatAngleReadout(null, null, null);
          guideText.innerText = isEn
            ? "❌ No body detected: step into camera view with good lighting and distance."
            : "❌ 未偵測到全身：請站入鏡頭畫面內，並保持適當距離與光線";
        }
      }
      animationFrameId = requestAnimationFrame(renderLoop);
    }

    function handlePreSessionAssessment(metrics) {
      const isEn = isLabEn();
      progressContainer.classList.remove("hidden");
      if (!scanStartTime) scanStartTime = performance.now();

      const elapsed = performance.now() - scanStartTime;
      const pct = Math.min(100, (elapsed / SCAN_DURATION_MS) * 100);
      scanProgress.style.width = `${pct}%`;

      const armHint = metrics.neckReliable
        ? ""
        : (isEn ? " · Bent/akimbo arms detected; keep arms relaxed." : " · 偵測到手臂彎曲/叉腰，建議雙手自然下垂");
      const secLeft = Math.ceil((SCAN_DURATION_MS - elapsed) / 1000);
      guideText.innerText = isEn
        ? `Scanning full-body posture... (${secLeft}s)${armHint}`
        : `正在進行全身多維度體態採樣... (${secLeft}s)${armHint}`;

      if (metrics.neckAngle !== null) samples.neck.push(metrics.neckAngle);
      if (metrics.pelvicAngle !== null) samples.pelvic.push(metrics.pelvicAngle);
      if (metrics.kneeAngle !== null) samples.knee.push(metrics.kneeAngle);
      samples.shoulderDiff.push(metrics.shoulderDiff);
      samples.hipDiff.push(metrics.hipDiff);
      samples.forwardHeadOffset.push(metrics.forwardHeadOffset);
      samples.totalCount += 1;
      if (!metrics.neckReliable) samples.lowConfidenceCount += 1;

      if (elapsed >= SCAN_DURATION_MS) {
        currentMode = "COMPLETED";
        progressContainer.classList.add("hidden");
        guideText.innerText = isEn
          ? "✅ Posture scan complete! Choose a prescription on the right to start PNF."
          : "✅ 全身問題識別完成！請選擇右側處方前往 PNF 頁面";

        const lowRel = samples.totalCount > 0 && (samples.lowConfidenceCount / samples.totalCount) >= 0.35;

        renderAllIssuesAndRecommendations({
          neck: avg(samples.neck),
          pelvic: avg(samples.pelvic),
          knee: avg(samples.knee),
          shoulderDiff: avg(samples.shoulderDiff),
          hipDiff: avg(samples.hipDiff),
          forwardHeadOffset: avg(samples.forwardHeadOffset),
          neckLowReliability: lowRel,
          neckWarningZh: lowRel ? "採樣期間偵測到手臂彎曲/叉腰或肩肘關鍵點偏移，頭肩角可能偏低，建議雙手自然下垂重測。" : "",
          neckWarningEn: lowRel ? "Bent/akimbo arms or shoulder landmark shift detected during scan; neck-shoulder angle may be lower. Retest with arms relaxed." : ""
        });
      }
    }

    photoInput.addEventListener("change", async (event) => {
      const file = event.target.files[0];
      if (!file) return;
      const isEn = isLabEn();

      window.stopAiCamera();
      video.classList.add("hidden");
      uploadedPhoto.classList.remove("hidden");
      progressContainer.classList.add("hidden");

      canvas.classList.remove("-scale-x-100");
      btnSourceCam.className = "min-h-[36px] px-3 py-1.5 rounded-lg text-xs font-bold bg-gray-800 hover:bg-gray-700 text-gray-300 border border-gray-700 transition inline-flex items-center gap-1.5";
      btnSourcePhoto.className = "min-h-[36px] cursor-pointer px-3 py-1.5 rounded-lg text-xs font-bold bg-indigo-600 text-white transition inline-flex items-center gap-1.5";

      const imageUrl = URL.createObjectURL(file);
      uploadedPhoto.src = imageUrl;

      uploadedPhoto.onload = async () => {
        guideText.innerText = isLabEn() ? "Analyzing full-body posture in photo..." : "正在使用 AI 分析照片全身體態...";
        if (!poseLandmarker) await initPoseLandmarker();

        if (currentRunningMode !== "IMAGE") {
          await poseLandmarker.setOptions({ runningMode: "IMAGE" });
          currentRunningMode = "IMAGE";
        }

        const results = poseLandmarker.detect(uploadedPhoto);

        if (results.landmarks && results.landmarks.length > 0) {
          const lm = results.landmarks[0];
          lastPhotoLandmarks = lm;
          drawPhotoSkeleton(lm);

          const metrics = extractPostureMetrics(lm);

          if (metrics.coreVisible) {
            angleReadout.innerText = formatAngleReadout(
              metrics.neckAngle,
              metrics.pelvicAngle,
              metrics.kneeAngle,
              metrics.neckReliable ? "" : " (手臂偏移·可信度中)",
              metrics.neckReliable ? "" : " (Arm offset·Med conf)"
            );
            guideText.innerText = metrics.neckReliable
              ? (isLabEn() ? "✅ Photo posture scan complete! Check prescriptions on the right." : "✅ 照片全身問題識別完成！請查看右側處方清單")
              : (isLabEn() ? "⚠️ Photo analyzed (bent/akimbo arms detected; medium neck-shoulder confidence)." : "⚠️ 照片辨識完成（偵測到手臂彎曲/叉腰，頭肩角可信度為中，建議參考右側說明）");
            renderAllIssuesAndRecommendations({
              neck: metrics.neckAngle,
              pelvic: metrics.pelvicAngle,
              knee: metrics.kneeAngle,
              shoulderDiff: metrics.shoulderDiff,
              hipDiff: metrics.hipDiff,
              forwardHeadOffset: metrics.forwardHeadOffset,
              neckLowReliability: !metrics.neckReliable,
              neckWarningZh: metrics.neckWarningZh,
              neckWarningEn: metrics.neckWarningEn
            });
          } else {
            guideText.innerText = isEn
              ? "⚠️ Unable to detect key joints clearly. Upload a full-body photo showing head, shoulders, hips, and knees."
              : "⚠️ 無法完整辨識主要關節，請上傳包含頭、肩、髖、膝的清晰全身照";
          }
        } else {
          lastPhotoLandmarks = null;
          ctx.clearRect(0, 0, canvas.width, canvas.height);
          guideText.innerText = isEn
            ? "❌ No human pose detected in photo. Try a well-lit full-body photo."
            : "❌ 照片中未偵測到人體骨架，請更換光線充足的全身照重試";
        }

        URL.revokeObjectURL(imageUrl);
        photoInput.value = "";
      };
    });

    async function switchToCameraMode() {
      lastPhotoLandmarks = null;
      lastSummaryForRender = null;
      uploadedPhoto.classList.add("hidden");
      video.classList.remove("hidden");
      canvas.classList.add("-scale-x-100");
      btnSourceCam.className = "min-h-[36px] px-3 py-1.5 rounded-lg text-xs font-bold bg-indigo-600 text-white transition inline-flex items-center gap-1.5";
      btnSourcePhoto.className = "min-h-[36px] cursor-pointer px-3 py-1.5 rounded-lg text-xs font-bold bg-gray-800 hover:bg-gray-700 text-gray-300 border border-gray-700 transition inline-flex items-center gap-1.5";

      currentMode = "ASSESSMENT";
      scanStartTime = null;
      samples = { neck: [], pelvic: [], knee: [], shoulderDiff: [], hipDiff: [], forwardHeadOffset: [], lowConfidenceCount: 0, totalCount: 0 };
      document.getElementById("mode-badge").innerText = LAB_I18N[isLabEn() ? "en" : "zh"]["lab.mode_badge"];
      document.getElementById("scanning-placeholder").classList.remove("hidden");
      document.getElementById("assessment-result").classList.add("hidden");
      reliabilityBanner.classList.add("hidden");
      angleReadout.innerText = formatAngleReadout(null, null, null);

      await window.startAiCamera();
    }

    btnSourceCam.addEventListener("click", switchToCameraMode);
    document.getElementById("btn-reset").addEventListener("click", switchToCameraMode);

    applyLabLang();
    window.startAiCamera();
  </script>
</body>
</html>