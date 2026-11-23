<section id="module-analytics" class="tab-content hidden space-y-6">
  <!-- Top Banner -->
  <div class="glass-card rounded-2xl p-5 border border-gray-800 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
    <div>
      <h2 class="text-lg font-bold text-white flex items-center gap-2">
        <i class="fa-solid fa-chart-line text-goldAccent"></i> Session Logs &amp; Research SUS Survey Widget
      </h2>
      <p class="text-xs text-gray-400 mt-1">
        Track real-time session execution data and submit System Usability Scale (SUS) research feedback directly.
      </p>
    </div>
  </div>

  <!-- Grid for Stats & Completed Logs -->
  <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

    <!-- Stat Summary Cards (4 cols) -->
    <div class="lg:col-span-4 space-y-4">
      
      <div class="glass-card rounded-2xl p-5 border border-gray-800 flex items-center justify-between">
        <div>
          <p class="text-xs text-gray-400 uppercase tracking-widest font-bold">Total PNF Cycles</p>
          <p id="stat-pnf-cycles" class="text-3xl font-extrabold text-white font-mono mt-1">0</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-goldAccent/10 text-goldAccent border border-goldAccent/30 flex items-center justify-center text-xl">
          <i class="fa-solid fa-stopwatch"></i>
        </div>
      </div>

      <div class="glass-card rounded-2xl p-5 border border-gray-800 flex items-center justify-between">
        <div>
          <p class="text-xs text-gray-400 uppercase tracking-widest font-bold">Completed TUT Reps</p>
          <p id="stat-tut-reps" class="text-3xl font-extrabold text-white font-mono mt-1">0</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-emeraldSuccess/10 text-emeraldSuccess border border-emeraldSuccess/30 flex items-center justify-center text-xl">
          <i class="fa-solid fa-dumbbell"></i>
        </div>
      </div>

      <div class="glass-card rounded-2xl p-5 border border-gray-800 flex items-center justify-between">
        <div>
          <p class="text-xs text-gray-400 uppercase tracking-widest font-bold">Active Time</p>
          <p id="stat-active-time" class="text-3xl font-extrabold text-white font-mono mt-1">0m 0s</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-cyanAccent/10 text-cyanAccent border border-cyanAccent/30 flex items-center justify-center text-xl">
          <i class="fa-solid fa-clock"></i>
        </div>
      </div>

      <!-- Activity Log Table Card -->
      <div class="glass-card rounded-2xl p-5 border border-gray-800">
        <h3 class="text-xs font-bold uppercase tracking-widest text-gray-300 border-b border-gray-800 pb-2 mb-3">
          <i class="fa-solid fa-list-check text-goldAccent mr-1"></i> Recent Activity Log
        </h3>
        <div id="activity-log-list" class="space-y-2 max-h-48 overflow-y-auto text-xs text-gray-400">
          <p class="text-gray-500 italic text-center py-4">No completed sessions logged yet.</p>
        </div>
      </div>

    </div>

    <!-- SUS Survey Interactive Widget (8 cols) -->
    <div class="lg:col-span-8 glass-card rounded-2xl p-6 md:p-8 border border-gray-800 space-y-6">
      
      <div class="border-b border-gray-800 pb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
        <div>
          <h3 class="text-base font-bold text-white flex items-center gap-2">
            <i class="fa-solid fa-square-poll-vertical text-goldAccent"></i> System Usability Scale (SUS) Feedback Survey
          </h3>
          <p class="text-xs text-gray-400 mt-0.5">
            Standardized 10-item usability evaluation widget for empirical prototype validation.
          </p>
        </div>
        <div id="sus-score-badge" class="px-3 py-1.5 rounded-xl bg-surfaceDark border border-gray-700 text-xs text-gray-300 font-mono flex items-center gap-2">
          SUS Score: <span id="sus-calculated-score" class="font-extrabold text-goldAccent text-sm">-- / 100</span>
        </div>
      </div>

      <!-- SUS 10 Questions Form -->
      <form id="sus-survey-form" onsubmit="calculateSusScore(event)" class="space-y-4">
        <div id="sus-questions-container" class="space-y-4 max-h-[380px] overflow-y-auto pr-2">
          <!-- Dynamically Populated Questions -->
        </div>

        <div class="pt-4 border-t border-gray-800 flex items-center justify-between">
          <span class="text-xs text-gray-400">Scale: 1 (Strongly Disagree) to 5 (Strongly Agree)</span>
          <button type="submit" class="py-2.5 px-6 rounded-xl font-bold text-xs bg-goldAccent text-black hover:bg-goldHover transition shadow-lg shadow-goldAccent/20">
            Submit &amp; Compute SUS Score
          </button>
        </div>
      </form>

    </div>

  </div>
</section>