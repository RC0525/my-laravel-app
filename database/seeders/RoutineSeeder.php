<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Routine;

class RoutineSeeder extends Seeder
{
    public function run(): void
    {
        // 1. PNF Stretching Protocols
        $pnfChest = Routine::create([
            'name' => 'PNF Chest Stretch Protocol',
            'type' => 'pnf',
            'description' => 'Hold-Relax PNF stretching for pectoralis major & shoulder anterior capsule.',
            'category' => 'chest'
        ]);

        $pnfChest->phases()->createMany([
            ['phase_order' => 1, 'name' => 'PREPARATION', 'duration_seconds' => 10, 'instruction' => 'Position chest in initial comfortable stretch position.'],
            ['phase_order' => 2, 'name' => 'ISOMETRIC CONTRACTION', 'duration_seconds' => 6, 'instruction' => 'Contract chest muscles against resistance at ~50% effort!'],
            ['phase_order' => 3, 'name' => 'RELAXATION', 'duration_seconds' => 2, 'instruction' => 'Completely release muscle tension.'],
            ['phase_order' => 4, 'name' => 'STATIC STRETCH', 'duration_seconds' => 30, 'instruction' => 'Push gently deeper into new range of motion and hold.']
        ]);

        $pnfHamstrings = Routine::create([
            'name' => 'PNF Hamstrings Stretch Protocol',
            'type' => 'pnf',
            'description' => 'Hold-Relax PNF stretching for hamstring muscle complex.',
            'category' => 'hamstrings'
        ]);

        $pnfHamstrings->phases()->createMany([
            ['phase_order' => 1, 'name' => 'PREPARATION', 'duration_seconds' => 10, 'instruction' => 'Elevate leg into passive hamstring stretch.'],
            ['phase_order' => 2, 'name' => 'ISOMETRIC CONTRACTION', 'duration_seconds' => 6, 'instruction' => 'Push heel downward against partner or strap at ~50% effort!'],
            ['phase_order' => 3, 'name' => 'RELAXATION', 'duration_seconds' => 2, 'instruction' => 'Relax leg completely.'],
            ['phase_order' => 4, 'name' => 'STATIC STRETCH', 'duration_seconds' => 30, 'instruction' => 'Deepen hamstring stretch for 30 seconds.']
        ]);

        // 2. TUT Resistance Training Protocols
        $tutSquat = Routine::create([
            'name' => 'Goblet Squat TUT Tempo (4-1-1-0)',
            'type' => 'tut',
            'description' => 'Hypertrophy-focused Time Under Tension squat tempo.',
            'category' => 'legs'
        ]);

        $tutSquat->phases()->createMany([
            ['phase_order' => 1, 'name' => 'ECCENTRIC', 'duration_seconds' => 4, 'instruction' => 'Lower hips down under control (4s).'],
            ['phase_order' => 2, 'name' => 'BOTTOM PAUSE', 'duration_seconds' => 1, 'instruction' => 'Pause and stabilize at the bottom (1s).'],
            ['phase_order' => 3, 'name' => 'CONCENTRIC', 'duration_seconds' => 1, 'instruction' => 'Drive through heels to stand up explosives (1s).'],
            ['phase_order' => 4, 'name' => 'TOP HOLD', 'duration_seconds' => 0, 'instruction' => 'Immediate transition at top.']
        ]);

        // 3. Postural Correction Exercises
        $postureYTWL = Routine::create([
            'name' => 'Prone Y-T-W-L Raises',
            'type' => 'posture',
            'description' => 'Strengthens lower traps and rhomboids while activating posterior shoulder muscles.',
            'category' => 'ucs'
        ]);

        $postureYTWL->phases()->create([
            'phase_order' => 1,
            'name' => 'POSTURE HOLD',
            'duration_seconds' => 30,
            'instruction' => 'Maintain prone neutral spine and hold arm raises at peak contraction.'
        ]);

        $postureWallSlides = Routine::create([
            'name' => 'Scapular Wall Slides',
            'type' => 'posture',
            'description' => 'Improves thoracic mobility and activates serratus anterior to correct winging scapula.',
            'category' => 'ucs'
        ]);

        $postureWallSlides->phases()->create([
            'phase_order' => 1,
            'name' => 'POSTURE HOLD',
            'duration_seconds' => 45,
            'instruction' => 'Keep back, head, and elbows flat against wall while sliding arms upward.'
        ]);

        $postureBridge = Routine::create([
            'name' => 'Posterior Pelvic Tilt Glute Bridge',
            'type' => 'posture',
            'description' => 'Activates underactive gluteus maximus and hamstrings to rotate pelvis posteriorly.',
            'category' => 'apt'
        ]);

        $postureBridge->phases()->create([
            'phase_order' => 1,
            'name' => 'POSTURE HOLD',
            'duration_seconds' => 40,
            'instruction' => 'Squeeze glutes and tuck pelvis at the top position.'
        ]);

        $posturePsoas = Routine::create([
            'name' => 'Half-Kneeling Psoas Release',
            'type' => 'posture',
            'description' => 'Inhibits tight, shortened hip flexors pulling pelvis into APT.',
            'category' => 'apt'
        ]);

        $posturePsoas->phases()->create([
            'phase_order' => 1,
            'name' => 'POSTURE HOLD',
            'duration_seconds' => 30,
            'instruction' => 'Tuck tailbone under before leaning forward into hip flexor stretch.'
        ]);
    }
}