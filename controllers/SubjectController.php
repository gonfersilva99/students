<?php

namespace app\controllers;

use app\models\Program;
use app\models\Subject;
use app\models\search\SubjectSearch;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use amnah\yii2\user\models\Role;

/**
 * SubjectController implements the CRUD actions for Subject model.
 */
class SubjectController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'actions' => ['index', 'view', 'create', 'update', 'delete', 'list-subscribed'],
                            'allow' => true,
                            // 'roles' => ['admin'],
                            'matchCallback' => function ($rule, $action) {
                                $a = Yii::$app->user->identity->id;
                                $b = Yii::$app->user->id;
                                
                                $role_id = Yii::$app->user->identity->role_id;
                                $role = Role::find()->where(['name' => 'Admin'])->one();
                                return $role_id == $role->id;
                            }
                        ],
                        [
                            'actions' => ['list'],
                            'allow' => true,
                        ],
                        [
                            'actions' => ['list-subscribed', 'subscribe', 'unsubscribe'],
                            'allow' => true,
                            'roles' => ['student'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Subject models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SubjectSearch();

        if($this->request->isPost){
            $subject_id = $this->request->post('id');
            $subject = Subject::findOne($subject_id);
            Yii::$app->session->addFlash("success", "Subject `{$subject->name}` Subscribed");


        }
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Subject model.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Subject model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Subject();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        $programs = Program::getAllAsArray();

        return $this->render('create', [
            'model' => $model,
            'programs' => $programs
        ]);
    }

    /**
     * Updates an existing Subject model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $programs = Program::getAllAsArray();

        return $this->render('update', [
            'model' => $model,
            'programs' => $programs
        ]);
    }

    /**
     * Deletes an existing Subject model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Subject model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Subject the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Subject::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Lists all Subject models.
     * @return mixed
     */
    public function actionList()
    {
        $model = new Subject();
        $searchModel = new SubjectSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        $this->_subscribeUnsubscribe();

        $programs = Program::getAllAsArray();

        return $this->render('list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'programs' => $programs

        ]);

    }

    /**
     * Lists all Subject models.
     * @return mixed
     */
    public function actionListSubscribed()
    {
        $searchModel = new SubjectSearch();
        $searchModel->user_id = Yii::$app->user->id;
        $dataProvider = $searchModel->searchSubscribed($this->request->queryParams);

        $this->_subscribeUnsubscribe();
        $programs = Program::getAllAsArray();
        return $this->render('list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'programs' => $programs
        ]);
    }

    private function _subscribeUnsubscribe(){
        if ($this->request->isPost) {
            $subject_id = $this->request->post('id');
            $subject = Subject::findOne($subject_id);
            if($subject) {
                $user_id = Yii::$app->user->id;
                $action = $this->request->post('action');
                if($action == 'subscribe') {
                    $res = $subject->subscribe($user_id);

                    if ($res) {
                        Yii::$app->session->addFlash("success", "Subject `{$subject->name}` subscribed");
                    } else {
                        Yii::$app->session->addFlash("danger", "Error subscribing Subject `{$subject->name}`");
                    }
                }else if($action == 'unsubscribe') {
                    if ($subject->unsubscribe($user_id)) {
                        Yii::$app->session->addFlash("success", "Subject `{$subject->name}` unsubscribed");
                    } else {
                        Yii::$app->session->addFlash("danger", "Error subscribing Subject `{$subject->name}`");
                    }
                }else{
                    Yii::$app->session->addFlash("danger", "Invalid option");
                }
            }else{
                Yii::$app->session->addFlash("danger", "Subject unavailable");
            }
        }
    }
}
