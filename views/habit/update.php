<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Habit */
/* @var $categories app\models\Category[] */

$this->title = 'Actualizar Hábito: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Hábitos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="habit-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'categories' => $categories,
    ]) ?>

</div>
