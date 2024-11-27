<?php


namespace app\modules\api\controllers;


use app\models\Program;
use app\models\Search\SubjectSearch;


use app\models\Subject;
use app\modules\api\models\SubjectRest;
use Yii;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\ServerErrorHttpException;


class SubjectController extends BaseRestController
{
    public $modelClass = Subject::class;

    public function behaviors()
    {
        $behaviors = parent::behaviors();


        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'actions' => ['index', 'view', 'create', 'update', 'delete', 'create-with-program'],
                    'allow' => true,
                    'roles' => ['admin'],
                ],

                [
                    'actions' => ['list'],
                    'allow' => true,
                ],
                [
                    'actions' => ['list-subscribed', 'subscribe'],
                    'allow' => true,
                    'roles' => ['admin'],
                ],
                [
                    'actions' => ['unsubscribe'],
                    'allow' => true,
                    'matchCallback' => function ($rule, $action) {
                        $user_id = Yii::$app->user->id;
                        $subject_id = Yii::$app->request->post('id');
                        $subject = Subject::findOne($subject_id);
                        return $subject && $subject->isSubscribed($user_id);
                    }
                ],
            ],
        ];
        return $behaviors;
    }


    public function actionList()
    {

        $searchModel = new SubjectRest();
        $dataProvider = $searchModel->searchSpecial(Yii::$app->request->queryParams);

//        $this->_subscribeUnsubscribe();

        return $dataProvider;
    }

    public function actionListSubscribed()
    {
        $searchModel = new SubjectRest();
        $searchModel->user_id = Yii::$app->user->id;
        $dataProvider = $searchModel->searchSubscribed(Yii::$app->request->queryParams);

        return $dataProvider;
    }

    public function actionSubscribe()
    {
        $subject_id = Yii::$app->request->post('id');
        $subject = Subject::findOne($subject_id);
        if ($subject) {
            $user_id = Yii::$app->user->id;
            $res = $subject->subscribe($user_id);
            if ($res) {
                return $res;
            } else {
                throw new ServerErrorHttpException("Could not Subscribe");
            }
        } else {
            throw new ServerErrorHttpException("Subject doesn't exist");
        }
    }

    public function actionUnsubscribe()
    {
        $subject_id = Yii::$app->request->post('id');
        $subject = Subject::findOne($subject_id);
        if ($subject) {
            $user_id = Yii::$app->user->id;
            if($subject->unsubscribe($user_id)){
                return [];
            }else{
                throw new ServerErrorHttpException("Could not subscribe");
            }

        } else {

        }
    }

    public function actionCreateWithProgram(){
        $subject = new Subject();
        $program = new Program();
        $post = Yii::$app->request->post();
        if($subject->load($post, '') && $program->load($post, '')){
            $transaction = Yii::$app->db->beginTransaction();
            if($program->save()){
                $subject->program_id = $program->id;
                if($subject->save()) {
                    $transaction->commit();
                }
            }else{
                $transaction->rollBack();
                return $program;
            }
        }else{
            throw new ServerErrorHttpException("Cannot create subject");
        }

        return $subject;
    }
}
