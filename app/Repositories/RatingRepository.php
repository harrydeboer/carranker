<?php

namespace App\Repositories;

use App\Models\Rating;

class RatingRepository extends BaseRepository
{
    public function findRecentReviews($limit)
    {
        return Rating::whereNotNull('content')->take($limit)->orderBy('time', 'desc')->get();
    }
}
