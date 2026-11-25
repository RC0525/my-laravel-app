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
          <i class="fa-solid fa-child-reaching text-goldAccent"></i> 體態矯正與 PNF 伸展引導
        </span>
      </h2>
      <p class="text-[11px] sm:text-xs text-gray-400 mt-1 leading-relaxed">
        針對常見體態偏差與緊繃肌肉，提供資料庫驅動的 PNF 伸展與保持動作指南。
      </p>
    </div>

    <!-- 分類篩選按鈕（手機/平板可橫向滑動，不撐破版面） -->
    <div class="flex items-center gap-2 overflow-x-auto no-scrollbar pb-1 w-full lg:w-auto lg:overflow-visible">
      <button type="button" onclick="filterPostureCategory('all', this)" class="posture-filter-btn active-filter shrink-0 min-h-[38px] whitespace-nowrap px-3 py-1.5 rounded-lg text-xs font-bold bg-goldAccent text-black shadow transition">
        全部動作 (All)
      </button>
      <button type="button" onclick="filterPostureCategory('shoulder_upper_limb', this)" class="posture-filter-btn shrink-0 min-h-[38px] whitespace-nowrap px-3 py-1.5 rounded-lg text-xs font-bold bg-cardDark text-gray-300 border border-gray-700 hover:bg-gray-800 transition">
        肩膀與上肢 (Shoulder & Upper Limb)
      </button>
      <button type="button" onclick="filterPostureCategory('lower_limb', this)" class="posture-filter-btn shrink-0 min-h-[38px] whitespace-nowrap px-3 py-1.5 rounded-lg text-xs font-bold bg-cardDark text-gray-300 border border-gray-700 hover:bg-gray-800 transition">
        下肢與髖部 (Lower Limb & Hip)
      </button>
    </div>
  </div>

  <!-- 動態卡片列表（手機 1 欄、iPad 2 欄、電腦 3 欄） -->
  <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-6" id="posture-cards-container">
    @if(isset($deviations) && $deviations->count() > 0)
      @foreach($deviations as $deviation)
        @foreach($deviation->recommendedStretches as $stretch)
          <div class="posture-card glass-card rounded-2xl p-4 sm:p-5 border border-gray-800 flex flex-col justify-between space-y-3.5 sm:space-y-4 hover:border-goldAccent/40 transition min-w-0" data-category="{{ $deviation->category }}">
            <div class="min-w-0">
              <div class="flex items-center justify-between gap-2 mb-2.5 sm:mb-3">
                <span class="text-[10px] font-extrabold uppercase tracking-wider px-2.5 py-1 rounded bg-goldAccent/20 text-goldAccent border border-goldAccent/30 truncate">
                  {{ $deviation->title_zh }}
                </span>
                <span class="text-[11px] sm:text-xs text-gray-400 font-mono shrink-0">{{ $stretch->difficulty }}</span>
              </div>
              <h3 class="text-sm sm:text-base font-bold text-white break-words">{{ $stretch->title_zh }}</h3>
              <p class="text-xs text-gray-400 mt-1 leading-relaxed break-words">
                {{ $deviation->assessment_guide }}
              </p>
            </div>

            <div class="p-3 rounded-xl bg-surfaceDark border border-gray-800 text-xs text-gray-300 space-y-1.5 break-words">
              <p><strong class="text-white">所需器材：</strong> {{ $stretch->equipment_needed }}</p>
              <p><strong class="text-white">安全提示：</strong> {{ $stretch->safety_warnings }}</p>
            </div>

            <div class="grid grid-cols-2 gap-2 pt-1">
              <button type="button"
                      data-stretch-id="{{ $stretch->id }}"
                      data-title="{{ $stretch->title_zh }}"
                      data-steps='{{ json_encode($stretch->pnfSteps) }}'
                      onclick="switchTab('pnf'); handlePnfPresetClick(this);"
                      class="min-h-[42px] py-2.5 px-3 rounded-xl text-xs font-bold bg-goldAccent/20 text-goldAccent hover:bg-goldAccent hover:text-black border border-goldAccent/40 transition flex items-center justify-center gap-1.5">
                <i class="fa-solid fa-stopwatch"></i> <span>PNF 流程</span>
              </button>

              <button type="button"
                      data-title="{{ $stretch->title_zh }}"
                      data-duration="30"
                      data-category-name="{{ $deviation->title_zh }}"
                      onclick="handlePostureCardClick(this)"
                      class="min-h-[42px] py-2.5 px-3 rounded-xl text-xs font-bold bg-cardDark hover:bg-gray-700 text-gray-200 border border-gray-700 transition flex items-center justify-center gap-1.5">
                <i class="fa-solid fa-circle-play"></i> <span>30s 保持</span>
              </button>
            </div>
          </div>
        @endforeach
      @endforeach
    @else
      <p class="text-sm text-gray-500 col-span-full">尚無體態矯正資料</p>
    @endif
  </div>
</section>