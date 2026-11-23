<section id="module-posture" class="tab-content hidden space-y-6">
  <!-- Top Banner -->
  <div class="glass-card rounded-2xl p-5 border border-gray-800 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
    <div>
      <h2 class="text-lg font-bold text-white flex items-center gap-2">
        <i class="fa-solid fa-child-reaching text-goldAccent"></i> 體態矯正與 PNF 伸展引導
      </h2>
      <p class="text-xs text-gray-400 mt-1">
        針對常見體態偏差與緊繃肌肉，提供資料庫驅動的 PNF 伸展與保持動作指南。
      </p>
    </div>

    <!-- 分類篩選按鈕 -->
    <div class="flex items-center gap-2 flex-wrap">
      <button onclick="filterPostureCategory('all', this)" class="posture-filter-btn active-filter px-3 py-1.5 rounded-lg text-xs font-bold bg-goldAccent text-black shadow transition">
        全部動作 (All)
      </button>
      <button onclick="filterPostureCategory('shoulder_upper_limb', this)" class="posture-filter-btn px-3 py-1.5 rounded-lg text-xs font-bold bg-cardDark text-gray-300 border border-gray-700 hover:bg-gray-800 transition">
        肩膀與上肢 (Shoulder & Upper Limb)
      </button>
      <button onclick="filterPostureCategory('lower_limb', this)" class="posture-filter-btn px-3 py-1.5 rounded-lg text-xs font-bold bg-cardDark text-gray-300 border border-gray-700 hover:bg-gray-800 transition">
        下肢與髖部 (Lower Limb & Hip)
      </button>
    </div>
  </div>

  <!-- 動態卡片列表 -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="posture-cards-container">
    @if(isset($deviations) && $deviations->count() > 0)
      @foreach($deviations as $deviation)
        @foreach($deviation->recommendedStretches as $stretch)
          <div class="posture-card glass-card rounded-2xl p-5 border border-gray-800 flex flex-col justify-between space-y-4 hover:border-goldAccent/40 transition" data-category="{{ $deviation->category }}">
            <div>
              <div class="flex items-center justify-between mb-3">
                <span class="text-[10px] font-extrabold uppercase tracking-widest px-2.5 py-1 rounded bg-goldAccent/20 text-goldAccent border border-goldAccent/30">
                  {{ $deviation->title_zh }}
                </span>
                <span class="text-xs text-gray-400 font-mono">{{ $stretch->difficulty }}</span>
              </div>
              <h3 class="text-base font-bold text-white">{{ $stretch->title_zh }}</h3>
              <p class="text-xs text-gray-400 mt-1 leading-relaxed">
                {{ $deviation->assessment_guide }}
              </p>
            </div>

            <div class="p-3 rounded-xl bg-surfaceDark border border-gray-800 text-xs text-gray-300 space-y-1">
              <p><strong class="text-white">所需器材：</strong> {{ $stretch->equipment_needed }}</p>
              <p><strong class="text-white">安全提示：</strong> {{ $stretch->safety_warnings }}</p>
            </div>

            <div class="grid grid-cols-2 gap-2 pt-1">
              <button type="button"
                      data-stretch-id="{{ $stretch->id }}"
                      data-title="{{ $stretch->title_zh }}"
                      data-steps='{{ json_encode($stretch->pnfSteps) }}'
                      onclick="switchTab('pnf'); handlePnfPresetClick(this);"
                      class="py-2.5 px-3 rounded-xl text-xs font-bold bg-goldAccent/20 text-goldAccent hover:bg-goldAccent hover:text-black border border-goldAccent/40 transition flex items-center justify-center gap-1">
                <i class="fa-solid fa-stopwatch"></i> PNF 流程
              </button>

              <button type="button"
                      data-title="{{ $stretch->title_zh }}"
                      data-duration="30"
                      data-category-name="{{ $deviation->title_zh }}"
                      onclick="handlePostureCardClick(this)"
                      class="py-2.5 px-3 rounded-xl text-xs font-bold bg-cardDark hover:bg-gray-700 text-gray-200 border border-gray-700 transition flex items-center justify-center gap-1">
                <i class="fa-solid fa-circle-play"></i> 30s 保持
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