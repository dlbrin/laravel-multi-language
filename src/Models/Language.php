<?php

namespace DilbrinAzad\LaravelMultiLang\Src\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $table = 'languages';
    protected $fillable = ['title', 'name', 'country_code', 'dir', 'locale', 'status'];
}
