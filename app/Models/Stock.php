<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Stock extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = ['nom', 'image','proprietaire_id', 'groupe_id'];

    // Hide pivot attribute
    protected $hidden = ['pivot'];

    public function produits()
    {
        return $this->hasMany(Produit::class, 'stock_id');
    }

    public function proprietaire()
    {
        return $this->belongsTo(User::class, 'proprietaire_id');
    }

    public function groupe()
    {
        return $this->belongsTo(Groupe::class);
    }

    public function getImageAttribute($value): string
    {
        if ($value) {
            return $value;
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->nom) . '&color=7F9CF5&background=EBF4FF';
    }


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('stocks')
            ->logAll();
    }
}
