<?php

namespace app\modules\api\controllers;


use app\modules\api\models\Role;
use app\modules\api\models\User;
use app\modules\api\models\DriverStatus;
use sizeg\jwt\JwtHttpBearerAuth;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Response;


class UserController extends \yii\web\Controller
{
	
	 public $enableCsrfValidation=false;








    public function actionIndex()
    {
		

        return $this->render('index');
    }

    public function actionCreateUser(){

        Yii::$app->response->format =Response::FORMAT_JSON;
        $user = new User();
        $user->scenario = User::SCENARIO_CREATE;
        $user->attributes = Yii::$app->request->post();

        if($user->validate()){
            $user->save();

          $role=Role::findOne($user->attributes['role_id']);



            Yii::$app->mailer->compose()
                ->setFrom(['ayiq.surucu_testing@mail.ru' => 'Ayiq Surucu Mobile App'])
                ->setTo($user->attributes['email'])
                ->setSubject("{$role->name}")
                ->setHtmlBody("<p><b>Tam Ad:</b>{$user->attributes['full_name']}</p>
                                   <p><b>EMAIL:</b>{$user->attributes['email']}</p>
                                   
                                   <p><a href='http://ayiq-surucu/api/user/activate-user/?id={$user->id}'><b>Akkauntu aktiv edin!!!</b></a></p>")
                ->send();

            return array('status'=>true,'data'=>"Yeni {$role->name} əlavə edildi!!!");
        }else{
            return array('status'=>false, 'data'=>$user->getErrors());
        }

    }

    public function actionActivateUser($id){
        Yii::$app->response->format =Response::FORMAT_JSON;
        $user=User::findOne(['id'=>$id]);
        $user->scenario = User::SCENARIO_ACTIVATE;
        $role=Role::findOne($user->role_id);
        $user->status=1;
        if($user->validate()){
            $user->save();


            if($role->id==1){

                $driver_status=new DriverStatus();
                $driver_status->user_id=$user->id;
                $driver_status->status='Aktiv';
                $driver_status->save();
            }



            Yii::$app->mailer->compose()
                ->setFrom(['ayiq.surucu_testing@mail.ru' => 'Ayiq Surucu Mobile App'])
                ->setTo($user->email)
                ->setSubject('Akkountun aktiv edilməsi')
                ->setHtmlBody("<p><b>{$role->name}  {$user->full_name},sizin akkautunuz aktiv edildi!!!</b></p>
                                   ")
                ->send();

            return array('status'=>true,'data'=>"{$role->name} akkount aktiv edildi!!!");
        }else{
            return array('status'=>false, 'data'=>$user->getErrors());
        }


    }


 




}



