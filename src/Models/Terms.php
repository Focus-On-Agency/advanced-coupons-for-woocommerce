<?php

namespace Focuson\WoocommerceDiscountAdvanced\Models;

use Illuminate\Database\Eloquent\Model;

class Terms extends Model
{
    protected $table = 'terms';

    /**
     * Scope
     */

    public static function getProductTags()
    {
        return static::whereHas('taxonomy', function ($query) {
            $query->where('taxonomy', 'product_tag');
        })->get();
    }

    /**
     * Relationships
     */

    public function taxonomy()
    {
        return $this->belongsTo(Taxonomy::class, 'term_id', 'term_id');
    }
}
