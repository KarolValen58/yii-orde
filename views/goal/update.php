<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Goal */
/* @var $habits app\models\Habit[] */

$this->title = 'Actualizar Meta: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Metas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="goal-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'habits' => $habits,
    ]) ?>

</div>
