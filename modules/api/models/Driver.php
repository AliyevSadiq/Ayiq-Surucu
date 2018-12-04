<?php

namespace app\modules\api\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "driver".
 *
 * @property int $id
 * @property int $user_id
 * @property int $client_id
 * @property int $order_id
 * @property string $created_date
 * @property string $update_date
 * @property int $status
 */
class Driver extends ActiveRecord
{

    const SCENARIO_CONFRIM_ORDER='confirm_order';
    const SCENARIO_UNCONFRIM_ORDER='unconfirm_order';


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'driver';
    }



    public function scenarios()
    {
        $scenarios=parent::scenarios();
        $scenarios['confirm_order']=['user_id','client_id','order_id','status'];
        $scenarios['unconfirm_order']=['user_id','client_id','status'];

        return $scenarios;

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
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'client_id','order_id'], 'required'],
            [['user_id', 'client_id', 'status','order_id'], 'integer'],
            [['created_date', 'update_date','status'], 'safe'],
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
            'client_id' => 'Client ID',
            'order_id' => 'Order ID',
            'created_date' => 'Created Date',
            'update_date' => 'Update Date',
            'status' => 'Status',
        ];
    }
}
