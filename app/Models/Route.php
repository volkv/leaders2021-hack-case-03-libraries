<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\Route
 *
 * @property int $id
 * @property mixed $points
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder|Route newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Route newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Route query()
 * @method static \Illuminate\Database\Eloquent\Builder|Route whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Route whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Route wherePoints($value)
 * @mixin \Eloquent
 * @property string $route_name
 * @property string $route_number
 * @method static \Illuminate\Database\Eloquent\Builder|Route whereRouteName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Route whereRouteNumber($value)
 */
class Route extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $incrementing = false;
    public $timestamps = false;
    protected $casts = ['points' => 'array', 'buses'=>'array'];
}
