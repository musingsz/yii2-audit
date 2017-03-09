<?php
/**
 * AssetBundle to register when you want to log javascript events as well.
 */

namespace musingsz\yii2\audit\web;

use musingsz\yii2\audit\Audit;
use yii\helpers\Url;
use yii\web\AssetBundle;
use yii\web\View;

/**
 * JSLoggingAsset
 * @package musingsz\yii2\audit\assets
 */
class JSLoggingAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@musingsz/yii2/audit/web/assets';

    /**
     * @var array
     */
    public $js = [
        'javascript/logger.js',
    ];

    /**
     *
     */
    public function init()
    {
        // Activate the logging as soon as we can
        $this->jsOptions['position'] = View::POS_HEAD;
        $this->publishOptions['forceCopy'] = YII_DEBUG;
        parent::init();
    }

    /**
     * @param \yii\web\AssetManager $assetManager
     */
    public function publish($assetManager)
    {
        $module = Audit::getInstance();
        // We can't be sure that the actual logger was loaded already, so we fallback on the window object
        // to store the associated audit url and entry id
        $url = Url::to(["/{$module->id}/js-log"]);
        $script = "window.auditUrl = '$url';";
        if ($module->entry) {
            $id = $module->getEntry()->id;
            $script .= "window.auditEntry = $id;";
        }
        \Yii::$app->view->registerJs($script, View::POS_HEAD);
        parent::publish($assetManager);
    }
}