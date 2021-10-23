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
 * @property string|null $contribution
 * @property string|null $surname
 * @property string|null $names
 * @property string|null $initials
 * @property string $full_name
 * @property string|null $full_name_alt
 * @method static Builder|Author whereContribution($value)
 * @method static Builder|Author whereFullName($value)
 * @method static Builder|Author whereFullNameAlt($value)
 * @method static Builder|Author whereId($value)
 * @method static Builder|Author whereInitials($value)
 * @method static Builder|Author whereNames($value)
 * @method static Builder|Author whereSurname($value)
 * @property string $simple_name
 * @method static Builder|Author whereSimpleName($value)
 */
class Author extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $guarded = [];
}
