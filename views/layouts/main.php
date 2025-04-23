<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? 'Aplicación de Registro de Hábitos']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? 'hábitos, metas, seguimiento, diario, registro']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);

// Registrar el archivo CSS para los colores pasteles
$this->registerCssFile('@web/css/pastel-theme.css');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header id="header">
    <?php
    NavBar::begin([
        'brandLabel' => 'Registro de Hábitos',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => ['class' => 'navbar-expand-md navbar-inverse fixed-top']
    ]);
    
    $menuItems = [
        ['label' => 'Inicio', 'url' => ['/site/index']],
    ];
    
    if (!Yii::$app->user->isGuest) {
        $menuItems = array_merge($menuItems, [
            ['label' => 'Hábitos', 
             'items' => [
                ['label' => 'Mis Hábitos', 'url' => ['/habit/index']],
                ['label' => 'Nuevo Hábito', 'url' => ['/habit/create']],
                ['label' => 'Categorías', 'url' => ['/category/index']],
             ]
            ],
            ['label' => 'Registros', 'url' => ['/habit-log/index']],
            ['label' => 'Metas', 'url' => ['/goal/index']],
        ]);
    }
    
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Iniciar Sesión', 'url' => ['/site/login']];
    } else {
        $menuItems[] = '<li class="nav-item">'
            . Html::beginForm(['/site/logout'])
            . Html::submitButton(
                'Cerrar Sesión (' . Yii::$app->user->identity->username . ')',
                ['class' => 'nav-link btn btn-link logout']
            )
            . Html::endForm()
            . '</li>';
    }
    
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right ms-auto'],
        'items' => $menuItems
    ]);
    NavBar::end();
    ?>
</header>

<main id="main" class="flex-shrink-0" role="main">
    <div class="container">
        <?php if (!empty($this->params['breadcrumbs'])): ?>
            <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
        <?php endif ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer id="footer" class="mt-auto py-3" style="background-color: var(--pastel-purple); color: #333;">
    <div class="container">
        <div class="row">
            <div class="col-md-6 text-center text-md-start">&copy; Registro de Hábitos <?= date('Y') ?></div>
            <div class="col-md-6 text-center text-md-end">Desarrollado con <?= Yii::powered() ?></div>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
