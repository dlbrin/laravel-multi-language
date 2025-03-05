<?php
namespace App\View\Components;

use App\Models\Language;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MultiLanguageInput extends Component
{
    /**
     * Create a new component instance.
     */
    public $label;
    public $type;
    public $name;
    public $placeholder;
    public $value;
    public $textId;
    public $currentLanguage;
    public $otherLanguages;

    public function __construct(
        $label = '',
        $type = 'text',
        $name = '',
        $placeholder = '',
        $value = '',
        $textId = 0,
        $currentLanguage = null,
        $otherLanguages = []
    ) {
        $this->label = $label;
        $this->type = $type;
        $this->name = $name;
        $this->placeholder = $placeholder;
        $this->value = $value;
        $this->textId = $textId;
        $this->currentLanguage = $currentLanguage;
        $this->otherLanguages = $otherLanguages;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $currentLanguageLocale = config('multilang.default_locale');

        $languages = Language::all()->keyBy('locale');
        $currentLanguage = $languages->firstWhere('locale', $currentLanguageLocale);
        $otherLanguages = $languages->filter(function ($language) use ($currentLanguageLocale) {
            return $language->locale !== $currentLanguageLocale;
        });
        return view('components.multi-language-input', compact('currentLanguage', 'otherLanguages'));
    }
}
