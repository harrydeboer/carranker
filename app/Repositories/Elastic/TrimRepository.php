<?php

declare(strict_types=1);

namespace App\Repositories\Elastic;

use App\Models\Aspect;
use App\Models\Trim;

class TrimRepository extends BaseRepository
{
    protected $index = 'trims';

    public function addAllToIndex(): void
    {
        $trims = Trim::all();

        foreach ($trims as $key => $trim) {
            $params['body'][] = [
                'index' => [
                    '_index' => $this->index,
                    '_id' => $trim->getId(),
                ]
            ];

            $params['body'][] = $this->propertiesToParams($trim);

            if ($key % 1000 === 0) {
                $this->client->bulk($params);
                unset($params);
            }
        }

        // Send the last batch if it exists
        if (!empty($params['body'])) {
            $this->client->bulk($params);
        }
    }
}