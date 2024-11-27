<?php


namespace app\modules\api\controllers;


use app\models\Student;
use Yii;
use yii\filters\AccessControl;

class StudentController extends BaseRestController
{
    public $modelClass = Student::class;
    public function behaviors()
    {
        $behaviors = parent::behaviors();


        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'actions' => ['index', 'view', 'create', 'update', 'delete'],
                    'allow' => true,
                    'roles' => ['admin'],
                ],
                [
                    'actions' => ['enroll'],
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ];
        return $behaviors;
    }

    public function actionEnroll()
    {

        $user_id = \Yii::$app->user->id;
        $model = Student::findByUserOrNew($user_id);

        $post= Yii::$app->request->post();

        if ($model && $model->load(Yii::$app->request->post(), '')) {
            if ($model->enroll()) {

            }
        }else{
            $model->validate();
            return $model;
        }
        return $model;

    }
}
