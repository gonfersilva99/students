<?php

use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\search\SubjectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Subjects';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subject-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Subject', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'year',
            'semester',
            'program_id',

            ['class' => 'yii\grid\ActionColumn',
                'template' => "{update} {delete} {view}
                    {subscribe} {subscribe-post}",
                'buttons' => [
                    'subscribe' => function ($url, $model, $key) {
                        return Html::a('Subscribe', ['', 'id' => $model->id]);
                    },
                    'subscribe-post' => function ($url, $model, $key) {
                        $form = Html::beginForm();
                        $form .= Html::hiddenInput("id", $model->id);
                        $form .= Html::submitButton("Subscribe with post", 
                            ['class' => 'btn btn-secondary']);
                        $form .= Html::endForm();
                        return $form;
                    },
                ]
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
