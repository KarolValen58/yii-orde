<?php

use app\models\Habit;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\Habit */
/* @var $logDataProvider yii\data\ActiveDataProvider */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Hábitos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="habit-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Registrar', ['log', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Ver registros', ['/habit-log/index', 'habitId' => $model->id], ['class' => 'btn btn-info']) ?>
        <?= Html::a('Crear meta', ['/goal/create', 'habitId' => $model->id], ['class' => 'btn btn-warning']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Estás seguro de que quieres eliminar este hábito?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'description:ntext',
            [
                'attribute' => 'category_id',
                'value' => $model->category ? $model->category->name : 'Sin categoría',
            ],
            [
                'attribute' => 'frequency_type',
                'value' => function ($model) {
                    $options = Habit::getFrequencyOptions();
                    return isset($options[$model->frequency_type]) ? $options[$model->frequency_type] : $model->frequency_type;
                }
            ],
            'frequency_value:ntext',
            [
                'attribute' => 'target_value',
                'value' => $model->target_value . ' ' . ($model->unit ? $model->unit : ''),
            ],
            'reminder_time',
            'start_date:date',
            'end_date:date',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function ($model) {
                    $options = Habit::getStatusOptions();
                    $statusLabel = isset($options[$model->status]) ? $options[$model->status] : $model->status;
                    $class = $model->status == Habit::STATUS_ACTIVE ? 'success' : 'danger';
                    return '<span class="label label-' . $class . '">' . $statusLabel . '</span>';
                }
            ],
            'created_at:datetime',
            'updated_at:datetime',
            [
                'label' => 'Tasa de completamiento',
                'value' => function ($model) {
                    return round($model->getCompletionRate(), 2) . '%';
                },
            ],
        ],
    ]) ?>

    <h2>Registros recientes</h2>

    <?php Pjax::begin(); ?>
    
    <?= GridView::widget([
        'dataProvider' => $logDataProvider,
        'columns' => [
            'log_date:date',
            'value',
            'notes:ntext',
            [
                'attribute' => 'mood',
                'value' => function ($model) {
                    return $model->getMoodLabel();
                }
            ],
            'created_at:datetime',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['/habit-log/update', 'id' => $model->id], [
                            'title' => 'Actualizar',
                            'data-pjax' => '0',
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['/habit-log/delete', 'id' => $model->id], [
                            'title' => 'Eliminar',
                            'data-confirm' => '¿Estás seguro de que quieres eliminar este registro?',
                            'data-method' => 'post',
                            'data-pjax' => '0',
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
