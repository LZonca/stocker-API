<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'proprietaire_id'];

    // Hide pivot attribute
    protected $hidden = ['pivot'];

    public function produits()
    {
        return $this->belongsToMany(Produit::class, 'produit_stock');
    }

    public function proprietaire()
    {
        return $this->belongsTo(User::class, 'proprietaire_id');
    }
}
