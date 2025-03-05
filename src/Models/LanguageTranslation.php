<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LanguageTranslation extends Model
{
    protected $table = 'language_translations';
    protected $fillable = ['language_text_id', 'language_id', 'content'];

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }
}
