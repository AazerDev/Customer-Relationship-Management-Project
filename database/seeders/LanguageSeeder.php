<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        DB::table('languages')->truncate();

        $languages = [
            // Most common languages first
            ['code' => 'en', 'name' => 'English', 'native_name' => 'English', 'flag' => '🇺🇸', 'is_rtl' => false, 'sort_order' => 1],
            ['code' => 'es', 'name' => 'Spanish', 'native_name' => 'Español', 'flag' => '🇪🇸', 'is_rtl' => false, 'sort_order' => 2],
            ['code' => 'fr', 'name' => 'French', 'native_name' => 'Français', 'flag' => '🇫🇷', 'is_rtl' => false, 'sort_order' => 3],
            ['code' => 'de', 'name' => 'German', 'native_name' => 'Deutsch', 'flag' => '🇩🇪', 'is_rtl' => false, 'sort_order' => 4],
            ['code' => 'it', 'name' => 'Italian', 'native_name' => 'Italiano', 'flag' => '🇮🇹', 'is_rtl' => false, 'sort_order' => 5],
            ['code' => 'pt', 'name' => 'Portuguese', 'native_name' => 'Português', 'flag' => '🇵🇹', 'is_rtl' => false, 'sort_order' => 6],
            ['code' => 'ru', 'name' => 'Russian', 'native_name' => 'Русский', 'flag' => '🇷🇺', 'is_rtl' => false, 'sort_order' => 7],
            ['code' => 'zh', 'name' => 'Chinese', 'native_name' => '中文', 'flag' => '🇨🇳', 'is_rtl' => false, 'sort_order' => 8],
            ['code' => 'ja', 'name' => 'Japanese', 'native_name' => '日本語', 'flag' => '🇯🇵', 'is_rtl' => false, 'sort_order' => 9],
            ['code' => 'ko', 'name' => 'Korean', 'native_name' => '한국어', 'flag' => '🇰🇷', 'is_rtl' => false, 'sort_order' => 10],
            
            // Additional European languages
            ['code' => 'nl', 'name' => 'Dutch', 'native_name' => 'Nederlands', 'flag' => '🇳🇱', 'is_rtl' => false, 'sort_order' => 11],
            ['code' => 'sv', 'name' => 'Swedish', 'native_name' => 'Svenska', 'flag' => '🇸🇪', 'is_rtl' => false, 'sort_order' => 12],
            ['code' => 'no', 'name' => 'Norwegian', 'native_name' => 'Norsk', 'flag' => '🇳🇴', 'is_rtl' => false, 'sort_order' => 13],
            ['code' => 'da', 'name' => 'Danish', 'native_name' => 'Dansk', 'flag' => '🇩🇰', 'is_rtl' => false, 'sort_order' => 14],
            ['code' => 'fi', 'name' => 'Finnish', 'native_name' => 'Suomi', 'flag' => '🇫🇮', 'is_rtl' => false, 'sort_order' => 15],
            ['code' => 'pl', 'name' => 'Polish', 'native_name' => 'Polski', 'flag' => '🇵🇱', 'is_rtl' => false, 'sort_order' => 16],
            ['code' => 'cs', 'name' => 'Czech', 'native_name' => 'Čeština', 'flag' => '🇨🇿', 'is_rtl' => false, 'sort_order' => 17],
            ['code' => 'hu', 'name' => 'Hungarian', 'native_name' => 'Magyar', 'flag' => '🇭🇺', 'is_rtl' => false, 'sort_order' => 18],
            ['code' => 'ro', 'name' => 'Romanian', 'native_name' => 'Română', 'flag' => '🇷🇴', 'is_rtl' => false, 'sort_order' => 19],
            ['code' => 'bg', 'name' => 'Bulgarian', 'native_name' => 'Български', 'flag' => '🇧🇬', 'is_rtl' => false, 'sort_order' => 20],
            
            // Middle Eastern & RTL languages
            ['code' => 'ar', 'name' => 'Arabic', 'native_name' => 'العربية', 'flag' => '🇸🇦', 'is_rtl' => true, 'sort_order' => 21],
            ['code' => 'he', 'name' => 'Hebrew', 'native_name' => 'עברית', 'flag' => '🇮🇱', 'is_rtl' => true, 'sort_order' => 22],
            ['code' => 'fa', 'name' => 'Persian', 'native_name' => 'فارسی', 'flag' => '🇮🇷', 'is_rtl' => true, 'sort_order' => 23],
            ['code' => 'ur', 'name' => 'Urdu', 'native_name' => 'اردو', 'flag' => '🇵🇰', 'is_rtl' => true, 'sort_order' => 24],
            
            // South Asian languages
            ['code' => 'hi', 'name' => 'Hindi', 'native_name' => 'हिन्दी', 'flag' => '🇮🇳', 'is_rtl' => false, 'sort_order' => 25],
            ['code' => 'bn', 'name' => 'Bengali', 'native_name' => 'বাংলা', 'flag' => '🇧🇩', 'is_rtl' => false, 'sort_order' => 26],
            ['code' => 'ta', 'name' => 'Tamil', 'native_name' => 'தமிழ்', 'flag' => '🇮🇳', 'is_rtl' => false, 'sort_order' => 27],
            
            // Southeast Asian languages
            ['code' => 'th', 'name' => 'Thai', 'native_name' => 'ไทย', 'flag' => '🇹🇭', 'is_rtl' => false, 'sort_order' => 28],
            ['code' => 'vi', 'name' => 'Vietnamese', 'native_name' => 'Tiếng Việt', 'flag' => '🇻🇳', 'is_rtl' => false, 'sort_order' => 29],
            ['code' => 'id', 'name' => 'Indonesian', 'native_name' => 'Bahasa Indonesia', 'flag' => '🇮🇩', 'is_rtl' => false, 'sort_order' => 30],
            ['code' => 'ms', 'name' => 'Malay', 'native_name' => 'Bahasa Melayu', 'flag' => '🇲🇾', 'is_rtl' => false, 'sort_order' => 31],
            
            // African languages
            ['code' => 'sw', 'name' => 'Swahili', 'native_name' => 'Kiswahili', 'flag' => '🇰🇪', 'is_rtl' => false, 'sort_order' => 32],
            ['code' => 'af', 'name' => 'Afrikaans', 'native_name' => 'Afrikaans', 'flag' => '🇿🇦', 'is_rtl' => false, 'sort_order' => 33],
            
            // Other popular languages
            ['code' => 'tr', 'name' => 'Turkish', 'native_name' => 'Türkçe', 'flag' => '🇹🇷', 'is_rtl' => false, 'sort_order' => 34],
            ['code' => 'el', 'name' => 'Greek', 'native_name' => 'Ελληνικά', 'flag' => '🇬🇷', 'is_rtl' => false, 'sort_order' => 35],
            ['code' => 'uk', 'name' => 'Ukrainian', 'native_name' => 'Українська', 'flag' => '🇺🇦', 'is_rtl' => false, 'sort_order' => 36],
        ];

        // Insert languages
        foreach ($languages as $language) {
            Language::create($language);
        }

        $this->command->info('Languages seeded successfully!');
    }
}
