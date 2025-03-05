<?php

namespace DilbrinAzad\LaravelMultiLang\Src\Http\Controllers;

use App\Http\Controllers\Controller;
use DilbrinAzad\LaravelMultiLang\Src\Models\LanguageText;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function getLanguageTextTranslation($text_id)
    {
        $languageText = LanguageText::with('translations')->find($text_id);
        if (!$languageText) {
            return response()->json(['error' => 'Language text not found.'], 404);
        }
        return response()->json($languageText->translations);
    }
}
