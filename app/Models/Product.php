<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Product extends Model {
    protected $guarded = [];
    /** Main category (first one chosen). Used for the card subtitle and the fallback icon. */
    public function category() { return $this->belongsTo(Category::class); }
    /** All categories this product belongs to. */
    public function categories() { return $this->belongsToMany(Category::class); }
    public function getPriceFormattedAttribute(): string {
        return config('shop.currency') . number_format($this->price / 100, 2);
    }
}
