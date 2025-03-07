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

## Components

### `MultiLanguageInput` Component

This component provides a customizable multilingual input field

```php
class MultiLanguageInput extends Component {
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
```

## **How It Works**

### **1. Component Class (`MultiLanguageInput.php`)**
- The component is located in `App\View\Components\MultiLanguageInput`.
- It extends Laravel’s `Component` class, making it reusable in Blade templates.
- The constructor accepts multiple parameters for configuration:
 ```php
    class MultiLanguageInput extends Component {
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
    }
  ```
  - `$label`: The label text for the input field.
  - `$type`: Input field type (default: `text`).
  - `$name`: The name attribute of the input.
  - `$placeholder`: Placeholder text.
  - `$value`: Pre-filled value (if any).
  - `$textId`: ID for retrieving translations via AJAX.
  - `$currentLanguage`: The primary language for input.
  - `$otherLanguages`: Additional languages for translation.
 
 

- **Fetching Languages:**
  ```php
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
  ```
  - It retrieves all available languages from the `languages` table.
  - It identifies the **current language** from the config file (`multilang.default_locale`).
  - It filters out other available languages for translation.
  - Passes this data to the Blade component view.
---

### **2. Blade Component (`multi-language-input.blade.php`)**
- **Primary Input Field**
  - Displays an input field for the **current language**.
  - Includes a **label** and placeholder.

- **Dropdown for Other Languages**
  - If multiple languages exist, a **dropdown button** appears next to the input field.
  - Clicking the dropdown button reveals additional input fields for other languages.
  - Each language gets a separate text input.

- **JavaScript (jQuery) for Interactivity**
  - Clicking the **dropdown button** toggles the additional language fields.
  - Clicking outside the dropdown **closes it automatically**.
  - If `$textId` is provided, an AJAX request fetches saved translations and fills the input fields dynamically.

## **Example Usage**

### **For Creating a New Entry**
```blade
<x-multi-language-input 
    label="Name"
    type="text"
    name="name_lang_id"
    placeholder="Enter name..."
/>
```

### **For Updating an Existing Entry**
```blade
<x-multi-language-input 
    label="Name"
    type="text"
    name="name_lang_id"
    placeholder="Enter name..."
    :text_id="$lookup->name_lang_id ?? 0"
/>
```
The component retrieves existing translations using $lookup->name_lang_id.
An AJAX request fetches saved translations and fills the input fields automatically.

---

## Example

## **Introduction**
This guide explains how to integrate the `MultiLanguageInput` component for handling multilingual fields in Laravel using the **Hotel model** as an example.

---

## **1. How It Works**
The **MultiLanguageInput** component allows users to:
- Enter **text** in different languages.
- Retrieve **existing translations** dynamically via AJAX.
- Use a **dropdown** to toggle between language inputs.
- **Store & update translations** automatically with a trait.

---

## **2. Setting Up the Hotel Model**
### **Step 1: Create the Model and Use the Trait**
In your **Hotel model**, you need to:
1. **Use the `LanguageTranslationTrait`.**
2. **Define translatable fields** in the `$translatable` array.
3. **Use Eloquent model events** (`creating` & `updating`) to handle translations.

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LanguageTranslationTrait;

class Hotel extends Model
{
    use LanguageTranslationTrait;

    // Define translatable fields
    public array $translatable = ['name_lang_id'];

    protected $table = 'hotels';

    protected $fillable = ['name_lang_id'];

    // Automatically handle translations on create & update
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            return $model->storeLangTranslation($model);
        });

        static::updating(function ($model) {
            return $model->updateLangTranslation($model);
        });
    }
}
```

## **Explanation**

- **`use LanguageTranslationTrait;`** → Includes the trait responsible for handling translations.  
- **`public array $translatable = ['name_lang_id'];`** → Defines which fields support multiple languages.  
- **`protected static function boot();`**  
  - Calls `storeLangTranslation($model)` **before creating** a new record.  
  - Calls `updateLangTranslation($model)` **before updating** an existing record.  

---

## **3. Creating a Hotel with Multi-Language Support**

### **Step 2: Create the Form**  
To **create a new hotel**, use the `<x-multi-language-input>` component in your Blade file.

```blade
<form action="{{ route('hotels.store') }}" method="POST">
    @csrf
    <div class="form-group">
        <x-multi-language-input 
            label="Name"
            type="text"
            name="name_lang_id"
            placeholder="Enter hotel name..."
        />
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>
</form>
```
### **What Happens Here?**  
- Users enter a **name** for the hotel in the default language.  
- If **multiple languages exist**, a dropdown appears to add translations.  
- Upon **submission**, the translations are stored properly.  

---

## **4. Updating an Existing Hotel**

### **Step 3: Edit Form with Translations**  
To **edit** a hotel’s name and retrieve existing translations, pass the `text_id` attribute.

```blade
<form action="{{ route('hotels.update', $hotel->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="form-group">
        <x-multi-language-input 
            label="Name"
            type="text"
            name="name_lang_id"
            placeholder="Enter hotel name..."
            :text_id="$hotel->name_lang_id ?? 0"
        />
    </div>

    <button type="submit" class="btn btn-primary">Update</button>
</form>
```
### **What Happens Here?**  
- text_id is passed to fetch existing translations via AJAX.  
- Users can modify existing translations directly.
---

## Contributing

Contributions are welcome! Feel free to submit issues and pull requests to improve the package.

---

## License

This package is open-source and available under the MIT License.

---

By following this guide, you will have a fully functional multilingual system integrated into your Laravel application with robust support for translations and dynamic language switching.

