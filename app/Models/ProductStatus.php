<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductStatus extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'title',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'status_id');
    }
}
