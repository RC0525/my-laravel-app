<style>
  #module-posture .no-scrollbar::-webkit-scrollbar {
    display: none;
  }
  #module-posture .no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
  }
</style>

<section id="module-posture" class="tab-content hidden space-y-4 sm:space-y-6 w-full max-w-full overflow-x-hidden">
  <!-- Top Banner -->
  <div class="glass-card rounded-2xl p-3.5 sm:p-5 border border-gray-800 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-3 sm:gap-4">
    <div class="min-w-0">
      <h2 class="text-base sm:text-lg font-bold text-white flex flex-wrap items-center gap-2">
        <span class="flex items-center gap-2">
          <i class="fa-solid fa-child-reaching text-goldAccent"></i>
          <span data-i18n="posture.title">體態矯正與 PNF 伸展引導</span>
        </span>
      </h2>
      <p data-i18n="posture.subtitle" class="text-[11px] sm:text-xs text-gray-400 mt-1 leading-relaxed">
        針對常見體態偏差與緊繃肌肉，提供資料庫驅動的 PNF 伸展與保持動作指南。
      </p>
    </div>

    <!-- 分類篩選按鈕（手機/平板可橫向滑動，不撐破版面） -->
    <div class="flex items-center gap-2 overflow-x-auto no-scrollbar pb-1 w-full lg:w-auto lg:overflow-visible">
      <button type="button" onclick="filterPostureCategory('all', this)" class="posture-filter-btn active-filter shrink-0 min-h-[38px] whitespace-nowrap px-3 py-1.5 rounded-lg text-xs font-bold bg-goldAccent text-black shadow transition">
        <span data-i18n="posture.filter_all">全部動作 (All)</span>
      </button>
      <button type="button" onclick="filterPostureCategory('shoulder_upper_limb', this)" class="posture-filter-btn shrink-0 min-h-[38px] whitespace-nowrap px-3 py-1.5 rounded-lg text-xs font-bold bg-cardDark text-gray-300 border border-gray-700 hover:bg-gray-800 transition">
        <span data-i18n="posture.filter_shoulder">肩膀與上肢 (Shoulder & Upper Limb)</span>
      </button>
      <button type="button" onclick="filterPostureCategory('lower_limb', this)" class="posture-filter-btn shrink-0 min-h-[38px] whitespace-nowrap px-3 py-1.5 rounded-lg text-xs font-bold bg-cardDark text-gray-300 border border-gray-700 hover:bg-gray-800 transition">
        <span data-i18n="posture.filter_lower">下肢與髖部 (Lower Limb & Hip)</span>
      </button>
    </div>
  </div>

  <!-- 動態卡片列表（手機 1 欄、iPad 2 欄、電腦 3 欄） -->
  <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-6" id="posture-cards-container">
    @if(isset($deviations) && $deviations->count() > 0)
      @foreach($deviations as $deviation)
        @foreach($deviation->recommendedStretches as $stretch)
          <div class="posture-card glass-card rounded-2xl p-4 sm:p-5 border border-gray-800 flex flex-col justify-between space-y-3.5 sm:space-y-4 hover:border-goldAccent/40 transition min-w-0"
               data-category="{{ $deviation->category }}"
               data-dev-zh="{{ $deviation->title_zh }}"
               data-dev-en="{{ $deviation->title_en ?? $deviation->title_zh }}"
               data-stretch-zh="{{ $stretch->title_zh }}"
               data-stretch-en="{{ $stretch->title_en ?? $stretch->title_zh }}"
               data-guide-zh="{{ $deviation->assessment_guide }}"
               data-guide-en="{{ $deviation->assessment_guide_en ?? $deviation->assessment_guide }}"
               data-diff-zh="{{ $stretch->difficulty }}"
               data-diff-en="{{ $stretch->difficulty_en ?? $stretch->difficulty }}"
               data-equip-zh="{{ $stretch->equipment_needed }}"
               data-equip-en="{{ $stretch->equipment_needed_en ?? $stretch->equipment_needed }}"
               data-safe-zh="{{ $stretch->safety_warnings }}"
               data-safe-en="{{ $stretch->safety_warnings_en ?? $stretch->safety_warnings }}">
            <div class="min-w-0">
              <div class="flex items-center justify-between gap-2 mb-2.5 sm:mb-3">
                <span class="posture-dev-badge text-[10px] font-extrabold uppercase tracking-wider px-2.5 py-1 rounded bg-goldAccent/20 text-goldAccent border border-goldAccent/30 truncate">
                  {{ $deviation->title_zh }}
                </span>
                <span class="posture-diff-text text-[11px] sm:text-xs text-gray-400 font-mono shrink-0">{{ $stretch->difficulty }}</span>
              </div>
              <h3 class="posture-card-title text-sm sm:text-base font-bold text-white break-words">{{ $stretch->title_zh }}</h3>
              <p class="posture-card-guide text-xs text-gray-400 mt-1 leading-relaxed break-words">
                {{ $deviation->assessment_guide }}
              </p>
            </div>

            <div class="p-3 rounded-xl bg-surfaceDark border border-gray-800 text-xs text-gray-300 space-y-1.5 break-words">
              <p><strong class="text-white" data-i18n="posture.equipment_label">所需器材：</strong> <span class="posture-equip-text">{{ $stretch->equipment_needed }}</span></p>
              <p><strong class="text-white" data-i18n="posture.safety_label">安全提示：</strong> <span class="posture-safe-text">{{ $stretch->safety_warnings }}</span></p>
            </div>

            <div class="grid grid-cols-2 gap-2 pt-1">
              <button type="button"
                      data-stretch-id="{{ $stretch->id }}"
                      data-title="{{ $stretch->title_zh }}"
                      data-title-en="{{ $stretch->title_en ?? $stretch->title_zh }}"
                      data-steps='{{ json_encode($stretch->pnfSteps) }}'
                      data-ai-rules='@json($stretch->ai_rules)'
                      onclick="switchTab('pnf'); handlePnfPresetClick(this);"
                      class="min-h-[42px] py-2.5 px-3 rounded-xl text-xs font-bold bg-goldAccent/20 text-goldAccent hover:bg-goldAccent hover:text-black border border-goldAccent/40 transition flex items-center justify-center gap-1.5">
                <i class="fa-solid fa-stopwatch"></i> <span data-i18n="posture.pnf_btn">PNF 流程</span>
              </button>

              <button type="button"
                      data-title="{{ $stretch->title_zh }}"
                      data-title-en="{{ $stretch->title_en ?? $stretch->title_zh }}"
                      data-duration="30"
                      data-category-name="{{ $deviation->title_zh }}"
                      data-category-name-en="{{ $deviation->title_en ?? $deviation->title_zh }}"
                      onclick="handlePostureCardClick(this)"
                      class="min-h-[42px] py-2.5 px-3 rounded-xl text-xs font-bold bg-cardDark hover:bg-gray-700 text-gray-200 border border-gray-700 transition flex items-center justify-center gap-1.5">
                <i class="fa-solid fa-circle-play"></i> <span data-i18n="posture.hold_30s">30s 保持</span>
              </button>
            </div>
          </div>
        @endforeach
      @endforeach
    @else
      <p data-i18n="posture.empty" class="text-sm text-gray-500 col-span-full">尚無體態矯正資料</p>
    @endif
  </div>
