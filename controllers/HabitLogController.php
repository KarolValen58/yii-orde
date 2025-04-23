<?php

namespace app\controllers;

use app\models\Habit;
use app\models\HabitLog;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * HabitLogController implements the CRUD actions for HabitLog model.
 */
class HabitLogController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'create', 'update', 'view', 'delete'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'update', 'view', 'delete'],
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
     * Lists all HabitLog models.
     * @param int $habitId
     * @return mixed
     */
    public function actionIndex($habitId = null)
    {
        $query = HabitLog::find();
        
        // Filtrar por hábito si se proporciona
        if ($habitId !== null) {
            $habit = Habit::findOne($habitId);
            if (!$habit || $habit->user_id != Yii::$app->user->id) {
                throw new NotFoundHttpException('No tienes permiso para acceder a este hábito.');
            }
            $query->andWhere(['habit_id' => $habitId]);
        } else {
            // Si no se proporciona un ID de hábito, mostrar solo los registros de los hábitos del usuario
            $query->innerJoin('habit', 'habit.id = habit_log.habit_id')
                  ->andWhere(['habit.user_id' => Yii::$app->user->id]);
        }
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'log_date' => SORT_DESC,
                ]
            ],
            'pagination' => [
                'pageSize' => 15,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'habitId' => $habitId,
        ]);
    }

    /**
     * Displays a single HabitLog model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        
        // Verificar que el registro pertenece a un hábito del usuario actual
        if ($model->habit->user_id != Yii::$app->user->id) {
            throw new NotFoundHttpException('No tienes permiso para ver este registro.');
        }
        
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new HabitLog model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param integer $habitId
     * @return mixed
     */
    public function actionCreate($habitId)
    {
        $habit = Habit::findOne($habitId);
        
        if (!$habit || $habit->user_id != Yii::$app->user->id) {
            throw new NotFoundHttpException('No tienes permiso para registrar este hábito.');
        }
        
        $model = new HabitLog();
        $model->habit_id = $habit->id;
        $model->log_date = date('Y-m-d');
        $model->value = $habit->target_value;
        
        // Verificar si ya existe un registro para la fecha seleccionada
        $existingLog = HabitLog::findOne([
            'habit_id' => $habit->id,
            'log_date' => $model->log_date,
        ]);
        
        if ($existingLog) {
            // Redirigir a la edición si ya existe un registro para hoy
            Yii::$app->session->setFlash('info', 'Ya existe un registro para esta fecha. Puedes editarlo a continuación.');
            return $this->redirect(['update', 'id' => $existingLog->id]);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Registro guardado correctamente.');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'habit' => $habit,
        ]);
    }

    /**
     * Updates an existing HabitLog model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        // Verificar que el registro pertenece a un hábito del usuario actual
        if ($model->habit->user_id != Yii::$app->user->id) {
            throw new NotFoundHttpException('No tienes permiso para editar este registro.');
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Registro actualizado correctamente.');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'habit' => $model->habit,
        ]);
    }

    /**
     * Deletes an existing HabitLog model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        
        // Verificar que el registro pertenece a un hábito del usuario actual
        if ($model->habit->user_id != Yii::$app->user->id) {
            throw new NotFoundHttpException('No tienes permiso para eliminar este registro.');
        }
        
        $habitId = $model->habit_id;
        $model->delete();
        
        Yii::$app->session->setFlash('success', 'Registro eliminado correctamente.');
        
        return $this->redirect(['/habit-log/index', 'habitId' => $habitId]);
    }

    /**
     * Finds the HabitLog model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return HabitLog the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = HabitLog::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('La página solicitada no existe.');
    }
}
