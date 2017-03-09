<?php

namespace tests\codeception\unit;

use musingsz\yii2\audit\Audit;
use musingsz\yii2\audit\models\AuditData;
use musingsz\yii2\audit\tests\UnitTester;

/**
 * AuditExtraDataTest
 */
class AuditExtraDataTest extends AuditTestCase
{

    /**
     * @var UnitTester
     */
    protected $tester;

    /**
     * Add Data
     */
    public function testAddData()
    {
        Audit::getInstance()->data('some type', 'some data');
        $this->finalizeAudit();

        $this->tester->seeRecord(AuditData::className(), [
            'entry_id' => 2,
            'type' => 'audit/extra',
        ]);
    }

    public function testThatFindForEntryWorks()
    {
        $this->entry();
        $this->finalizeAudit();

        $this->assertNotNull(AuditData::findForEntry(2, 'audit/request'));
        $this->assertNotNull(AuditData::findForEntry(2, 'audit/log'));
    }

    public function testThatFindEntryTypesWorks()
    {
        $this->entry();
        $this->finalizeAudit();

        $types = AuditData::findEntryTypes(2);
        $subset = [
            'audit/config',
            'audit/db',
            'audit/log',
            'audit/profiling',
            'audit/request',
        ];
        sort($types);
        sort($subset);
        $this->assertArraySubset($subset, $types);
    }

}