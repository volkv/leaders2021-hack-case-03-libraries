<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\UserBookRating
 *
 * @method static Builder|UserBookRating newModelQuery()
 * @method static Builder|UserBookRating newQuery()
 * @method static Builder|UserBookRating query()
 * @mixin Eloquent
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|UserBookRating whereCreatedAt($value)
 * @method static Builder|UserBookRating whereId($value)
 * @method static Builder|UserBookRating whereUpdatedAt($value)
 */
class UserBookRating extends Model
{
    use HasFactory;
}
