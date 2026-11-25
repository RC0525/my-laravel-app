<!DOCTYPE html>
<html lang="zh-Hant">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>PNF Posture Lab - 全身多維度 AI 體態快篩與處方推薦</title>
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
        <h1 class="text-sm sm:text-base font-bold text-white truncate">PNF Posture Lab · 全身 AI 體態實驗室</h1>
        <p class="text-[11px] sm:text-xs text-gray-400 leading-snug sm:truncate">多維度關節角度快篩（頭頸、肩胸、骨盆、膝關節、左右對稱度）與 PNF 處方推薦</p>
      </div>
    </div>
    <a href="/" class="self-start sm:self-auto shrink-0 min-h-[38px] px-3.5 py-2 rounded-xl bg-cardDark hover:bg-gray-800 border border-gray-700 text-xs font-semibold text-gray-300 transition inline-flex items-center gap-2">
      <i class="fa-solid fa-arrow-left"></i> 返回主訓練系統
    </a>
  </div>

  <!-- 主版面：手機 1 欄、iPad 橫向與桌面 3 欄 -->
  <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 text-white">
  
    <!-- 左側：即時 AI 視覺辨識與照片上傳區 -->
    <div class="lg:col-span-2 bg-cardDark p-3 sm:p-4 rounded-2xl border border-gray-800 min-w-0">
      <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 mb-3">
        <!-- 來源切換控制列 -->
        <div class="flex flex-wrap items-center gap-2">
          <button id="btn-source-cam" type="button" class="min-h-[36px] px-3 py-1.5 rounded-lg text-xs font-bold bg-indigo-600 text-white transition inline-flex items-center gap-1.5">
            <i class="fa-solid fa-video"></i> 即時攝影機
          </button>
          <label for="photo-upload" id="btn-source-photo" class="min-h-[36px] cursor-pointer px-3 py-1.5 rounded-lg text-xs font-bold bg-gray-800 hover:bg-gray-700 text-gray-300 border border-gray-700 transition inline-flex items-center gap-1.5">
            <i class="fa-solid fa-image"></i> 上傳全身照片檢測
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
          <p id="guide-text" class="text-xs sm:text-sm font-bold">正在初始化 AI 視覺引擎...</p>
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
            <p class="text-xs font-bold text-white">上傳全身姿勢照片進行 AI 檢測</p>
            <p class="text-[11px] text-gray-400 leading-snug">支援 JPG / PNG / WEBP；建議雙手自然下垂，可直接點擊按鈕或拖曳照片至此</p>
          </div>
        </div>
        <button type="button" id="btn-trigger-photo-upload" class="shrink-0 w-full sm:w-auto min-h-[38px] px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow">
          <i class="fa-solid fa-folder-open"></i> 選擇照片上傳
        </button>
      </div>
    </div>

    <!-- 右側：多維度診斷結果、全部問題清單與 PNF 頁面導流 -->
    <div class="bg-cardDark p-4 sm:p-6 rounded-2xl border border-gray-800 flex flex-col justify-between min-w-0">
      <div>
        <div class="flex items-center justify-between gap-2 mb-3 sm:mb-4">
          <h2 class="text-base sm:text-lg font-bold flex items-center gap-2">
            <i class="fa-solid fa-robot text-indigo-400"></i> AI 全身姿態診斷
          </h2>
          <span id="mode-badge" class="shrink-0 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-500/20 text-amber-400 border border-amber-500/30">
            全身快篩
          </span>
        </div>

        <!-- 預設提示說明 -->
        <div id="scanning-placeholder" class="p-3.5 sm:p-4 rounded-xl bg-gray-900/60 border border-gray-800 text-xs text-gray-400 leading-relaxed mb-4 space-y-2">
          <p><strong class="text-gray-200">方式 1（即時鏡頭）：</strong>全身站立於鏡頭前 1.5～2.5 公尺處，雙手自然下垂，系統將自動進行 5 秒多維度採樣。</p>
          <p><strong class="text-gray-200">方式 2（上傳照片）：</strong>使用上方或下方「選擇照片上傳」／拖放區上傳包含頭、肩、髖、膝、踝的全身照，系統將自動識別所有潛在體態問題。</p>
        </div>

        <!-- 快篩結果與全部問題識別卡片 -->
        <div id="assessment-result" class="hidden space-y-3 sm:space-y-4">
          <!-- 多維度量化數據 -->
          <div class="p-3 sm:p-3.5 rounded-xl bg-gray-900/80 border border-gray-800 grid grid-cols-3 gap-2 text-center">
            <div>
              <p class="text-[10px] text-gray-400">頭肩夾角</p>
              <p id="avg-neck-angle" class="text-xs sm:text-sm font-bold text-white mt-0.5">--°</p>
            </div>
            <div>
              <p class="text-[10px] text-gray-400">骨盆髖角</p>
              <p id="avg-pelvic-angle" class="text-xs sm:text-sm font-bold text-white mt-0.5">--°</p>
            </div>
            <div>
              <p class="text-[10px] text-gray-400">膝關節角</p>
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
        重置並重新檢測
      </button>
    </div>
  </div>

  <script type="module">
    import { PoseLandmarker, FilesetResolver, DrawingUtils } from "https://cdn.jsdelivr.net/npm/@mediapipe/tasks-vision@0.10.14";

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
      guideText.innerText = "正在載入 AI 全身骨架模型...";
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
          guideText.innerText = "正在請求攝影機權限...";
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
        guideText.innerText = "⚠️ 無法開啟攝影機，您仍可使用「選擇照片上傳」功能";
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

    // 評估手臂是否彎曲/叉腰導致肩肘關鍵點偏移（影響頭肩角）
    function checkArmReliability(lm, isLeft) {
      const shoulder = lm[isLeft ? 11 : 12];
      const elbow = lm[isLeft ? 13 : 14];
      const wrist = lm[isLeft ? 15 : 16];
      const hip = lm[isLeft ? 23 : 24];

      if (!shoulder || !elbow || !wrist || (shoulder.visibility ?? 0) < 0.55) {
        return { reliable: false, reason: "肩部關鍵點能見度偏低" };
      }

      const elbowVis = (elbow.visibility ?? 0) >= 0.5;
      const wristVis = (wrist.visibility ?? 0) >= 0.5;
      const elbowAngle = (elbowVis && wristVis) ? getAngle(shoulder, elbow, wrist) : 180;
      const wristNearHip = (hip && wristVis) ? Math.hypot(wrist.x - hip.x, wrist.y - hip.y) < 0.16 : false;
      const armRaised = elbowVis ? (elbow.y - shoulder.y) < -0.04 : false;

      if (!elbowVis || !wristVis || elbowAngle < 155 || wristNearHip || armRaised) {
        return {
          reliable: false,
          reason: "手臂彎曲/叉腰或手部遮擋可能使肩部關鍵點偏移，導致頭肩角偏低（如 ~126°）"
        };
      }

      return { reliable: true, reason: "" };
    }

    // 檢查即時鏡頭全身入鏡與距離狀態
    function checkLiveFraming(lm) {
      const visPts = lm.filter((pt) => pt && (pt.visibility ?? 0) >= 0.5);
      if (visPts.length < 10) {
        return { ok: false, msg: "❌ 未偵測到完整全身：請調整鏡頭角度，讓全身完整入鏡" };
      }
      const ys = visPts.map((pt) => pt.y);
      const bodyHeightRatio = Math.max(...ys) - Math.min(...ys);
      if (bodyHeightRatio < 0.35) {
        return { ok: false, msg: "📏 距離鏡頭太遠：請往前走近約 1.5～2 公尺，讓身體佔畫面約 50%～75%" };
      }
      if (Math.min(...ys) < 0.03 || Math.max(...ys) > 0.97) {
        return { ok: false, msg: "📐 部分身體超出畫面邊緣：請微調距離，確保頭部與雙腳完整入鏡" };
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

      const coreVisible = chosen.slice(0, 4).every((pt) => pt && pt.visibility > 0.55);
      const ankleVisible = chosen[4] && chosen[4].visibility > 0.5;

      const ear = chosen[0];
      const shoulder = chosen[1];
      const hip = chosen[2];
      const knee = chosen[3];
      const ankle = chosen[4];

      const neckAngle = coreVisible ? getAngle(ear, shoulder, hip) : null;
      const pelvicAngle = coreVisible ? getAngle(shoulder, hip, knee) : null;
      const kneeAngle = (coreVisible && ankleVisible) ? getAngle(hip, knee, ankle) : null;

      const armCheck = checkArmReliability(lm, useLeft);

      const shoulderDiff = (lm[11] && lm[12] && lm[11].visibility > 0.5 && lm[12].visibility > 0.5)
        ? Math.round(Math.abs(lm[11].y - lm[12].y) * 100)
        : 0;

      const hipDiff = (lm[23] && lm[24] && lm[23].visibility > 0.5 && lm[24].visibility > 0.5)
        ? Math.round(Math.abs(lm[23].y - lm[24].y) * 100)
        : 0;

      const forwardHeadOffset = (ear && shoulder)
        ? Math.round(Math.abs(ear.x - shoulder.x) * 100)
        : 0;

      return {
        coreVisible,
        neckAngle,
        pelvicAngle,
        kneeAngle,
        shoulderDiff,
        hipDiff,
        forwardHeadOffset,
        neckReliable: armCheck.reliable,
        neckWarning: armCheck.reason
      };
    }

    function analyzeAllProblems(summary) {
      const issues = [];
      const lowRelNote = summary.neckLowReliability
        ? "（註：偵測到手臂彎曲/叉腰或關節偏移，頭肩角可能偏低，建議雙手自然下垂重測以確認）"
        : "";

      if (summary.neck !== null && summary.neck < 162) {
        issues.push({
          title: "圓肩與頭部前傾（上交叉症候群）",
          badge: summary.neckLowReliability ? "肩頸/胸椎 · 中可信度" : "肩頸/胸椎",
          desc: `平均頭肩角為 ${summary.neck}°（標準參考約 162°），胸大肌與頸部肌群可能處於緊繃縮短狀態。${lowRelNote}`,
          stretchTitle: "胸大肌門框 PNF 拉伸"
        });
      }

      if (summary.pelvic !== null && summary.pelvic < 165) {
        issues.push({
          title: "骨盆前傾與髖屈肌緊繃",
          badge: "髖部/骨盆",
          desc: `平均軀幹髖角為 ${summary.pelvic}°（低於標準 165°），髂腰肌或股直肌可能過度緊繃。`,
          stretchTitle: "髂腰肌高跪姿 PNF 伸展"
        });
      }

      if (summary.pelvic !== null && summary.pelvic > 183) {
        issues.push({
          title: "骨盆後傾與下背/臀腿張力異常",
          badge: "腰椎/臀腿",
          desc: `平均軀幹髖角為 ${summary.pelvic}°（高於標準 183°），建議放鬆下背與臀部肌群。`,
          stretchTitle: "臀大肌仰臥抱膝 PNF 伸展"
        });
      }

      if (summary.knee !== null && summary.knee > 178) {
        issues.push({
          title: "膝關節超伸（Genu Recurvatum）",
          badge: "膝關節/小腿",
          desc: `平均膝關節夾角達 ${summary.knee}°（過度伸直），易造成膝蓋與小腿後側壓力。`,
          stretchTitle: "腓腸肌推牆直膝 PNF 伸展"
        });
      }

      if (summary.shoulderDiff >= 4) {
        issues.push({
          title: "高低肩與斜方肌/側軀幹張力不對稱",
          badge: "左右對稱",
          desc: `偵測到左右肩高度差指標為 ${summary.shoulderDiff}，建議針對緊繃側進行側屈與頸肩 PNF 伸展。`,
          stretchTitle: "斜方肌上束側傾 PNF 伸展"
        });
      }

      if (summary.hipDiff >= 4) {
        issues.push({
          title: "骨盆高低差與腰方肌/腹外斜肌緊繃",
          badge: "骨盆對稱",
          desc: `偵測到左右髖部高度差指標為 ${summary.hipDiff}，建議進行軀幹側彎與髖部伸展。`,
          stretchTitle: "腹外斜肌側彎 PNF 伸展"
        });
      }

      if (issues.length === 0) {
        issues.push({
          title: "整體體態排列良好（日常維持）",
          badge: "狀態良好",
          desc: "您的主要關節角度與對稱度皆在理想範圍內，建議透過 PNF 伸展維持關節活動度與肌肉彈性。",
          stretchTitle: "胸大肌門框 PNF 拉伸"
        });
      }

      return issues;
    }

    function avg(arr) {
      if (!arr || arr.length === 0) return null;
      return Math.round(arr.reduce((a, b) => a + b, 0) / arr.length);
    }

    function renderAllIssuesAndRecommendations(summary) {
      document.getElementById("scanning-placeholder").classList.add("hidden");
      document.getElementById("avg-neck-angle").innerText = summary.neck !== null ? `${summary.neck}°` : "--°";
      document.getElementById("avg-pelvic-angle").innerText = summary.pelvic !== null ? `${summary.pelvic}°` : "--°";
      document.getElementById("avg-knee-angle").innerText = summary.knee !== null ? `${summary.knee}°` : "--°";
      document.getElementById("assessment-result").classList.remove("hidden");

      if (summary.neckLowReliability) {
        reliabilityBanner.className = "p-2.5 sm:p-3 rounded-xl border border-amber-500/40 bg-amber-500/10 text-amber-200 text-[11px] leading-relaxed";
        reliabilityBanner.innerHTML = `<i class="fa-solid fa-triangle-exclamation text-amber-400 mr-1"></i><strong>頭肩角可信度提醒：</strong>${summary.neckWarning || "偵測到手臂彎曲/叉腰，肩部關鍵點可能偏移，建議雙手自然下垂重測。"}`;
        reliabilityBanner.classList.remove("hidden");
      } else {
        reliabilityBanner.className = "p-2.5 sm:p-3 rounded-xl border border-emerald-500/30 bg-emerald-500/10 text-emerald-300 text-[11px] leading-relaxed";
        reliabilityBanner.innerHTML = `<i class="fa-solid fa-circle-check text-emerald-400 mr-1"></i><strong>關節可信度高：</strong>雙手姿態與主要關節能見度良好，角度判讀穩定。`;
        reliabilityBanner.classList.remove("hidden");
      }

      const issues = analyzeAllProblems(summary);
      const listEl = document.getElementById("detected-issues-list");
      listEl.innerHTML = "";

      issues.forEach((item) => {
        const card = document.createElement("div");
        card.className = "p-3.5 rounded-xl border border-goldAccent/40 bg-goldAccent/10 space-y-2";
        card.innerHTML = `
          <div class="flex items-center justify-between gap-2 flex-wrap">
            <span class="text-[11px] font-bold px-2 py-0.5 rounded bg-goldAccent/20 text-goldAccent">${item.badge}</span>
            <span class="text-[11px] text-gray-300 font-semibold">推薦：${item.stretchTitle}</span>
          </div>
          <h3 class="text-sm font-bold text-white">${item.title}</h3>
          <p class="text-xs text-gray-300 leading-relaxed">${item.desc}</p>
          <button type="button" data-stretch="${item.stretchTitle}" class="btn-go-pnf-item mt-1 min-h-[40px] w-full py-2 px-3 bg-gradient-to-r from-goldAccent to-amber-600 hover:from-amber-500 hover:to-amber-700 text-black text-xs font-extrabold rounded-lg transition flex items-center justify-center gap-1.5 shadow">
            <i class="fa-solid fa-arrow-right-to-bracket"></i> <span>前往 PNF 頁面開始（${item.stretchTitle}）</span>
          </button>
        `;

        card.querySelector(".btn-go-pnf-item").addEventListener("click", () => {
          localStorage.setItem("preferred_pnf_stretch", item.stretchTitle);
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
              angleReadout.innerText = "頭肩: --° | 骨盆: --° | 膝角: --°";
              guideText.innerText = framing.msg;
            }
          } else if (metrics.coreVisible) {
            const relTag = metrics.neckReliable ? "" : " (手臂偏移·可信度中)";
            angleReadout.innerText = `頭肩: ${metrics.neckAngle}°${relTag} | 骨盆: ${metrics.pelvicAngle}° | 膝角: ${metrics.kneeAngle ?? "--"}°`;

            if (currentMode === "ASSESSMENT") {
              handlePreSessionAssessment(metrics);
            }
          } else if (currentMode === "ASSESSMENT") {
            scanStartTime = null;
            samples = { neck: [], pelvic: [], knee: [], shoulderDiff: [], hipDiff: [], forwardHeadOffset: [], lowConfidenceCount: 0, totalCount: 0 };
            progressContainer.classList.add("hidden");
            guideText.innerText = "⚠️ 請站立於鏡頭前，確保頭、肩、髖、膝完整入鏡";
          }
        } else if (currentMode === "ASSESSMENT") {
          scanStartTime = null;
          samples = { neck: [], pelvic: [], knee: [], shoulderDiff: [], hipDiff: [], forwardHeadOffset: [], lowConfidenceCount: 0, totalCount: 0 };
          progressContainer.classList.add("hidden");
          angleReadout.innerText = "頭肩: --° | 骨盆: --° | 膝角: --°";
          guideText.innerText = "❌ 未偵測到全身：請站入鏡頭畫面內，並保持適當距離與光線";
        }
      }
      animationFrameId = requestAnimationFrame(renderLoop);
    }

    function handlePreSessionAssessment(metrics) {
      progressContainer.classList.remove("hidden");
      if (!scanStartTime) scanStartTime = performance.now();

      const elapsed = performance.now() - scanStartTime;
      const pct = Math.min(100, (elapsed / SCAN_DURATION_MS) * 100);
      scanProgress.style.width = `${pct}%`;

      const armHint = metrics.neckReliable ? "" : " · 偵測到手臂彎曲/叉腰，建議雙手自然下垂";
      guideText.innerText = `正在進行全身多維度體態採樣... (${Math.ceil((SCAN_DURATION_MS - elapsed) / 1000)}s)${armHint}`;

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
        guideText.innerText = "✅ 全身問題識別完成！請選擇右側處方前往 PNF 頁面";

        const lowRel = samples.totalCount > 0 && (samples.lowConfidenceCount / samples.totalCount) >= 0.35;

        renderAllIssuesAndRecommendations({
          neck: avg(samples.neck),
          pelvic: avg(samples.pelvic),
          knee: avg(samples.knee),
          shoulderDiff: avg(samples.shoulderDiff),
          hipDiff: avg(samples.hipDiff),
          forwardHeadOffset: avg(samples.forwardHeadOffset),
          neckLowReliability: lowRel,
          neckWarning: lowRel ? "採樣期間偵測到手臂彎曲/叉腰或肩肘關鍵點偏移，頭肩角可能偏低，建議雙手自然下垂重測。" : ""
        });
      }
    }

    photoInput.addEventListener("change", async (event) => {
      const file = event.target.files[0];
      if (!file) return;

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
        guideText.innerText = "正在使用 AI 分析照片全身體態...";
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
            const relTag = metrics.neckReliable ? "" : " (手臂偏移·可信度中)";
            angleReadout.innerText = `頭肩: ${metrics.neckAngle}°${relTag} | 骨盆: ${metrics.pelvicAngle}° | 膝角: ${metrics.kneeAngle ?? "--"}°`;
            guideText.innerText = metrics.neckReliable
              ? "✅ 照片全身問題識別完成！請查看右側處方清單"
              : "⚠️ 照片辨識完成（偵測到手臂彎曲/叉腰，頭肩角可信度為中，建議參考右側說明）";
            renderAllIssuesAndRecommendations({
              neck: metrics.neckAngle,
              pelvic: metrics.pelvicAngle,
              knee: metrics.kneeAngle,
              shoulderDiff: metrics.shoulderDiff,
              hipDiff: metrics.hipDiff,
              forwardHeadOffset: metrics.forwardHeadOffset,
              neckLowReliability: !metrics.neckReliable,
              neckWarning: metrics.neckWarning
            });
          } else {
            guideText.innerText = "⚠️ 無法完整辨識主要關節，請上傳包含頭、肩、髖、膝的清晰全身照";
          }
        } else {
          lastPhotoLandmarks = null;
          ctx.clearRect(0, 0, canvas.width, canvas.height);
          guideText.innerText = "❌ 照片中未偵測到人體骨架，請更換光線充足的全身照重試";
        }

        URL.revokeObjectURL(imageUrl);
        photoInput.value = "";
      };
    });

    async function switchToCameraMode() {
      lastPhotoLandmarks = null;
      uploadedPhoto.classList.add("hidden");
      video.classList.remove("hidden");
      canvas.classList.add("-scale-x-100");
      btnSourceCam.className = "min-h-[36px] px-3 py-1.5 rounded-lg text-xs font-bold bg-indigo-600 text-white transition inline-flex items-center gap-1.5";
      btnSourcePhoto.className = "min-h-[36px] cursor-pointer px-3 py-1.5 rounded-lg text-xs font-bold bg-gray-800 hover:bg-gray-700 text-gray-300 border border-gray-700 transition inline-flex items-center gap-1.5";

      currentMode = "ASSESSMENT";
      scanStartTime = null;
      samples = { neck: [], pelvic: [], knee: [], shoulderDiff: [], hipDiff: [], forwardHeadOffset: [], lowConfidenceCount: 0, totalCount: 0 };
      document.getElementById("mode-badge").innerText = "全身快篩";
      document.getElementById("scanning-placeholder").classList.remove("hidden");
      document.getElementById("assessment-result").classList.add("hidden");
      reliabilityBanner.classList.add("hidden");

      await window.startAiCamera();
    }

    btnSourceCam.addEventListener("click", switchToCameraMode);
    document.getElementById("btn-reset").addEventListener("click", switchToCameraMode);

    window.startAiCamera();
  </script>
</body>
</html>