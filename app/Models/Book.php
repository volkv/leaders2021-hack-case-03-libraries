<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Book
 *
 * @method static Builder|BookUnique newModelQuery()
 * @method static Builder|BookUnique newQuery()
 * @method static Builder|BookUnique query()
 * @mixin Eloquent
 * @property int $id
 * @property int $isbn
 * @property int|null $year
 * @property string|null $annotation
 * @property int $rubric_id
 * @property int $author_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|BookUnique whereAnnotation($value)
 * @method static Builder|BookUnique whereAuthorId($value)
 * @method static Builder|BookUnique whereCreatedAt($value)
 * @method static Builder|BookUnique whereId($value)
 * @method static Builder|BookUnique whereIsbn($value)
 * @method static Builder|BookUnique whereRubricId($value)
 * @method static Builder|BookUnique whereUpdatedAt($value)
 * @method static Builder|BookUnique whereYear($value)
 * @property string $title
 * @method static Builder|BookUnique whereTitle($value)
 * @property-read Collection|Library[] $library
 * @property-read int|null $library_count
 * @property string|null $cover_url
 * @method static Builder|BookUnique whereCoverUrl($value)
 * @property int $book_unique_id
 * @property-read BookUnique $uniqueBook
 * @method static Builder|Book whereBookUniqueId($value)
 * @property int $count
 * @method static Builder|Book whereCount($value)
 */
class Book extends Model
{

    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = [];

    public function uniqueBook()
    {
        return $this->belongsTo(BookUnique::class);
    }
}
