<?php

namespace app\modules\api\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "client".
 *
 * @property int $id
 * @property int $user_id
 * @property int $driver_id
 * @property string $address
 * @property string $created_date
 * @property string $update_date
 * @property int $status
 */
class Client extends ActiveRecord
{

    const SCENARIO_CREATE_ORDER='create_order';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'client';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_date'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['update_date'],
                ],

                'value' => new Expression('NOW()'),
            ],
        ];
    }



    public function scenarios()
    {
        $scenarios=parent::scenarios();
        $scenarios['create_order']=['user_id','driver_id','address'];

        return $scenarios;

    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'driver_id', 'address', 'created_date', 'update_date', 'status'], 'required'],
            [['user_id', 'driver_id', 'status'], 'integer'],
            [['address'], 'string'],
            [['created_date', 'update_date'], 'safe'],
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
            'driver_id' => 'Driver ID',
            'address' => 'Address',
            'created_date' => 'Created Date',
            'update_date' => 'Update Date',
            'status' => 'Status',
        ];
    }
}
