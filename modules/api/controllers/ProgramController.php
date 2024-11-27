<?php
namespace app\modules\api\controllers;

use app\models\Program;
use app\models\Search\ProgramSearch;
use app\modules\api\models\ProgramRest;
use Yii;
use yii\filters\AccessControl;
use yii\web\Response;

class ProgramController extends BaseRestController
{
    public $modelClass = Program::class;
    //'app\models\User';

    public function behaviors()
    {
        $behaviors = parent::behaviors();


        $behaviors['access']['rules'][] =
                [
                    'actions' => ['list-by-name'],
                    'allow' => true,
                ];
        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        return $actions;
    }


    public function actionIndex()
    {
        $searchModel = new ProgramRest();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

//        $this->_subscribeUnsubscribe();

        return $dataProvider;
    }

    public function actionListByName()
    {
        $searchModel = new ProgramRest();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

//        $this->_subscribeUnsubscribe();
        return $dataProvider;
    }
}
