<?php

namespace musingsz\yii2\audit\controllers;

use musingsz\yii2\audit\components\web\Controller;
use musingsz\yii2\audit\models\AuditData;
use musingsz\yii2\audit\models\AuditEntry;
use musingsz\yii2\audit\models\AuditTrail;
use musingsz\yii2\audit\models\AuditError;
use musingsz\yii2\audit\models\AuditMail;

use musingsz\yii2\audit\models\AuditTrailSearch;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * TrailController
 * @package musingsz\yii2\audit\controllers
 */
class TrailController extends Controller
{
    /**
     * Lists all AuditTrail models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AuditTrailSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel'  => $searchModel,
        ]);
    }

    /**
     * Displays a single AuditTrail model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $model = AuditTrail::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('The requested trail does not exist.');
        }
        return $this->render('view', [
            'model' => $model,
        ]);
    }


    /**
     * move
     * http://audit.bwlocal.com/index.php?r=audit%2Ftrail%2Fmove
     * http://audit.bwlocal.com/index.php/audit/trail/move
     */
    public function actionMove()
    {

        set_time_limit(3000);
        echo  "[".date('Y-m-d H:i:s ')."]->begin move------------------<br>";
        $request = Yii::$app->request;
        $month = $request->get('month', 2);
        $table_suffix = date('Ym', strtotime("-".$month." month"));

        $audit =  new AuditTrail();
        $audit->createMonthlyTable($table_suffix);
        $audit->moveTableData($table_suffix);

        $audit =  new AuditData();
        $audit->createMonthlyTable($table_suffix);
        $audit->moveTableData($table_suffix);

        $audit =  new AuditError();
        $audit->createMonthlyTable($table_suffix);
        $audit->moveTableData($table_suffix);

        $audit =  new AuditMail();
        $audit->createMonthlyTable($table_suffix);
        $audit->moveTableData($table_suffix);

        $audit =  new AuditEntry();
        $audit->createMonthlyTable($table_suffix);
        $audit->moveTableData($table_suffix);

        echo  "[".date('Y-m-d H:i:s ')."]->-move finish---------------------------------<br>";
    }
}
