<?php

use app\models\HabitLog;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\HabitLog */
/* @var $habit app\models\Habit */

$this->title = 'Registrar hábito: ' . $habit->name;
$this->params['breadcrumbs'][] = ['label' => 'Hábitos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $habit->name, 'url' => ['view', 'id' => $habit->id]];
$this->params['breadcrumbs'][] = 'Registrar';
?>
<div class="habit-log">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="habit-log-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'log_date')->input('date') ?>

        <?= $form->field($model, 'value')->textInput(['type' => 'number', 'step' => '0.01']) ?>
        
        <div class="help-block">
            Valor objetivo: <?= $habit->target_value ?> <?= $habit->unit ?>
        </div>

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

</div>
