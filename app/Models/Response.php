<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    use HasFactory;
    protected $fillable = ['form_id', 'user_id', 'answers'];

    // Define relationships
    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
