<section id="module-analytics" class="tab-content hidden space-y-4 sm:space-y-6 w-full max-w-full min-w-0">
  <!-- Top Banner -->
  <div class="glass-card rounded-2xl p-4 sm:p-5 border border-gray-800 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-3 sm:gap-4 min-w-0">
    <div class="min-w-0">
      <h2 class="text-base sm:text-lg font-bold text-white flex flex-wrap items-center gap-2">
        <span class="flex items-center gap-2">
          <i class="fa-solid fa-chart-line text-goldAccent"></i> Training Analytics &amp; System Usability Scale (SUS)
        </span>
      </h2>
      <p class="text-[11px] sm:text-xs text-gray-400 mt-1 leading-relaxed">
        即時追蹤 PNF 伸展週期、TUT 次數與訓練時長，並提供標準 10 題 SUS 自評及 Google Form 正式問卷入口。
      </p>
    </div>

    <!-- 把 href 換成你的 Google Form 網址 -->
    <a href="https://forms.cloud.microsoft/Pages/ResponsePage.aspx?id=DQSIkWdsW0yxEjajBLZtrQAAAAAAAAAAAAMAAB7NwRVUNkNKVUNDVkVKOVZaUERaTTI2Q1I0ODk2VS4u" target="_blank" rel="noopener noreferrer"
      class="shrink-0 min-h-[38px] px-4 py-2 rounded-xl text-xs font-extrabold bg-indigo-600 hover:bg-indigo-500 text-white shadow transition inline-flex items-center gap-2">
      <i class=""></i> 前往填寫問卷
    </a>
  </div>

  <!-- 頂部數據摘要卡片（包含主頁 JS 綁定的 4 個 ID） -->
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 min-w-0">
    <div class="glass-card rounded-2xl p-4 border border-gray-800 min-w-0">
      <p class="text-[11px] text-gray-400 font-semibold uppercase">Active Training Time</p>
      <p id="stat-active-time" class="text-2xl sm:text-3xl font-extrabold text-white font-mono mt-1">0m 0s</p>
      <span class="text-[10px] text-gray-400 mt-1 inline-block">Live session timer</span>
    </div>

    <div class="glass-card rounded-2xl p-4 border border-gray-800 min-w-0">
      <p class="text-[11px] text-gray-400 font-semibold uppercase">PNF Cycles Completed</p>
      <p id="stat-pnf-cycles" class="text-2xl sm:text-3xl font-extrabold text-goldAccent font-mono mt-1">0</p>
      <span class="text-[10px] text-emerald-400 mt-1 inline-block">Hold-Relax cycles</span>
    </div>

    <div class="glass-card rounded-2xl p-4 border border-gray-800 min-w-0">
      <p class="text-[11px] text-gray-400 font-semibold uppercase">TUT Reps Completed</p>
      <p id="stat-tut-reps" class="text-2xl sm:text-3xl font-extrabold text-emeraldSuccess font-mono mt-1">0</p>
      <span class="text-[10px] text-gray-400 mt-1 inline-block">Controlled tempo reps</span>
    </div>

    <div class="glass-card rounded-2xl p-4 border border-goldAccent/30 bg-amber-500/5 min-w-0">
      <p class="text-[11px] text-goldAccent font-bold uppercase">Current SUS Score</p>
      <p id="stat-sus-score" class="text-2xl sm:text-3xl font-extrabold text-white font-mono mt-1">82.5</p>
      <span id="stat-sus-grade" class="text-[10px] px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-400 font-bold mt-1 inline-block">Grade A · Excellent</span>
    </div>
  </div>

  <!-- 下方主內容：左側 10 題 SUS 自評 + 右側即時訓練紀錄（含 #activity-log-list） -->
  <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 sm:gap-6 min-w-0">

    <!-- 左側：10 題標準 SUS 快速互動自評表 (8 cols) -->
    <div class="lg:col-span-8 glass-card rounded-2xl p-4 sm:p-6 border border-gray-800 space-y-4 min-w-0">
      <div class="flex flex-wrap items-center justify-between gap-2 border-b border-gray-800 pb-3">
        <h3 class="text-sm font-bold text-white flex items-center gap-2">
          <i class="fa-solid fa-clipboard-check text-goldAccent"></i> Interactive SUS Questionnaire (1 = 非常不同意 ~ 5 = 非常同意)
        </h3>
        <div class="flex items-center gap-2">
          <button type="button" onclick="resetSusDefaults()" class="min-h-[34px] px-3 py-1.5 rounded-lg text-xs font-bold bg-surfaceDark hover:bg-gray-800 text-gray-300 border border-gray-700 transition">
            重設
          </button>
          <button type="button" onclick="submitSusEvaluation()" class="min-h-[34px] px-4 py-1.5 rounded-lg text-xs font-extrabold bg-goldAccent hover:bg-goldHover text-black shadow transition">
            <i class="fa-solid fa-paper-plane mr-1"></i> Submit SUS
          </button>
        </div>
      </div>

      <div id="sus-questions-container" class="space-y-2.5 max-h-[380px] overflow-y-auto pr-1 text-xs">
        <!-- 由 JS 動態生成 10 題 SUS -->
      </div>

      <div class="pt-3 border-t border-gray-800/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <p id="sus-submit-feedback" class="text-xs text-gray-400">
          選擇 1～5 分後點擊 <strong class="text-goldAccent">Submit SUS</strong> 送出並更新分數。
        </p>
        <button type="button" onclick="submitSusEvaluation()" class="shrink-0 min-h-[38px] px-5 py-2 rounded-xl text-xs font-extrabold bg-gradient-to-r from-goldAccent to-goldHover text-black shadow-lg shadow-goldAccent/20 hover:brightness-105 transition">
          <i class="fa-solid fa-check mr-1"></i> Submit SUS
        </button>
      </div>
    </div>

    <!-- 右側：訓練活動紀錄與 SUS 等級說明 (4 cols) -->
    <div class="lg:col-span-4 glass-card rounded-2xl p-4 sm:p-6 border border-gray-800 flex flex-col justify-between space-y-4 min-w-0">
      <div class="space-y-3 min-w-0">
        <h3 class="text-sm font-bold text-goldAccent flex items-center gap-2">
          <i class="fa-solid fa-clock-rotate-left"></i> Session Activity Log
        </h3>
        <div id="activity-log-list" class="space-y-2 max-h-[220px] overflow-y-auto pr-1 text-xs">
          <p class="text-gray-500 italic">No completed sessions logged yet. Start a PNF or TUT session!</p>
        </div>
      </div>

      <div class="p-3.5 rounded-xl bg-surfaceDark border border-gray-800 text-[11px] text-gray-300 space-y-1.5">
        <p class="font-bold text-white"><i class="fa-solid fa-award text-goldAccent mr-1"></i> SUS 評分標準參考</p>
        <p class="text-gray-400 leading-relaxed">平均基準為 <strong class="text-white">68 分</strong>；達到 <strong class="text-emerald-400">80.3 分以上</strong> 屬於 Grade A（優秀易用性）。正式受測數據請透過上方 Google Form 提交。</p>
      </div>
    </div>

  </div>
