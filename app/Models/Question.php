<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $fillable = ['form_id', 'user_id', 'submitted_at', 'answers'];

    protected $casts = [
        'answers' => 'array',
        'options' => 'array',
    ];

    public function getOptionsAttribute($value)
    {
        return json_decode($value, true);
    }

    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function responses()
    {
        return $this->hasMany(Response::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
