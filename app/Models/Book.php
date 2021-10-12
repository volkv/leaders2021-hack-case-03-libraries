<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

/**
 * App\Models\Book
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Book newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Book newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Book query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $isbn
 * @property int|null $year
 * @property string|null $annotation
 * @property int $rubric_id
 * @property int $author_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereAnnotation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereIsbn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereRubricId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereYear($value)
 * @property string $title
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereTitle($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Library[] $library
 * @property-read int|null $library_count
 */
class Book extends Model
{
    use HasFactory, Searchable;

    protected $guarded = [];
    public $incrementing = false;
    public $timestamps = false;


    public function library()
    {
        return $this->belongsToMany(Library::class);
    }
}
