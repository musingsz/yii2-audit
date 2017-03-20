<?php

namespace musingsz\yii2\audit\models;
use musingsz\yii2\audit\components\db\ActiveRecord;
use Yii;

/**
 * AuditData
 * Extra data associated with a specific audit line. There are currently no guidelines concerning what the name/type
 * needs to be, this is at your own discretion.
 *
 * @property int    $id
 * @property int    $entry_id
 * @property string $type
 * @property string $data
 * @property string $created
 *
 * @property AuditEntry    $entry
 *
 * @package musingsz\yii2\audit\models
 */
class AuditData extends ActiveRecord
{


    const  TABLE_NAME = 'audit_data';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%audit_data}}';
    }

    /**
     * @param $entry_id
     * @return array
     */
    public static function findEntryTypes($entry_id)
    {
        return static::find()->select('type')->where(['entry_id' => $entry_id])->column();
    }

    /**
     * @param $entry_id
     * @param $type
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function findForEntry($entry_id, $type)
    {
        return static::find()->where(['entry_id' => $entry_id, 'type' => $type])->one();
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'        => Yii::t('audit', 'ID'),
            'entry_id'  => Yii::t('audit', 'Entry ID'),
            'created'   => Yii::t('audit', 'Created'),
            'message'   => Yii::t('audit', 'Type'),
            'code'      => Yii::t('audit', 'Data'),
        ];
    }



    /**
     * @return  create table
     */
    public function createMonthlyTable($table_suffix)
    {
        $table = self::TABLE_NAME.'_'.$table_suffix;
        $sql = " CREATE TABLE  IF NOT EXISTS `".$table."` (
              	`id` INT(11) NOT NULL AUTO_INCREMENT,
                `entry_id` INT(11) NOT NULL,
                `type` VARCHAR(255) NOT NULL,
                `data` BLOB NULL,
                `created` DATETIME NULL DEFAULT NULL,
                PRIMARY KEY (`id`),
                INDEX `fk_audit_data_entry_id` (`entry_id`)
            )
            COLLATE='utf8_general_ci'
            ENGINE=InnoDB
            AUTO_INCREMENT=0

                ";
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