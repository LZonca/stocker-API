<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Groupe extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = ['nom', 'image', 'proprietaire_id', 'groupe_id'];

    public function members()
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

    public function group()
    {
        return $this->belongsTo(Groupe::class, 'groupe_id');
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
            ->useLogName('groupes')
            ->logAll();
    }
}
