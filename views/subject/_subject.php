<?php

use app\models\Subject;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\widgets\Pjax;

/** @var Subject $model */
?>

<?php // Pjax::begin(['id' => 'pjax-'.$model->id]); ?>
<div class="card h-100">

    <div class="card-body">
        <h5 class="card-title"><?= $model->name ?></h5>
        <strong>Program:</strong> <?= $model->program->name ?><br/>
        <strong>Year:</strong> <?= $model->year ?><br/>

    </div>
    <?php

    if (Yii::$app->user->can('student')) {
        $user_id = Yii::$app->user->id;
        if (!$model->isSubscribed($user_id)) {
            $button = Html::submitButton('Subscribe subject', ['class' => 'btn btn-primary', 'name' => 'action', 'value' => 'subscribe']);
        } else {
            $button = Html::submitButton('Unsubscribe subject', ['class' => 'btn btn-warning', 'name' => 'action', 'value' => 'unsubscribe']);
        }
        ?>
        <div class="card-footer text-center">
            <?php $form = ActiveForm::begin(); ?>
            <?= Html::hiddenInput("id", $model->id) ?>
            <?= $button ?>
            <?php ActiveForm::end(); ?>

        </div>
        <?php
    }
    ?>
</div>
<?php // Pjax::end(); ?>
