<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'nom', 'description', 'prix', 'image', 'expiry_date', 'categorie_id'];

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function stocks()
    {
        return $this->belongsToMany(Stock::class)->withPivot('quantite');
    }

    public function userProduits()
    {
        return $this->hasMany(UserProduit::class);
    }

    public function getImageAttribute($value): string
    {
        if ($value) {
            return $value;
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->nom) . '&color=00000&background=6A8D73'; // &color=00000&background=6A8D73
    }

    public function getQuantite()
    {
        $stock = $this->stocks()->first();
        return $stock ? $stock->pivot->quantite : 'N/A';
    }
}
