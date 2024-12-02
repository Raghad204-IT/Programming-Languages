<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'quantity',
        'product_image', // هذا العمود يجب أن يكون موجودًا هنا
        'store_id'
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_details')->withPivot('quantity', 'price');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
}
