<?php
/**
 * This model allows for storing of mail entries linked to a specific audit entry
 */

namespace musingsz\yii2\audit\models;

use musingsz\yii2\audit\components\db\ActiveRecord;
use Yii;

/**
 * AuditMail
 *
 * @package musingsz\yii2\audit\models
 * @property int    $id
 * @property int    $entry_id
 * @property string $created
 * @property int    $successful
 * @property string $from
 * @property string $to
 * @property string $reply
 * @property string $cc
 * @property string $bcc
 * @property string $subject
 * @property string $text
 * @property string $html
 * @property string $data
 *
 * @property AuditEntry    $entry
 */
class AuditMail extends ActiveRecord
{

    const  TABLE_NAME = 'audit_mail';

    protected $serializeAttributes = ['text', 'html', 'data'];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%audit_mail}}';
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
            'id' => Yii::t('audit', 'ID'),
            'entry_id' => Yii::t('audit', 'Entry ID'),
            'created' => Yii::t('audit', 'Created'),
            'successful' => Yii::t('audit', 'Successful'),
            'from' => Yii::t('audit', 'From'),
            'to' => Yii::t('audit', 'To'),
            'reply' => Yii::t('audit', 'Reply'),
            'cc' => Yii::t('audit', 'CC'),
            'bcc' => Yii::t('audit', 'BCC'),
            'subject' => Yii::t('audit', 'Subject'),
            'text' => Yii::t('audit', 'Text Body'),
            'html' => Yii::t('audit', 'HTML Body'),
            'data' => Yii::t('audit', 'Data'),
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
                `successful` INT(11) NOT NULL,
                `from` VARCHAR(255) NULL DEFAULT NULL,
                `to` VARCHAR(255) NULL DEFAULT NULL,
                `reply` VARCHAR(255) NULL DEFAULT NULL,
                `cc` VARCHAR(255) NULL DEFAULT NULL,
                `bcc` VARCHAR(255) NULL DEFAULT NULL,
                `subject` VARCHAR(255) NULL DEFAULT NULL,
                `text` BLOB NULL,
                `html` BLOB NULL,
                `data` BLOB NULL,
                PRIMARY KEY (`id`),
                INDEX `fk_audit_mail_entry_id` (`entry_id`)
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