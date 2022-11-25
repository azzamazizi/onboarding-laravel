<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $table = 'tags';
    protected $primaryKey = 'id';
    protected $hidden = [
        'created_at','updated_at','deleted_at','pivot'
    ];
    protected $fillable = [
        'id','name'
    ];
    
    public function movie()
    {
        return $this->belongsToMany(Movie::class, MovieTag::class);
    }
}
