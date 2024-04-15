<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Groupe extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'image', 'proprietaire_id'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_belongs_to_groupe');
    }
    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function proprietaire()
    {
        return $this->belongsTo(User::class, 'proprietaire_id');
    }
}
