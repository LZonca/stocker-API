<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ShoppingList extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = ['user_id', 'stock_id', 'name'];

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('shopping-lists')
            ->logAll();
    }
}
