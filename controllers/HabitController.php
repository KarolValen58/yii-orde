<?php

namespace app\controllers;

use app\models\Category;
use app\models\Habit;
use app\models\HabitLog;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * HabitController implements the CRUD actions for Habit model.
 */
class HabitController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'create', 'update', 'view', 'delete', 'log'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'update', 'view', 'delete', 'log'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Habit models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Habit::find()->where(['user_id' => Yii::$app->user->id]),
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ]
            ],
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Habit model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        
        $logDataProvider = new ActiveDataProvider([
            'query' => $model->getHabitLogs(),
            'sort' => [
                'defaultOrder' => [
                    'log_date' => SORT_DESC,
                ]
            ],
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        
        return $this->render('view', [
            'model' => $model,
            'logDataProvider' => $logDataProvider,
        ]);
    }

    /**
     * Creates a new Habit model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Habit();
        $model->user_id = Yii::$app->user->id;
        $model->status = Habit::STATUS_ACTIVE;
        $model->start_date = date('Y-m-d');
        $model->target_value = 1;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Hábito creado correctamente.');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'categories' => Category::find()->all(),
        ]);
    }

    /**
     * Updates an existing Habit model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        // Verificar que el hábito pertenece al usuario actual
        if ($model->user_id != Yii::$app->user->id) {
            throw new NotFoundHttpException('No tienes permiso para editar este hábito.');
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Hábito actualizado correctamente.');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'categories' => Category::find()->all(),
        ]);
    }

    /**
     * Deletes an existing Habit model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        
        // Verificar que el hábito pertenece al usuario actual
        if ($model->user_id != Yii::$app->user->id) {
            throw new NotFoundHttpException('No tienes permiso para eliminar este hábito.');
        }
        
        $model->delete();
        Yii::$app->session->setFlash('success', 'Hábito eliminado correctamente.');

        return $this->redirect(['index']);
    }
    
    /**
     * Log an entry for a habit.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionLog($id)
    {
        $habit = $this->findModel($id);
        
        // Verificar que el hábito pertenece al usuario actual
        if ($habit->user_id != Yii::$app->user->id) {
            throw new NotFoundHttpException('No tienes permiso para registrar este hábito.');
        }
        
        $model = new HabitLog();
        $model->habit_id = $habit->id;
        $model->log_date = date('Y-m-d');
        $model->value = $habit->target_value;
        
        // Verificar si ya existe un registro para hoy
        $existingLog = HabitLog::findOne([
            'habit_id' => $habit->id,
            'log_date' => $model->log_date,
        ]);
        
        if ($existingLog) {
            $model = $existingLog;
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Registro guardado correctamente.');
            return $this->redirect(['view', 'id' => $habit->id]);
        }

        return $this->render('log', [
            'model' => $model,
            'habit' => $habit,
        ]);
    }

    /**
     * Finds the Habit model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Habit the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Habit::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('La página solicitada no existe.');
    }
}
