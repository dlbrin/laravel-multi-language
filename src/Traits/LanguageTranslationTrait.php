<?php

namespace App\Traits;

use App\Models\Language;
use App\Models\LanguageText;
use App\Models\LanguageTranslation;
use Illuminate\Support\Facades\DB;

trait LanguageTranslationTrait
{
    public function storeLangTranslation($model): void
    {
        DB::beginTransaction();
        try {
            foreach ($model->getTranslatableColumns() as $column) {
                $data = $model->attributes[$column];
                $defaultContent = $data[config('multilang.default_locale')] ?? null;
                $langText = LanguageText::create(['default_content' => $defaultContent]);

                foreach ($data as $locale => $content) {
                    if ($content) {
                        LanguageTranslation::create([
                            'language_text_id' => $langText->id,
                            'language_id' => $this->getLangIdByLocale($locale),
                            'content' => $content,
                        ]);
                    }
                }
                $model->attributes[$column] = $langText->id;
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }

    public function updateLangTranslation($model): void
    {
        DB::beginTransaction();
        try {
            $translatableColumns = $model->getTranslatableColumns() ?? [];
            if (count($translatableColumns) > 0) {
                foreach ($translatableColumns as $column) {
                    $data = $model->attributes[$column];
                    $modelBeforeUpdateData = $this->find($model->id);
                    $languageText = LanguageText::find($modelBeforeUpdateData->$column);
                    if ($languageText){
                        LanguageTranslation::where('language_text_id', $languageText['id'])->delete();
                        foreach ($data as $languageLocale => $content) {
                            if ($content) {
                                LanguageTranslation::create([
                                    'language_text_id' => $languageText->id,
                                    'language_id' => $this->getLangIdByLocale($languageLocale),
                                    'content' => $content,
                                ]);
                            }
                        }
                    }
                    $model->attributes[$column] = $languageText->id;
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }


    public function getLangIdByLocale($locale)
    {
        return Language::where('locale', $locale)->first()->id ?? null;
    }

    public function getTranslatableColumns()
    {
        return $this->translatable ?? [];
    }
}
