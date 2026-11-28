<style>
  #module-tut .no-scrollbar::-webkit-scrollbar {
    display: none;
  }
  #module-tut .no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
  }
</style>

<section id="module-tut" class="tab-content hidden space-y-4 sm:space-y-6 w-full max-w-full min-w-0">
  <!-- Top Banner -->
  <div class="glass-card rounded-2xl p-3.5 sm:p-5 border border-gray-800 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-3 sm:gap-4 min-w-0">
    <div class="min-w-0">
      <h2 class="text-base sm:text-lg font-bold text-white flex flex-wrap items-center gap-2">
        <span class="flex items-center gap-2">
          <i class="fa-solid fa-dumbbell text-goldAccent"></i>
          <span data-i18n="tut.title">Time Under Tension (TUT) Tempo Controller</span>
        </span>
      </h2>
      <p id="tut-subtitle-desc" data-i18n="tut.subtitle" class="text-[11px] sm:text-xs text-gray-400 mt-1 leading-relaxed">
        Enforce controlled muscular mechanical tension per repetition (Eccentric → Pause → Concentric → Top Pause).
      </p>
    </div>

    <!-- Quick Tempo Presets -->
    <div class="flex items-center gap-2 overflow-x-auto no-scrollbar pb-1 w-full lg:w-auto lg:overflow-visible min-w-0 touch-pan-x">
      <span data-i18n="tut.tempos_label" class="text-xs text-gray-400 font-medium whitespace-nowrap shrink-0">Tempos:</span>
      <button type="button" onclick="setTutTempo(4,1,1,0)" class="shrink-0 min-h-[38px] whitespace-nowrap px-2.5 py-1.5 rounded-lg text-xs font-bold bg-goldAccent/20 text-goldAccent border border-goldAccent/40 hover:bg-goldAccent hover:text-black transition">
        4-1-1-0 (<span data-i18n="tut.preset_hypertrophy">Hypertrophy</span>)
      </button>
      <button type="button" onclick="setTutTempo(3,0,1,0)" class="shrink-0 min-h-[38px] whitespace-nowrap px-2.5 py-1.5 rounded-lg text-xs font-bold bg-cardDark text-gray-300 border border-gray-700 hover:bg-gray-800 transition">
        3-0-1-0 (<span data-i18n="tut.preset_standard">Standard</span>)
      </button>
      <button type="button" onclick="setTutTempo(5,2,1,1)" class="shrink-0 min-h-[38px] whitespace-nowrap px-2.5 py-1.5 rounded-lg text-xs font-bold bg-cardDark text-gray-300 border border-gray-700 hover:bg-gray-800 transition">
        5-2-1-1 (<span data-i18n="tut.preset_eccentric">Eccentric Focus</span>)
      </button>
    </div>
  </div>

  <!-- Main Dashboard Grid -->
  <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 sm:gap-6 min-w-0">

    <!-- Left Column: Custom Tempo Inputs & Rep Stats (4 cols on lg+) -->
    <div class="lg:col-span-4 glass-card rounded-2xl p-4 sm:p-6 border border-gray-800 space-y-4 sm:space-y-6 min-w-0">
      <h3 class="text-sm font-bold text-white flex items-center gap-2 border-b border-gray-800 pb-3">
        <i class="fa-solid fa-sliders text-goldAccent"></i>
        <span data-i18n="tut.configure_title">Configure Repetition Tempo</span>
      </h3>

      <!-- Interactive Tempo Digit Editors (2 cols on phone, 4 cols on sm+/iPad/desktop) -->
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-center">
        <div class="p-2.5 rounded-xl bg-surfaceDark border border-gray-800 min-w-0">
          <label for="tut-input-e" data-i18n="tut.label_eccentric" class="block text-[10px] text-gray-400 font-bold uppercase truncate">Eccentric</label>
          <input id="tut-input-e" type="number" inputmode="numeric" min="1" max="10" value="4" onchange="updateTutFromInputs()" class="w-full min-h-[36px] text-center bg-transparent text-lg sm:text-xl font-bold font-mono text-goldAccent focus:outline-none">
          <span data-i18n="tut.sub_lowering" class="text-[9px] text-gray-500 block truncate">Lowering</span>
        </div>
        <div class="p-2.5 rounded-xl bg-surfaceDark border border-gray-800 min-w-0">
          <label for="tut-input-p1" data-i18n="tut.label_bottom" class="block text-[10px] text-gray-400 font-bold uppercase truncate">Bottom</label>
          <input id="tut-input-p1" type="number" inputmode="numeric" min="0" max="10" value="1" onchange="updateTutFromInputs()" class="w-full min-h-[36px] text-center bg-transparent text-lg sm:text-xl font-bold font-mono text-amber-400 focus:outline-none">
          <span data-i18n="tut.sub_pause" class="text-[9px] text-gray-500 block truncate">Pause</span>
        </div>
        <div class="p-2.5 rounded-xl bg-surfaceDark border border-gray-800 min-w-0">
          <label for="tut-input-c" data-i18n="tut.label_concentric" class="block text-[10px] text-gray-400 font-bold uppercase truncate">Concentric</label>
          <input id="tut-input-c" type="number" inputmode="numeric" min="1" max="10" value="1" onchange="updateTutFromInputs()" class="w-full min-h-[36px] text-center bg-transparent text-lg sm:text-xl font-bold font-mono text-emeraldSuccess focus:outline-none">
          <span data-i18n="tut.sub_lifting" class="text-[9px] text-gray-500 block truncate">Lifting</span>
        </div>
        <div class="p-2.5 rounded-xl bg-surfaceDark border border-gray-800 min-w-0">
          <label for="tut-input-p2" data-i18n="tut.label_top" class="block text-[10px] text-gray-400 font-bold uppercase truncate">Top</label>
          <input id="tut-input-p2" type="number" inputmode="numeric" min="0" max="10" value="0" onchange="updateTutFromInputs()" class="w-full min-h-[36px] text-center bg-transparent text-lg sm:text-xl font-bold font-mono text-cyanAccent focus:outline-none">
          <span data-i18n="tut.sub_hold" class="text-[9px] text-gray-500 block truncate">Hold</span>
        </div>
      </div>

      <!-- Total Rep Duration Summary -->
      <div class="p-3.5 sm:p-4 rounded-xl bg-surfaceDark border border-gray-800/80 flex items-center justify-between gap-3">
        <div class="min-w-0">
          <p data-i18n="tut.single_rep_duration" class="text-xs text-gray-400 font-medium">Single Rep Duration:</p>
          <p id="tut-rep-duration" class="text-lg sm:text-xl font-bold text-white font-mono">6.0 seconds</p>
        </div>
        <div class="text-right shrink-0">
          <label for="tut-target-reps" data-i18n="tut.target_reps" class="text-xs text-gray-400 font-medium block">Target Reps:</label>
          <input id="tut-target-reps" type="number" inputmode="numeric" value="10" min="1" max="30" class="mt-1 w-16 min-h-[36px] text-right bg-cardDark border border-gray-700 rounded p-1.5 text-sm font-bold font-mono text-goldAccent">
        </div>
      </div>

      <!-- Rep Counter Stats Box -->
      <div class="p-4 sm:p-5 rounded-2xl bg-gradient-to-br from-cardDark to-surfaceDark border border-goldAccent/20 flex items-center justify-between gap-3">
        <div class="min-w-0">
          <p data-i18n="tut.reps_completed" class="text-xs text-gray-400 uppercase tracking-wider font-bold">Reps Completed</p>
          <p class="text-3xl sm:text-4xl font-extrabold text-white font-mono mt-1">
            <span id="tut-current-reps" class="text-goldAccent">0</span> / <span id="tut-total-target-reps">10</span>
          </p>
        </div>
        <div class="w-12 h-12 sm:w-14 sm:h-14 shrink-0 rounded-2xl bg-goldAccent/10 border border-goldAccent/30 flex items-center justify-center text-goldAccent text-xl sm:text-2xl font-bold">
          <i class="fa-solid fa-arrows-rotate"></i>
        </div>
      </div>
    </div>

    <!-- Right Column: Visual Rhythm Cadence Visualizer & Timer Controls (8 cols on lg+) -->
    <div class="lg:col-span-8 glass-card rounded-2xl p-4 sm:p-6 md:p-8 flex flex-col items-center justify-between relative overflow-hidden min-h-[360px] sm:min-h-[440px] min-w-0">

      <!-- Current Action Header -->
      <div class="w-full flex flex-wrap items-center justify-between gap-2 border-b border-gray-800 pb-3 sm:pb-4">
        <div class="flex items-center gap-2">
          <span id="tut-phase-badge" data-i18n="tut.ready_badge" class="px-2.5 sm:px-3 py-1 rounded-full text-[11px] sm:text-xs font-extrabold tracking-wider uppercase bg-goldAccent/20 text-goldAccent border border-goldAccent/30">
            READY TO START
          </span>
        </div>
        <span data-i18n="tut.metronome_sync" class="text-[11px] sm:text-xs text-gray-400 font-mono">TUT Metronome Sync</span>
      </div>

      <!-- Animated Rhythm Bar & Giant Seconds Display -->
      <div class="w-full my-5 sm:my-8 space-y-5 sm:space-y-6 flex flex-col items-center">
        <div class="text-center">
          <div id="tut-seconds-display" class="text-6xl sm:text-7xl lg:text-8xl font-extrabold tracking-tight font-mono text-white">
            0
          </div>
          <p id="tut-phase-instruction" data-i18n="tut.press_start" class="text-xs sm:text-sm font-semibold text-gray-400 mt-2 px-2">
            Press Start to begin repetition tempo guide
          </p>
        </div>

        <div class="w-full max-w-xl h-5 sm:h-6 bg-surfaceDark rounded-full p-1 border border-gray-800 relative overflow-hidden">
          <div id="tut-rhythm-bar" class="h-full bg-gradient-to-r from-goldAccent to-emeraldSuccess rounded-full w-0 transition-all duration-200"></div>
        </div>
      </div>

      <!-- Interactive Control Buttons -->
      <div class="grid grid-cols-2 sm:flex sm:items-center sm:justify-center gap-2 sm:gap-3 w-full max-w-md pt-4 border-t border-gray-800/80">
        <button id="tut-btn-start" type="button" onclick="startTutTimer()" class="col-span-2 sm:flex-1 min-h-[44px] py-3 px-4 rounded-xl font-bold text-xs sm:text-sm bg-gradient-to-r from-goldAccent to-goldHover text-black shadow-lg shadow-goldAccent/20 hover:scale-[1.02] active:scale-95 transition">
          <i class="fa-solid fa-play mr-1.5"></i> <span data-i18n="tut.start">Start Workout</span>
        </button>
        <button id="tut-btn-pause" type="button" onclick="pauseTutTimer()" disabled class="min-h-[42px] py-3 px-4 rounded-xl font-bold text-xs sm:text-sm bg-gray-800 text-gray-400 border border-gray-700 hover:bg-gray-700 disabled:opacity-40 disabled:pointer-events-none transition">
          <i class="fa-solid fa-pause mr-1"></i><span data-i18n="tut.pause">Pause</span>
        </button>
        <button type="button" onclick="resetTutTimer()" class="min-h-[42px] py-3 px-4 rounded-xl font-bold text-xs sm:text-sm bg-gray-800 text-gray-300 border border-gray-700 hover:bg-gray-700 transition">
          <i class="fa-solid fa-rotate-left mr-1"></i><span data-i18n="tut.reset">Reset</span>
        </button>
      </div>

    </div>

  </div>
