<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Produit;


class Stock extends Model
{
    use HasFactory;
    protected $fillable = ['nom', 'proprietaire_id'];

    public function proprietaire()
    {
        return $this->belongsTo(User::class, 'proprietaire_id');
    }

    public function produits()
    {
        return $this->hasMany(Produit::class);
    }
}
