<?php

declare(strict_types=1);

use App\Repositories\TrimRepository;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TrimRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    private $trimRepository;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->trimRepository = new TrimRepository();
    }
}