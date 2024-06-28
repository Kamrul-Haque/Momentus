<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::create([
            'name' => 'Limbo Hub',
            'email' => 'info@limbohub.com',
            'phone' => '0123456789',
            'copyright_text' => 'Copyright © Limbo Hub. All Rights Reserved.',
            'description' => 'Limbo Hub is a Bangladesh based software company owned and managed by a team of IT professionals and technocrats, who have local and global experience in business process exploration, automation and consultancy for ICT products measuring the present market demand. The team has the unique skills that assure outstanding performance through consistency, accuracy, reliability, punctuality and honesty. Buyonia Bangladesh Limited is a concern of Buyonia Limited.',
            'primary_color' => '#111827',
            'secondary_color' => '#1f2937',
            'accent_color' => '#0033CC',
            'background_color' => '#F3F4F6',
            'table_stripe_color' => '#EAECF1',
        ]);
    }
}
