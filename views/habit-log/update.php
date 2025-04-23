<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\HabitLog */
/* @var $habit app\models\Habit */

$this->title = 'Actualizar Registro: ' . $habit->name . ' (' . Yii::$app->formatter->asDate($model->log_date) . ')';
$this->params['breadcrumbs'][] = ['label' => 'Hábitos', 'url' => ['/habit/index']];
$this->params['breadcrumbs'][] = ['label' => $habit->name, 'url' => ['/habit/view', 'id' => $habit->id]];
$this->params['breadcrumbs'][] = ['label' => 'Registros', 'url' => ['index', 'habitId' => $habit->id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="habit-log-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'habit' => $habit,
    ]) ?>

</div>
