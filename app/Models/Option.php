<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    protected $table = 'options';

    protected $fillable = ['option_text', 'question_id','is_correct'];

    public function question()
    {
        return $this->belongsTo(Soal::class,'question_id','id');
    }
}
