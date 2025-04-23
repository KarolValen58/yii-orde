<?php

use app\models\HabitLog;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\HabitLog */

$this->title = 'Registro: ' . $model->habit->name . ' (' . Yii::$app->formatter->asDate($model->log_date) . ')';
$this->params['breadcrumbs'][] = ['label' => 'Hábitos', 'url' => ['/habit/index']];
$this->params['breadcrumbs'][] = ['label' => $model->habit->name, 'url' => ['/habit/view', 'id' => $model->habit_id]];
$this->params['breadcrumbs'][] = ['label' => 'Registros', 'url' => ['index', 'habitId' => $model->habit_id]];
$this->params['breadcrumbs'][] = 'Detalles';
\yii\web\YiiAsset::register($this);
?>
<div class="habit-log-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Estás seguro de que quieres eliminar este registro?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'habit_id',
                'value' => $model->habit->name,
            ],
            'log_date:date',
            [
                'attribute' => 'value',
                'value' => $model->value . ' ' . ($model->habit->unit ? $model->habit->unit : ''),
            ],
            'notes:ntext',
            [
                'attribute' => 'mood',
                'value' => $model->getMoodLabel(),
            ],
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
