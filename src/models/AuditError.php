<?php

namespace musingsz\yii2\audit\models;

use musingsz\yii2\audit\components\db\ActiveRecord;
use Yii;

/**
 * AuditError
 * @package musingsz\yii2\audit\models
 *
 * @property int           $id
 * @property int           $entry_id
 * @property string        $created
 * @property string        $message
 * @property int           $code
 * @property string        $file
 * @property int           $line
 * @property mixed         $trace
 * @property string        $hash
 * @property int           $emailed
 *
 * @property AuditEntry    $entry
 */
class AuditError extends ActiveRecord
{

    const  TABLE_NAME = 'audit_error';

    /**
     * @var array
     */
    protected $serializeAttributes = ['trace'];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%audit_error}}';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntry()
    {
        return $this->hasOne(AuditEntry::className(), ['id' => 'entry_id']);
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
            'message'   => Yii::t('audit', 'Message'),
            'code'      => Yii::t('audit', 'Error Code'),
            'file'      => Yii::t('audit', 'File'),
            'line'      => Yii::t('audit', 'Line'),
            'trace'     => Yii::t('audit', 'Trace'),
            'hash'      => Yii::t('audit', 'Hash'),
            'emailed'   => Yii::t('audit', 'Emailed'),
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
                `created` DATETIME NOT NULL,
                `message` TEXT NOT NULL,
                `code` INT(11) NULL DEFAULT '0',
                `file` VARCHAR(512) NULL DEFAULT NULL,
                `line` INT(11) NULL DEFAULT NULL,
                `trace` BLOB NULL,
                `hash` VARCHAR(32) NULL DEFAULT NULL,
                `emailed` TINYINT(1) NOT NULL DEFAULT '0',
                PRIMARY KEY (`id`),
                INDEX `fk_audit_error_entry_id` (`entry_id`),
                INDEX `idx_file` (`file`(180)),
                INDEX `idx_emailed` (`emailed`)

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
