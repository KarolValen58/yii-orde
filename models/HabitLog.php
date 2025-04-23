<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "habit_log".
 *
 * @property int $id
 * @property int $habit_id
 * @property string $log_date
 * @property float $value
 * @property string|null $notes
 * @property int|null $mood
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Habit $habit
 */
class HabitLog extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'habit_log';
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
            [['habit_id', 'log_date'], 'required'],
            [['habit_id', 'mood'], 'integer'],
            [['log_date', 'created_at', 'updated_at'], 'safe'],
            [['value'], 'number'],
            [['notes'], 'string'],
            [['mood'], 'in', 'range' => [1, 2, 3, 4, 5]],
            [['habit_id', 'log_date'], 'unique', 'targetAttribute' => ['habit_id', 'log_date']],
            [['habit_id'], 'exist', 'skipOnError' => true, 'targetClass' => Habit::class, 'targetAttribute' => ['habit_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'habit_id' => 'Hábito',
            'log_date' => 'Fecha',
            'value' => 'Valor',
            'notes' => 'Notas',
            'mood' => 'Estado de ánimo',
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
     * Get mood options
     * 
     * @return array
     */
    public static function getMoodOptions()
    {
        return [
            1 => 'Muy Mal',
            2 => 'Mal',
            3 => 'Regular',
            4 => 'Bien',
            5 => 'Muy Bien'
        ];
    }
    
    /**
     * Get mood label
     * 
     * @return string
     */
    public function getMoodLabel()
    {
        $options = self::getMoodOptions();
        return isset($this->mood) ? $options[$this->mood] : '-';
    }
}
