<?php

namespace App\Repositories;

use App\Models\Rating;

class RatingRepository extends BaseRepository
{
    public function findRecentReviews($limit)
    {
        return Rating::whereNotNull('content')->take($limit)->orderBy('time', 'desc')->get();
    }

    public function createRating($user, $model, $trim, $form)
    {
        $createArray = [
            'user_id' => $user->getId(),
            'model_id' => $model->getId(),
            'trim_id' => $trim->getId(),
            'time' => time(),
        ];
        if ($form->content === null) {
            $createArray['content'] = null;
        } else {
            $createArray['content'] = mb_convert_encoding($form->content, 'HTML-ENTITIES', 'ISO-8859-1');
        }
        foreach ($form->star as $key => $aspect) {
            $createArray[$key] = (int) $aspect;
        }

        $this->create($createArray);
    }

    public function updateRating($rating, $form)
    {
        foreach ($form->star as $key => $aspect) {
            $rating->setAspect($key, (int) $aspect);
        }
        if ($form->content === null) {
            $rating->setContent();
        } else {
            $rating->setContent($form->content);
        }
        $this->update($rating);
    }
}
