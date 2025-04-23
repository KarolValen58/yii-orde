<?php

use app\models\Habit;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Mis Hábitos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="habit-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Crear nuevo hábito', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            [
                'attribute' => 'category_id',
                'value' => 'category.name',
                'filter' => false,
            ],
            [
                'attribute' => 'frequency_type',
                'value' => function ($model) {
                    $options = Habit::getFrequencyOptions();
                    return isset($options[$model->frequency_type]) ? $options[$model->frequency_type] : $model->frequency_type;
                }
            ],
            [
                'attribute' => 'target_value',
                'value' => function ($model) {
                    return $model->target_value . ' ' . ($model->unit ? $model->unit : '');
                }
            ],
            'start_date:date',
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
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {log} {delete}',
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
                    'log' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-check"></span>', ['log', 'id' => $model->id], [
                            'title' => 'Registrar',
                            'data-pjax' => '0',
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => 'Eliminar',
                            'data-confirm' => '¿Estás seguro de que quieres eliminar este hábito?',
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
