<?php

namespace DilbrinAzad\LaravelMultiLang\Src\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LanguageText extends Model
{
    protected $table = 'language_texts';
    protected $fillable = ['default_content'];

    public function translations(): HasMany
    {
        return $this->hasMany(LanguageTranslation::class, 'language_text_id');
    }
}
