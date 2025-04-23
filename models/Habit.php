<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "habit".
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $category_id
 * @property string $name
 * @property string|null $description
 * @property string $frequency_type
 * @property string|null $frequency_value
 * @property float|null $target_value
 * @property string|null $unit
 * @property string|null $reminder_time
 * @property string $start_date
 * @property string|null $end_date
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Category $category
 * @property User $user
 * @property HabitLog[] $habitLogs
 * @property Goal[] $goals
 */
class Habit extends ActiveRecord
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'habit';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'name', 'start_date'], 'required'],
            [['user_id', 'category_id', 'status'], 'integer'],
            [['description', 'frequency_value'], 'string'],
            [['target_value'], 'number'],
            [['reminder_time', 'start_date', 'end_date', 'created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['frequency_type'], 'string', 'max' => 10],
            [['unit'], 'string', 'max' => 50],
            [['frequency_type'], 'in', 'range' => ['daily', 'weekly', 'monthly', 'custom']],
            [['status'], 'default', 'value' => self::STATUS_ACTIVE],
            [['status'], 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE]],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['category_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Usuario',
            'category_id' => 'Categoría',
            'name' => 'Nombre',
            'description' => 'Descripción',
            'frequency_type' => 'Tipo de Frecuencia',
            'frequency_value' => 'Valor de Frecuencia',
            'target_value' => 'Valor Objetivo',
            'unit' => 'Unidad',
            'reminder_time' => 'Recordatorio',
            'start_date' => 'Fecha de Inicio',
            'end_date' => 'Fecha de Fin',
            'status' => 'Estado',
            'created_at' => 'Creado el',
            'updated_at' => 'Actualizado el',
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Gets query for [[HabitLogs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHabitLogs()
    {
        return $this->hasMany(HabitLog::class, ['habit_id' => 'id']);
    }

    /**
     * Gets query for [[Goals]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGoals()
    {
        return $this->hasMany(Goal::class, ['habit_id' => 'id']);
    }
    
    /**
     * Get completion rate for the habit
     * 
     * @param string $startDate
     * @param string $endDate
     * @return float
     */
    public function getCompletionRate($startDate = null, $endDate = null)
    {
        if ($startDate === null) {
            $startDate = $this->start_date;
        }
        
        if ($endDate === null) {
            $endDate = date('Y-m-d');
        }
        
        $logs = $this->getHabitLogs()
            ->where(['>=', 'log_date', $startDate])
            ->andWhere(['<=', 'log_date', $endDate])
            ->count();
            
        $totalDays = (strtotime($endDate) - strtotime($startDate)) / (60 * 60 * 24) + 1;
        
        if ($totalDays <= 0) {
            return 0;
        }
        
        return ($logs / $totalDays) * 100;
    }
    
    /**
     * Get frequency options
     * 
     * @return array
     */
    public static function getFrequencyOptions()
    {
        return [
            'daily' => 'Diario',
            'weekly' => 'Semanal', 
            'monthly' => 'Mensual',
            'custom' => 'Personalizado'
        ];
    }
    
    /**
     * Get status options
     * 
     * @return array
     */
    public static function getStatusOptions()
    {
        return [
            self::STATUS_ACTIVE => 'Activo',
            self::STATUS_INACTIVE => 'Inactivo'
        ];
    }
}
