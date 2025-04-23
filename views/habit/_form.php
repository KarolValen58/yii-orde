<?php

use app\models\Category;
use app\models\Habit;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Habit */
/* @var $form yii\widgets\ActiveForm */
/* @var $categories app\models\Category[] */
?>

<div class="habit-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'category_id')->dropDownList(
        ArrayHelper::map($categories, 'id', 'name'),
        ['prompt' => 'Selecciona una categoría']
    ) ?>

    <?= $form->field($model, 'frequency_type')->dropDownList(Habit::getFrequencyOptions()) ?>

    <?= $form->field($model, 'frequency_value')->textarea(['rows' => 2, 'placeholder' => 'Para frecuencia personalizada']) ?>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'target_value')->textInput(['type' => 'number', 'step' => '0.01']) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'unit')->textInput(['maxlength' => true, 'placeholder' => 'ej. veces, minutos, km']) ?>
        </div>
    </div>

    <?= $form->field($model, 'reminder_time')->input('time') ?>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'start_date')->input('date') ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'end_date')->input('date') ?>
        </div>
    </div>

    <?= $form->field($model, 'status')->dropDownList(Habit::getStatusOptions()) ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
