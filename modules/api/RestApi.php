<?php

namespace app\modules\api;

use Yii;

/**
 * api module definition class
 */
class RestApi extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\api\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
//        Yii::$app->user->enableSession = false;
    }
}
