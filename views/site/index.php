<?php

/** @var yii\web\View $this */
/** @var app\models\Habit[] $habits */
/** @var int $habitsCount */
/** @var app\models\Goal[] $goals */
/** @var int $goalsCount */
/** @var int $categoriesCount */
/** @var app\models\HabitLog[] $logs */
/** @var int $logsCount */
/** @var int $totalHabits */
/** @var int $completedHabits */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Mi Dashboard de Hábitos';
?>
<div class="site-index">

    <div class="jumbotron text-center" style="background-color: var(--pastel-purple); padding: 20px; border-radius: 10px; margin-bottom: 20px;">
        <h1 style="color: #333;">¡Bienvenido a tu Dashboard de Hábitos!</h1>
        <p class="lead" style="color: #333;">Aquí puedes visualizar tu progreso y gestionar tus hábitos diarios.</p>
    </div>

    <!-- Resumen de estadísticas -->
    <div class="row" style="margin-bottom: 20px;">
        <div class="col-md-3">
            <div class="panel panel-default" style="border-color: var(--pastel-blue);">
                <div class="panel-heading" style="background-color: var(--pastel-blue); color: #333;">
                    <h3 class="panel-title text-center"><i class="glyphicon glyphicon-check"></i> Hábitos Activos</h3>
                </div>
                <div class="panel-body text-center">
                    <h2><?= $habitsCount ?></h2>
                    <p><?= Html::a('Ver todos', ['/habit/index'], ['class' => 'btn btn-sm', 'style' => 'background-color: var(--pastel-blue); color: #333;']) ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="panel panel-default" style="border-color: var(--pastel-green);">
                <div class="panel-heading" style="background-color: var(--pastel-green); color: #333;">
                    <h3 class="panel-title text-center"><i class="glyphicon glyphicon-flag"></i> Metas Activas</h3>
                </div>
                <div class="panel-body text-center">
                    <h2><?= $goalsCount ?></h2>
                    <p><?= Html::a('Ver todas', ['/goal/index'], ['class' => 'btn btn-sm', 'style' => 'background-color: var(--pastel-green); color: #333;']) ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="panel panel-default" style="border-color: var(--pastel-yellow);">
                <div class="panel-heading" style="background-color: var(--pastel-yellow); color: #333;">
                    <h3 class="panel-title text-center"><i class="glyphicon glyphicon-tag"></i> Categorías</h3>
                </div>
                <div class="panel-body text-center">
                    <h2><?= $categoriesCount ?></h2>
                    <p><?= Html::a('Administrar', ['/category/index'], ['class' => 'btn btn-sm', 'style' => 'background-color: var(--pastel-yellow); color: #333;']) ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="panel panel-default" style="border-color: var(--pastel-teal);">
                <div class="panel-heading" style="background-color: var(--pastel-teal); color: #333;">
                    <h3 class="panel-title text-center"><i class="glyphicon glyphicon-list-alt"></i> Registros</h3>
                </div>
                <div class="panel-body text-center">
                    <h2><?= $logsCount ?></h2>
                    <p><?= Html::a('Ver todos', ['/habit-log/index'], ['class' => 'btn btn-sm', 'style' => 'background-color: var(--pastel-teal); color: #333;']) ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Progreso diario -->
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default" style="border-color: var(--pastel-purple);">
                <div class="panel-heading" style="background-color: var(--pastel-purple); color: #333;">
                    <h3 class="panel-title"><i class="glyphicon glyphicon-dashboard"></i> Progreso diario</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="progress" style="height: 30px;">
                                <?php 
                                $percentage = $totalHabits > 0 ? round(($completedHabits / $totalHabits) * 100) : 0;
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
                                <div class="progress-bar progress-bar-<?= $class ?>" role="progressbar" 
                                     aria-valuenow="<?= $percentage ?>" aria-valuemin="0" aria-valuemax="100" 
                                     style="width: <?= $percentage ?>%; min-width: 2em; line-height: 30px;">
                                    <?= $percentage ?>%
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <p><strong>Hábitos completados hoy:</strong> <?= $completedHabits ?> de <?= $totalHabits ?></p>
                            <?= Html::a('Registrar un hábito', ['/habit/index'], ['class' => 'btn', 'style' => 'background-color: var(--pastel-green); color: #333;']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Hábitos recientes -->
        <div class="col-md-6">
            <div class="panel panel-default" style="border-color: var(--pastel-blue);">
                <div class="panel-heading" style="background-color: var(--pastel-blue); color: #333;">
                    <h3 class="panel-title"><i class="glyphicon glyphicon-time"></i> Mis hábitos recientes</h3>
                </div>
                <div class="panel-body">
                    <?php if (empty($habits)): ?>
                        <p class="text-center">No tienes hábitos activos. ¡Crea tu primer hábito!</p>
                        <p class="text-center"><?= Html::a('Crear hábito', ['/habit/create'], ['class' => 'btn', 'style' => 'background-color: var(--pastel-green); color: #333;']) ?></p>
                    <?php else: ?>
                        <div class="list-group">
                            <?php foreach ($habits as $habit): ?>
                                <a href="<?= Url::to(['/habit/view', 'id' => $habit->id]) ?>" class="list-group-item">
                                    <h4 class="list-group-item-heading"><?= Html::encode($habit->name) ?></h4>
                                    <p class="list-group-item-text">
                                        <span class="label" style="background-color: <?= $habit->category ? $habit->category->color : 'var(--pastel-gray)' ?>; color: #333;">
                                            <?= $habit->category ? Html::encode($habit->category->name) : 'Sin categoría' ?>
                                        </span>
                                        &nbsp;
                                        <small><i class="glyphicon glyphicon-calendar"></i> Inicio: <?= Yii::$app->formatter->asDate($habit->start_date) ?></small>
                                    </p>
                                </a>
                            <?php endforeach; ?>
                        </div>
                        <p class="text-center"><?= Html::a('Ver todos', ['/habit/index'], ['class' => 'btn btn-sm', 'style' => 'background-color: var(--pastel-blue); color: #333;']) ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Metas próximas -->
        <div class="col-md-6">
            <div class="panel panel-default" style="border-color: var(--pastel-green);">
                <div class="panel-heading" style="background-color: var(--pastel-green); color: #333;">
                    <h3 class="panel-title"><i class="glyphicon glyphicon-flag"></i> Metas próximas</h3>
                </div>
                <div class="panel-body">
                    <?php if (empty($goals)): ?>
                        <p class="text-center">No tienes metas activas. ¡Establece tu primera meta!</p>
                        <p class="text-center"><?= Html::a('Crear meta', ['/goal/create'], ['class' => 'btn', 'style' => 'background-color: var(--pastel-green); color: #333;']) ?></p>
                    <?php else: ?>
                        <div class="list-group">
                            <?php foreach ($goals as $goal): ?>
                                <a href="<?= Url::to(['/goal/view', 'id' => $goal->id]) ?>" class="list-group-item">
                                    <h4 class="list-group-item-heading"><?= Html::encode($goal->name) ?></h4>
                                    <p class="list-group-item-text">
                                        <div class="progress" style="margin-bottom: 5px;">
                                            <?php 
                                            $percentage = $goal->getCompletionPercentage();
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
                                            <div class="progress-bar progress-bar-<?= $class ?>" role="progressbar" 
                                                 aria-valuenow="<?= $percentage ?>" aria-valuemin="0" aria-valuemax="100" 
                                                 style="width: <?= $percentage ?>%; min-width: 2em;">
                                                <?= $percentage ?>%
                                            </div>
                                        </div>
                                        <small><i class="glyphicon glyphicon-calendar"></i> Fecha límite: <?= Yii::$app->formatter->asDate($goal->target_date) ?></small>
                                    </p>
                                </a>
                            <?php endforeach; ?>
                        </div>
                        <p class="text-center"><?= Html::a('Ver todas', ['/goal/index'], ['class' => 'btn btn-sm', 'style' => 'background-color: var(--pastel-green); color: #333;']) ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Registros recientes -->
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default" style="border-color: var(--pastel-teal);">
                <div class="panel-heading" style="background-color: var(--pastel-teal); color: #333;">
                    <h3 class="panel-title"><i class="glyphicon glyphicon-list-alt"></i> Registros recientes</h3>
                </div>
                <div class="panel-body">
                    <?php if (empty($logs)): ?>
                        <p class="text-center">No tienes registros recientes. ¡Comienza a registrar tus hábitos!</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Hábito</th>
                                        <th>Fecha</th>
                                        <th>Valor</th>
                                        <th>Estado</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($logs as $log): ?>
                                        <tr>
                                            <td><?= Html::encode($log->habit->name) ?></td>
                                            <td><?= Yii::$app->formatter->asDate($log->log_date) ?></td>
                                            <td><?= $log->value ?> <?= $log->habit->unit ?></td>
                                            <td>
                                                <?php if ($log->mood): ?>
                                                    <span class="label" style="background-color: <?= ($log->mood > 3) ? 'var(--pastel-green)' : (($log->mood < 3) ? 'var(--pastel-red)' : 'var(--pastel-yellow)') ?>; color: #333;">
                                                        <?= $log->getMoodLabel() ?>
                                                    </span>
                                                <?php else: ?>
                                                    -
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?= Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['/habit-log/view', 'id' => $log->id], ['class' => 'btn btn-xs', 'style' => 'background-color: var(--pastel-blue); color: #333;']) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <p class="text-center"><?= Html::a('Ver todos los registros', ['/habit-log/index'], ['class' => 'btn btn-sm', 'style' => 'background-color: var(--pastel-teal); color: #333;']) ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
