<?php

declare(strict_types=1);

use CarrankerAdmin\App\Models\Make;

class MakeTest extends WP_UnitTestCase
{
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        require_once dirname(__DIR__, 4) . '/plugins/carranker-admin-pages/app/Models/BaseModel.php';
        require_once dirname(__DIR__, 4) . '/plugins/carranker-admin-pages/app/Models/Make.php';
    }

    public function testCreate()
    {
        $createObj = new stdClass();
        $createObj->name = 'Ford';
        $createObj->content = 'testcontent';
        $createObj->wiki_car_make = 'Ford_WIKI';
        $make = new Make($createObj);
        $make->create();

        $makeDB = Make::getById($make->getId());
        $this->assertSame( $make->getId(), $makeDB->getId() );
        $this->assertSame( $make->getName(), $makeDB->getName() );
        $this->assertSame( $make->getContent(), $makeDB->getContent() );
        $this->assertSame( $make->getWikiCarMake(), $makeDB->getWikiCarMake() );
    }
}