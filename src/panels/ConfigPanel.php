<?php

namespace musingsz\yii2\audit\panels;

use musingsz\yii2\audit\components\panels\DataStoragePanelTrait;
use Yii;

/**
 * ConfigPanel
 * @package musingsz\yii2\audit\panels
 */
class ConfigPanel extends \yii\debug\panels\ConfigPanel
{
    use DataStoragePanelTrait;

    /**
     * @return string
     */
    public function getDetail()
    {
        return Yii::$app->view->render('@yii/debug/views/default/panels/config/detail', [
            'panel' => $this,
        ]);
    }

}