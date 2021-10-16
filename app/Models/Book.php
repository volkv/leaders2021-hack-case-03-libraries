<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

/**
 * App\Models\Book
 *
 * @method static \Illuminate\Database\Eloquent\Builder|BookUnique newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BookUnique newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BookUnique query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $isbn
 * @property int|null $year
 * @property string|null $annotation
 * @property int $rubric_id
 * @property int $author_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|BookUnique whereAnnotation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookUnique whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookUnique whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookUnique whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookUnique whereIsbn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookUnique whereRubricId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookUnique whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookUnique whereYear($value)
 * @property string $title
 * @method static \Illuminate\Database\Eloquent\Builder|BookUnique whereTitle($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Library[] $library
 * @property-read int|null $library_count
 * @property string|null $cover_url
 * @method static \Illuminate\Database\Eloquent\Builder|BookUnique whereCoverUrl($value)
 * @property int $book_unique_id
 * @property-read \App\Models\BookUnique $uniqueBook
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereBookUniqueId($value)
 */
class Book extends Model
{

    protected $guarded = [];
    public $incrementing = false;
    public $timestamps = false;

    public function uniqueBook()
    {
        return $this->belongsTo(BookUnique::class);
    }
}
