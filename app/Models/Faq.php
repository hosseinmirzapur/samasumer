<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Faq extends Model
{
    use HasTranslations;

    public $translatable = ['question', 'answer'];

    const TYPES = ['ticket', 'hotel', 'misc'];
}
