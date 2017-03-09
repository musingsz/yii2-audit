<?php

namespace tests\codeception\unit;

use musingsz\yii2\audit\Audit;
use musingsz\yii2\audit\tests\UnitTester;
use Codeception\Specify;
use Yii;

/**
 * AuditUserIdCallbackTest
 */
class AuditUserIdCallbackTest extends AuditTestCase
{
    use Specify;

    /**
     * @var UnitTester
     */
    protected $tester;

    public function testUserIdCallbackTest()
    {
        Audit::getInstance()->userIdCallback = ['tests\app\models\User', 'userIdCallback'];
        $this->assertEquals(Audit::getInstance()->getUserId(), '1');
    }

}