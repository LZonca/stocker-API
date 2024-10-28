<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProduit extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'produit_id', 'custom_name', 'custom_image','custom_description', 'custom_code', 'custom_price', 'custom_expiry_date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function shoppingLists()
    {
        return $this->belongsToMany(ShoppingList::class, 'shopping_list_user_produit')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function getImageAttribute($value): string
    {
        if ($value) {
            return $value;
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->custom_name) . '&color=7F9CF5&background=EBF4FF';
    }
}
