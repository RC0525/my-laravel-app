<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostureStretchSeeder extends Seeder {
    public function run(): void {
        // 清空舊資料
        DB::statement('PRAGMA foreign_keys = OFF;');
        DB::table('pnf_steps')->truncate();
        DB::table('deviation_stretch')->truncate();
        DB::table('deviation_muscle')->truncate();
        DB::table('stretches')->truncate();
        DB::table('postural_deviations')->truncate();
        DB::table('muscles')->truncate();
        DB::statement('PRAGMA foreign_keys = ON;');

        $stretchesData = [
            // 1. 胸大肌 (上肢)
            [
                'muscle' => ['name_zh' => '胸大肌', 'name_en' => 'Pectoralis Major', 'body_part' => 'shoulder_upper_limb', 'description' => '位於胸部前方的扇形肌肉，過度緊繃會導致圓肩與駝背。'],
                'deviation' => ['title_zh' => '圓肩與前頭姿勢', 'title_en' => 'Forward Head & Rounded Shoulders', 'category' => 'shoulder_upper_limb', 'assessment_guide' => '側面觀察耳垂落在肩峰前側；雙肩向前內旋。', 'severity_tips' => '若伴隨手臂麻木請諮詢治療師。'],
                'stretch' => ['title_zh' => '胸大肌門框 PNF 伸展', 'title_en' => 'Doorway Pectoralis Major PNF Stretch', 'equipment_needed' => '門框或牆角', 'difficulty' => 'beginner', 'safety_warnings' => '保持核心收緊，切勿過度擠壓頸椎。', 'image_path' => 'images/stretches/pectoralis.png'],
                'steps' => [
                    ['step_number' => 1, 'step_name_zh' => '初始預備', 'instruction' => '前臂貼齊門框，單腳微向前跨立，挺胸預備。', 'duration_seconds' => 10],
                    ['step_number' => 2, 'step_name_zh' => '初次拉伸', 'instruction' => '身體微前傾，感受胸大肌溫和無痛張力。', 'duration_seconds' => 10],
                    ['step_number' => 3, 'step_name_zh' => '等長收縮', 'instruction' => '前臂輕推門框對抗，施以約 50% 最大發力。', 'duration_seconds' => 6],
                    ['step_number' => 4, 'step_name_zh' => '短暫放鬆', 'instruction' => '深呼氣，完全卸除推門力量放鬆胸肌。', 'duration_seconds' => 3],
                    ['step_number' => 5, 'step_name_zh' => '二次推進', 'instruction' => '身體順勢再向前跨一步，進入更深層伸展終點。', 'duration_seconds' => 15],
                    ['step_number' => 6, 'step_name_zh' => '復位放鬆', 'instruction' => '緩慢收回身體，放鬆肩膀與胸廓。', 'duration_seconds' => 5],
                ]
            ],
            // 2. 大腿後肌 (下肢)
            [
                'muscle' => ['name_zh' => '大腿後肌 (腘繩肌)', 'name_en' => 'Hamstrings', 'body_part' => 'lower_limb', 'description' => '大腿後側肌群，緊繃會限制屈髖角度並引發下背張力。'],
                'deviation' => ['title_zh' => '骨盆後傾與腿後肌過緊', 'title_en' => 'Posterior Pelvic Tilt & Tight Hamstrings', 'category' => 'lower_limb', 'assessment_guide' => '體前屈指尖無法觸地，腰椎平直。', 'severity_tips' => '切忌強力彈振膝關節。'],
                'stretch' => ['title_zh' => '大腿後肌仰臥 PNF 伸展', 'title_en' => 'Supine Hamstring PNF Stretch', 'equipment_needed' => '瑜伽墊與毛巾/彈力帶', 'difficulty' => 'beginner', 'safety_warnings' => '下背緊貼地面，避免骨盆代償。', 'image_path' => 'images/stretches/hamstring.png'],
                'steps' => [
                    ['step_number' => 1, 'step_name_zh' => '初始預備', 'instruction' => '仰臥墊上，單腿抬高，彈力帶環繞腳掌。', 'duration_seconds' => 10],
                    ['step_number' => 2, 'step_name_zh' => '初次拉伸', 'instruction' => '伸直腿部向身體拉近，感受腿後側溫和拉伸。', 'duration_seconds' => 10],
                    ['step_number' => 3, 'step_name_zh' => '等長收縮', 'instruction' => '腳跟向下踩對抗毛巾阻力，施以約 50% 力量。', 'duration_seconds' => 6],
                    ['step_number' => 4, 'step_name_zh' => '短暫放鬆', 'instruction' => '深呼吸完全放鬆腿後肌肉張力。', 'duration_seconds' => 3],
                    ['step_number' => 5, 'step_name_zh' => '二次推進', 'instruction' => '呼氣時將腿再拉近一些，進入全新幅度。', 'duration_seconds' => 15],
                    ['step_number' => 6, 'step_name_zh' => '復位放鬆', 'instruction' => '緩慢放下腿部放鬆。', 'duration_seconds' => 5],
                ]
            ],
            // 3. 上斜方肌 (上肢)
            [
                'muscle' => ['name_zh' => '上斜方肌', 'name_en' => 'Upper Trapezius', 'body_part' => 'shoulder_upper_limb', 'description' => '連接頸椎與肩胛骨，看螢幕聳肩易導致極度僵硬。'],
                'deviation' => ['title_zh' => '慢性肩頸緊繃與高低肩', 'title_en' => 'Cervical Strain & Upper Trapezius Tension', 'category' => 'shoulder_upper_limb', 'assessment_guide' => '頭部側屈角度受限，聳肩有強烈僵硬感。', 'severity_tips' => '若有頭暈手麻請減輕力道。'],
                'stretch' => ['title_zh' => '上斜方肌坐姿 PNF 伸展', 'title_en' => 'Seated Upper Trapezius PNF Stretch', 'equipment_needed' => '穩固椅子', 'difficulty' => 'beginner', 'safety_warnings' => '手掌輕扶頭部即可，切勿用力扳拉頸椎。', 'image_path' => 'images/stretches/trapezius.png'],
                'steps' => [
                    ['step_number' => 1, 'step_name_zh' => '初始預備', 'instruction' => '坐姿端正，單手抓椅面固定肩膀，一手輕扶頭側。', 'duration_seconds' => 10],
                    ['step_number' => 2, 'step_name_zh' => '初次拉伸', 'instruction' => '頭部輕輕向對側傾斜，感受側頸溫和拉伸。', 'duration_seconds' => 10],
                    ['step_number' => 3, 'step_name_zh' => '等長收縮', 'instruction' => '頭部輕向上頂對抗手部阻力，發力約 35%。', 'duration_seconds' => 6],
                    ['step_number' => 4, 'step_name_zh' => '短暫放鬆', 'instruction' => '完全放鬆肩頸張力。', 'duration_seconds' => 3],
                    ['step_number' => 5, 'step_name_zh' => '二次推進', 'instruction' => '呼氣時頭部再微側傾，加深伸展幅度。', 'duration_seconds' => 15],
                    ['step_number' => 6, 'step_name_zh' => '復位放鬆', 'instruction' => '緩慢將頭擺正放鬆。', 'duration_seconds' => 5],
                ]
            ],
            // 4. 股四頭肌 (下肢)
            [
                'muscle' => ['name_zh' => '股四頭肌', 'name_en' => 'Quadriceps Femoris', 'body_part' => 'lower_limb', 'description' => '大腿前側主肌群，過緊會拉扯骨盆導致前傾與膝蓋張力。'],
                'deviation' => ['title_zh' => '大腿前側過緊與骨盆前傾', 'title_en' => 'Quadriceps Tightness & Anterior Pelvic Tilt', 'category' => 'lower_limb', 'assessment_guide' => '站立時大腿前側緊繃，屈膝角度受限。', 'severity_tips' => '保持核心收緊，避免腰椎過度前凸。'],
                'stretch' => ['title_zh' => '股四頭肌側臥 PNF 伸展', 'title_en' => 'Side-Lying Quadriceps PNF Stretch', 'equipment_needed' => '瑜伽墊', 'difficulty' => 'beginner', 'safety_warnings' => '切勿過度扭轉膝關節。', 'image_path' => 'images/stretches/quads.png'],
                'steps' => [
                    ['step_number' => 1, 'step_name_zh' => '初始預備', 'instruction' => '側臥於墊上，一手抓住上側腳踝，準備屈膝。', 'duration_seconds' => 10],
                    ['step_number' => 2, 'step_name_zh' => '初次拉伸', 'instruction' => '將腳跟拉近臀部，感受到大腿前側溫和張力。', 'duration_seconds' => 10],
                    ['step_number' => 3, 'step_name_zh' => '等長收縮', 'instruction' => '腳踝嘗試向前踢（對抗手部阻力），發力 50%。', 'duration_seconds' => 6],
                    ['step_number' => 4, 'step_name_zh' => '短暫放鬆', 'instruction' => '呼氣完全卸除腳部踢力。', 'duration_seconds' => 3],
                    ['step_number' => 5, 'step_name_zh' => '二次推進', 'instruction' => '將腳跟進一步拉近臀部，加深大腿前側伸展。', 'duration_seconds' => 15],
                    ['step_number' => 6, 'step_name_zh' => '復位放鬆', 'instruction' => '鬆開手部，將腿部緩慢伸直放鬆。', 'duration_seconds' => 5],
                ]
            ],
            // 5. 髂腰肌 (下肢)
            [
                'muscle' => ['name_zh' => '髂腰肌', 'name_en' => 'Iliopsoas', 'body_part' => 'lower_limb', 'description' => '深層髖屈肌，久坐會極度縮短，引發下背疼痛與骨盆前傾。'],
                'deviation' => ['title_zh' => '久坐髖屈肌縮短與下背僵硬', 'title_en' => 'Iliopsoas Shortening & Low Back Tension', 'category' => 'lower_limb', 'assessment_guide' => '托馬斯測試（Thomas Test）大腿無法平貼床面。', 'severity_tips' => '後腳膝蓋下方可墊軟墊保護。'],
                'stretch' => ['title_zh' => '髂腰肌高跪姿 PNF 伸展', 'title_en' => 'Kneeling Iliopsoas PNF Stretch', 'equipment_needed' => '瑜伽墊', 'difficulty' => 'intermediate', 'safety_warnings' => '保持夾臀與核心收緊，避免凹腰。', 'image_path' => 'images/stretches/iliopsoas.png'],
                'steps' => [
                    ['step_number' => 1, 'step_name_zh' => '初始預備', 'instruction' => '呈高跪姿（弓箭步），後腳膝蓋著地，骨盆保持中立。', 'duration_seconds' => 10],
                    ['step_number' => 2, 'step_name_zh' => '初次拉伸', 'instruction' => '重心微向前推，感受後腿髖前側深層拉伸。', 'duration_seconds' => 10],
                    ['step_number' => 3, 'step_name_zh' => '等長收縮', 'instruction' => '後腳膝蓋嘗試向下/向前下壓地面對抗，發力 50%。', 'duration_seconds' => 6],
                    ['step_number' => 4, 'step_name_zh' => '短暫放鬆', 'instruction' => '深呼氣，徹底卸除下壓力量。', 'duration_seconds' => 3],
                    ['step_number' => 5, 'step_name_zh' => '二次推進', 'instruction' => '骨盆再微向前下推進，尋找全新伸展終點。', 'duration_seconds' => 15],
                    ['step_number' => 6, 'step_name_zh' => '復位放鬆', 'instruction' => '重心後移，還原至初始跪姿。', 'duration_seconds' => 5],
                ]
            ],
            // 6. 臀大肌 (下肢)
            [
                'muscle' => ['name_zh' => '臀大肌', 'name_en' => 'Gluteus Maximus', 'body_part' => 'lower_limb', 'description' => '主要伸髖肌群，緊張時會限制屈髖幅度並影響髖關節活動度。'],
                'deviation' => ['title_zh' => '臀肌緊張與髖關節受限', 'title_en' => 'Gluteal Tightness & Hip Restriction', 'category' => 'lower_limb', 'assessment_guide' => '抱膝貼胸時感受到深層臀部拉扯與阻力。', 'severity_tips' => '頭部與肩胛骨緊貼地墊。'],
                'stretch' => ['title_zh' => '臀大肌仰臥抱膝 PNF 伸展', 'title_en' => 'Supine Gluteus Maximus PNF Stretch', 'equipment_needed' => '瑜伽墊', 'difficulty' => 'beginner', 'safety_warnings' => '避免過度壓迫胸廓。', 'image_path' => 'images/stretches/glutes.png'],
                'steps' => [
                    ['step_number' => 1, 'step_name_zh' => '初始預備', 'instruction' => '仰臥墊上，雙手環抱單側膝蓋於胸前。', 'duration_seconds' => 10],
                    ['step_number' => 2, 'step_name_zh' => '初次拉伸', 'instruction' => '將膝蓋拉近胸口，感受臀部溫和拉伸。', 'duration_seconds' => 10],
                    ['step_number' => 3, 'step_name_zh' => '等長收縮', 'instruction' => '膝蓋嘗試向前/向外頂對抗雙手，發力 50%。', 'duration_seconds' => 6],
                    ['step_number' => 4, 'step_name_zh' => '短暫放鬆', 'instruction' => '呼氣完全卸除推抵力量。', 'duration_seconds' => 3],
                    ['step_number' => 5, 'step_name_zh' => '二次推進', 'instruction' => '雙手將膝蓋再向胸口拉近一些。', 'duration_seconds' => 15],
                    ['step_number' => 6, 'step_name_zh' => '復位放鬆', 'instruction' => '緩慢鬆開雙手，將腿放回地墊。', 'duration_seconds' => 5],
                ]
            ],
            // 7. 闊背肌 (上肢)
            [
                'muscle' => ['name_zh' => '闊背肌', 'name_en' => 'Latissimus Dorsi', 'body_part' => 'shoulder_upper_limb', 'description' => '背部大面積肌肉，過緊會限制肩膀上舉並拉扯腰椎。'],
                'deviation' => ['title_zh' => '闊背肌緊繃與肩關節上舉受限', 'title_en' => 'Latissimus Dorsi Restriction', 'category' => 'shoulder_upper_limb', 'assessment_guide' => '雙手貼牆上舉時腰椎出現代償拱起。', 'severity_tips' => '保持側面平行側屈，切勿旋轉軀幹。'],
                'stretch' => ['title_zh' => '闊背肌側屈 PNF 伸展', 'title_en' => 'Seated Latissimus Dorsi PNF Stretch', 'equipment_needed' => '椅子或牆面', 'difficulty' => 'beginner', 'safety_warnings' => '保持臀部平貼椅面。', 'image_path' => 'images/stretches/lats.png'],
                'steps' => [
                    ['step_number' => 1, 'step_name_zh' => '初始預備', 'instruction' => '坐姿，單手高舉過頭，準備向對側側屈。', 'duration_seconds' => 10],
                    ['step_number' => 2, 'step_name_zh' => '初次拉伸', 'instruction' => '軀幹向對側傾斜，感受側背與手臂下方拉伸。', 'duration_seconds' => 10],
                    ['step_number' => 3, 'step_name_zh' => '等長收縮', 'instruction' => '舉起的手向下/向後拉對抗（或對抗牆面），發力 50%。', 'duration_seconds' => 6],
                    ['step_number' => 4, 'step_name_zh' => '短暫放鬆', 'instruction' => '呼氣完全放鬆側背肌肉。', 'duration_seconds' => 3],
                    ['step_number' => 5, 'step_name_zh' => '二次推進', 'instruction' => '軀幹再稍微向側邊延伸，加深側背張力。', 'duration_seconds' => 15],
                    ['step_number' => 6, 'step_name_zh' => '復位放鬆', 'instruction' => '緩慢將軀幹回正，手臂放下。', 'duration_seconds' => 5],
                ]
            ],
            // 8. 腓腸肌 (下肢)
            [
                'muscle' => ['name_zh' => '腓腸肌 (小腿肌)', 'name_en' => 'Gastrocnemius', 'body_part' => 'lower_limb', 'description' => '小腿後側主肌群，過緊會限制踝關節背屈角度，影響蹲下姿勢。'],
                'deviation' => ['title_zh' => '小腿緊繃與踝關節背屈受限', 'title_en' => 'Gastrocnemius Tightness & Ankle Restriction', 'category' => 'lower_limb', 'assessment_guide' => '深蹲時腳跟容易離地或後倒。', 'severity_tips' => '伸展時後腳跟必須平貼地面。'],
                'stretch' => ['title_zh' => '腓腸肌壁面 PNF 伸展', 'title_en' => 'Wall Calf Gastrocnemius PNF Stretch', 'equipment_needed' => '牆面', 'difficulty' => 'beginner', 'safety_warnings' => '後腿膝蓋必須保持打直。', 'image_path' => 'images/stretches/calf.png'],
                'steps' => [
                    ['step_number' => 1, 'step_name_zh' => '初始預備', 'instruction' => '雙手扶牆，呈弓箭步，後腿打直且腳跟著地。', 'duration_seconds' => 10],
                    ['step_number' => 2, 'step_name_zh' => '初次拉伸', 'instruction' => '身體向前推，感受後腿小腿上側溫和拉伸。', 'duration_seconds' => 10],
                    ['step_number' => 3, 'step_name_zh' => '等長收縮', 'instruction' => '後腳掌前端用力踩地對抗（嘗試踮腳），發力 50%。', 'duration_seconds' => 6],
                    ['step_number' => 4, 'step_name_zh' => '短暫放鬆', 'instruction' => '呼氣放鬆小腿發力。', 'duration_seconds' => 3],
                    ['step_number' => 5, 'step_name_zh' => '二次推進', 'instruction' => '髖部再向前推，腳跟持續貼地以加深幅度。', 'duration_seconds' => 15],
                    ['step_number' => 6, 'step_name_zh' => '復位放鬆', 'instruction' => '收回前腿，緩慢站直放鬆。', 'duration_seconds' => 5],
                ]
            ],
            // 9. 梨狀肌 (下肢)
            [
                'muscle' => ['name_zh' => '梨狀肌', 'name_en' => 'Piriformis', 'body_part' => 'lower_limb', 'description' => '臀部深層外旋肌，過度緊繃可能壓迫坐骨神經引起臀腿痠麻。'],
                'deviation' => ['title_zh' => '梨狀肌症候群與臀深層麻痛', 'title_en' => 'Piriformis Syndrome & Deep Gluteal Pain', 'category' => 'lower_limb', 'assessment_guide' => '翹二郎腿時臀部深層有明顯酸痛緊繃感。', 'severity_tips' => '若有劇烈刺痛請立即停止。'],
                'stretch' => ['title_zh' => '梨狀肌仰臥翹腳 PNF 伸展', 'title_en' => 'Supine Figure-4 Piriformis PNF Stretch', 'equipment_needed' => '瑜伽墊', 'difficulty' => 'intermediate', 'safety_warnings' => '保持腰椎平貼地面。', 'image_path' => 'images/stretches/piriformis.png'],
                'steps' => [
                    ['step_number' => 1, 'step_name_zh' => '初始預備', 'instruction' => '仰臥屈膝，將單腳腳踝跨在對側大腿上（數字4姿勢）。', 'duration_seconds' => 10],
                    ['step_number' => 2, 'step_name_zh' => '初次拉伸', 'instruction' => '雙手環抱下側大腿拉近胸口，感受跨腳側臀部拉伸。', 'duration_seconds' => 10],
                    ['step_number' => 3, 'step_name_zh' => '等長收縮', 'instruction' => '跨腳側膝蓋嘗試向外/向前頂對抗雙手，發力 40%。', 'duration_seconds' => 6],
                    ['step_number' => 4, 'step_name_zh' => '短暫放鬆', 'instruction' => '呼氣完全放鬆臀部深層。', 'duration_seconds' => 3],
                    ['step_number' => 5, 'step_name_zh' => '二次推進', 'instruction' => '將大腿再拉近胸口一些，深化梨狀肌伸展。', 'duration_seconds' => 15],
                    ['step_number' => 6, 'step_name_zh' => '復位放鬆', 'instruction' => '鬆開雙手，雙腳放回地面。', 'duration_seconds' => 5],
                ]
            ],
            // 10. 前臂屈肌 (上肢)
            [
                'muscle' => ['name_zh' => '前臂屈肌群', 'name_en' => 'Forearm Flexors', 'body_part' => 'shoulder_upper_limb', 'description' => '前臂內側肌群，頻繁打字與使用滑鼠極易過度使用僵硬。'],
                'deviation' => ['title_zh' => '滑鼠手與前臂肌腱勞損', 'title_en' => 'Forearm Flexor Strain & Mouse Hand', 'category' => 'shoulder_upper_limb', 'assessment_guide' => '反向伸展手腕時前臂內側出現明顯拉扯緊繃。', 'severity_tips' => '動作輕柔，切勿暴力壓折手掌。'],
                'stretch' => ['title_zh' => '前臂屈肌壁面 PNF 伸展', 'title_en' => 'Wall Forearm Flexor PNF Stretch', 'equipment_needed' => '牆面或桌面', 'difficulty' => 'beginner', 'safety_warnings' => '手掌貼牆高低可依個人適應度調整。', 'image_path' => 'images/stretches/forearm.png'],
                'steps' => [
                    ['step_number' => 1, 'step_name_zh' => '初始預備', 'instruction' => '面對牆面，手臂伸直，手掌反向貼牆（手指朝下）。', 'duration_seconds' => 10],
                    ['step_number' => 2, 'step_name_zh' => '初次拉伸', 'instruction' => '身體稍微後移或壓低手掌，感受前臂內側伸展。', 'duration_seconds' => 10],
                    ['step_number' => 3, 'step_name_zh' => '等長收縮', 'instruction' => '手指與手掌嘗試向牆面壓扣對抗，發力 40%。', 'duration_seconds' => 6],
                    ['step_number' => 4, 'step_name_zh' => '短暫放鬆', 'instruction' => '呼氣徹底放鬆手掌與前臂力量。', 'duration_seconds' => 3],
                    ['step_number' => 5, 'step_name_zh' => '二次推進', 'instruction' => '手掌再向上/向後移動一點，增加手腕背屈角度。', 'duration_seconds' => 15],
                    ['step_number' => 6, 'step_name_zh' => '復位放鬆', 'instruction' => '將手掌離開牆面，甩手輕柔放鬆。', 'duration_seconds' => 5],
                ]
            ],

            // 11. 胸小肌 (上肢)
            [
                'muscle' => ['name_zh' => '胸小肌', 'name_en' => 'Pectoralis Minor', 'body_part' => 'shoulder_upper_limb', 'description' => '位於胸大肌深層，連接喙突，緊繃時會牽引肩胛骨前傾引發圓肩。'],
                'deviation' => ['title_zh' => '肩胛骨前傾與胸小肌過緊', 'title_en' => 'Scapular Anterior Tilt & Pectoralis Minor Tightness', 'category' => 'shoulder_upper_limb', 'assessment_guide' => '仰臥時肩膀後側無法平貼床面，喙突處有壓痛。', 'severity_tips' => '若手臂出現神經麻木感請立刻暫停。'],
                'stretch' => ['title_zh' => '胸小肌高角度 PNF 伸展', 'title_en' => 'High-Angle Doorway Pectoralis Minor PNF Stretch', 'equipment_needed' => '門框', 'difficulty' => 'intermediate', 'safety_warnings' => '手臂需抬高至約 120 度角。', 'image_path' => 'images/stretches/pectoralis_minor.png'],
                'steps' => [
                    ['step_number' => 1, 'step_name_zh' => '初始預備', 'instruction' => '手臂抬高成 120 度角貼於門框，單腳向前跨立。', 'duration_seconds' => 10],
                    ['step_number' => 2, 'step_name_zh' => '初次拉伸', 'instruction' => '胸廓微向前推，感受到肩膀深層胸小肌張力。', 'duration_seconds' => 10],
                    ['step_number' => 3, 'step_name_zh' => '等長收縮', 'instruction' => '肘部與手臂輕推門框對抗，發力約 40%。', 'duration_seconds' => 6],
                    ['step_number' => 4, 'step_name_zh' => '短暫放鬆', 'instruction' => '深呼氣，徹底卸除手臂推力。', 'duration_seconds' => 3],
                    ['step_number' => 5, 'step_name_zh' => '二次推進', 'instruction' => '胸廓再向前下微推，深化深層胸肌拉伸。', 'duration_seconds' => 15],
                    ['step_number' => 6, 'step_name_zh' => '復位放鬆', 'instruction' => '緩慢收回身體放鬆肩膀。', 'duration_seconds' => 5],
                ]
            ],
            // 12. 腹直肌 (下肢/核心)
            [
                'muscle' => ['name_zh' => '腹直肌', 'name_en' => 'Rectus Abdominis', 'body_part' => 'lower_limb', 'description' => '腹部前側垂直肌群，過緊會限制軀幹背伸能力並導致駝背姿勢。'],
                'deviation' => ['title_zh' => '軀幹屈曲過度與腹肌緊繃', 'title_en' => 'Excessive Trunk Flexion & Abdominal Tightness', 'category' => 'lower_limb', 'assessment_guide' => '站立或仰臥挺胸伸展時腹部前側有強烈牽拉感。', 'severity_tips' => '腰椎保持延伸，避免單點摺腰。'],
                'stretch' => ['title_zh' => '腹直肌仰臥背伸 PNF 伸展', 'title_en' => 'Prone Cobra Abdominal PNF Stretch', 'equipment_needed' => '瑜伽墊', 'difficulty' => 'beginner', 'safety_warnings' => '收緊臀部保護下背腰椎。', 'image_path' => 'images/stretches/abs.png'],
                'steps' => [
                    ['step_number' => 1, 'step_name_zh' => '初始預備', 'instruction' => '俯臥墊上，雙手支撐於胸旁，準備撐起上半身。', 'duration_seconds' => 10],
                    ['step_number' => 2, 'step_name_zh' => '初次拉伸', 'instruction' => '手臂伸直撐起上半身，感受腹部前側溫和拉伸。', 'duration_seconds' => 10],
                    ['step_number' => 3, 'step_name_zh' => '等長收縮', 'instruction' => '腹肌嘗試用力收縮（向下壓地），發力 40%。', 'duration_seconds' => 6],
                    ['step_number' => 4, 'step_name_zh' => '短暫放鬆', 'instruction' => '呼氣徹底放鬆腹肌力量。', 'duration_seconds' => 3],
                    ['step_number' => 5, 'step_name_zh' => '二次推進', 'instruction' => '胸廓微向上向上延伸，加深腹肌伸展。', 'duration_seconds' => 15],
                    ['step_number' => 6, 'step_name_zh' => '復位放鬆', 'instruction' => '緩慢趴回墊上放鬆。', 'duration_seconds' => 5],
                ]
            ],
            // 13. 腹外斜肌 (下肢/核心)
            [
                'muscle' => ['name_zh' => '腹外斜肌', 'name_en' => 'External Oblique', 'body_part' => 'lower_limb', 'description' => '腹部側方肌群，影響軀幹側屈與旋轉活動度。'],
                'deviation' => ['title_zh' => '軀幹旋轉受限與側腹緊繃', 'title_en' => 'Restricted Trunk Rotation & Oblique Tension', 'category' => 'lower_limb', 'assessment_guide' => '轉體或側彎時側腰感到卡頓與牽拉。', 'severity_tips' => '保持骨盆穩定不隨之旋轉。'],
                'stretch' => ['title_zh' => '腹外斜肌側彎 PNF 伸展', 'title_en' => 'Seated Trunk Oblique PNF Stretch', 'equipment_needed' => '椅子', 'difficulty' => 'beginner', 'safety_warnings' => '保持兩側坐骨平貼椅面。', 'image_path' => 'images/stretches/obliques.png'],
                'steps' => [
                    ['step_number' => 1, 'step_name_zh' => '初始預備', 'instruction' => '坐姿端正，一手高舉，準備向對側微側屈轉體。', 'duration_seconds' => 10],
                    ['step_number' => 2, 'step_name_zh' => '初次拉伸', 'instruction' => '軀幹側屈並微旋轉，感受側腹溫和張力。', 'duration_seconds' => 10],
                    ['step_number' => 3, 'step_name_zh' => '等長收縮', 'instruction' => '側腹發力嘗試回正（對抗手部阻力），發力 40%。', 'duration_seconds' => 6],
                    ['step_number' => 4, 'step_name_zh' => '短暫放鬆', 'instruction' => '呼氣完全放鬆側腹。', 'duration_seconds' => 3],
                    ['step_number' => 5, 'step_name_zh' => '二次推進', 'instruction' => '軀幹再稍微向側後方延伸。', 'duration_seconds' => 15],
                    ['step_number' => 6, 'step_name_zh' => '復位放鬆', 'instruction' => '緩慢將軀幹回正放鬆。', 'duration_seconds' => 5],
                ]
            ],
            // 14. 股直肌 (下肢)
            [
                'muscle' => ['name_zh' => '股直肌', 'name_en' => 'Rectus Femoris', 'body_part' => 'lower_limb', 'description' => '股四頭肌中唯一的雙關節肌，跨越髖與膝關節，過緊極易引發骨盆前傾。'],
                'deviation' => ['title_zh' => '股直肌縮短與骨盆前傾代償', 'title_en' => 'Rectus Femoris Shortening & Pelvic Tilt', 'category' => 'lower_limb', 'assessment_guide' => '屈膝同時伸髖時大腿前側出現強烈緊繃。', 'severity_tips' => '膝蓋上方可墊軟墊。'],
                'stretch' => ['title_zh' => '股直肌高跪姿抓腳 PNF 伸展', 'title_en' => 'Couch / Kneeling Rectus Femoris PNF Stretch', 'equipment_needed' => '瑜伽墊或牆面', 'difficulty' => 'advanced', 'safety_warnings' => '保持腹部收緊避免凹腰。', 'image_path' => 'images/stretches/rectus_femoris.png'],
                'steps' => [
                    ['step_number' => 1, 'step_name_zh' => '初始預備', 'instruction' => '高跪姿，後腳勾起抓住腳踝（或貼牆），準備挺直軀幹。', 'duration_seconds' => 10],
                    ['step_number' => 2, 'step_name_zh' => '初次拉伸', 'instruction' => '挺直軀幹並夾臀，感受大腿前側上端拉伸。', 'duration_seconds' => 10],
                    ['step_number' => 3, 'step_name_zh' => '等長收縮', 'instruction' => '後腳背用力向後踢對抗手部阻力，發力 50%。', 'duration_seconds' => 6],
                    ['step_number' => 4, 'step_name_zh' => '短暫放鬆', 'instruction' => '呼氣徹底卸除腳部踢力。', 'duration_seconds' => 3],
                    ['step_number' => 5, 'step_name_zh' => '二次推進', 'instruction' => '將腳跟進一步拉近臀部，臀部再向前推。', 'duration_seconds' => 15],
                    ['step_number' => 6, 'step_name_zh' => '復位放鬆', 'instruction' => '緩慢鬆開手部，回到 kneeling 姿。', 'duration_seconds' => 5],
                ]
            ],
            // 15. 縫匠肌 (下肢)
            [
                'muscle' => ['name_zh' => '縫匠肌', 'name_en' => 'Sartorius', 'body_part' => 'lower_limb', 'description' => '人體最長的帶狀肌肉，斜跨大腿前側，影響屈髖與外旋。'],
                'deviation' => ['title_zh' => '大腿前內側牽拉與旋轉受限', 'title_en' => 'Sartorius Tension & Rotational Restriction', 'category' => 'lower_limb', 'assessment_guide' => '盤腿或盤腿屈髖時大腿斜向肌肉有拉扯感。', 'severity_tips' => '動作順應關節自然角度。'],
                'stretch' => ['title_zh' => '縫匠肌仰臥盤腿 PNF 伸展', 'title_en' => 'Supine Butterfly Sartorius PNF Stretch', 'equipment_needed' => '瑜伽墊', 'difficulty' => 'intermediate', 'safety_warnings' => '切勿暴力下壓膝關節。', 'image_path' => 'images/stretches/sartorius.png'],
                'steps' => [
                    ['step_number' => 1, 'step_name_zh' => '初始預備', 'instruction' => '仰臥屈膝，單腳向外盤開呈外旋位。', 'duration_seconds' => 10],
                    ['step_number' => 2, 'step_name_zh' => '初次拉伸', 'instruction' => '膝蓋向地面沉降，感受大腿斜側溫和拉伸。', 'duration_seconds' => 10],
                    ['step_number' => 3, 'step_name_zh' => '等長收縮', 'instruction' => '膝蓋嘗試向上合攏對抗手部阻力，發力 40%。', 'duration_seconds' => 6],
                    ['step_number' => 4, 'step_name_zh' => '短暫放鬆', 'instruction' => '完全放鬆大腿發力。', 'duration_seconds' => 3],
                    ['step_number' => 5, 'step_name_zh' => '二次推進', 'instruction' => '呼氣時膝蓋再放鬆向地面沉一些。', 'duration_seconds' => 15],
                    ['step_number' => 6, 'step_name_zh' => '復位放鬆', 'instruction' => '收回腿部平放放鬆。', 'duration_seconds' => 5],
                ]
            ],
            // 16. 內收長肌 (下肢)
            [
                'muscle' => ['name_zh' => '內收長肌', 'name_en' => 'Adductor Longus', 'body_part' => 'lower_limb', 'description' => '大腿內側主要內收肌群，過緊會限制髖關節外展並影響站姿。'],
                'deviation' => ['title_zh' => '大腿內側緊繃與髖外展受限', 'title_en' => 'Adductor Tightness & Restricted Hip Abduction', 'category' => 'lower_limb', 'assessment_guide' => '坐姿分腿或盤腿時膝蓋無法下沉，內側緊繃。', 'severity_tips' => '腰椎保持挺直。'],
                'stretch' => ['title_zh' => '內收長肌坐姿蝶式 PNF 伸展', 'title_en' => 'Seated Butterfly Adductor PNF Stretch', 'equipment_needed' => '瑜伽墊', 'difficulty' => 'beginner', 'safety_warnings' => '手掌壓在膝蓋內側而非關節上方。', 'image_path' => 'images/stretches/adductors.png'],
                'steps' => [
                    ['step_number' => 1, 'step_name_zh' => '初始預備', 'instruction' => '坐姿腳掌相對（蝶式），雙手輕放在膝蓋內側。', 'duration_seconds' => 10],
                    ['step_number' => 2, 'step_name_zh' => '初次拉伸', 'instruction' => '軀幹微前傾，雙膝自然向外下沉。', 'duration_seconds' => 10],
                    ['step_number' => 3, 'step_name_zh' => '等長收縮', 'instruction' => '雙膝嘗試向上夾緊對抗雙手，發力 50%。', 'duration_seconds' => 6],
                    ['step_number' => 4, 'step_name_zh' => '短暫放鬆', 'instruction' => '呼氣完全卸除夾腿力量。', 'duration_seconds' => 3],
                    ['step_number' => 5, 'step_name_zh' => '二次推進', 'instruction' => '軀幹再稍微前傾，讓雙膝進一步下沉。', 'duration_seconds' => 15],
                    ['step_number' => 6, 'step_name_zh' => '復位放鬆', 'instruction' => '緩慢扶膝蓋合攏放鬆。', 'duration_seconds' => 5],
                ]
            ],
            // 17. 比目魚肌 (下肢)
            [
                'muscle' => ['name_zh' => '比目魚肌', 'name_en' => 'Soleus', 'body_part' => 'lower_limb', 'description' => '小腿深層肌群，跨越踝關節，緊繃是深蹲卡關的主要原因之一。'],
                'deviation' => ['title_zh' => '深蹲腳跟離地與比目魚肌過緊', 'title_en' => 'Deep Soleus Tightness & Ankle Restriction', 'category' => 'lower_limb', 'assessment_guide' => '屈膝背屈測試（Knee-to-Wall Test）距離小於 10cm。', 'severity_tips' => '伸展時後腿膝蓋必須微彎。'],
                'stretch' => ['title_zh' => '比目魚肌屈膝壁面 PNF 伸展', 'title_en' => 'Bent-Knee Soleus Wall PNF Stretch', 'equipment_needed' => '牆面', 'difficulty' => 'beginner', 'safety_warnings' => '腳跟務必全程踩實。', 'image_path' => 'images/stretches/soleus.png'],
                'steps' => [
                    ['step_number' => 1, 'step_name_zh' => '初始預備', 'instruction' => '面對牆面呈短弓箭步，後腿膝蓋微彎，腳跟踩實。', 'duration_seconds' => 10],
                    ['step_number' => 2, 'step_name_zh' => '初次拉伸', 'instruction' => '重心下壓並將後膝蓋向牆面推，感受小腿深層拉伸。', 'duration_seconds' => 10],
                    ['step_number' => 3, 'step_name_zh' => '等長收縮', 'instruction' => '後腳前掌用力向下踩地（踮腳感覺），發力 50%。', 'duration_seconds' => 6],
                    ['step_number' => 4, 'step_name_zh' => '短暫放鬆', 'instruction' => '深呼氣，卸除踩地力量。', 'duration_seconds' => 3],
                    ['step_number' => 5, 'step_name_zh' => '二次推進', 'instruction' => '後膝蓋再進一步彎曲向下下壓。', 'duration_seconds' => 15],
                    ['step_number' => 6, 'step_name_zh' => '復位放鬆', 'instruction' => '伸直膝蓋站立放鬆。', 'duration_seconds' => 5],
                ]
            ],
            // 18. 脛骨前肌 (下肢)
            [
                'muscle' => ['name_zh' => '脛骨前肌', 'name_en' => 'Tibialis Anterior', 'body_part' => 'lower_limb', 'description' => '小腿前側肌群，過度使用易引發脛骨痛與蹠屈受限。'],
                'deviation' => ['title_zh' => '小腿前側脹痛與壓腳背受限', 'title_en' => 'Tibialis Anterior Strain & Plantarflexion Limit', 'category' => 'lower_limb', 'assessment_guide' => '跪姿壓腳背時小腿前側有明顯拉扯痛感。', 'severity_tips' => '腳背下方可墊毛巾。'],
                'stretch' => ['title_zh' => '脛骨前肌跪姿壓腳背 PNF 伸展', 'title_en' => 'Seated Tibialis Anterior PNF Stretch', 'equipment_needed' => '瑜伽墊', 'difficulty' => 'beginner', 'safety_warnings' => '動作輕柔，保護腳踝關節。', 'image_path' => 'images/stretches/tibialis.png'],
                'steps' => [
                    ['step_number' => 1, 'step_name_zh' => '初始預備', 'instruction' => '高跪姿，腳背平貼地面，準備臀部後坐。', 'duration_seconds' => 10],
                    ['step_number' => 2, 'step_name_zh' => '初次拉伸', 'instruction' => '臀部微微向腳跟後坐，感受小腿前側拉伸。', 'duration_seconds' => 10],
                    ['step_number' => 3, 'step_name_zh' => '等長收縮', 'instruction' => '腳背用力向地面下壓（嘗試向上背屈），發力 40%。', 'duration_seconds' => 6],
                    ['step_number' => 4, 'step_name_zh' => '短暫放鬆', 'instruction' => '呼氣完全卸除腳背下壓力量。', 'duration_seconds' => 3],
                    ['step_number' => 5, 'step_name_zh' => '二次推進', 'instruction' => '臀部再稍微向後坐一些，加深小腿前側伸展。', 'duration_seconds' => 15],
                    ['step_number' => 6, 'step_name_zh' => '復位放鬆', 'instruction' => '重心前移回到高跪姿放鬆。', 'duration_seconds' => 5],
                ]
            ],
            // 19. 肱二頭肌 (上肢)
            [
                'muscle' => ['name_zh' => '肱二頭肌', 'name_en' => 'Biceps Brachii', 'body_part' => 'shoulder_upper_limb', 'description' => '手臂前側屈肘肌群，頻繁彎舉或打字會導致前側張力過高。'],
                'deviation' => ['title_zh' => '手臂前側緊繃與肘關節伸直受限', 'title_en' => 'Biceps Tension & Restricted Elbow Extension', 'category' => 'shoulder_upper_limb', 'assessment_guide' => '手臂完全後伸時前臂與二頭肌有拉扯感。', 'severity_tips' => '避免肩膀過度聳起。'],
                'stretch' => ['title_zh' => '肱二頭肌後伸 PNF 伸展', 'title_en' => 'Behind-Back Biceps PNF Stretch', 'equipment_needed' => '牆面或欄桿', 'difficulty' => 'beginner', 'safety_warnings' => '挺胸收腹，切勿腰椎前凸。', 'image_path' => 'images/stretches/biceps.png'],
                'steps' => [
                    ['step_number' => 1, 'step_name_zh' => '初始預備', 'instruction' => '背對牆面，單手手臂打直向後扶牆（大拇指朝下）。', 'duration_seconds' => 10],
                    ['step_number' => 2, 'step_name_zh' => '初次拉伸', 'instruction' => '身體微向前微轉，感受手臂二頭肌溫和張力。', 'duration_seconds' => 10],
                    ['step_number' => 3, 'step_name_zh' => '等長收縮', 'instruction' => '手掌用力按壓牆面（嘗試屈肘），發力 40%。', 'duration_seconds' => 6],
                    ['step_number' => 4, 'step_name_zh' => '短暫放鬆', 'instruction' => '完全放鬆手臂按壓發力。', 'duration_seconds' => 3],
                    ['step_number' => 5, 'step_name_zh' => '二次推進', 'instruction' => '身體再微向前轉一些，加深二頭肌拉伸。', 'duration_seconds' => 15],
                    ['step_number' => 6, 'step_name_zh' => '復位放鬆', 'instruction' => '手臂離開牆面甩手放鬆。', 'duration_seconds' => 5],
                ]
            ],
            // 20. 肱三頭肌 (上肢)
            [
                'muscle' => ['name_zh' => '肱三頭肌', 'name_en' => 'Triceps Brachii', 'body_part' => 'shoulder_upper_limb', 'description' => '手臂後側伸肘肌群，過緊會限制肩關節完全上舉。'],
                'deviation' => ['title_zh' => '手臂後側緊繃與肩上舉卡頓', 'title_en' => 'Triceps Tightness & Shoulder Flexion Limit', 'category' => 'shoulder_upper_limb', 'assessment_guide' => '屈肘過頭時手臂後側與肩胛處張力極高。', 'severity_tips' => '保持頭部挺直，切勿被手臂壓低。'],
                'stretch' => ['title_zh' => '肱三頭肌過頭 PNF 伸展', 'title_en' => 'Overhead Triceps PNF Stretch', 'equipment_needed' => '無', 'difficulty' => 'beginner', 'safety_warnings' => '切勿用力向下壓迫肘關節。', 'image_path' => 'images/stretches/triceps.png'],
                'steps' => [
                    ['step_number' => 1, 'step_name_zh' => '初始預備', 'instruction' => '單手屈肘過頭，另一手扶住肘關節。', 'duration_seconds' => 10],
                    ['step_number' => 2, 'step_name_zh' => '初次拉伸', 'instruction' => '扶肘的手輕向後拉，感受手臂後側拉伸。', 'duration_seconds' => 10],
                    ['step_number' => 3, 'step_name_zh' => '等長收縮', 'instruction' => '肘關節嘗試向上/向前發力對抗阻擋手，發力 50%。', 'duration_seconds' => 6],
                    ['step_number' => 4, 'step_name_zh' => '短暫放鬆', 'instruction' => '深呼氣，完全放鬆手臂發力。', 'duration_seconds' => 3],
                    ['step_number' => 5, 'step_name_zh' => '二次推進', 'instruction' => '手掌再順著背部向下滑動一點。', 'duration_seconds' => 15],
                    ['step_number' => 6, 'step_name_zh' => '復位放鬆', 'instruction' => '緩慢放下手臂放鬆。', 'duration_seconds' => 5],
                ]
            ],
            // 21. 肩胛提肌 (上肢)
            [
                'muscle' => ['name_zh' => '肩胛提肌', 'name_en' => 'Levator Scapulae', 'body_part' => 'shoulder_upper_limb', 'description' => '連接頸椎與肩胛骨上角，低頭看手機極易導致其勞損酸痛。'],
                'deviation' => ['title_zh' => '後頸硬塊與肩胛上角酸痛', 'title_en' => 'Levator Scapulae Strain & Neck Stiffness', 'category' => 'shoulder_upper_limb', 'assessment_guide' => '轉頭同時低頭時後頸側深層有銳利酸痛感。', 'severity_tips' => '動作極度輕柔，避免猛拉。'],
                'stretch' => ['title_zh' => '肩胛提肌轉頭俯視 PNF 伸展', 'title_en' => 'Seated Levator Scapulae PNF Stretch', 'equipment_needed' => '椅子', 'difficulty' => 'intermediate', 'safety_warnings' => '手掌僅做引導，切勿大力扳扣頭部。', 'image_path' => 'images/stretches/levator.png'],
                'steps' => [
                    ['step_number' => 1, 'step_name_zh' => '初始預備', 'instruction' => '坐姿，頭部向側轉 45 度並微微低頭看腋下，一手扶後腦。', 'duration_seconds' => 10],
                    ['step_number' => 2, 'step_name_zh' => '初次拉伸', 'instruction' => '輕引導低頭，感受到後頸至肩胛骨角溫和拉伸。', 'duration_seconds' => 10],
                    ['step_number' => 3, 'step_name_zh' => '等長收縮', 'instruction' => '頭部嘗試向上/向後抬對抗手部阻力，發力 30%。', 'duration_seconds' => 6],
                    ['step_number' => 4, 'step_name_zh' => '短暫放鬆', 'instruction' => '呼氣徹底放鬆後頸肌肉。', 'duration_seconds' => 3],
                    ['step_number' => 5, 'step_name_zh' => '二次推進', 'instruction' => '頭部順勢再微向低頭一些，深化伸展。', 'duration_seconds' => 15],
                    ['step_number' => 6, 'step_name_zh' => '復位放鬆', 'instruction' => '緩慢抬頭將頭擺正。', 'duration_seconds' => 5],
                ]
            ],
            // 22. 頭板狀肌 (上肢)
            [
                'muscle' => ['name_zh' => '頭板狀肌', 'name_en' => 'Splenius Capitis', 'body_part' => 'shoulder_upper_limb', 'description' => '頸部後外側肌群，影響頭部後仰與旋轉，緊繃常引發後腦勺張力頭痛。'],
                'deviation' => ['title_zh' => '後腦勺張力與轉頭受限', 'title_en' => 'Splenius Tension & Cervical Rotation Limit', 'category' => 'shoulder_upper_limb', 'assessment_guide' => '轉頭看後方時後頸深層卡頓。', 'severity_tips' => '若有頭暈感請減輕力道。'],
                'stretch' => ['title_zh' => '頭板狀肌側屈轉頭 PNF 伸展', 'title_en' => 'Splenius Capitis PNF Stretch', 'equipment_needed' => '椅子', 'difficulty' => 'intermediate', 'safety_warnings' => '脊椎保持挺直。', 'image_path' => 'images/stretches/splenius.png'],
                'steps' => [
                    ['step_number' => 1, 'step_name_zh' => '初始預備', 'instruction' => '坐姿端正，頭部微側屈並轉向同側，手扶頭頂。', 'duration_seconds' => 10],
                    ['step_number' => 2, 'step_name_zh' => '初次拉伸', 'instruction' => '輕引導頭部向對側側屈，感受後頸深層拉伸。', 'duration_seconds' => 10],
                    ['step_number' => 3, 'step_name_zh' => '等長收縮', 'instruction' => '頭部嘗試向回頂對抗阻力手，發力 30%。', 'duration_seconds' => 6],
                    ['step_number' => 4, 'step_name_zh' => '短暫放鬆', 'instruction' => '呼氣完全放鬆深層頸肌。', 'duration_seconds' => 3],
                    ['step_number' => 5, 'step_name_zh' => '二次推進', 'instruction' => '頭部再微側傾一些，增加拉伸幅度。', 'duration_seconds' => 15],
                    ['step_number' => 6, 'step_name_zh' => '復位放鬆', 'instruction' => '緩慢將頭部擺正。', 'duration_seconds' => 5],
                ]
            ],
        ];

        foreach ($stretchesData as $data) {
            $muscleId = DB::table('muscles')->insertGetId(array_merge($data['muscle'], [
                'created_at' => now(), 'updated_at' => now(),
            ]));

            $devId = DB::table('postural_deviations')->insertGetId(array_merge($data['deviation'], [
                'created_at' => now(), 'updated_at' => now(),
            ]));

            DB::table('deviation_muscle')->insert([
                'postural_deviation_id' => $devId,
                'muscle_id' => $muscleId,
                'muscle_state' => 'shortened',
                'created_at' => now(), 'updated_at' => now(),
            ]);

            $stretchId = DB::table('stretches')->insertGetId(array_merge($data['stretch'], [
                'target_muscle_id' => $muscleId,
                'created_at' => now(), 'updated_at' => now(),
            ]));

            DB::table('deviation_stretch')->insert([
                'postural_deviation_id' => $devId,
                'stretch_id' => $stretchId,
                'priority_order' => 1,
                'created_at' => now(), 'updated_at' => now(),
            ]);

            foreach ($data['steps'] as $step) {
                DB::table('pnf_steps')->insert(array_merge($step, [
                    'stretch_id' => $stretchId,
                    'ui_color_accent' => '#E0AC69',
                    'created_at' => now(), 'updated_at' => now(),
                ]));
            }
        }
    }
}