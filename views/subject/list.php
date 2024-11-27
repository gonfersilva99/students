<?php

use yii\widgets\ListView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Search\SubjectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/** @var array $programs */

$this->title = 'Subjects';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subject-list">


        <?php Pjax::begin(); ?>
    <?php
    echo $this->render('_searchProgram', [
            'model' => $searchModel,
            'programs' => $programs]); ?>


    <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView' => '_subject',
            'itemOptions' => ['class' => 'col-lg-4 col-md-6 mb-3'],
            'layout' => '{summary}<div class="row">{items}</div>{pager}'
        ]
    );
    ?>

    <?php Pjax::end(); ?>

</div>
