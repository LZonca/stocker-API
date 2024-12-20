<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Produit extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = ['stock_id', 'group_id', 'code', 'nom', 'description', 'prix', 'image', 'expiry_date', 'quantite', 'categorie_id', 'archived'];

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function getImageAttribute($value): string
    {
        if ($value) {
            return $value;
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->nom) . '&color=00000&background=6A8D73'; // &color=00000&background=6A8D73
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('produits')
            ->logAll();
    }
}
