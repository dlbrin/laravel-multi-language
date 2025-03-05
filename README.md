Laravel MultiLang Package
Laravel MultiLang is a powerful and flexible package for adding multi-language support to your Laravel application. It allows you to easily handle multiple languages, translate content, and switch between languages in a clean and efficient way.

This package supports easy integration into your Laravel application and provides a simple way to manage translations for various languages.

Table of Contents
Installation
Configuration
Usage
Publish Migrations and Seeders
Add Multi-language Fields
Use Translations in Views
Components
Seeding Languages
Contributing
License
Installation
Step 1: Install via Composer
Run the following command to install the package via Composer:

bash
Copy
Edit
composer require dilbrinazad/laravel-multi-lang
This will add the package to your composer.json file and install it into your Laravel application.

Configuration
After installing the package, you need to publish the package configuration file.

Step 2: Publish Configuration File
Publish the package’s configuration file using the following command:

bash
Copy
Edit
php artisan vendor:publish --provider="DilbrinAzad\LaravelMultiLang\Src\Providers\LaravelMultiLangServiceProvider" --tag="config"
This will create a multilang.php file in your config directory, where you can customize language settings.

Usage
Publish Migrations and Seeders
Publish the migrations and seeders to set up the necessary database structure for the multi-language feature.

Run the following command to publish the migrations:

bash
Copy
Edit
php artisan vendor:publish --provider="DilbrinAzad\LaravelMultiLang\Src\Providers\LaravelMultiLangServiceProvider" --tag="migrations"
Once the migrations are published, run the migration command to create the necessary tables in your database:

bash
Copy
Edit
php artisan migrate
Next, publish the seeders for language:

bash
Copy
Edit
php artisan vendor:publish --provider="DilbrinAzad\LaravelMultiLang\Src\Providers\LaravelMultiLangServiceProvider" --tag="seeders"
Run the seeder to populate default languages:

bash
Copy
Edit
php artisan db:seed --class=LanguageSeeder
Add Multi-language Fields
Once the migrations are set up, you can use the multi-language fields in your models and views. This will allow you to store data for multiple languages.

Example:

In your migration file:
php
Copy
Edit
Schema::create('languages', function (Blueprint $table) {
    $table->id();
    $table->string('locale');
    $table->timestamps();
});
In your model:
php
Copy
Edit
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $fillable = ['locale'];
}
Use Translations in Views
You can now use translations in your views.

blade
Copy
Edit
@lang('messages.welcome')
This will automatically display the translation for the current language.

Components
This package includes several view components that help you manage multi-language content more efficiently.

Multi-Language Input Field
You can use the provided multi-language input component to handle translations for different languages.

Example Usage in a Blade View:

blade
Copy
Edit
<x-multilang-input label="Welcome Message" name="welcome_message" :other-languages="$languages" />
This will render an input field for each language, allowing you to input translations.

Seeding Languages
If you need to add languages to your system, use the provided seeder class. By default, languages are seeded from the config/languages.php file.

Example:

php
Copy
Edit
// Add to config/languages.php
return [
    'languages' => [
        ['locale' => 'en', 'title' => 'English'],
        ['locale' => 'es', 'title' => 'Spanish'],
        // Add more languages
    ]
];
Then, run the following command:

bash
Copy
Edit
php artisan db:seed --class=LanguageSeeder
Contributing
We welcome contributions to improve this package! If you'd like to contribute, please follow these steps:

Fork the repository.
Create a new branch for your changes.
Make your changes and ensure tests pass.
Submit a pull request with a clear description of your changes.
License
This package is open-source and available under the MIT License.

Example Project
Below is an example of how you might set up a basic Laravel project with this package:

Install Laravel

If you don't already have a Laravel project, create one by running:

bash
Copy
Edit
laravel new my-laravel-app
cd my-laravel-app
Install the MultiLang Package

Run the following command:

bash
Copy
Edit
composer require dilbrinazad/laravel-multi-lang
Publish Migrations, Seeders, and Configuration

bash
Copy
Edit
php artisan vendor:publish --provider="DilbrinAzad\LaravelMultiLang\Src\Providers\LaravelMultiLangServiceProvider" --tag="config"
php artisan vendor:publish --provider="DilbrinAzad\LaravelMultiLang\Src\Providers\LaravelMultiLangServiceProvider" --tag="migrations"
php artisan vendor:publish --provider="DilbrinAzad\LaravelMultiLang\Src\Providers\LaravelMultiLangServiceProvider" --tag="seeders"
Run Migrations

bash
Copy
Edit
php artisan migrate
Seed Languages

bash
Copy
Edit
php artisan db:seed --class=LanguageSeeder
Use MultiLang Components in Your Views

Add the component to your Blade view:

blade
Copy
Edit
<x-multilang-input label="Your Label" name="field_name" :other-languages="$languages" />
Final Notes
This package allows for a smooth and scalable integration of multi-language support in your Laravel applications. Customize it as needed for your project, and feel free to extend the functionality to suit your specific use case.
