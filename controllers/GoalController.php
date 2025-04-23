<?php

namespace app\controllers;

use app\models\Goal;
use app\models\Habit;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * GoalController implements the CRUD actions for Goal model.
 */
class GoalController extends Controller
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
     * Lists all Goal models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Goal::find()->where(['user_id' => Yii::$app->user->id]),
            'sort' => [
                'defaultOrder' => [
                    'status' => SORT_ASC,
                    'target_date' => SORT_ASC,
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
     * Displays a single Goal model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        
        // Verificar que la meta pertenece al usuario actual
        if ($model->user_id != Yii::$app->user->id) {
            throw new NotFoundHttpException('No tienes permiso para ver esta meta.');
        }
        
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Goal model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param integer|null $habitId
     * @return mixed
     */
    public function actionCreate($habitId = null)
    {
        $model = new Goal();
        $model->user_id = Yii::$app->user->id;
        $model->status = 'pending';
        $model->start_date = date('Y-m-d');
        $model->target_date = date('Y-m-d', strtotime('+30 days'));
        $model->achieved_value = 0;
        
        // Si se proporciona un hábito, vincular la meta a él
        if ($habitId !== null) {
            $habit = Habit::findOne($habitId);
            if (!$habit || $habit->user_id != Yii::$app->user->id) {
                throw new NotFoundHttpException('No tienes permiso para acceder a este hábito.');
            }
            $model->habit_id = $habit->id;
            $model->unit = $habit->unit;
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Meta creada correctamente.');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'habits' => Habit::find()->where(['user_id' => Yii::$app->user->id])->all(),
        ]);
    }

    /**
     * Updates an existing Goal model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        // Verificar que la meta pertenece al usuario actual
        if ($model->user_id != Yii::$app->user->id) {
            throw new NotFoundHttpException('No tienes permiso para editar esta meta.');
        }
        
        // Si la meta está completada, comprobar si se ha cambiado el estado
        $oldStatus = $model->status;

        if ($model->load(Yii::$app->request->post())) {
            // Si se cambia el estado a completado, actualizar la fecha de finalización
            if ($oldStatus != 'completed' && $model->status == 'completed') {
                $model->completion_date = date('Y-m-d');
            } elseif ($oldStatus == 'completed' && $model->status != 'completed') {
                $model->completion_date = null;
            }
            
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Meta actualizada correctamente.');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'habits' => Habit::find()->where(['user_id' => Yii::$app->user->id])->all(),
        ]);
    }

    /**
     * Deletes an existing Goal model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        
        // Verificar que la meta pertenece al usuario actual
        if ($model->user_id != Yii::$app->user->id) {
            throw new NotFoundHttpException('No tienes permiso para eliminar esta meta.');
        }
        
        $model->delete();
        
        Yii::$app->session->setFlash('success', 'Meta eliminada correctamente.');

        return $this->redirect(['index']);
    }

    /**
     * Finds the Goal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Goal the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Goal::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('La página solicitada no existe.');
    }
}
