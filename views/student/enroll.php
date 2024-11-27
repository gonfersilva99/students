<?php

use app\models\Program;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Student */
/** @var array $programs */

$this->title = 'Enroll Student';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="student-enroll">

    <?php $form = ActiveForm::begin(); ?>

    <?php echo $form->errorSummary($model) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?php

    echo $form->field($model, "program_id")
        ->dropDownList(
            $programs, ['prompt' => Yii::t('app', '-- Select Program --')]);
    ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>


</div>
