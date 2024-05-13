<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProduit extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'produit_id', 'custom_name', 'custom_image','custom_description', 'custom_code'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }
}
