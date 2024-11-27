<?php

use app\models\Program;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Search\StudentSearch */
/* @var $form yii\widgets\ActiveForm */
/** @var array $programs */
?>

<div class="student-search">

    <?php $form = ActiveForm::begin([
        //'action' => ['index'],
        'method' => 'get',
        'options' => [
            // Must define the key. The value is not important.
            'data-pjax' => ''
        ],
    ]); ?>


    <?php

    echo $form->field($model, "program_id")
        ->dropDownList(
            $programs, ['prompt' => Yii::t('app', '-- All Programs --')]);
    ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
