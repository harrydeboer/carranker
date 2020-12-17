<?php

declare(strict_types=1);

namespace Tests\Feature\Repositories;

use App\Repositories\FXRateRepository;
use Tests\TestCase;

class FXRateRepositoryTest extends TestCase
{
    private $fxrateRepository;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->fxrateRepository = new FXRateRepository();
    }

    public function testGetByName()
    {
        $fxrate = $this->fxrateRepository->get(1);
        $fxrateFromDb = $this->fxrateRepository->getByName($fxrate->getName());

        $this->assertEquals($fxrate->getId(), $fxrateFromDb->getId());
    }
}