<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Library
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Library newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Library newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Library query()
 * @mixin \Eloquent
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Library whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Library whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Library whereUpdatedAt($value)
 * @property string $title
 * @property string $address
 * @property string $phone
 * @property float $lat
 * @property float $lng
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\BookUnique[] $books
 * @property-read int|null $books_count
 * @method static \Illuminate\Database\Eloquent\Builder|Library whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Library whereLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Library whereLng($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Library wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Library whereTitle($value)
 */
class Library extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $guarded = [];

    public function books()
    {
        return $this->belongsToMany(BookUnique::class)->withPivot(['count','available']);
    }
}
