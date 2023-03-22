<?php

namespace App\Models;

use App\Support\Traits\Models\RequestSaver;
use App\Support\Traits\Models\StatusChangeable;
use MosseboShopCore\Models\Review as BaseReview;

class Review extends BaseReview
{
    use StatusChangeable, RequestSaver;

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
