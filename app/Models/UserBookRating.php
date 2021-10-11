<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserBookRating
 *
 * @method static \Illuminate\Database\Eloquent\Builder|UserBookRating newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserBookRating newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserBookRating query()
 * @mixin \Eloquent
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|UserBookRating whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserBookRating whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserBookRating whereUpdatedAt($value)
 */
class UserBookRating extends Model
{
    use HasFactory;
}