</section>

<script>
  const DEFAULT_SUS_ANSWERS = [5, 2, 4, 1, 5, 2, 4, 1, 5, 2];

  function renderSusQuestions() {
    const container = document.getElementById("sus-questions-container");
    if (!container) return;
    container.innerHTML = "";

    const questions = (typeof SUS_QUESTIONS !== "undefined" && Array.isArray(SUS_QUESTIONS))
      ? SUS_QUESTIONS
      : [
          "1. 我認為我會願意經常使用這個 Web App。",
          "2. 我覺得這個 Web App 過於複雜。",
          "3. 我認為這個 Web App 很容易使用。",
          "4. 我認為我需要技術人員的協助才能使用這個 Web App。",
          "5. 我覺得這個 Web App 的各項功能整合得很好。",
          "6. 我覺得這個 Web App 有太多不一致的地方。",
          "7. 我想像大多數人很快就能學會使用這個 Web App。",
          "8. 我覺得這個 Web App 操作起來非常繁瑣。",
          "9. 我在使用這個 Web App 時感到很有信心。",
          "10. 在使用這個 Web App 前，我需要學習很多東西。"
        ];

    questions.forEach((text, idx) => {
      const defaultVal = DEFAULT_SUS_ANSWERS[idx];
      const row = document.createElement("div");
      row.className = "p-3 rounded-xl bg-surfaceDark border border-gray-800 flex flex-col sm:flex-row sm:items-center justify-between gap-2";

      let radiosHtml = `<div class="flex items-center gap-1.5 shrink-0">`;
      for (let score = 1; score <= 5; score++) {
        const checked = score === defaultVal ? "checked" : "";
        radiosHtml += `
          <label class="cursor-pointer">
            <input type="radio" name="sus_q_${idx}" value="${score}" ${checked} onchange="calculateSusScore()" class="peer hidden">
            <span class="w-7 h-7 rounded-lg border border-gray-700 bg-cardDark text-gray-400 peer-checked:bg-goldAccent peer-checked:text-black peer-checked:border-goldAccent font-bold text-xs flex items-center justify-center transition">
              ${score}
            </span>
          </label>
        `;
      }
      radiosHtml += `</div>`;

      row.innerHTML = `<span class="text-gray-200 leading-snug">${idx + 1}. ${text.replace(/^\d+\.\s*/, "")}</span>${radiosHtml}`;
      container.appendChild(row);
    });
  }

  window.calculateSusScore = function () {
    let totalPoints = 0;
    for (let i = 0; i < 10; i++) {
      const selected = document.querySelector(`input[name="sus_q_${i}"]:checked`);
      const val = selected ? parseInt(selected.value, 10) : 3;
      totalPoints += (i % 2 === 0) ? (val - 1) : (5 - val);
    }
    const finalSus = (totalPoints * 2.5).toFixed(1);

    const scoreEl = document.getElementById("stat-sus-score");
    const gradeEl = document.getElementById("stat-sus-grade");
    if (scoreEl) scoreEl.innerText = finalSus;

    if (gradeEl) {
      const num = parseFloat(finalSus);
      if (num >= 80.3) {
        gradeEl.className = "text-[10px] px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-400 font-bold mt-1 inline-block";
        gradeEl.innerText = "Grade A · Excellent";
      } else if (num >= 68) {
        gradeEl.className = "text-[10px] px-2 py-0.5 rounded bg-amber-500/20 text-goldAccent font-bold mt-1 inline-block";
        gradeEl.innerText = "Grade B/C · Good";
      } else {
        gradeEl.className = "text-[10px] px-2 py-0.5 rounded bg-red-500/20 text-red-400 font-bold mt-1 inline-block";
        gradeEl.innerText = "Needs Improvement";
      }
    }

    return finalSus;
  };

  window.submitSusEvaluation = function () {
    const score = window.calculateSusScore ? window.calculateSusScore() : "0.0";
    const feedback = document.getElementById("sus-submit-feedback");
    if (feedback) {
      feedback.className = "text-xs text-emerald-400 font-semibold";
      feedback.innerHTML = `<i class="fa-solid fa-circle-check mr-1"></i> 已提交 SUS 評估：總分 <strong>${score}</strong> / 100`;
    }
    if (typeof logActivity === "function") {
      logActivity(`Submitted SUS Evaluation (Score: ${score})`);
    }
  };

  window.resetSusDefaults = function () {
    for (let i = 0; i < 10; i++) {
      const targetVal = DEFAULT_SUS_ANSWERS[i];
      const radio = document.querySelector(`input[name="sus_q_${i}"][value="${targetVal}"]`);
      if (radio) radio.checked = true;
    }
    if (window.calculateSusScore) window.calculateSusScore();
    const feedback = document.getElementById("sus-submit-feedback");
    if (feedback) {
      feedback.className = "text-xs text-gray-400";
      feedback.innerHTML = `已重設為預設選項，點擊 <strong class="text-goldAccent">Submit SUS</strong> 送出。`;
    }
  };
</script>