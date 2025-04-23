<?php

use app\models\HabitLog;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\HabitLog */
/* @var $habit app\models\Habit */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="habit-log-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Información del hábito</h3>
        </div>
        <div class="panel-body">
            <p><strong>Nombre:</strong> <?= Html::encode($habit->name) ?></p>
            <p><strong>Categoría:</strong> <?= $habit->category ? Html::encode($habit->category->name) : 'Sin categoría' ?></p>
            <p><strong>Objetivo diario:</strong> <?= $habit->target_value ?> <?= $habit->unit ?></p>
        </div>
    </div>

    <?= $form->field($model, 'log_date')->input('date') ?>

    <?= $form->field($model, 'value')->textInput(['type' => 'number', 'step' => '0.01']) ?>

    <?= $form->field($model, 'notes')->textarea(['rows' => 4]) ?>

    <?= $form->field($model, 'mood')->dropDownList(
        HabitLog::getMoodOptions(),
        ['prompt' => 'Selecciona tu estado de ánimo']
    ) ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
