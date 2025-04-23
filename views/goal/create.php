<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Goal */
/* @var $habits app\models\Habit[] */

$this->title = 'Crear Meta';
$this->params['breadcrumbs'][] = ['label' => 'Metas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goal-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'habits' => $habits,
    ]) ?>

</div>
