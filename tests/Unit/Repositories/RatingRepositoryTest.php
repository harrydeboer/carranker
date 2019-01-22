<?php

declare(strict_types=1);

use App\Repositories\RatingRepository;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RatingRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    private $ratingRepository;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->ratingRepository = new RatingRepository();
    }
}