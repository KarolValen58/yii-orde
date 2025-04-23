<?php

use app\models\Goal;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Mis Metas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goal-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Crear nueva meta', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            [
                'attribute' => 'habit_id',
                'value' => 'habit.name',
                'label' => 'Hábito relacionado',
            ],
            [
                'attribute' => 'target_value',
                'value' => function ($model) {
                    return $model->target_value . ' ' . ($model->unit ? $model->unit : '');
                }
            ],
            [
                'attribute' => 'achieved_value',
                'value' => function ($model) {
                    return $model->achieved_value . ' ' . ($model->unit ? $model->unit : '');
                }
            ],
            [
                'attribute' => 'completion_percentage',
                'label' => 'Progreso',
                'format' => 'raw',
                'value' => function ($model) {
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
                    
                    return '<div class="progress">
                                <div class="progress-bar progress-bar-' . $class . '" role="progressbar" 
                                    aria-valuenow="' . $percentage . '" aria-valuemin="0" aria-valuemax="100" 
                                    style="width: ' . $percentage . '%;">
                                    ' . $percentage . '%
                                </div>
                            </div>';
                },
            ],
            'target_date:date',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function ($model) {
                    $statusLabels = [
                        'pending' => '<span class="label label-default">Pendiente</span>',
                        'in_progress' => '<span class="label label-primary">En progreso</span>',
                        'completed' => '<span class="label label-success">Completado</span>',
                        'cancelled' => '<span class="label label-danger">Cancelado</span>',
                    ];
                    return isset($statusLabels[$model->status]) ? $statusLabels[$model->status] : $model->status;
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                            'title' => 'Ver',
                            'data-pjax' => '0',
                        ]);
                    },
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => 'Actualizar',
                            'data-pjax' => '0',
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => 'Eliminar',
                            'data-confirm' => '¿Estás seguro de que quieres eliminar esta meta?',
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