</section>

<script>
  (function () {
    const TUT_I18N = {
      zh: {
        "tut.title": "最大張力時間（TUT）節奏控制器",
        "tut.subtitle": "掌控每次動作的肌肉機械張力節奏（離心下放 → 底部停頓 → 向心發力 → 頂端停留）。",
        "tut.tempos_label": "節奏預設：",
        "tut.preset_hypertrophy": "肌肥大",
        "tut.preset_standard": "標準",
        "tut.preset_eccentric": "離心強化",
        "tut.configure_title": "設定單次動作節奏",
        "tut.label_eccentric": "離心階段",
        "tut.sub_lowering": "下放",
        "tut.label_bottom": "底部停頓",
        "tut.sub_pause": "停頓",
        "tut.label_concentric": "向心階段",
        "tut.sub_lifting": "發力",
        "tut.label_top": "頂端停留",
        "tut.sub_hold": "維持",
        "tut.single_rep_duration": "單次動作時長：",
        "tut.target_reps": "目標次數：",
        "tut.reps_completed": "已完成次數",
        "tut.ready_badge": "準備開始",
        "tut.metronome_sync": "TUT 節拍同步",
        "tut.press_start": "按下「開始訓練」啟動動作節奏引導",
        "tut.start": "開始訓練",
        "tut.pause": "暫停",
        "tut.reset": "重設"
      },
      en: {
        "tut.title": "Time Under Tension (TUT) Tempo Controller",
        "tut.subtitle": "Enforce controlled muscular mechanical tension per repetition (Eccentric → Pause → Concentric → Top Pause).",
        "tut.tempos_label": "Tempos:",
        "tut.preset_hypertrophy": "Hypertrophy",
        "tut.preset_standard": "Standard",
        "tut.preset_eccentric": "Eccentric Focus",
        "tut.configure_title": "Configure Repetition Tempo",
        "tut.label_eccentric": "Eccentric",
        "tut.sub_lowering": "Lowering",
        "tut.label_bottom": "Bottom",
        "tut.sub_pause": "Pause",
        "tut.label_concentric": "Concentric",
        "tut.sub_lifting": "Lifting",
        "tut.label_top": "Top",
        "tut.sub_hold": "Hold",
        "tut.single_rep_duration": "Single Rep Duration:",
        "tut.target_reps": "Target Reps:",
        "tut.reps_completed": "Reps Completed",
        "tut.ready_badge": "READY TO START",
        "tut.metronome_sync": "TUT Metronome Sync",
        "tut.press_start": "Press Start to begin repetition tempo guide",
        "tut.start": "Start Workout",
        "tut.pause": "Pause",
        "tut.reset": "Reset"
      }
    };

    function isTutEn() {
      const raw = window.currentLang || document.documentElement.lang || localStorage.getItem("lang") || localStorage.getItem("locale") || "zh";
      return String(raw).toLowerCase().startsWith("en");
    }

    function formatDurationText(rawText, isEn) {
      if (!rawText) return "";
      const numMatch = String(rawText).match(/(\d+(?:\.\d+)?)/);
      if (!numMatch) return rawText;
      const val = numMatch[1];
      return isEn ? `${val} seconds` : `${val} 秒`;
    }

    function applyTutLang() {
      const isEn = isTutEn();
      const dict = TUT_I18N[isEn ? "en" : "zh"];

      document.querySelectorAll("#module-tut [data-i18n]").forEach((el) => {
        const key = el.getAttribute("data-i18n");
        if (dict[key] && el.textContent !== dict[key]) {
          el.textContent = dict[key];
        }
      });

      const durEl = document.getElementById("tut-rep-duration");
      if (durEl) {
        const nextDur = formatDurationText(durEl.textContent, isEn);
        if (nextDur && durEl.textContent !== nextDur) {
          durEl.textContent = nextDur;
        }
      }

      sanitizeTutArrows();
    }

    function syncTutAfterToggle() {
      applyTutLang();
      requestAnimationFrame(applyTutLang);
      setTimeout(applyTutLang, 50);
    }

    // 自動把任何殘留的 \rightarrow 或 $...rightarrow$ 字串轉成 →
    window.sanitizeTutArrows = function sanitizeTutArrows() {
      const tutRoot = document.getElementById("module-tut");
      if (!tutRoot) return;
      const walker = document.createTreeWalker(tutRoot, NodeFilter.SHOW_TEXT);
      let node;
      while ((node = walker.nextNode())) {
        if (node.nodeValue && node.nodeValue.includes("rightarrow")) {
          node.nodeValue = node.nodeValue.replace(/\$?\\+rightarrow\$?/g, "→");
        }
      }
    };

    // 包裝常見 TUT 函式，確保切換節奏或重設時秒數單位與箭頭保持一致
    ["setTutTempo", "updateTutFromInputs", "resetTutTimer"].forEach((fnName) => {
      const origFn = window[fnName];
      if (typeof origFn === "function" && !origFn.__tutWrapped) {
        const wrapped = function () {
          const res = origFn.apply(this, arguments);
          syncTutAfterToggle();
          return res;
        };
        wrapped.__tutWrapped = true;
        window[fnName] = wrapped;
      }
    });

    document.addEventListener("click", (e) => {
      const btn = e.target.closest("button, a, [data-lang], #lang-toggle, .lang-toggle");
      if (!btn) return;
      const text = (btn.textContent || "").trim().toUpperCase();
      if (text.includes("EN") || text.includes("中文") || btn.hasAttribute("data-lang")) {
        setTimeout(syncTutAfterToggle, 20);
      }
    });

    const observer = new MutationObserver(syncTutAfterToggle);
    observer.observe(document.documentElement, { attributes: true, attributeFilter: ["lang"] });

    document.addEventListener("DOMContentLoaded", syncTutAfterToggle);
    window.addEventListener("load", syncTutAfterToggle);
    syncTutAfterToggle();
  })();
</script>