<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\HabitLog */
/* @var $habit app\models\Habit */

$this->title = 'Registrar: ' . $habit->name;
$this->params['breadcrumbs'][] = ['label' => 'Hábitos', 'url' => ['/habit/index']];
$this->params['breadcrumbs'][] = ['label' => $habit->name, 'url' => ['/habit/view', 'id' => $habit->id]];
$this->params['breadcrumbs'][] = ['label' => 'Registros', 'url' => ['index', 'habitId' => $habit->id]];
$this->params['breadcrumbs'][] = 'Crear';
?>
<div class="habit-log-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'habit' => $habit,
    ]) ?>

</div>
