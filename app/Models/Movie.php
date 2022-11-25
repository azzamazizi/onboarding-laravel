<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $table = 'movies';
    protected $primaryKey = 'id';
    protected $fillable = [
        'title','overview','poster'
    ];
    protected $hidden = [
        'created_at','updated_at','deleted_at','play_until','pivot'
    ];

    public function tags()
    {
        // code below work
        // return $this->hasMany(MovieTag::class)->join('tags', 'tags.id', '=', 'movie_tags.tag_id');
        return $this->belongsToMany(Tag::class, 'movie_tags');
    }

    public function schedules()
    {
        // code below work
        return $this->hasMany(movieSchedule::class);
    }

}
