<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Author
 *
 * @method static Builder|Author newModelQuery()
 * @method static Builder|Author newQuery()
 * @method static Builder|Author query()
 * @mixin Eloquent
 * @property int $id
 * @property string $name
 * @method static Builder|Rubric whereId($value)
 * @method static Builder|Rubric whereName($value)
 */
class Rubric extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $guarded = [];
}
