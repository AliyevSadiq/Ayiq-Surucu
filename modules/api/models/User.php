<?php

namespace app\modules\api\models;


use Yii;
use yii\base\Security;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;


/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $full_name
 * @property string $email
 * @property string $password
 * @property string $phone
 * @property int $role_id
 * @property string $created_date
 * @property string $update_date
 * @property int $status
 */
class User extends ActiveRecord
{

    public $password_repeat;

    const SCENARIO_CREATE='create';
    const SCENARIO_ACTIVATE='activate';
  




    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
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
        $scenarios['create']=['full_name','email','phone','password','password_repeat','role_id'];
        $scenarios['activate']=['status'];
    



        return $scenarios;

    }


    public function beforeSave($insert) {
        if(isset($this->password))
            $this->password = Yii::$app->getSecurity()->generatePasswordHash($insert);
        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['full_name', 'email', 'password', 'phone', 'role_id'], 'required'],
            ['email','email','message'=>'Email düzgün deyil'],
            ['email','unique','message'=>'Bu email istifadə olunub!!!'],
            ['password','match','pattern' =>'/^(?=.*[A-Z])(?=.*[A-Z])([a-zA-Z0-9]+)$/','message'=>'Parol böyük həriflə başlamalıdır'],
            ['password_repeat', 'required'],
            ['password_repeat', 'compare', 'compareAttribute'=>'password', 'message'=>"Parol düzgün deyil" ],
            [['role_id', 'status'], 'integer'],
            [['created_date', 'update_date'], 'safe'],
            [['full_name', 'password','email'], 'string', 'max' => 100],
            [['phone'], 'string', 'max' => 50],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'full_name' => 'Full Name',
            'email' => 'Email',
            'password' => 'Password',
            'password_repeat' => 'Repeat Password',
            'phone' => 'Phone',
            'role_id' => 'Role ID',
            'created_date' => 'Created Date',
            'update_date' => 'Update Date',
            'status' => 'Status'
        ];
    }
}
