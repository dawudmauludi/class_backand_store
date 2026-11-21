<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string $name
 * @property string|null $description
 * @property int $price
 * @property int $stock
 * @property string $category_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read CategoryProduct|null $category
 * @property-read CategoryProduct|null $categoryProduct
 */
#[Fillable(['name', 'description', 'price', 'stock', 'category_id'])]
class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory, HasUuids;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'stock' => 'integer',
        ];
    }

    /**
     * Get the category that owns the product.
     *
     * @return BelongsTo<CategoryProduct, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(CategoryProduct::class, 'category_id');
    }

    /**
     * Alias for category relationship.
     *
     * @return BelongsTo<CategoryProduct, $this>
     */
    public function categoryProduct(): BelongsTo
    {
        return $this->category();
    }
}
