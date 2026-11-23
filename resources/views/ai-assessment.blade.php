<!DOCTYPE html>
<html lang="zh-Hant">
<head>
  <meta charset="UTF-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>PNF Posture Lab - AI 體態快篩與處方推薦</title>
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
</head>
<body class="bg-[#0B0D14] text-white p-6 min-h-screen">

  <!-- 頂部導覽列 -->
  <div class="max-w-5xl mx-auto mb-6 flex justify-between items-center border-b border-gray-800 pb-4">
    <div class="flex items-center gap-3">
      <div class="w-9 h-9 rounded-xl bg-goldAccent/20 border border-goldAccent/40 flex items-center justify-center text-goldAccent">
        <i class="fa-solid fa-person-rays"></i>
      </div>
      <div>
        <h1 class="text-base font-bold text-white">PNF Posture Lab · AI 體態實驗室</h1>
        <p class="text-xs text-gray-400">支援即時攝影機快篩與靜態側面照片分析</p>
      </div>
    </div>
    <a href="/" class="px-3.5 py-2 rounded-xl bg-cardDark hover:bg-gray-800 border border-gray-700 text-xs font-semibold text-gray-300 transition flex items-center gap-2">
      <i class="fa-solid fa-arrow-left"></i> 返回主訓練系統
    </a>
  </div>

  <div class="max-w-5xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-6 text-white">
  
    <!-- 左側：即時 AI 視覺辨識與照片上傳區 -->
    <div class="lg:col-span-2 bg-cardDark p-4 rounded-2xl border border-gray-800">
      <div class="flex flex-wrap justify-between items-center gap-2 mb-3">
        <!-- 來源切換控制列 -->
        <div class="flex items-center gap-2">
          <button id="btn-source-cam" class="px-3 py-1.5 rounded-lg text-xs font-bold bg-indigo-600 text-white transition flex items-center gap-1.5">
            <i class="fa-solid fa-video"></i> 即時攝影機
          </button>
          <label for="photo-upload" id="btn-source-photo" class="cursor-pointer px-3 py-1.5 rounded-lg text-xs font-bold bg-gray-800 hover:bg-gray-700 text-gray-300 border border-gray-700 transition flex items-center gap-1.5">
            <i class="fa-solid fa-image"></i> 上傳側面照片檢測
          </label>
          <input type="file" id="photo-upload" accept="image/*" class="hidden">
        </div>

        <span id="angle-readout" class="font-mono text-xs text-gray-400">頭肩角: --° | 骨盆角: --°</span>
      </div>

      <div class="relative w-full aspect-video bg-black rounded-xl overflow-hidden border border-gray-800 flex items-center justify-center">
        <!-- 即時攝影機畫面 -->
        <video id="webcam" autoplay playsinline class="w-full h-full object-cover -scale-x-100"></video>
        
        <!-- 靜態上傳照片預覽 -->
        <img id="uploaded-photo" class="hidden max-w-full max-h-full object-contain" alt="Uploaded Posture">
        
        <!-- 骨架繪製畫布 -->
        <canvas id="output_canvas" class="absolute inset-0 w-full h-full pointer-events-none -scale-x-100"></canvas>
        
        <!-- 畫面底部引導與進度條 -->
        <div id="overlay-banner" class="absolute bottom-4 left-4 right-4 bg-black/80 backdrop-blur-md border border-gray-700 text-white p-3.5 rounded-xl text-center">
          <p id="guide-text" class="text-sm font-bold">正在初始化 AI 視覺引擎...</p>
          <div id="progress-bar-container" class="w-full bg-gray-800 h-2 rounded-full mt-2 hidden">
            <div id="scan-progress" class="bg-emerald-400 h-2 rounded-full transition-all duration-100" style="width: 0%"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- 右側：診斷結果、問題識別與 PNF 頁面導流 -->
    <div class="bg-cardDark p-6 rounded-2xl border border-gray-800 flex flex-col justify-between">
      <div>
        <div class="flex items-center justify-between mb-4">
          <h2 class="text-lg font-bold flex items-center gap-2">
            <i class="fa-solid fa-robot text-indigo-400"></i> AI 體態分析控制台
          </h2>
          <span id="mode-badge" class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-500/20 text-amber-400 border border-amber-500/30">
            體態快篩
          </span>
        </div>

        <!-- 預設提示說明 -->
        <div id="scanning-placeholder" class="p-4 rounded-xl bg-gray-900/60 border border-gray-800 text-xs text-gray-400 leading-relaxed mb-4 space-y-2">
          <p><strong class="text-gray-200">方式 1（即時鏡頭）：</strong>側身站立於鏡頭前 1.5～2 公尺處,系統將自動進行 5 秒靜態體態採樣。</p>
          <p><strong class="text-gray-200">方式 2（上傳照片）：</strong>點擊左上角「上傳側面照片檢測」,選擇包含頭、肩、髖、膝的側面全身照即可秒出分析報告。</p>
        </div>

        <!-- 快篩結果與問題識別卡片 -->
        <div id="assessment-result" class="hidden space-y-4">
          <div class="p-4 rounded-xl bg-gray-900/80 border border-gray-800 space-y-1.5">
            <p class="text-xs text-gray-400 uppercase font-bold">體態量化基準值</p>
            <p class="text-sm text-gray-300">平均頭肩夾角：<strong id="avg-neck-angle" class="text-white">--°</strong></p>
            <p class="text-sm text-gray-300">平均骨盆髖角：<strong id="avg-pelvic-angle" class="text-white">--°</strong></p>
          </div>

          <!-- 問題識別與處方引導區 -->
          <div id="recommendation-card" class="p-4 rounded-xl border border-goldAccent/50 bg-goldAccent/10">
            <span class="text-xs font-bold text-goldAccent">識別出特定體態問題</span>
            <h3 id="rec-title" class="text-base font-bold text-white mt-1">--</h3>
            <p id="rec-desc" class="text-xs text-gray-300 mt-1 leading-relaxed">--</p>
            
            <!-- 一鍵返回 PNF Assistant 頁面按鈕 (透過 localStorage 傳遞推薦動作並跳轉回首頁) -->
            <button id="btn-goto-pnf" type="button" class="mt-4 w-full py-2.5 bg-gradient-to-r from-goldAccent to-amber-600 hover:from-amber-500 hover:to-amber-700 text-black text-sm font-extrabold rounded-xl transition flex items-center justify-center gap-2 shadow-lg shadow-goldAccent/20">
              <i class="fa-solid fa-arrow-right-to-bracket"></i> 前往 PNF 頁面開始矯正
            </button>
          </div>
        </div>
      </div>

      <button id="btn-reset" class="mt-6 w-full py-2.5 border border-gray-700 text-gray-300 text-xs font-semibold rounded-xl hover:bg-gray-800 transition">
        重置並重新檢測
      </button>
    </div>
  </div>

  <script type="module">
    import { PoseLandmarker, FilesetResolver, DrawingUtils } from "https://cdn.jsdelivr.net/npm/@mediapipe/tasks-vision@0.10.14";

    const video = document.getElementById("webcam");
    const uploadedPhoto = document.getElementById("uploaded-photo");
    const photoInput = document.getElementById("photo-upload");
    const btnSourceCam = document.getElementById("btn-source-cam");
    const btnSourcePhoto = document.getElementById("btn-source-photo");
    const canvas = document.getElementById("output_canvas");
    const ctx = canvas.getContext("2d");
    const guideText = document.getElementById("guide-text");
    const angleReadout = document.getElementById("angle-readout");
    const scanProgress = document.getElementById("scan-progress");
    const progressContainer = document.getElementById("progress-bar-container");

    let poseLandmarker = null;
    let webcamStream = null;
    let animationFrameId = null;
    let lastVideoTime = -1;
    let currentRunningMode = "VIDEO";

    let currentMode = "ASSESSMENT";
    const SCAN_DURATION_MS = 5000;
    let scanStartTime = null;
    let neckAngleSamples = [];
    let pelvicAngleSamples = [];

    async function initPoseLandmarker() {
      guideText.innerText = "正在載入 AI 骨架模型...";
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
        guideText.innerText = "⚠️ 無法開啟攝影機，您仍可使用「上傳側面照片檢測」功能";
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

    function getBestSideLandmarks(lm) {
      const leftSide = [lm[7], lm[11], lm[23], lm[25]];
      const rightSide = [lm[8], lm[12], lm[24], lm[26]];
      const leftScore = leftSide.reduce((sum, pt) => sum + (pt?.visibility || 0), 0);
      const rightScore = rightSide.reduce((sum, pt) => sum + (pt?.visibility || 0), 0);
      const chosen = leftScore >= rightScore ? leftSide : rightSide;
      const isVisible = chosen.every((pt) => pt && pt.visibility > 0.55);
      return { ear: chosen[0], shoulder: chosen[1], hip: chosen[2], knee: chosen[3], isVisible };
    }

    // 識別問題並設定點擊後導回主系統 PNF 頁面的行為
    function showAssessmentRecommendation(avgNeck, avgPelvic) {
      document.getElementById("scanning-placeholder").classList.add("hidden");
      document.getElementById("avg-neck-angle").innerText = `${avgNeck}°`;
      document.getElementById("avg-pelvic-angle").innerText = `${avgPelvic}°`;
      document.getElementById("assessment-result").classList.remove("hidden");

      const recTitle = document.getElementById("rec-title");
      const recDesc = document.getElementById("rec-desc");
      const btnGotoPnf = document.getElementById("btn-goto-pnf");

      let targetStretchKeyword = "胸大肌門框 PNF 拉伸";

      if (avgNeck < 162) {
        recTitle.innerText = "識別出：圓肩與頭部前傾";
        recDesc.innerText = `偵測到頭肩角 ${avgNeck}° 低於標準值。建議優先進行胸大肌與上斜方肌 PNF 伸展。`;
        targetStretchKeyword = "胸大肌門框 PNF 拉伸";
      } else if (avgPelvic < 165) {
        recTitle.innerText = "識別出：骨盆前傾代償";
        recDesc.innerText = `偵測到軀幹髖角 ${avgPelvic}° 異常。建議優先進行髂腰肌與股直肌 PNF 伸展。`;
        targetStretchKeyword = "髂腰肌高跪姿 PNF 伸展";
      } else {
        recTitle.innerText = "體態良好：全背維持課程";
        recDesc.innerText = "您的靜態體態排列良好！建議維持日常 PNF 動態伸展與關節活動度訓練。";
        targetStretchKeyword = "胸大肌門框 PNF 拉伸";
      }

      // 點擊按鈕時，將推薦的動作名稱存入 localStorage，並導航回主系統首頁的 PNF 模組
      btnGotoPnf.onclick = () => {
        localStorage.setItem("preferred_pnf_stretch", targetStretchKeyword);
        window.location.href = "/"; // 返回你的主訓練系統首頁 (包含 PNF Assistant 頁面)
      };
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
          const { ear, shoulder, hip, knee, isVisible } = getBestSideLandmarks(lm);

          drawingUtils.drawConnectors(lm, PoseLandmarker.POSE_CONNECTIONS, { color: "#6366F1", lineWidth: 4 });
          drawingUtils.drawLandmarks(lm, { color: "#F43F5E", radius: 5 });

          if (isVisible) {
            const neckAngle = getAngle(ear, shoulder, hip);
            const pelvicAngle = getAngle(shoulder, hip, knee);
            angleReadout.innerText = `頭肩角: ${neckAngle}° | 骨盆角: ${pelvicAngle}°`;

            if (currentMode === "ASSESSMENT") {
              handlePreSessionAssessment(neckAngle, pelvicAngle);
            }
          } else if (currentMode === "ASSESSMENT") {
            scanStartTime = null;
            neckAngleSamples = [];
            pelvicAngleSamples = [];
            progressContainer.classList.add("hidden");
            guideText.innerText = "⚠️ 請側身站立，確保耳、肩、髖、膝完整入鏡";
          }
        }
      }
      animationFrameId = requestAnimationFrame(renderLoop);
    }

    function handlePreSessionAssessment(neckAngle, pelvicAngle) {
      progressContainer.classList.remove("hidden");
      if (!scanStartTime) scanStartTime = performance.now();

      const elapsed = performance.now() - scanStartTime;
      const pct = Math.min(100, (elapsed / SCAN_DURATION_MS) * 100);
      scanProgress.style.width = `${pct}%`;
      guideText.innerText = `正在採樣體態數據，請保持側身靜止... (${Math.ceil((SCAN_DURATION_MS - elapsed) / 1000)}s)`;

      neckAngleSamples.push(neckAngle);
      pelvicAngleSamples.push(pelvicAngle);

      if (elapsed >= SCAN_DURATION_MS) {
        currentMode = "COMPLETED";
        progressContainer.classList.add("hidden");
        guideText.innerText = "✅ 體態問題識別完成！請點擊下方按鈕前往 PNF 頁面";

        const avgNeck = Math.round(neckAngleSamples.reduce((a, b) => a + b, 0) / neckAngleSamples.length);
        const avgPelvic = Math.round(pelvicAngleSamples.reduce((a, b) => a + b, 0) / pelvicAngleSamples.length);
        showAssessmentRecommendation(avgNeck, avgPelvic);
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
      btnSourceCam.className = "px-3 py-1.5 rounded-lg text-xs font-bold bg-gray-800 hover:bg-gray-700 text-gray-300 border border-gray-700 transition flex items-center gap-1.5";
      btnSourcePhoto.className = "cursor-pointer px-3 py-1.5 rounded-lg text-xs font-bold bg-indigo-600 text-white transition flex items-center gap-1.5";

      const imageUrl = URL.createObjectURL(file);
      uploadedPhoto.src = imageUrl;

      uploadedPhoto.onload = async () => {
        guideText.innerText = "正在使用 AI 分析照片體態...";
        if (!poseLandmarker) await initPoseLandmarker();

        if (currentRunningMode !== "IMAGE") {
          await poseLandmarker.setOptions({ runningMode: "IMAGE" });
          currentRunningMode = "IMAGE";
        }

        const results = poseLandmarker.detect(uploadedPhoto);
        const container = uploadedPhoto.parentElement;
        canvas.width = container.clientWidth;
        canvas.height = container.clientHeight;
        ctx.clearRect(0, 0, canvas.width, canvas.height);

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

        if (results.landmarks && results.landmarks.length > 0) {
          const lm = results.landmarks[0];
          const mappedLandmarks = lm.map(pt => ({
            ...pt,
            x: (offsetX + pt.x * drawW) / canvas.width,
            y: (offsetY + pt.y * drawH) / canvas.height
          }));

          const drawingUtils = new DrawingUtils(ctx);
          drawingUtils.drawConnectors(mappedLandmarks, PoseLandmarker.POSE_CONNECTIONS, { color: "#10B981", lineWidth: 3 });
          drawingUtils.drawLandmarks(mappedLandmarks, { color: "#F43F5E", radius: 4 });

          const { ear, shoulder, hip, knee, isVisible } = getBestSideLandmarks(lm);

          if (isVisible) {
            const neckAngle = getAngle(ear, shoulder, hip);
            const pelvicAngle = getAngle(shoulder, hip, knee);
            angleReadout.innerText = `頭肩角: ${neckAngle}° | 骨盆角: ${pelvicAngle}°`;
            guideText.innerText = "✅ 照片體態問題識別完成！請查看右側處方";
            showAssessmentRecommendation(neckAngle, pelvicAngle);
          } else {
            guideText.innerText = "⚠️ 無法完整辨識側面關節，請上傳包含頭, 肩, 髖, 膝的清晰全身側面照";
          }
        } else {
          guideText.innerText = "❌ 照片中未偵測到人體骨架，請更換光線充足的側面照重試";
        }

        URL.revokeObjectURL(imageUrl);
        photoInput.value = "";
      };
    });

    async function switchToCameraMode() {
      uploadedPhoto.classList.add("hidden");
      video.classList.remove("hidden");
      canvas.classList.add("-scale-x-100");
      btnSourceCam.className = "px-3 py-1.5 rounded-lg text-xs font-bold bg-indigo-600 text-white transition flex items-center gap-1.5";
      btnSourcePhoto.className = "cursor-pointer px-3 py-1.5 rounded-lg text-xs font-bold bg-gray-800 hover:bg-gray-700 text-gray-300 border border-gray-700 transition flex items-center gap-1.5";

      currentMode = "ASSESSMENT";
      scanStartTime = null;
      neckAngleSamples = [];
      pelvicAngleSamples = [];
      document.getElementById("mode-badge").innerText = "體態快篩";
      document.getElementById("scanning-placeholder").classList.remove("hidden");
      document.getElementById("assessment-result").classList.add("hidden");

      await window.startAiCamera();
    }

    btnSourceCam.addEventListener("click", switchToCameraMode);
    document.getElementById("btn-reset").addEventListener("click", switchToCameraMode);

    window.startAiCamera();
  </script>
</body>
</html>