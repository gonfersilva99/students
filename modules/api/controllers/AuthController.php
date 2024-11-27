<?php


namespace app\modules\api\controllers;


use amnah\yii2\user\models\forms\LoginForm;
use amnah\yii2\user\models\Profile;
use amnah\yii2\user\models\Role;
use amnah\yii2\user\models\User;
use amnah\yii2\user\models\UserToken;
use http\Exception\InvalidArgumentException;
use Yii;
use yii\rest\Controller;
use yii\web\ServerErrorHttpException;


class AuthController extends Controller
{

    public function verbs()
    {
        return [
            'index' => ['POST'],
            'register' => ['POST']
        ];
    }

    public function actionIndex()
    {
        $model = new LoginForm();


        if ($model->load(Yii::$app->request->post(), '') && $model->validate()) {
            Yii::$app->user->login($model->getUser());
            $user = Yii::$app->user->identity;
            return ['access_token' => Yii::$app->user->identity->access_token];

        } else {
            $model->validate();
            return $model;
        }
    }


    public function actionRegister(){
        // set up new user objects
        $user = new User();
        $user->scenario = "register";
        // load post data
        $post = Yii::$app->request->post();
        if(!$post){
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }
        if ($user->load($post, '')) {
            // validate for normal request
            if ($user->validate()) {
                // perform registration
                $role = new Role();
                $user->setRegisterAttributes($role::ROLE_USER)->save();
                $this->afterRegister($user);
            }
        }
        return $user;
    }

    /**
     * Process data after registration
     * @param \amnah\yii2\user\models\User $user
     */
    protected function afterRegister($user)
    {
        /** @var \amnah\yii2\user\models\UserToken $userToken */
        $userToken = new UserToken();

        // determine userToken type to see if we need to send email
        $userTokenType = null;
        if ($user->status == $user::STATUS_INACTIVE) {
            $userTokenType = $userToken::TYPE_EMAIL_ACTIVATE;
        } elseif ($user->status == $user::STATUS_UNCONFIRMED_EMAIL) {
            $userTokenType = $userToken::TYPE_EMAIL_CHANGE;
        }

        // check if we have a userToken type to process, or just log user in directly
        if ($userTokenType) {
            $userToken = $userToken::generate($user->id, $userTokenType);
            if (!$numSent = $user->sendEmailConfirmation($userToken)) {

                // handle email error
                //Yii::$app->session->setFlash("Email-error", "Failed to send email");
            }
        } else {
            // Yii::$app->user->login($user, $this->module->loginDuration);
            Yii::$app->user->login($user);
        }
    }
}
