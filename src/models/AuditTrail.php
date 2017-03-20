<?php

namespace musingsz\yii2\audit\models;

use musingsz\yii2\audit\components\db\ActiveRecord;
use Yii;
use yii\db\Schema;
/**
 * AuditTrail
 *
 * @property integer $id
 * @property integer $entry_id
 * @property integer $user_id
 * @property string  $action
 * @property string  $model
 * @property string  $model_id
 * @property string  $field
 * @property string  $new_value
 * @property string  $old_value
 * @property string  $created
 *
 * @property AuditEntry    $entry
 */
class AuditTrail extends ActiveRecord
{

    const  TABLE_NAME = 'audit_trail';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%audit_trail}}';
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id'        => Yii::t('audit', 'ID'),
            'entry_id'  => Yii::t('audit', 'Entry ID'),
            'user_id'   => Yii::t('audit', 'User ID'),
            'action'    => Yii::t('audit', 'Action'),
            'model'     => Yii::t('audit', 'Type'),
            'model_id'  => Yii::t('audit', 'ID'),
            'field'     => Yii::t('audit', 'Field'),
            'old_value' => Yii::t('audit', 'Old Value'),
            'new_value' => Yii::t('audit', 'New Value'),
            'created'   => Yii::t('audit', 'Created'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntry()
    {
        return $this->hasOne(AuditEntry::className(), ['id' => 'entry_id']);
    }

    /**
     * @return mixed
     */
    public function getDiffHtml()
    {
        $old = explode("\n", $this->old_value);
        $new = explode("\n", $this->new_value);

        foreach ($old as $i => $line) {
            $old[$i] = rtrim($line, "\r\n");
        }
        foreach ($new as $i => $line) {
            $new[$i] = rtrim($line, "\r\n");
        }

        $diff = new \Diff($old, $new);
        return $diff->render(new \Diff_Renderer_Html_Inline);
    }

    /**
     * @return ActiveRecord|bool
     */
    public function getParent()
    {
        $parentModel = new $this->model;
        $parent = $parentModel::findOne($this->model_id);
        return $parent ? $parent : $parentModel;
    }


    /**
     * @return  create table
     */
    public function createMonthlyTable($table_suffix)
    {
        $table = self::TABLE_NAME.'_'.$table_suffix;
        $sql = " CREATE TABLE  IF NOT EXISTS `".$table."` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `entry_id` INT(11) NULL DEFAULT NULL,
                `user_id` INT(11) NULL DEFAULT NULL,
                `action` VARCHAR(255) NOT NULL,
                `model` VARCHAR(255) NOT NULL,
                `model_id` VARCHAR(255) NOT NULL,
                `field` VARCHAR(255) NULL DEFAULT NULL,
                `old_value` TEXT NULL,
                `new_value` TEXT NULL,
                `created` DATETIME NOT NULL,
                PRIMARY KEY (`id`),
                INDEX `fk_audit_trail_entry_id` (`entry_id`),
                INDEX `idx_audit_user_id` (`user_id`),
                INDEX `idx_audit_trail_field` (`model`, `model_id`, `field`),
                INDEX `idx_audit_trail_action` (`action`)
                )
                COLLATE='utf8_general_ci'
                ENGINE=InnoDB
                AUTO_INCREMENT=0";
        $this->db->createCommand($sql)->execute();

    }

    /**
     * @return  create table
     *
     */
    public function moveTableData($table_suffix)
    {

        $table =  self::TABLE_NAME.'_'.$table_suffix;

        $sql = "INSERT IGNORE INTO ".$table."  SELECT * FROM ".self::TABLE_NAME." where  DATE_FORMAT(created, '%Y%m')='".$table_suffix."' ";
        // echo $sql ."<br>";
        $count  =  $this->db->createCommand($sql)->execute();
        echo $table.  ' -> INSERT '. $count ."<br>";
        $delsql = " DELETE FROM ".self::TABLE_NAME." WHERE DATE_FORMAT(created, '%Y%m')='".$table_suffix."'";
        // echo $delsql ."<br>";
        $count  = $this->db->createCommand($delsql)->execute();
        echo $table. ' -> DELETE '. $count ."<br>";
    }


}
