<?php

use app\models\HabitLog;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $habitId int|null */

$this->title = 'Registros de Hábitos';
if ($habitId) {
    $habit = \app\models\Habit::findOne($habitId);
    if ($habit) {
        $this->title = 'Registros del hábito: ' . $habit->name;
        $this->params['breadcrumbs'][] = ['label' => 'Hábitos', 'url' => ['/habit/index']];
        $this->params['breadcrumbs'][] = ['label' => $habit->name, 'url' => ['/habit/view', 'id' => $habit->id]];
    }
}
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="habit-log-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if ($habitId): ?>
    <p>
        <?= Html::a('Registrar nuevo', ['create', 'habitId' => $habitId], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Volver al hábito', ['/habit/view', 'id' => $habitId], ['class' => 'btn btn-primary']) ?>
    </p>
    <?php endif; ?>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'habit_id',
                'value' => 'habit.name',
                'visible' => !$habitId,
            ],
            'log_date:date',
            [
                'attribute' => 'value',
                'value' => function ($model) {
                    return $model->value . ' ' . ($model->habit->unit ? $model->habit->unit : '');
                }
            ],
            'notes:ntext',
            [
                'attribute' => 'mood',
                'format' => 'raw',
                'value' => function ($model) {
                    if (!$model->mood) return '-';
                    $moodLabels = [
                        1 => '<span class="label label-danger">Muy Mal</span>',
                        2 => '<span class="label label-warning">Mal</span>',
                        3 => '<span class="label label-default">Regular</span>',
                        4 => '<span class="label label-info">Bien</span>',
                        5 => '<span class="label label-success">Muy Bien</span>',
                    ];
                    return isset($moodLabels[$model->mood]) ? $moodLabels[$model->mood] : $model->mood;
                }
            ],
            'created_at:datetime',

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
