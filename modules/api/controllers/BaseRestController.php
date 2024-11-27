<?php
namespace app\modules\api\controllers;

use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\filters\Cors;



class BaseRestController extends \yii\rest\ActiveController
{

    public function behaviors()
    {

        $behaviors = parent::behaviors();

        // remove authentication filter
        $auth = $behaviors['authenticator'];
        unset($behaviors['authenticator']);

        $behaviors['corsFilter'] = [
            'class' => Cors::class,
        ];

//        $behaviors['verbFilter']['actions']['index'][] = 'OPTIONS';


        $behaviors['authenticator'] = [
            'class' => CompositeAuth::class,
            'authMethods' => [
                HttpBasicAuth::class,
                HttpBearerAuth::class,
                QueryParamAuth::class,
            ],
        ];

        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'actions' => ['index', 'view', 'create', 'update', 'delete'],
                    'allow' => true,
                    'roles' => ['admin'],
                ],

            ],
        ];
        return $behaviors;
    }


//    public function verbs()
//    {
//        return [
//            'index' => ['GET', 'HEAD', 'OPTIONS'],
//            'view' => ['GET', 'HEAD'],
//            'create' => ['POST'],
//            'update' => ['PUT', 'PATCH'],
//            'delete' => ['DELETE'],
//        ];
//    }
}
