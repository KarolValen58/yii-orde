<?php

use app\models\Goal;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Goal */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Metas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="goal-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php if ($model->habit): ?>
            <?= Html::a('Ver hábito', ['/habit/view', 'id' => $model->habit_id], ['class' => 'btn btn-info']) ?>
        <?php endif; ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Estás seguro de que quieres eliminar esta meta?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="row">
        <div class="col-md-6">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'name',
                    'description:ntext',
                    [
                        'attribute' => 'habit_id',
                        'value' => $model->habit ? $model->habit->name : 'Sin hábito relacionado',
                    ],
                    [
                        'attribute' => 'target_value',
                        'value' => $model->target_value . ' ' . ($model->unit ? $model->unit : ''),
                    ],
                    [
                        'attribute' => 'achieved_value',
                        'value' => $model->achieved_value . ' ' . ($model->unit ? $model->unit : ''),
                    ],
                    'start_date:date',
                    'target_date:date',
                    'completion_date:date',
                    [
                        'attribute' => 'status',
                        'value' => $model->getStatusLabel(),
                    ],
                    'created_at:datetime',
                    'updated_at:datetime',
                ],
            ]) ?>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Progreso</h3>
                </div>
                <div class="panel-body">
                    <?php
                    $percentage = $model->getCompletionPercentage();
                    $class = 'info';
                    if ($percentage >= 100) {
                        $class = 'success';
                    } elseif ($percentage >= 50) {
                        $class = 'primary';
                    } elseif ($percentage >= 25) {
                        $class = 'warning';
                    } elseif ($percentage < 25) {
                        $class = 'danger';
                    }
                    ?>
                    <h4>Progreso: <?= $percentage ?>%</h4>
                    <div class="progress">
                        <div class="progress-bar progress-bar-<?= $class ?>" role="progressbar" 
                            aria-valuenow="<?= $percentage ?>" aria-valuemin="0" aria-valuemax="100" 
                            style="width: <?= $percentage ?>%;">
                            <?= $percentage ?>%
                        </div>
                    </div>
                    
                    <h4>Información adicional</h4>
                    <p><strong>Valor actual:</strong> <?= $model->achieved_value ?> <?= $model->unit ?></p>
                    <p><strong>Objetivo:</strong> <?= $model->target_value ?> <?= $model->unit ?></p>
                    <p><strong>Restante:</strong> <?= max(0, $model->target_value - $model->achieved_value) ?> <?= $model->unit ?></p>
                    
                    <?php if ($model->target_date > date('Y-m-d')): ?>
                        <p><strong>Días hasta la fecha objetivo:</strong> <?= ceil((strtotime($model->target_date) - time()) / (60 * 60 * 24)) ?></p>
                    <?php elseif ($model->status != 'completed'): ?>
                        <p><strong>Estado:</strong> <span class="label label-danger">Fecha objetivo vencida</span></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

</div>
