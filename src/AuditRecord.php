<?php

namespace musingsz\yii2\audit;
use yii\base\Application;
use yii\base\BootstrapInterface;

/**
 * Bootstrap
 * @package musingsz\yii2\audit
 */
class AuditRecord extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            'musingsz\yii2\audit\AuditTrailBehavior'
        ];
    }
}
