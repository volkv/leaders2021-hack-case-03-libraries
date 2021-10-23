<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Laravel\Scout\Searchable;

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
 * @property-read Collection|Book[] $book
 * @property-read int|null $book_count
 * @property string $unique-title
 * @property bool $is_book_jsn
 * @method static Builder|BookUnique whereIsBookJsn($value)
 * @method static Builder|BookUnique whereUniqueTitle($value)
 * @property string $unique_title
 * @property-read Author|null $author
 * @property-read mixed $author_name
 * @property int $count
 * @property int|null $book_id
 * @method static Builder|BookUnique whereBookId($value)
 * @method static Builder|BookUnique whereCount($value)
 */
class BookUnique extends Model
{
    use HasFactory, Searchable;

    public $timestamps = false;
    protected $guarded = [];
    protected $appends = ['author_name'];
    protected $hidden = ['id', 'annotation', 'unique_title', 'rubric_id', 'author_id', 'author', 'book'];

    public function getAuthorNameAttribute()
    {
        return $this->author?->full_name ?? null;
    }


    public function library()
    {
        return $this->belongsToMany(Library::class);
    }

    public function book()
    {
        return $this->hasMany(Book::class);
    }


    public function author()
    {
        return $this->belongsTo(Author::class);
    }


    public function toSearchableArray()
    {
        if ($this->author) {
            $title = $this->title.' '.$this->author->full_name;
        } else {
            $title = $this->title;
        }


        return [
            'id' => $this->id,
            'name' => $title,
            'sort' => $this->count,
        ];
    }


}
