<?php
/**
 * Error handler version for web based modules
 */

namespace musingsz\yii2\audit\components\web;

use musingsz\yii2\audit\components\base\ErrorHandlerTrait;

/**
 * ErrorHandler
 * @package musingsz\yii2\audit\components\web
 */
class ErrorHandler extends \yii\web\ErrorHandler
{
    use ErrorHandlerTrait;
}