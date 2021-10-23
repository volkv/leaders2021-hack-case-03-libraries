<?php

namespace App\Models;


use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Library
 *
 * @method static Builder|Library newModelQuery()
 * @method static Builder|Library newQuery()
 * @method static Builder|Library query()
 * @mixin Eloquent
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Library whereCreatedAt($value)
 * @method static Builder|Library whereId($value)
 * @method static Builder|Library whereUpdatedAt($value)
 * @property string $title
 * @property string $address
 * @property string $phone
 * @property float $lat
 * @property float $lng
 * @property-read Collection|BookUnique[] $books
 * @property-read int|null $books_count
 * @method static Builder|Library whereAddress($value)
 * @method static Builder|Library whereLat($value)
 * @method static Builder|Library whereLng($value)
 * @method static Builder|Library wherePhone($value)
 * @method static Builder|Library whereTitle($value)
 */
class Library extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $guarded = [];

    public function books()
    {
        return $this->belongsToMany(BookUnique::class)->withPivot(['count', 'available']);
    }
}
