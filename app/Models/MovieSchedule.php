<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovieSchedule extends Model
{
    use HasFactory;

    protected $table = 'movie_schedules';
    protected $primaryKey = 'id';
    protected $hidden = [
        'created_at','updated_at','deleted_at','movie_id','studio_id'
    ];
    protected $fillable = [
        'movie_id','studio_id','start_time','end_time','price','date'
    ];

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'movie_tags');
    }

    public function movies()
    {
        return $this->belongsTo(Movie::class, 'movie_id');
    }

    public function studio_number()
    {
        return $this->belongsTo(Studio::class, 'studio_id');
        // return $this->hasMany(Studio::class, 'id');
    }
}
