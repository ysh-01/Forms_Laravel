<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Form extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['user_id', 'title', 'description', 'ispublished'];

    // Define relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($form) {
            if ($form->isForceDeleting()) {
                // Force delete questions if form is permanently deleted
                $form->questions()->forceDelete();
            } else {
                // Soft delete questions if form is soft-deleted
                $form->questions()->delete();
            }
        });
    }

    public function responses()
    {
        return $this->hasMany(Response::class);
    }
}
