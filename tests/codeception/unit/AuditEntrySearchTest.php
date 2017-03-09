<?php

namespace tests\codeception\unit;

use musingsz\yii2\audit\models\AuditEntry;
use musingsz\yii2\audit\models\AuditEntrySearch;
use musingsz\yii2\audit\models\AuditErrorSearch;
use musingsz\yii2\audit\models\AuditJavascript;
use musingsz\yii2\audit\models\AuditJavascriptSearch;
use Codeception\Specify;

/**
 * AuditErrorSearchTest
 */
class AuditEntrySearchTest extends AuditTestCase
{

    public function testRouteFilterWorks()
    {
        $this->assertEquals(['/default/index' => '/default/index'], AuditEntrySearch::routeFilter());
    }

    public function testMethodFilterWorks()
    {
        $this->assertEquals(['GET' => 'GET'], AuditEntrySearch::methodFilter());
    }

    public function testUserCallbackIsCalledForString()
    {
        $mock = $this->getMock('stdClass', ['testCallback']);
        $mock->expects($this->once())
            ->method('testCallback')
            ->will($this->returnValue([1, 2]));

        $this->module()->userFilterCallback = [$mock, 'testCallback'];

        $search = new AuditEntrySearch();
        $search->search(['AuditEntrySearch' => ['user_id' => 'testuser']]);
    }

    public function testUserCallbackIsNotCalledForInteger()
    {
        $mock = $this->getMock('stdClass', ['testCallback']);
        $mock->expects($this->never())
            ->method('testCallback');

        $this->module()->userFilterCallback = [$mock, 'testCallback'];

        $search = new AuditEntrySearch();
        $search->search(['AuditEntrySearch' => ['user_id' => 12]]);
    }
}