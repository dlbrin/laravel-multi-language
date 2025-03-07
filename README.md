# Laravel MultiLang Package

**Laravel MultiLang** is a powerful and flexible package designed to seamlessly integrate multi-language support into your Laravel application. This package enables efficient handling of multiple languages, content translation, and language switching in a structured and scalable manner.

## Table of Contents

1. [Installation](#installation)
2. [Configuration](#configuration)
3. [Usage](#usage)
    - [Database Migrations](#database-migrations)
    - [Seeding Languages](#seeding-languages)
    - [Model Structure](#model-structure)
    - [Traits](#traits)
    - [View Components](#view-components)
4. [Contributing](#contributing)
5. [License](#license)

---

## Installation

### Step 1: Install via Composer

Run the following command to install the package:

```bash
composer require dilbrinazad/laravel-multi-lang
```

This will add the package to your Laravel project and make it available for use.

---

## Configuration

After installation, publish the package resources using:

```bash
php artisan vendor:publish --provider="DilbrinAzad\LaravelMultiLang\Providers\LaravelMultiLangServiceProvider" --tag="multilang-resources"
```

This will publish the following resources:
- **Configuration File:** `config/multilang.php`
- **Database Migrations** for language management
- **Seeders** for preloading language data
- **Models** for multilingual entities
- **Controllers** for language handling
- **Traits** for reusable multilingual functionality
- **View Components** for easy language selection and input

---

## Usage

### Database Migrations

Run the migration command to create the necessary tables:

```bash
php artisan migrate
```

This will generate three tables:

#### `languages` Table
```php
Schema::create('languages', function (Blueprint $table) {
    $table->id();
    $table->string('title'); // Display name of the language
    $table->string('name'); // Language name in English
    $table->string('country_code'); // ISO country code
    $table->string('dir'); // Text direction ('ltr' or 'rtl')
    $table->string('locale')->unique(); // Unique language identifier
    $table->boolean('status')->default(1); // Active or inactive status
    $table->timestamps();
});
```

#### `language_texts` Table
```php
Schema::create('language_texts', function (Blueprint $table) {
    $table->id();
    $table->text('default_content'); // Original text before translation
    $table->timestamps();
});
```

#### `language_translations` Table
```php
Schema::create('language_translations', function (Blueprint $table) {
    $table->id();
    $table->foreignId('language_text_id')->constrained('language_texts')->onDelete('cascade');
    $table->foreignId('language_id')->constrained('languages')->onDelete('cascade');
    $table->text('content'); // Translated content
    $table->timestamps();
});
```

### Seeding Languages

You can seed default languages by running:

```bash
php artisan db:seed --class=LanguageSeeder
```

By default, the following languages are included:

```php
$languages = [
    ['title' => 'English', 'name' => 'English', 'country_code' => 'US', 'dir' => 'ltr', 'locale' => 'en', 'status' => 1],
    ['title' => 'Kurdish', 'name' => 'Kurdish', 'country_code' => 'KU', 'dir' => 'rtl', 'locale' => 'ku', 'status' => 1],
];
```

You can modify this list to include any language you need.

---

## Model Structure

### Language Model
```php
class Language extends Model {
    protected $table = 'languages';
    protected $fillable = ['title', 'name', 'country_code', 'dir', 'locale', 'status'];
}
```

### LanguageText Model
```php
class LanguageText extends Model {
    protected $table = 'language_texts';
    protected $fillable = ['default_content'];

    public function translations(): HasMany {
        return $this->hasMany(LanguageTranslation::class, 'language_text_id');
    }
}
```

### LanguageTranslation Model
```php
class LanguageTranslation extends Model {
    protected $table = 'language_translations';
    protected $fillable = ['language_text_id', 'language_id', 'content'];

    public function language(): BelongsTo {
        return $this->belongsTo(Language::class);
    }
}
```

---

## Traits

### `LanguageTranslationTrait`

This trait simplifies storing and updating translations:

```php
trait LanguageTranslationTrait {
    public function storeLangTranslation($model): void {
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
}
```

This trait ensures that translations are efficiently stored and updated while handling errors gracefully.

---

## View Components

### `MultiLanguageInput` Component

This component provides a customizable multilingual input field:

```php
class MultiLanguageInput extends Component {
    public function __construct(
        public $label = '',
        public $type = 'text',
        public $name = '',
        public $placeholder = '',
        public $value = '',
        public $textId = 0
    ) {}

    public function render(): View|Closure|string {
        return view('components.multi-language-input');
    }
}
```

### Blade Template for Component

```html
@props(['label' => '', 'type' => 'text', 'name' => '', 'placeholder'=> '', 'value' => '', 'text_id'=> 0])

<div>
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700">{{ $label }}</label>
    <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}" placeholder="{{ $placeholder }}" value="{{ $value }}"
           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
</div>
```

---

## Contributing

Contributions are welcome! Feel free to submit issues and pull requests to improve the package.

---

## License

This package is open-source and available under the MIT License.

---

By following this guide, you will have a fully functional multilingual system integrated into your Laravel application with robust support for translations and dynamic language switching.

