<?php

namespace musingsz\yii2\audit\controllers;

use musingsz\yii2\audit\components\web\Controller;
use musingsz\yii2\audit\models\AuditTrail;
use Yii;

/**
 * DefaultController
 * @package musingsz\yii2\audit\controllers
 */
class DefaultController extends Controller
{
    /**
     * Module Default Action.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }



}