</section>

<script>
  (function () {
    const POSTURE_I18N = {
      zh: {
        "posture.title": "體態矯正與 PNF 伸展引導",
        "posture.subtitle": "針對常見體態偏差與緊繃肌肉，提供資料庫驅動的 PNF 伸展與保持動作指南。",
        "posture.filter_all": "全部動作 (All)",
        "posture.filter_shoulder": "肩膀與上肢 (Shoulder & Upper Limb)",
        "posture.filter_lower": "下肢與髖部 (Lower Limb & Hip)",
        "posture.equipment_label": "所需器材：",
        "posture.safety_label": "安全提示：",
        "posture.pnf_btn": "PNF 流程",
        "posture.hold_30s": "30s 保持",
        "posture.empty": "尚無體態矯正資料"
      },
      en: {
        "posture.title": "Posture Correction & PNF Stretch Guide",
        "posture.subtitle": "Database-driven PNF stretching and hold guides for common postural deviations and tight muscles.",
        "posture.filter_all": "All Stretches",
        "posture.filter_shoulder": "Shoulder & Upper Limb",
        "posture.filter_lower": "Lower Limb & Hip",
        "posture.equipment_label": "Equipment:",
        "posture.safety_label": "Safety Note:",
        "posture.pnf_btn": "PNF Protocol",
        "posture.hold_30s": "30s Hold",
        "posture.empty": "No posture correction data available"
      }
    };

    const EQUIP_EN_MAP = {
      "門框或牆角": "Doorway or wall corner",
      "瑜伽墊與毛巾/彈力帶": "Yoga mat & towel / resistance band",
      "穩固椅子": "Sturdy chair",
      "椅子": "Chair",
      "瑜伽墊": "Yoga mat",
      "瑜伽墊或牆面": "Yoga mat or wall",
      "牆面": "Wall",
      "牆面或桌面": "Wall or table",
      "牆面或欄桿": "Wall or bar",
      "椅子或牆面": "Chair or wall",
      "門框": "Doorway",
      "無需器材": "No equipment required",
      "無": "None"
    };

    const TEXT_EN_MAP = {
      // 評估指南（Assessment Guide）
      "側面觀察耳垂落在肩峰前側；雙肩向前內旋。": "Side view shows earlobes forward of the acromion; shoulders internally rotated forward.",
      "體前屈指尖無法觸地，腰椎平直。": "Fingertips cannot reach the floor during forward bend; lumbar spine stays flat.",
      "頭部側屈角度受限，聳肩有強烈僵硬感。": "Limited lateral neck flexion with strong stiffness when shrugging.",
      "站立時大腿前側緊繃，屈膝角度受限。": "Front thigh tightness while standing with restricted knee flexion range.",
      "托馬斯測試（Thomas Test）大腿無法平貼床面。": "Thomas Test shows thigh cannot rest flat on the table surface.",
      "抱膝貼胸時感受到深層臀部拉扯與阻力。": "Deep gluteal pull and resistance felt when hugging knee to chest.",
      "雙手貼牆上舉時腰椎出現代償拱起。": "Lower back arches as compensation when raising both hands against a wall.",
      "深蹲時腳跟容易離地或後倒。": "Heels tend to lift or tip backward during deep squats.",
      "翹二郎腿時臀部深層有明顯酸痛緊繃感。": "Noticeable deep gluteal ache and tightness when crossing legs in a figure-4 position.",
      "反向伸展手腕時前臂內側出現明顯拉扯緊繃。": "Noticeable pulling tightness along the inner forearm during reverse wrist extension.",
      "仰臥時肩膀後側無法平貼床面，喙突處有壓痛。": "Back of shoulder cannot rest flat supine with tenderness near the coracoid process.",
      "站立或仰臥挺胸伸展時腹部前側有強烈牽拉感。": "Strong pull along the front of the abdomen during standing or supine chest extension.",
      "轉體或側彎時側腰感到卡頓與牽拉。": "Stiffness and pull felt along the side waist during rotation or side-bending.",
      "屈膝同時伸髖時大腿前側出現強烈緊繃。": "Strong tightness in the front of the thigh when extending the hip with knee flexed.",
      "盤腿或盤腿屈髖時大腿斜向肌肉有拉扯感。": "Pulling sensation across the diagonal thigh muscle when cross-legged or flexing hip.",
      "坐姿分腿或盤腿時膝蓋無法下沉，內側緊繃。": "Knees cannot lower in seated straddle or butterfly with inner-thigh tightness.",
      "屈膝背屈測試（Knee-to-Wall Test）距離小於 10cm。": "Knee-to-Wall dorsiflexion test distance is under 10 cm.",
      "跪姿壓腳背時小腿前側有明顯拉扯痛感。": "Noticeable pulling discomfort along the front of the shin when pressing instep in kneeling.",
      "手臂完全後伸時前臂與二頭肌有拉扯感。": "Pulling sensation in forearm and biceps with arm fully extended behind.",
      "屈肘過頭時手臂後側與肩胛處張力極高。": "High tension along back of upper arm and scapula with elbow bent overhead.",
      "轉頭同時低頭時頸側深層有銳利酸痛感。": "Sharp aching tension deep in the side of the neck when turning and tilting head down.",
      "轉頭看後方時後頸深層卡頓。": "Deep posterior neck stiffness when turning head to look backward.",

      // 安全提示（Safety Warnings）
      "保持核心收緊，切勿過度擠壓頸椎。": "Keep core braced and avoid compressing the cervical spine.",
      "下背緊貼地面，避免骨盆代償。": "Keep lower back flat on the floor to prevent pelvic compensation.",
      "手掌輕扶頭部即可，切勿用力扳拉頸椎。": "Rest palm lightly on head; never forcefully pull the neck.",
      "切勿過度扭轉膝關節。": "Avoid twisting or torquing the knee joint.",
      "保持夾臀與核心收緊，避免凹腰。": "Keep glutes squeezed and core engaged; avoid lower-back arching.",
      "避免過度壓迫胸廓。": "Avoid excessive compression on the ribcage.",
      "保持臀部平貼椅面。": "Keep buttocks flat against the chair seat.",
      "後腿膝蓋必須保持打直。": "Keep the back knee straight throughout.",
      "保持腰椎平貼地面。": "Keep lumbar spine flat against the floor.",
      "手掌貼牆高低可依個人適應度調整。": "Adjust palm height on the wall according to personal comfort and mobility.",
      "手臂需抬高至約 120 度角。": "Raise arm to roughly a 120-degree angle.",
      "收緊臀部保護下背腰椎。": "Engage glutes to protect the lower back and lumbar spine.",
      "保持兩側坐骨平貼椅面。": "Keep both sitting bones evenly planted on the chair.",
      "保持腹部收緊避免凹腰。": "Keep abdominals braced to avoid lower-back arching.",
      "切勿暴力下壓膝關節。": "Never forcefully press down on the knee joint.",
      "手掌壓在膝蓋內側而非關節上方。": "Place palms on the inner knee rather than directly over the joint.",
      "腳跟務必全程踩實。": "Keep heel planted firmly on the floor throughout.",
      "動作輕柔，保護腳踝關節。": "Move gently to protect the ankle joint.",
      "挺胸收腹，切勿腰椎前凸。": "Lift chest and brace core; avoid lumbar hyperextension.",
      "切勿用力向下壓迫肘關節。": "Avoid forcefully pressing the elbow joint downward.",
      "手掌僅做引導，切勿大力扳扣頭部。": "Use palm only for gentle guidance; never forcefully pull or yank the head.",
      "脊椎保持挺直。": "Keep the spine upright."
    };

    function translateFieldToEn(rawText, extraMap) {
      if (!rawText) return "";
      const trimmed = rawText.trim();
      if (extraMap && extraMap[trimmed]) return extraMap[trimmed];
      if (TEXT_EN_MAP[trimmed]) return TEXT_EN_MAP[trimmed];
      return trimmed;
    }

    function isPostureEn() {
      const raw = window.currentLang || document.documentElement.lang || localStorage.getItem("lang") || localStorage.getItem("locale") || "zh";
      return String(raw).toLowerCase().startsWith("en");
    }

    function applyPostureLang() {
      const lang = isPostureEn() ? "en" : "zh";
      const dict = POSTURE_I18N[lang];

      document.querySelectorAll("#module-posture [data-i18n]").forEach((el) => {
        const key = el.getAttribute("data-i18n");
        if (dict[key] && el.textContent !== dict[key]) {
          el.textContent = dict[key];
        }
      });

      document.querySelectorAll("#module-posture .posture-card").forEach((card) => {
        const devEl = card.querySelector(".posture-dev-badge");
        const diffEl = card.querySelector(".posture-diff-text");
        const titleEl = card.querySelector(".posture-card-title");
        const guideEl = card.querySelector(".posture-card-guide");
        const equipEl = card.querySelector(".posture-equip-text");
        const safeEl = card.querySelector(".posture-safe-text");

        const guideEn = card.dataset.guideEn && card.dataset.guideEn !== card.dataset.guideZh
          ? card.dataset.guideEn
          : translateFieldToEn(card.dataset.guideZh);

        const equipEn = card.dataset.equipEn && card.dataset.equipEn !== card.dataset.equipZh
          ? card.dataset.equipEn
          : translateFieldToEn(card.dataset.equipZh, EQUIP_EN_MAP);

        const safeEn = card.dataset.safeEn && card.dataset.safeEn !== card.dataset.safeZh
          ? card.dataset.safeEn
          : translateFieldToEn(card.dataset.safeZh);

        if (devEl) devEl.textContent = lang === "en" ? (card.dataset.devEn || card.dataset.devZh) : card.dataset.devZh;
        if (diffEl) diffEl.textContent = lang === "en" ? (card.dataset.diffEn || card.dataset.diffZh) : card.dataset.diffZh;
        if (titleEl) titleEl.textContent = lang === "en" ? (card.dataset.stretchEn || card.dataset.stretchZh) : card.dataset.stretchZh;
        if (guideEl) guideEl.textContent = lang === "en" ? guideEn : card.dataset.guideZh;
        if (equipEl) equipEl.textContent = lang === "en" ? equipEn : card.dataset.equipZh;
        if (safeEl) safeEl.textContent = lang === "en" ? safeEn : card.dataset.safeZh;
      });
    }

    function syncPostureAfterToggle() {
      applyPostureLang();
      requestAnimationFrame(applyPostureLang);
      setTimeout(applyPostureLang, 50);
    }

    document.addEventListener("click", (e) => {
      const btn = e.target.closest("button, a, [data-lang], #lang-toggle, .lang-toggle");
      if (!btn) return;
      const text = (btn.textContent || "").trim().toUpperCase();
      if (text.includes("EN") || text.includes("中文") || btn.hasAttribute("data-lang")) {
        setTimeout(syncPostureAfterToggle, 20);
      }
    });

    const observer = new MutationObserver(syncPostureAfterToggle);
    observer.observe(document.documentElement, { attributes: true, attributeFilter: ["lang"] });

    document.addEventListener("DOMContentLoaded", syncPostureAfterToggle);
    syncPostureAfterToggle();
  })();
</script>