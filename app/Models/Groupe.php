<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Groupe extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'image', 'proprietaire_id'];

    public function groupes()
    {
        return $this->belongsToMany(Groupe::class, 'user_groupes');
    }
}
