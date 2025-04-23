<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Registro de Hábitos';
?>
<div class="site-welcome">
    <div class="jumbotron text-center" style="background-color: var(--pastel-purple); padding: 30px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
        <h1 style="color: #333;">¡Bienvenido a tu Diario de Hábitos!</h1>
        <p class="lead" style="color: #333;">Una forma sencilla y efectiva de seguir tus hábitos diarios y alcanzar tus metas.</p>
        <p>
            <?= Html::a('Iniciar sesión', ['site/login'], ['class' => 'btn btn-lg', 'style' => 'background-color: var(--pastel-green); color: #333;']) ?>
        </p>
    </div>

    <div class="row mt-4">
        <div class="col-lg-4">
            <div class="panel panel-default" style="border-color: var(--pastel-blue); min-height: 250px;">
                <div class="panel-heading" style="background-color: var(--pastel-blue); color: #333;">
                    <h2 class="panel-title text-center"><i class="glyphicon glyphicon-check"></i> Registra tus hábitos</h2>
                </div>
                <div class="panel-body">
                    <p>Mantén un seguimiento diario de tus hábitos. Establece metas, categorías y visualiza tu progreso a lo largo del tiempo.</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="panel panel-default" style="border-color: var(--pastel-green); min-height: 250px;">
                <div class="panel-heading" style="background-color: var(--pastel-green); color: #333;">
                    <h2 class="panel-title text-center"><i class="glyphicon glyphicon-stats"></i> Visualiza tu progreso</h2>
                </div>
                <div class="panel-body">
                    <p>Observa tus estadísticas y evolución. La plataforma te ayuda a mantener la motivación mostrándote tu avance diario.</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="panel panel-default" style="border-color: var(--pastel-orange); min-height: 250px;">
                <div class="panel-heading" style="background-color: var(--pastel-orange); color: #333;">
                    <h2 class="panel-title text-center"><i class="glyphicon glyphicon-flag"></i> Alcanza tus metas</h2>
                </div>
                <div class="panel-body">
                    <p>Establece metas claras y monitorea tu avance hacia ellas. Celebra tus logros y mantén el rumbo hacia una vida más saludable.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="panel panel-default" style="border-color: var(--pastel-purple);">
                <div class="panel-heading" style="background-color: var(--pastel-purple); color: #333;">
                    <h2 class="panel-title text-center">¿Por qué registrar tus hábitos?</h2>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <ul>
                                <li>Aumenta tu conciencia de los patrones diarios</li>
                                <li>Mejora tu motivación al ver tu progreso</li>
                                <li>Establece rutinas más saludables</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul>
                                <li>Te ayuda a mantener el enfoque en tus objetivos</li>
                                <li>Facilita la identificación de obstáculos</li>
                                <li>Celebra tus logros, por pequeños que sean</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
