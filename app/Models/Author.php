<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Author
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Author newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Author newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Author query()
 * @mixin \Eloquent
 * @property int $id
 * @property string|null $contribution
 * @property string|null $surname
 * @property string|null $names
 * @property string|null $initials
 * @property string $full_name
 * @property string|null $full_name_alt
 * @method static \Illuminate\Database\Eloquent\Builder|Author whereContribution($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Author whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Author whereFullNameAlt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Author whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Author whereInitials($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Author whereNames($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Author whereSurname($value)
 * @property string $simple_name
 * @method static \Illuminate\Database\Eloquent\Builder|Author whereSimpleName($value)
 */
class Author extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $timestamps = false;
}
