<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            // Si el usuario no está autenticado, mostrar la página de bienvenida
            return $this->render('welcome');
        }
        
        // Si el usuario está autenticado, mostrar el dashboard
        $habitsQuery = \app\models\Habit::find()
            ->where(['user_id' => Yii::$app->user->id, 'status' => \app\models\Habit::STATUS_ACTIVE])
            ->orderBy(['created_at' => SORT_DESC]);
        
        $habitsCount = $habitsQuery->count();
        $habits = $habitsQuery->limit(5)->all();
        
        $goalsQuery = \app\models\Goal::find()
            ->where(['user_id' => Yii::$app->user->id])
            ->andWhere(['not', ['status' => 'completed']])
            ->orderBy(['target_date' => SORT_ASC]);
        
        $goalsCount = $goalsQuery->count();
        $goals = $goalsQuery->limit(5)->all();
        
        $categoriesCount = \app\models\Category::find()->count();
        
        // Registros recientes
        $logsQuery = \app\models\HabitLog::find()
            ->alias('hl')
            ->innerJoin('habit h', 'h.id = hl.habit_id')
            ->where(['h.user_id' => Yii::$app->user->id])
            ->orderBy(['hl.log_date' => SORT_DESC]);
        
        $logsCount = $logsQuery->count();
        $logs = $logsQuery->limit(5)->all();
        
        // Estadísticas
        $totalHabits = $habitsCount;
        $completedHabits = \app\models\HabitLog::find()
            ->alias('hl')
            ->innerJoin('habit h', 'h.id = hl.habit_id')
            ->where(['h.user_id' => Yii::$app->user->id])
            ->andWhere(['hl.log_date' => date('Y-m-d')])
            ->count();
        
        return $this->render('index', [
            'habits' => $habits,
            'habitsCount' => $habitsCount,
            'goals' => $goals,
            'goalsCount' => $goalsCount,
            'categoriesCount' => $categoriesCount,
            'logs' => $logs,
            'logsCount' => $logsCount,
            'totalHabits' => $totalHabits,
            'completedHabits' => $completedHabits,
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
