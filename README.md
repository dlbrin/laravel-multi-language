Laravel MultiLang Package Laravel MultiLang is a powerful and flexible
package for adding multi-language support to your Laravel application.
It allows you to easily handle multiple languages, translate content,
and switch between languages in a clean and efficient way. This package
supports easy integration into your Laravel application and provides a
simple way to manage translations for various languages.
\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_
Table of Contents 1. Installation 2. Configuration 3. Usage o Publish
Migrations and Seeders o Add Multi-language Fields o Use Translations in
Views 4. Components 5. Seeding Languages 6. Contributing 7. License
\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_
Installation Step 1: Install via Composer Run the following command to
install the package via Composer: bash CopyEdit composer require
dilbrinazad/laravel-multi-lang This will add the package to your
composer.json file and install it into your Laravel application.
\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_
Configuration After installing the package, you need to publish the
package configuration file. Step 2: Publish Configuration File Publish
the package's configuration file using the following command: bash
CopyEdit php artisan vendor:publish
\--provider=\"DilbrinAzad\\LaravelMultiLang\\Src\\Providers\\LaravelMultiLangServiceProvider\"
\--tag=\"config\" This will create a multilang.php file in your config
directory, where you can customize language settings.
\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_
Usage Publish Migrations and Seeders Publish the migrations and seeders
to set up the necessary database structure for the multi-language
feature. Run the following command to publish the migrations: bash
CopyEdit php artisan vendor:publish
\--provider=\"DilbrinAzad\\LaravelMultiLang\\Src\\Providers\\LaravelMultiLangServiceProvider\"
\--tag=\"migrations\" Once the migrations are published, run the
migration command to create the necessary tables in your database: bash
CopyEdit php artisan migrate Next, publish the seeders for language:
bash CopyEdit php artisan vendor:publish
\--provider=\"DilbrinAzad\\LaravelMultiLang\\Src\\Providers\\LaravelMultiLangServiceProvider\"
\--tag=\"seeders\" Run the seeder to populate default languages: bash
CopyEdit php artisan db:seed \--class=LanguageSeeder
\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_
Add Multi-language Fields Once the migrations are set up, you can use
the multi-language fields in your models and views. This will allow you
to store data for multiple languages. Example: 1. In your migration
file: php CopyEdit Schema::create(\'languages\', function (Blueprint
\$table) { \$table-\>id(); \$table-\>string(\'locale\');
\$table-\>timestamps(); }); 2. In your model: php CopyEdit namespace
App\\Models;

use Illuminate\\Database\\Eloquent\\Model;

class Language extends Model { protected \$fillable = \[\'locale\'\]; }
Use Translations in Views You can now use translations in your views.
blade CopyEdit \@lang(\'messages.welcome\') This will automatically
display the translation for the current language.
\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_
Components This package includes several view components that help you
manage multi-language content more efficiently. Multi-Language Input
Field You can use the provided multi-language input component to handle
translations for different languages. Example Usage in a Blade View:
blade CopyEdit \<x-multilang-input label=\"Welcome Message\"
name=\"welcome_message\" :other-languages=\"\$languages\" /\> This will
render an input field for each language, allowing you to input
translations.
\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_
Seeding Languages If you need to add languages to your system, use the
provided seeder class. By default, languages are seeded from the
config/languages.php file. Example: php CopyEdit // Add to
config/languages.php return \[ \'languages\' =\> \[ \[\'locale\' =\>
\'en\', \'title\' =\> \'English\'\], \[\'locale\' =\> \'es\', \'title\'
=\> \'Spanish\'\], // Add more languages \] \]; Then, run the following
command: bash CopyEdit php artisan db:seed \--class=LanguageSeeder
\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_
Contributing We welcome contributions to improve this package! If you\'d
like to contribute, please follow these steps: 1. Fork the repository.
2. Create a new branch for your changes. 3. Make your changes and ensure
tests pass. 4. Submit a pull request with a clear description of your
changes.
\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_
License This package is open-source and available under the MIT License.
\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_
Example Project Below is an example of how you might set up a basic
Laravel project with this package: 1. Install Laravel If you don\'t
already have a Laravel project, create one by running: bash CopyEdit
laravel new my-laravel-app cd my-laravel-app 2. Install the MultiLang
Package Run the following command: bash CopyEdit composer require
dilbrinazad/laravel-multi-lang 3. Publish Migrations, Seeders, and
Configuration bash CopyEdit php artisan vendor:publish
\--provider=\"DilbrinAzad\\LaravelMultiLang\\Src\\Providers\\LaravelMultiLangServiceProvider\"
\--tag=\"config\" php artisan vendor:publish
\--provider=\"DilbrinAzad\\LaravelMultiLang\\Src\\Providers\\LaravelMultiLangServiceProvider\"
\--tag=\"migrations\" php artisan vendor:publish
\--provider=\"DilbrinAzad\\LaravelMultiLang\\Src\\Providers\\LaravelMultiLangServiceProvider\"
\--tag=\"seeders\" 4. Run Migrations bash CopyEdit php artisan migrate
5. Seed Languages bash CopyEdit php artisan db:seed
\--class=LanguageSeeder 6. Use MultiLang Components in Your Views Add
the component to your Blade view: blade CopyEdit \<x-multilang-input
label=\"Your Label\" name=\"field_name\"
:other-languages=\"\$languages\" /\>
\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_
Final Notes This package allows for a smooth and scalable integration of
multi-language support in your Laravel applications. Customize it as
needed for your project, and feel free to extend the functionality to
suit your specific use case.
