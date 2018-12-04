<?php

namespace app\modules\api\models;

use Yii;

/**
 * This is the model class for table "driver_status".
 *
 * @property int $id
 * @property int $user_id
 * @property string $status
 */
class DriverStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'driver_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'status'], 'required'],
            [['user_id'], 'integer'],
            [['status'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'status' => 'Status',
        ];
    }
}
