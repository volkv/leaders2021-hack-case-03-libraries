<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\Stop
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Stop newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Stop newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Stop query()
 * @mixin \Eloquent
 */
class Stop extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $incrementing = false;
    public $timestamps = false;
    protected $casts = ['points' => 'array'];
}
