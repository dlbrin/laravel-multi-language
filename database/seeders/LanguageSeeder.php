<?php

namespace Database\Seeders;

use DilbrinAzad\LaravelMultiLang\Src\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = [
            [
                'title' => 'English',
                'name' => 'English',
                'country_code' => 'US',
                'dir' => 'ltr',
                'locale' => 'en',
                'status' => 1,
            ],
            [
                'title' => 'Kurdish',
                'name' => 'Kurdish',
                'country_code' => 'KU',
                'dir' => 'rtl',
                'locale' => 'ku',
                'status' => 1,
            ]
        ];

        foreach ($languages as $language) {
            Language::firstOrCreate(['locale' => $language['locale']], $language);
        }
    }
}
