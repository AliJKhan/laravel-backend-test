<?php

namespace App\Models;

use App\Events\CommentWritten;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    /**
     * @return void
     * Fire a comment written event when a new comment is created
     */
    public static function boot(): void {
        parent::boot();
        static::created(function($comment) {
            event(new CommentWritten($comment));
        });
    }


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'body',
        'user_id'
    ];

    /**
     * Get the user that wrote the comment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
