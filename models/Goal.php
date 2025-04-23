<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "goal".
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $habit_id
 * @property string $name
 * @property string|null $description
 * @property float $target_value
 * @property float $achieved_value
 * @property string|null $unit
 * @property string $start_date
 * @property string $target_date
 * @property string|null $completion_date
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Habit $habit
 * @property User $user
 */
class Goal extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'goal';
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
            [['user_id', 'name', 'target_value', 'start_date', 'target_date'], 'required'],
            [['user_id', 'habit_id'], 'integer'],
            [['description'], 'string'],
            [['target_value', 'achieved_value'], 'number'],
            [['start_date', 'target_date', 'completion_date', 'created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['unit'], 'string', 'max' => 50],
            [['status'], 'string', 'max' => 15],
            [['status'], 'in', 'range' => ['pending', 'in_progress', 'completed', 'cancelled']],
            [['status'], 'default', 'value' => 'pending'],
            [['achieved_value'], 'default', 'value' => 0],
            [['habit_id'], 'exist', 'skipOnError' => true, 'targetClass' => Habit::class, 'targetAttribute' => ['habit_id' => 'id']],
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
            'habit_id' => 'Hábito relacionado',
            'name' => 'Nombre',
            'description' => 'Descripción',
            'target_value' => 'Valor objetivo',
            'achieved_value' => 'Valor logrado',
            'unit' => 'Unidad',
            'start_date' => 'Fecha de inicio',
            'target_date' => 'Fecha objetivo',
            'completion_date' => 'Fecha de finalización',
            'status' => 'Estado',
            'created_at' => 'Creado el',
            'updated_at' => 'Actualizado el',
        ];
    }

    /**
     * Gets query for [[Habit]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHabit()
    {
        return $this->hasOne(Habit::class, ['id' => 'habit_id']);
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
     * Get completion percentage
     * 
     * @return float
     */
    public function getCompletionPercentage()
    {
        if ($this->target_value == 0) {
            return 0;
        }
        
        return min(100, round(($this->achieved_value / $this->target_value) * 100, 2));
    }
    
    /**
     * Get status options
     * 
     * @return array
     */
    public static function getStatusOptions()
    {
        return [
            'pending' => 'Pendiente',
            'in_progress' => 'En progreso',
            'completed' => 'Completado',
            'cancelled' => 'Cancelado'
        ];
    }
    
    /**
     * Get status label
     * 
     * @return string
     */
    public function getStatusLabel()
    {
        $options = self::getStatusOptions();
        return isset($this->status) ? $options[$this->status] : '-';
    }
}
