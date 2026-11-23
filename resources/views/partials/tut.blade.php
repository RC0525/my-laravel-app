<section id="module-tut" class="tab-content hidden space-y-6">
  <!-- Top Banner -->
  <div class="glass-card rounded-2xl p-5 border border-gray-800 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
    <div>
      <h2 class="text-lg font-bold text-white flex items-center gap-2">
        <i class="fa-solid fa-dumbbell text-goldAccent"></i> Time Under Tension (TUT) Tempo Controller
      </h2>
      <p class="text-xs text-gray-400 mt-1">
        Enforce controlled muscular mechanical tension per repetition (Eccentric $\rightarrow$ Pause $\rightarrow$ Concentric $\rightarrow$ Top Pause).
      </p>
    </div>

    <!-- Quick Tempo Presets -->
    <div class="flex items-center gap-2 flex-wrap">
      <span class="text-xs text-gray-400 font-medium">Tempos:</span>
      <button onclick="setTutTempo(4,1,1,0)" class="px-2.5 py-1 rounded-lg text-xs font-bold bg-goldAccent/20 text-goldAccent border border-goldAccent/40 hover:bg-goldAccent hover:text-black transition">
        4-1-1-0 (Hypertrophy)
      </button>
      <button onclick="setTutTempo(3,0,1,0)" class="px-2.5 py-1 rounded-lg text-xs font-bold bg-cardDark text-gray-300 border border-gray-700 hover:bg-gray-800 transition">
        3-0-1-0 (Standard)
      </button>
      <button onclick="setTutTempo(5,2,1,1)" class="px-2.5 py-1 rounded-lg text-xs font-bold bg-cardDark text-gray-300 border border-gray-700 hover:bg-gray-800 transition">
        5-2-1-1 (Eccentric Focus)
      </button>
    </div>
  </div>

  <!-- Main Dashboard Grid -->
  <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

    <!-- Left Column: Custom Tempo Inputs & Rep Stats (4 cols) -->
    <div class="lg:col-span-4 glass-card rounded-2xl p-6 border border-gray-800 space-y-6">
      <h3 class="text-sm font-bold text-white flex items-center gap-2 border-b border-gray-800 pb-3">
        <i class="fa-solid fa-sliders text-goldAccent"></i> Configure Repetition Tempo
      </h3>

      <!-- Interactive Tempo Digit Editors -->
      <div class="grid grid-cols-4 gap-2 text-center">
        <div class="p-2.5 rounded-xl bg-surfaceDark border border-gray-800">
          <label class="block text-[10px] text-gray-400 font-bold uppercase">Eccentric</label>
          <input id="tut-input-e" type="number" min="1" max="10" value="4" onchange="updateTutFromInputs()" class="w-full text-center bg-transparent text-xl font-bold font-mono text-goldAccent focus:outline-none">
          <span class="text-[9px] text-gray-500">Lowering</span>
        </div>
        <div class="p-2.5 rounded-xl bg-surfaceDark border border-gray-800">
          <label class="block text-[10px] text-gray-400 font-bold uppercase">Bottom</label>
          <input id="tut-input-p1" type="number" min="0" max="10" value="1" onchange="updateTutFromInputs()" class="w-full text-center bg-transparent text-xl font-bold font-mono text-amber-400 focus:outline-none">
          <span class="text-[9px] text-gray-500">Pause</span>
        </div>
        <div class="p-2.5 rounded-xl bg-surfaceDark border border-gray-800">
          <label class="block text-[10px] text-gray-400 font-bold uppercase">Concentric</label>
          <input id="tut-input-c" type="number" min="1" max="10" value="1" onchange="updateTutFromInputs()" class="w-full text-center bg-transparent text-xl font-bold font-mono text-emeraldSuccess focus:outline-none">
          <span class="text-[9px] text-gray-500">Lifting</span>
        </div>
        <div class="p-2.5 rounded-xl bg-surfaceDark border border-gray-800">
          <label class="block text-[10px] text-gray-400 font-bold uppercase">Top</label>
          <input id="tut-input-p2" type="number" min="0" max="10" value="0" onchange="updateTutFromInputs()" class="w-full text-center bg-transparent text-xl font-bold font-mono text-cyanAccent focus:outline-none">
          <span class="text-[9px] text-gray-500">Hold</span>
        </div>
      </div>

      <!-- Total Rep Duration Summary -->
      <div class="p-4 rounded-xl bg-surfaceDark border border-gray-800/80 flex items-center justify-between">
        <div>
          <p class="text-xs text-gray-400 font-medium">Single Rep Duration:</p>
          <p id="tut-rep-duration" class="text-xl font-bold text-white font-mono">6.0 seconds</p>
        </div>
        <div class="text-right">
          <p class="text-xs text-gray-400 font-medium">Target Reps:</p>
          <input id="tut-target-reps" type="number" value="10" min="1" max="30" class="w-16 text-right bg-cardDark border border-gray-700 rounded p-1 text-sm font-bold font-mono text-goldAccent">
        </div>
      </div>

      <!-- Rep Counter Stats Box -->
      <div class="p-5 rounded-2xl bg-gradient-to-br from-cardDark to-surfaceDark border border-goldAccent/20 flex items-center justify-between">
        <div>
          <p class="text-xs text-gray-400 uppercase tracking-widest font-bold">Reps Completed</p>
          <p class="text-4xl font-extrabold text-white font-mono mt-1">
            <span id="tut-current-reps" class="text-goldAccent">0</span> / <span id="tut-total-target-reps">10</span>
          </p>
        </div>
        <div class="w-14 h-14 rounded-2xl bg-goldAccent/10 border border-goldAccent/30 flex items-center justify-center text-goldAccent text-2xl font-bold">
          <i class="fa-solid fa-arrows-rotate"></i>
        </div>
      </div>
    </div>

    <!-- Right Column: Visual Rhythm Cadence Visualizer & Timer Controls (8 cols) -->
    <div class="lg:col-span-8 glass-card rounded-2xl p-6 md:p-8 flex flex-col items-center justify-between relative overflow-hidden min-h-[440px]">
      
      <!-- Current Action Header -->
      <div class="w-full flex items-center justify-between border-b border-gray-800 pb-4">
        <div class="flex items-center gap-2">
          <span id="tut-phase-badge" class="px-3 py-1 rounded-full text-xs font-extrabold tracking-widest uppercase bg-goldAccent/20 text-goldAccent border border-goldAccent/30">
            READY TO START
          </span>
        </div>
        <span class="text-xs text-gray-400 font-mono">TUT Metronome Sync</span>
      </div>

      <!-- Animated Rhythm Bar & Giant Seconds Display -->
      <div class="w-full my-8 space-y-6 flex flex-col items-center">
        <div class="text-center">
          <div id="tut-seconds-display" class="text-7xl sm:text-8xl font-extrabold tracking-tight font-mono text-white">
            0
          </div>
          <p id="tut-phase-instruction" class="text-sm font-semibold text-gray-400 mt-2">
            Press Start to begin repetition tempo guide
          </p>
        </div>

        <div class="w-full max-w-xl h-6 bg-surfaceDark rounded-full p-1 border border-gray-800 relative overflow-hidden">
          <div id="tut-rhythm-bar" class="h-full bg-gradient-to-r from-goldAccent to-emeraldSuccess rounded-full w-0 transition-all duration-200"></div>
        </div>
      </div>

      <!-- Interactive Control Buttons -->
      <div class="flex items-center justify-center gap-3 flex-wrap w-full max-w-md pt-4 border-t border-gray-800/80">
        <button id="tut-btn-start" onclick="startTutTimer()" class="flex-1 min-w-[130px] py-3.5 px-5 rounded-xl font-bold text-sm bg-gradient-to-r from-goldAccent to-goldHover text-black shadow-lg shadow-goldAccent/20 hover:scale-[1.02] active:scale-95 transition">
          <i class="fa-solid fa-play mr-1.5"></i> Start Workout
        </button>
        <button id="tut-btn-pause" onclick="pauseTutTimer()" disabled class="py-3.5 px-5 rounded-xl font-bold text-sm bg-gray-800 text-gray-400 border border-gray-700 hover:bg-gray-700 disabled:opacity-40 disabled:pointer-events-none transition">
          <i class="fa-solid fa-pause"></i> Pause
        </button>
        <button onclick="resetTutTimer()" class="py-3.5 px-5 rounded-xl font-bold text-sm bg-gray-800 text-gray-300 border border-gray-700 hover:bg-gray-700 transition">
          <i class="fa-solid fa-rotate-left"></i> Reset
        </button>
      </div>

    </div>

  </div>
</section>