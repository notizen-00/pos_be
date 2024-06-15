<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $table = 'questions';

    protected $fillable = 'question_text';
    public function options()
    {
        return $this->hasMany(Option::class,'question_id','id');
    }
}
