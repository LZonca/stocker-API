<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    use HasFactory;
    protected $fillable = ['nom', 'description', 'image'];

    public function produits()
    {
        return $this->hasMany(Produit::class);
    }

    public function stocks()
    {
        return $this->belongsToMany(Stock::class, 'categorie_stock');
    }
}
