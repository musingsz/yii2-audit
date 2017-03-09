<?php
/**
 * Console compatible error handler
 */

namespace musingsz\yii2\audit\components\console;

use musingsz\yii2\audit\components\base\ErrorHandlerTrait;

/**
 * ErrorHandler
 * @package musingsz\yii2\audit\components\console
 */
class ErrorHandler extends \yii\console\ErrorHandler
{
    use ErrorHandlerTrait;
}