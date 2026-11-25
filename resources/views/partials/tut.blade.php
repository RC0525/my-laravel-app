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
          <i class="fa-solid fa-dumbbell text-goldAccent"></i> Time Under Tension (TUT) Tempo Controller
        </span>
      </h2>
      <p id="tut-subtitle-desc" class="text-[11px] sm:text-xs text-gray-400 mt-1 leading-relaxed">
        Enforce controlled muscular mechanical tension per repetition (Eccentric → Pause → Concentric → Top Pause).
      </p>
    </div>

    <!-- Quick Tempo Presets -->
    <div class="flex items-center gap-2 overflow-x-auto no-scrollbar pb-1 w-full lg:w-auto lg:overflow-visible min-w-0 touch-pan-x">
      <span class="text-xs text-gray-400 font-medium whitespace-nowrap shrink-0">Tempos:</span>
      <button type="button" onclick="setTutTempo(4,1,1,0)" class="shrink-0 min-h-[38px] whitespace-nowrap px-2.5 py-1.5 rounded-lg text-xs font-bold bg-goldAccent/20 text-goldAccent border border-goldAccent/40 hover:bg-goldAccent hover:text-black transition">
        4-1-1-0 (Hypertrophy)
      </button>
      <button type="button" onclick="setTutTempo(3,0,1,0)" class="shrink-0 min-h-[38px] whitespace-nowrap px-2.5 py-1.5 rounded-lg text-xs font-bold bg-cardDark text-gray-300 border border-gray-700 hover:bg-gray-800 transition">
        3-0-1-0 (Standard)
      </button>
      <button type="button" onclick="setTutTempo(5,2,1,1)" class="shrink-0 min-h-[38px] whitespace-nowrap px-2.5 py-1.5 rounded-lg text-xs font-bold bg-cardDark text-gray-300 border border-gray-700 hover:bg-gray-800 transition">
        5-2-1-1 (Eccentric Focus)
      </button>
    </div>
  </div>

  <!-- Main Dashboard Grid -->
  <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 sm:gap-6 min-w-0">

    <!-- Left Column: Custom Tempo Inputs & Rep Stats (4 cols on lg+) -->
    <div class="lg:col-span-4 glass-card rounded-2xl p-4 sm:p-6 border border-gray-800 space-y-4 sm:space-y-6 min-w-0">
      <h3 class="text-sm font-bold text-white flex items-center gap-2 border-b border-gray-800 pb-3">
        <i class="fa-solid fa-sliders text-goldAccent"></i> Configure Repetition Tempo
      </h3>

      <!-- Interactive Tempo Digit Editors (2 cols on phone, 4 cols on sm+/iPad/desktop) -->
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-center">
        <div class="p-2.5 rounded-xl bg-surfaceDark border border-gray-800 min-w-0">
          <label for="tut-input-e" class="block text-[10px] text-gray-400 font-bold uppercase truncate">Eccentric</label>
          <input id="tut-input-e" type="number" inputmode="numeric" min="1" max="10" value="4" onchange="updateTutFromInputs()" class="w-full min-h-[36px] text-center bg-transparent text-lg sm:text-xl font-bold font-mono text-goldAccent focus:outline-none">
          <span class="text-[9px] text-gray-500 block truncate">Lowering</span>
        </div>
        <div class="p-2.5 rounded-xl bg-surfaceDark border border-gray-800 min-w-0">
          <label for="tut-input-p1" class="block text-[10px] text-gray-400 font-bold uppercase truncate">Bottom</label>
          <input id="tut-input-p1" type="number" inputmode="numeric" min="0" max="10" value="1" onchange="updateTutFromInputs()" class="w-full min-h-[36px] text-center bg-transparent text-lg sm:text-xl font-bold font-mono text-amber-400 focus:outline-none">
          <span class="text-[9px] text-gray-500 block truncate">Pause</span>
        </div>
        <div class="p-2.5 rounded-xl bg-surfaceDark border border-gray-800 min-w-0">
          <label for="tut-input-c" class="block text-[10px] text-gray-400 font-bold uppercase truncate">Concentric</label>
          <input id="tut-input-c" type="number" inputmode="numeric" min="1" max="10" value="1" onchange="updateTutFromInputs()" class="w-full min-h-[36px] text-center bg-transparent text-lg sm:text-xl font-bold font-mono text-emeraldSuccess focus:outline-none">
          <span class="text-[9px] text-gray-500 block truncate">Lifting</span>
        </div>
        <div class="p-2.5 rounded-xl bg-surfaceDark border border-gray-800 min-w-0">
          <label for="tut-input-p2" class="block text-[10px] text-gray-400 font-bold uppercase truncate">Top</label>
          <input id="tut-input-p2" type="number" inputmode="numeric" min="0" max="10" value="0" onchange="updateTutFromInputs()" class="w-full min-h-[36px] text-center bg-transparent text-lg sm:text-xl font-bold font-mono text-cyanAccent focus:outline-none">
          <span class="text-[9px] text-gray-500 block truncate">Hold</span>
        </div>
      </div>

      <!-- Total Rep Duration Summary -->
      <div class="p-3.5 sm:p-4 rounded-xl bg-surfaceDark border border-gray-800/80 flex items-center justify-between gap-3">
        <div class="min-w-0">
          <p class="text-xs text-gray-400 font-medium">Single Rep Duration:</p>
          <p id="tut-rep-duration" class="text-lg sm:text-xl font-bold text-white font-mono">6.0 seconds</p>
        </div>
        <div class="text-right shrink-0">
          <label for="tut-target-reps" class="text-xs text-gray-400 font-medium block">Target Reps:</label>
          <input id="tut-target-reps" type="number" inputmode="numeric" value="10" min="1" max="30" class="mt-1 w-16 min-h-[36px] text-right bg-cardDark border border-gray-700 rounded p-1.5 text-sm font-bold font-mono text-goldAccent">
        </div>
      </div>

      <!-- Rep Counter Stats Box -->
      <div class="p-4 sm:p-5 rounded-2xl bg-gradient-to-br from-cardDark to-surfaceDark border border-goldAccent/20 flex items-center justify-between gap-3">
        <div class="min-w-0">
          <p class="text-xs text-gray-400 uppercase tracking-wider font-bold">Reps Completed</p>
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
          <span id="tut-phase-badge" class="px-2.5 sm:px-3 py-1 rounded-full text-[11px] sm:text-xs font-extrabold tracking-wider uppercase bg-goldAccent/20 text-goldAccent border border-goldAccent/30">
            READY TO START
          </span>
        </div>
        <span class="text-[11px] sm:text-xs text-gray-400 font-mono">TUT Metronome Sync</span>
      </div>

      <!-- Animated Rhythm Bar & Giant Seconds Display -->
      <div class="w-full my-5 sm:my-8 space-y-5 sm:space-y-6 flex flex-col items-center">
        <div class="text-center">
          <div id="tut-seconds-display" class="text-6xl sm:text-7xl lg:text-8xl font-extrabold tracking-tight font-mono text-white">
            0
          </div>
          <p id="tut-phase-instruction" class="text-xs sm:text-sm font-semibold text-gray-400 mt-2 px-2">
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
          <i class="fa-solid fa-play mr-1.5"></i> Start Workout
        </button>
        <button id="tut-btn-pause" type="button" onclick="pauseTutTimer()" disabled class="min-h-[42px] py-3 px-4 rounded-xl font-bold text-xs sm:text-sm bg-gray-800 text-gray-400 border border-gray-700 hover:bg-gray-700 disabled:opacity-40 disabled:pointer-events-none transition">
          <i class="fa-solid fa-pause mr-1"></i>Pause
        </button>
        <button type="button" onclick="resetTutTimer()" class="min-h-[42px] py-3 px-4 rounded-xl font-bold text-xs sm:text-sm bg-gray-800 text-gray-300 border border-gray-700 hover:bg-gray-700 transition">
          <i class="fa-solid fa-rotate-left mr-1"></i>Reset
        </button>
      </div>

    </div>

  </div>
</section>

<script>
  // 自動把任何殘留的 \rightarrow 或 $...rightarrow$ 字串轉成 →
  function sanitizeTutArrows() {
    const tutRoot = document.getElementById("module-tut");
    if (!tutRoot) return;
    const walker = document.createTreeWalker(tutRoot, NodeFilter.SHOW_TEXT);
    let node;
    while ((node = walker.nextNode())) {
      if (node.nodeValue && node.nodeValue.includes("rightarrow")) {
        node.nodeValue = node.nodeValue.replace(/\$?\\+rightarrow\$?/g, "→");
      }
    }
  }

  document.addEventListener("DOMContentLoaded", sanitizeTutArrows);
  window.addEventListener("load", sanitizeTutArrows);
</script>