<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Habit */
/* @var $categories app\models\Category[] */

$this->title = 'Crear Hábito';
$this->params['breadcrumbs'][] = ['label' => 'Hábitos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="habit-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'categories' => $categories,
    ]) ?>

</div>
