<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PnfEnglishStepsSeeder extends Seeder
{
    public function run(): void
    {
        $defaultSteps = [
            1 => ['Initial Prep', 'Set your starting posture with a neutral spine and relaxed shoulders.'],
            2 => ['Initial Stretch', 'Move gently into a mild, pain-free stretch in the target muscle.'],
            3 => ['Isometric Hold', 'Contract against resistance at ~50% max effort while holding position steady.'],
            4 => ['Brief Relax', 'Exhale and release the contraction completely to let the muscle relax.'],
            5 => ['Deep Stretch', 'Move slightly deeper into the new range of motion without pain.'],
            6 => ['Recovery', 'Return slowly to the starting position and relax completely.'],
        ];

        // Match by Chinese keywords inside step_name_zh or instruction
        $keywordSteps = [
            '上斜方' => [
                1 => 'Sit tall with shoulders relaxed and one hand holding the seat edge.',
                2 => 'Tilt your head gently to the side until you feel a mild upper trapezius stretch.',
                3 => 'Press your head lightly back toward your hand (~30–50% effort) without moving.',
                4 => 'Exhale and release neck tension completely.',
                5 => 'Gently guide your head slightly further into the new stretch range.',
                6 => 'Bring your head slowly back to center and relax.',
            ],
            '股四頭' => [
                1 => 'Lie on your side with your lower arm supporting your head and top hand holding your ankle.',
                2 => 'Pull your heel gently toward your glutes while keeping hips stacked.',
                3 => 'Press your foot gently back against your hand (~50% effort) isometrically.',
                4 => 'Exhale and relax quadriceps tension.',
                5 => 'Draw the heel slightly closer with a gentle glute squeeze.',
                6 => 'Lower your leg slowly and relax.',
            ],
            '髂腰肌' => [
                1 => 'Assume a half-kneeling lunge with pelvis neutral and core lightly braced.',
                2 => 'Shift your hips gently forward until you feel a mild stretch in the front of the rear hip.',
                3 => 'Press your rear shin/foot gently into the floor (~50% effort) without shifting.',
                4 => 'Exhale and let the front of the hip relax.',
                5 => 'Shift slightly further forward into the deeper stretch range.',
                6 => 'Ease back to the starting position and relax.',
            ],
            '臀大肌' => [
                1 => 'Lie on your back with one knee bent and hold behind or over your knee.',
                2 => 'Draw your knee gently toward your chest until you feel a mild glute stretch.',
                3 => 'Press your knee lightly away into your hands (~50% effort) isometrically.',
                4 => 'Exhale and relax the glute muscles completely.',
                5 => 'Pull the knee slightly closer toward your chest into the new range.',
                6 => 'Lower your leg slowly and relax.',
            ],
            '闊背' => [
                1 => 'Sit tall and raise one arm overhead with posture upright.',
                2 => 'Side-bend your torso gently to the opposite side until you feel a lat stretch.',
                3 => 'Pull the raised arm downward/backward against resistance (~50% effort).',
                4 => 'Exhale and release the contraction completely.',
                5 => 'Side-bend slightly further into the new range of motion.',
                6 => 'Return to upright sitting and lower your arm slowly.',
            ],
            '腓腸肌' => [
                1 => 'Face a wall with hands supported and step one foot back with knee straight.',
                2 => 'Lean forward gently while keeping the back heel down for a mild calf stretch.',
                3 => 'Press the ball of your back foot into the floor (~50% effort) without lifting the heel.',
                4 => 'Exhale and relax calf tension.',
                5 => 'Lean slightly further forward to deepen the gastrocnemius stretch.',
                6 => 'Step back to neutral standing and relax.',
            ],
            '梨狀肌' => [
                1 => 'Lie on your back and cross one ankle over the opposite knee in a figure-4 shape.',
                2 => 'Draw the bottom thigh gently toward your chest until you feel a deep glute/piriformis stretch.',
                3 => 'Press the crossed outer hip/ankle gently outward (~50% effort) isometrically.',
                4 => 'Exhale and relax hip tension completely.',
                5 => 'Pull the thigh slightly closer to deepen the stretch.',
                6 => 'Uncross and lower both legs slowly.',
            ],
            '前臂' => [
                1 => 'Stand near a wall with one arm straight, palm against the wall, fingers pointing down.',
                2 => 'Turn or lean gently until you feel a mild stretch along the inner forearm.',
                3 => 'Press fingers/palm lightly into the wall (~40–50% effort) without moving.',
                4 => 'Exhale and relax the forearm flexors.',
                5 => 'Adjust slightly deeper into the stretch range.',
                6 => 'Release and shake out your wrist gently.',
            ],
            '胸小肌' => [
                1 => 'Stand in a doorway with elbow/forearm raised above shoulder height (~110–135°).',
                2 => 'Step one foot forward and lean through gently for a pec minor stretch.',
                3 => 'Press forearm/elbow lightly into the frame (~50% effort) isometrically.',
                4 => 'Exhale and relax chest and shoulder tension.',
                5 => 'Step slightly further forward into the new range.',
                6 => 'Step back and lower your arm slowly.',
            ],
            '腹' => [
                1 => 'Lie face down with hands beside shoulders and legs relaxed.',
                2 => 'Lift your chest gently off the floor until you feel a mild abdominal stretch.',
                3 => 'Engage core/abdominals lightly downward against the lift (~30–50% effort).',
                4 => 'Exhale and soften the abdominal wall.',
                5 => 'Lift slightly higher within a comfortable, pain-free range.',
                6 => 'Lower your chest slowly to the floor and relax.',
            ],
            '斜肌' => [
                1 => 'Sit tall with feet flat and posture upright.',
                2 => 'Side-bend and rotate gently to one side until you feel a stretch along the side waist.',
                3 => 'Resist gently back toward neutral (~30–50% effort) without twisting away.',
                4 => 'Exhale and relax the oblique muscles.',
                5 => 'Side-bend slightly deeper into the new range.',
                6 => 'Return slowly to center and relax.',
            ],
            '股直肌' => [
                1 => 'Assume a half-kneeling lunge with rear foot elevated or held behind you.',
                2 => 'Tuck pelvis slightly and shift weight gently until you feel a front-thigh stretch.',
                3 => 'Press rear shin/foot gently backward against resistance (~50% effort).',
                4 => 'Exhale and relax the rectus femoris.',
                5 => 'Deepen the stretch slightly while keeping your lower back neutral.',
                6 => 'Release slowly and return to starting position.',
            ],
            '縫匠肌' => [
                1 => 'Lie on your back with knees bent and soles of the feet together.',
                2 => 'Let knees open gently outward until you feel a mild inner/front hip stretch.',
                3 => 'Press knees lightly inward/upward against light resistance (~30–50% effort).',
                4 => 'Exhale and let knees relax outward.',
                5 => 'Allow knees to open slightly further into the new range.',
                6 => 'Bring knees together gently and relax.',
            ],
            '內收' => [
                1 => 'Sit tall with soles of the feet together and knees relaxed outward.',
                2 => 'Hinge gently forward from the hips until you feel a mild inner-thigh stretch.',
                3 => 'Press knees gently upward against light hand/elbow resistance (~50% effort).',
                4 => 'Exhale and relax the adductor muscles.',
                5 => 'Lean slightly further forward or press knees gently down.',
                6 => 'Sit upright and bring knees together slowly.',
            ],
            '比目魚' => [
                1 => 'Face a wall with hands on the wall and one foot stepped back.',
                2 => 'Bend both knees slightly while keeping the back heel planted for a lower-calf stretch.',
                3 => 'Press the ball of the back foot gently into the floor (~50% effort) heel-down.',
                4 => 'Exhale and relax soleus tension.',
                5 => 'Bend the knee slightly further forward into the new range.',
                6 => 'Step back to neutral and relax.',
            ],
            '脛前' => [
                1 => 'Sit comfortably with legs extended or slightly bent and ankles relaxed.',
                2 => 'Point toes and gently plantarflex/invert the ankle for a front-shin stretch.',
                3 => 'Pull toes/foot gently upward toward your shin (~30–50% effort) isometrically.',
                4 => 'Exhale and relax the front of the lower leg.',
                5 => 'Point slightly further into a comfortable stretch range.',
                6 => 'Return ankle to neutral and relax.',
            ],
            '二頭' => [
                1 => 'Stand or sit tall and clasp hands or hold a strap behind your back.',
                2 => 'Lift arms gently upward/backward until you feel a mild stretch in the front of the arm.',
                3 => 'Press arms gently downward/forward against resistance (~30–50% effort).',
                4 => 'Exhale and relax biceps and front-shoulder tension.',
                5 => 'Lift slightly higher into the new range without shrugging.',
                6 => 'Lower arms slowly and unclasping hands.',
            ],
            '三頭' => [
                1 => 'Sit or stand tall and raise one arm overhead, bending the elbow behind your head.',
                2 => 'Use your other hand on the elbow to guide a gentle triceps stretch.',
                3 => 'Extend the elbow gently against your guiding hand (~50% effort) without moving.',
                4 => 'Exhale and relax the back of the upper arm.',
                5 => 'Guide the elbow slightly further into the new range.',
                6 => 'Lower your arm slowly and relax.',
            ],
            '提肩胛' => [
                1 => 'Sit tall and turn your head ~45° to one side, then look down toward your armpit.',
                2 => 'Place your hand lightly on the back of your head for a mild levator scapulae stretch.',
                3 => 'Press your head gently backward/upward into your hand (~30–50% effort).',
                4 => 'Exhale and relax neck and shoulder tension.',
                5 => 'Guide chin slightly closer toward the chest/armpit in the new range.',
                6 => 'Lift head slowly back to center and relax.',
            ],
            '頭夾肌' => [
                1 => 'Sit tall with shoulders relaxed and posture neutral.',
                2 => 'Gently tuck/flex your neck slightly while keeping upper back upright for a neck stretch.',
                3 => 'Press the back of your head lightly backward against light resistance (~30% effort).',
                4 => 'Exhale and relax the posterior neck muscles.',
                5 => 'Flex slightly further within a gentle, comfortable range.',
                6 => 'Return head slowly to neutral and relax.',
            ],
        ];

        $stepsByStretch = DB::table('pnf_steps')
            ->orderBy('recommended_stretch_id')
            ->orderBy('step_number')
            ->get()
            ->groupBy('recommended_stretch_id');

        foreach ($stepsByStretch as $stretchId => $steps) {
            $combinedZh = $steps->map(fn ($s) => ($s->step_name_zh ?? '') . ' ' . ($s->instruction ?? ''))->implode(' ');
            $matchedCustom = null;

            foreach ($keywordSteps as $kw => $customMap) {
                if (mb_strpos($combinedZh, $kw) !== false) {
                    $matchedCustom = $customMap;
                    break;
                }
            }

            foreach ($steps as $step) {
                $num = (int) $step->step_number;
                $nameEn = $defaultSteps[$num][0] ?? "Step {$num}";
                $instEn = $matchedCustom[$num] ?? ($defaultSteps[$num][1] ?? $step->instruction);

                DB::table('pnf_steps')
                    ->where('id', $step->id)
                    ->update([
                        'step_name_en' => $nameEn,
                        'instruction_en' => $instEn,
                    ]);
            }
        }
    }
}