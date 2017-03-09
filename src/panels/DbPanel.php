<?php

namespace musingsz\yii2\audit\panels;

use musingsz\yii2\audit\components\panels\DataStoragePanelTrait;
use Yii;
use yii\debug\models\search\Db;
use yii\grid\GridViewAsset;

/**
 * DbPanel
 * @package musingsz\yii2\audit\panels
 *
 * @method bool hasExplain()
 */
class DbPanel extends \yii\debug\panels\DbPanel
{
    use DataStoragePanelTrait;

    /**
     * @var array current database request timings
     */
    private $_timings;

    /**
     * @inheritdoc
     */
    public function getLabel()
    {
        $timings = $this->calculateTimings();
        $queryCount = count($timings);
        $queryTime = number_format($this->getTotalQueryTime($timings) * 1000) . ' ms';
        return $this->getName() . ' <small>(' . $queryCount . ' / ' . $queryTime . ')</small>';
    }

    /**
     * @inheritdoc
     */
    public function getDetail()
    {
        $searchModel = new Db();
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(), $this->getModels());

        return Yii::$app->view->render('@yii/debug/views/default/panels/db/detail', [
            'panel' => $this,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'hasExplain' => method_exists($this, 'hasExplain') ? $this->hasExplain() : null,
        ]);
    }

    public function save()
    {
        $data = parent::save();
        return (isset($data['messages']) && count($data['messages']) > 0) ? $data : null;
    }

    /**
     * @inheritdoc
     */
    public function registerAssets($view)
    {
        GridViewAsset::register($view);
    }

    /**
     * Calculates given request profile timings.
     *
     * @return array timings [token, category, timestamp, traces, nesting level, elapsed time]
     */
    public function calculateTimings()
    {
        if ($this->_timings === null) {
            $this->_timings = [];
            if (isset($this->data['messages'])) {
                $this->_timings = Yii::getLogger()->calculateTimings($this->data['messages']);
            }
        }
        return $this->_timings;
    }
}