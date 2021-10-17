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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Book[] $book
 * @property-read int|null $book_count
 * @property string $unique-title
 * @property bool $is_book_jsn
 * @method static \Illuminate\Database\Eloquent\Builder|BookUnique whereIsBookJsn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookUnique whereUniqueTitle($value)
 * @property string $unique_title
 * @property-read \App\Models\Author|null $author
 */
class BookUnique extends Model
{
    use HasFactory, Searchable;

    protected $guarded = [];

    public $timestamps = false;
    protected $appends = ['author_name'];


    public function getAuthorNameAttribute()
    {
        return $this->author?->full_name??null;
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
        ];
    }


}
