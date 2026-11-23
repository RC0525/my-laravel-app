<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Stretch;

class PnfAiRuleSeeder extends Seeder
{
    public function run(): void
    {
        $rulesMap = [
            // --- 原有 13 組 ---
            '胸大肌門框 PNF 拉伸' => [
                'left' => [13, 11, 23], 'right' => [14, 12, 24],
                'target' => 75, 'comparator' => '>=',
                'tip' => '抬起手臂扶住門框（大臂外展 ≥ 75°）並挺胸前跨'
            ],
            '胸小肌高角度 PNF 伸展' => [
                'left' => [13, 11, 23], 'right' => [14, 12, 24],
                'target' => 105, 'comparator' => '>=',
                'tip' => '手臂高舉成 Y 字扶牆（大臂外展 ≥ 105°），挺胸前傾'
            ],
            '前臂屈肌壁面 PNF 伸展' => [
                'left' => [11, 13, 15], 'right' => [12, 14, 16],
                'target' => 158, 'comparator' => '>=', 'requireArmStraight' => true,
                'tip' => '手臂完全伸直貼牆（手肘角度 ≥ 158°），不可彎曲手肘'
            ],
            '斜方肌上束側傾 PNF 伸展' => [
                'left' => [7, 11, 12], 'right' => [8, 12, 11],
                'target' => 112, 'comparator' => '>=',
                'tip' => '保持雙肩下沉，頭部向對側緩慢側傾 ≥ 112°'
            ],
            '胸鎖乳突肌仰頭側旋 PNF 伸展' => [
                'left' => [7, 11, 12], 'right' => [8, 12, 11],
                'target' => 110, 'comparator' => '>=',
                'tip' => '固定鎖骨下方，頭部向對側後仰側傾 ≥ 110°'
            ],
            '肱三頭肌過頭 PNF 伸展' => [
                'left' => [13, 11, 23], 'right' => [14, 12, 24],
                'target' => 145, 'comparator' => '>=', 'requireOverhead' => true,
                'tip' => '屈肘將手掌置於上背，大臂高舉過頭 ≥ 145°'
            ],
            '後三角肌水平內收 PNF 伸展' => [
                'left' => [13, 11, 12], 'right' => [14, 12, 11],
                'target' => 45, 'comparator' => '<=',
                'tip' => '單臂水平橫過胸前，保持肩關節水平內收 ≤ 45°'
            ],
            '菱形肌抱胸圓背 PNF 伸展' => [
                'left' => [13, 11, 12], 'right' => [14, 12, 11],
                'target' => 55, 'comparator' => '<=',
                'tip' => '雙手交叉環抱對側肩膀，上背微拱拉開肩胛骨'
            ],
            '闊背肌側屈 PNF 伸展' => [
                'left' => [13, 11, 23], 'right' => [14, 12, 24],
                'target' => 145, 'comparator' => '>=', 'requireOverhead' => true,
                'tip' => '單手高舉過頭（手臂貼近耳朵 ≥ 145°）並向對側側屈'
            ],
            '腹外斜肌側彎 PNF 伸展' => [
                'left' => [11, 23, 25], 'right' => [12, 24, 26],
                'target' => 155, 'comparator' => '<=',
                'tip' => '軀幹明顯向對側側彎（軀幹側屈角 ≤ 155°），不可直立站著'
            ],
            '股直肌高跪姿抓腳 PNF 伸展' => [
                'left' => [23, 25, 27], 'right' => [24, 26, 28],
                'target' => 75, 'comparator' => '<=', 'requireLowerBody' => true,
                'tip' => '單手抓腳踝將腳跟拉向臀部，保持屈膝角度 ≤ 75°'
            ],
            '髂腰肌高跪姿 PNF 伸展' => [
                'left' => [11, 23, 25], 'right' => [12, 24, 26],
                'target' => 165, 'comparator' => '>=', 'requireLowerBody' => true, 'requireKneeling' => true,
                'tip' => '採單膝跪地高跪姿，重心微前移展開後側髖部 ≥ 165°'
            ],
            '臀大肌仰臥抱膝 PNF 伸展' => [
                'left' => [11, 23, 25], 'right' => [12, 24, 26],
                'target' => 65, 'comparator' => '<=', 'requireLowerBody' => true,
                'tip' => '仰臥雙手抱膝拉向胸口，保持屈髖角度 ≤ 65°'
            ],
            '梨狀肌仰臥翹腿 PNF 伸展' => [
                'left' => [11, 23, 25], 'right' => [12, 24, 26],
                'target' => 75, 'comparator' => '<=', 'requireLowerBody' => true,
                'tip' => '仰臥翹腳呈 4 字型並將大腿拉向胸口（髖角 ≤ 75°）'
            ],
            '腓腸肌推牆直膝 PNF 伸展' => [
                'left' => [25, 27, 31], 'right' => [26, 28, 32],
                'target' => 88, 'comparator' => '<=', 'requireLowerBody' => true,
                'tip' => '後腳膝蓋伸直且腳跟踩實地面，身體前傾推牆'
            ],
            '比目魚肌推牆屈膝 PNF 伸展' => [
                'left' => [25, 27, 31], 'right' => [26, 28, 32],
                'target' => 82, 'comparator' => '<=', 'requireLowerBody' => true,
                'tip' => '後腳微屈膝且腳跟不離地，重心下沉拉伸深層小腿'
            ],

            // --- 補齊剩餘 9 組 ---
            '大腿後肌仰臥 PNF 伸展' => [
                'left' => [23, 25, 27], 'right' => [24, 26, 28],
                'target' => 162, 'comparator' => '>=', 'requireLowerBody' => true,
                'tip' => '仰臥抬起單腿並保持膝蓋伸直（膝角 ≥ 162°）'
            ],
            '股四頭肌側臥 PNF 伸展' => [
                'left' => [23, 25, 27], 'right' => [24, 26, 28],
                'target' => 75, 'comparator' => '<=', 'requireLowerBody' => true,
                'tip' => '側臥單手握住腳踝將腳跟拉向臀部（屈膝角 ≤ 75°）'
            ],
            '腹直肌仰臥背伸 PNF 伸展' => [
                'left' => [11, 23, 25], 'right' => [12, 24, 26],
                'target' => 135, 'comparator' => '<=', 'requireArmStraight' => true,
                'tip' => '俯臥雙手撐地推起上半身（眼鏡蛇式背伸，髖角 ≤ 135°）'
            ],
            '縫匠肌仰臥盤腿 PNF 伸展' => [
                'left' => [23, 25, 27], 'right' => [24, 26, 28],
                'target' => 95, 'comparator' => '<=', 'requireLowerBody' => true,
                'tip' => '仰臥單腳踝置於對側膝蓋上方外展（屈膝角 ≤ 95°）'
            ],
            '內收長肌坐姿蝶式 PNF 伸展' => [
                'left' => [23, 25, 27], 'right' => [24, 26, 28],
                'target' => 95, 'comparator' => '<=', 'requireLowerBody' => true,
                'tip' => '坐姿雙腳掌相對屈膝外展，保持雙膝下沉穩定（≤ 95°）'
            ],
            '脛骨前肌跪姿壓腳背 PNF 伸展' => [
                'left' => [23, 25, 27], 'right' => [24, 26, 28],
                'target' => 55, 'comparator' => '<=', 'requireLowerBody' => true, 'requireKneeling' => true,
                'tip' => '雙膝跪地腳背貼地，臀部坐向腳跟（屈膝角 ≤ 55°）'
            ],
            '肱二頭肌後伸 PNF 伸展' => [
                'left' => [11, 13, 15], 'right' => [12, 14, 16],
                'target' => 158, 'comparator' => '>=', 'requireArmStraight' => true,
                'tip' => '手臂完全伸直向後扶牆（手肘角度 ≥ 158°），挺胸前傾'
            ],
            '肩胛提肌轉頭俯視 PNF 伸展' => [
                'left' => [7, 11, 12], 'right' => [8, 12, 11],
                'target' => 110, 'comparator' => '>=',
                'tip' => '坐姿單手壓肩，頭部向對側轉頭低頭俯視（頸側角 ≥ 110°）'
            ],
            '頭板狀肌側屈轉頭 PNF 伸展' => [
                'left' => [7, 11, 12], 'right' => [8, 12, 11],
                'target' => 112, 'comparator' => '>=',
                'tip' => '坐姿保持挺胸，頭部向對側側屈並微轉頭（頸側角 ≥ 112°）'
            ],
        ];

        // 逐筆比對資料庫（去除空白後比對，確保 100% 寫入）
        $stretches = Stretch::all();
        foreach ($stretches as $stretch) {
            $cleanDbTitle = preg_replace('/\s+/', '', $stretch->title_zh);
            foreach ($rulesMap as $mapTitle => $rule) {
                $cleanMapTitle = preg_replace('/\s+/', '', $mapTitle);
                if ($cleanDbTitle === $cleanMapTitle || str_contains($cleanDbTitle, mb_substr($cleanMapTitle, 0, 4))) {
                    $stretch->ai_rules = $rule;
                    $stretch->save();
                    break;
                }
            }
        }

        $this->command->info('目前已有 ai_rules 的總筆數：' . Stretch::whereNotNull('ai_rules')->count());
    }
}