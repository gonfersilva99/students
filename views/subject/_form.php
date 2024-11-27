<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Subject */
/* @var $form yii\widgets\ActiveForm */
/* @var $programs array */
?>

<div class="subject-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'year')->textInput() ?>

    <?= $form->field($model, 'semester')->textInput() ?>


    <?= $form->field($model, 'program_id')
    ->dropDownList($programs, ['prompt' => 'Select Program']) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
