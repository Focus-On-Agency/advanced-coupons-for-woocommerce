<?php

namespace Focuson\AdvancedCoupons\Models;

use Illuminate\Database\Eloquent\Model;

class Taxonomy extends Model
{
	protected $table = 'term_taxonomy';

	/**
	 * Relationships
	 */
	public function terms()
	{
		return $this->hasMany(Terms::class, 'term_id', 'term_id');
	}
}