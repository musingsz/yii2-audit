<?php

namespace tests\codeception\unit;

use tests\app\models\User;
use musingsz\yii2\audit\Audit;
use musingsz\yii2\audit\components\Access;
use musingsz\yii2\audit\tests\UnitTester;

/**
 * AccessTest
 */
class AccessTest extends \yii\codeception\TestCase
{
    /**
     * @var string
     */
    public $appConfig = '@tests/codeception/config/functional.php';

    /**
     * @var UnitTester
     */
    protected $tester;

    public function testAccessAnyAllow()
    {
        $this->assertTrue(Access::checkAccess());
    }

    public function testAccessIpAllow()
    {
        $audit = Audit::getInstance();

        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $audit->accessIps = ['127.0.0.1'];
        $this->assertTrue(Access::checkAccess());
        $_SERVER['REMOTE_ADDR'] = null;
        $audit->accessIps = null;
    }

    public function testAccessIpDeny()
    {
        $audit = Audit::getInstance();

        $audit->accessIps = ['127.0.0.2'];
        $this->assertFalse(Access::checkAccess());
        $audit->accessIps = null;
    }

    public function testAccessRoleAllow()
    {
        $audit = Audit::getInstance();

        $user = User::findOne(1);
        \Yii::$app->user->login($user);

        $audit->accessRoles = ['admin'];
        $this->assertTrue(Access::checkAccess());
        $audit->accessRoles = null;

        \Yii::$app->user->logout();
    }

    public function testAccessRoleDeny()
    {
        $audit = Audit::getInstance();

        $audit->accessRoles = ['admin'];
        $this->assertFalse(Access::checkAccess());
        $audit->accessRoles = null;
    }

    public function testAccessGuestAllow()
    {
        $audit = Audit::getInstance();

        $audit->accessRoles = ['?'];
        $this->assertTrue(Access::checkAccess());
        $audit->accessRoles = null;
    }

    public function testAccessGuestDeny()
    {
        $audit = Audit::getInstance();

        $user = User::findOne(1);
        \Yii::$app->user->login($user);

        $audit->accessRoles = ['?'];
        $this->assertFalse(Access::checkAccess());
        $audit->accessRoles = null;

        \Yii::$app->user->logout();
    }

    public function testAccessLoggedInAllow()
    {
        $audit = Audit::getInstance();

        $user = User::findOne(1);
        \Yii::$app->user->login($user);

        $audit->accessRoles = ['@'];
        $this->assertTrue(Access::checkAccess());
        $audit->accessRoles = null;

        \Yii::$app->user->logout();
    }

    public function testAccessLoggedInDeny()
    {
        $audit = Audit::getInstance();

        $audit->accessRoles = ['@'];
        $this->assertFalse(Access::checkAccess());
        $audit->accessRoles = null;
    }

    public function testAccessUserAllow()
    {
        $audit = Audit::getInstance();

        $user = User::findOne(1);
        \Yii::$app->user->login($user);

        $audit->accessUsers = [1];
        $this->assertTrue(Access::checkAccess());
        $audit->accessUsers = null;

        \Yii::$app->user->logout();
    }

    public function testAccessUserDeny()
    {
        $audit = Audit::getInstance();

        $audit->accessUsers = [1];
        $this->assertFalse(Access::checkAccess());
        $audit->accessUsers = null;
    }
}