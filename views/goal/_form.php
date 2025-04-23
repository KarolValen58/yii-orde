<?php

use app\models\Goal;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Goal */
/* @var $habits app\models\Habit[] */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="goal-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'habit_id')->dropDownList(
        ArrayHelper::map($habits, 'id', 'name'),
        ['prompt' => 'Selecciona un hábito (opcional)']
    ) ?>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'target_value')->textInput(['type' => 'number', 'step' => '0.01']) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'unit')->textInput(['maxlength' => true, 'placeholder' => 'ej. veces, minutos, km']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'achieved_value')->textInput(['type' => 'number', 'step' => '0.01']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'start_date')->input('date') ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'target_date')->input('date') ?>
        </div>
    </div>

    <?= $form->field($model, 'status')->dropDownList(Goal::getStatusOptions()) ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
