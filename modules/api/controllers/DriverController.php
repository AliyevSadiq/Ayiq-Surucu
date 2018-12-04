<?php
/**
 * Created by PhpStorm.
 * User: SADIQ
 * Date: 02.12.2018
 * Time: 17:05
 */

namespace app\modules\api\controllers;
use app\modules\api\models\Client;

use app\modules\api\models\Driver;
use app\modules\api\models\User;
use app\modules\api\models\DriverStatus;
use Yii;
use yii\web\Response;

class DriverController extends \yii\web\Controller
{


    public $enableCsrfValidation=false;

    public function actionConfirmOrder($id){

        $client=Client::findOne($id);

        $driver_status=DriverStatus::find()->where(['user_id'=>$client->driver_id])->one();
        $client_user=User::findOne($client->user_id);




        Yii::$app->response->format =Response::FORMAT_JSON;
        $driver = new Driver();
        $driver->scenario = Driver::SCENARIO_CONFRIM_ORDER;
        $driver->attributes = Yii::$app->request->post();

        $driver->user_id=$client->driver_id;
        $driver->client_id=$client->user_id;
        $driver->order_id=$client->id;
        $driver->status=1;
        if($driver->validate()) {
            $driver->save();

            $client->status=1;
            $client->save();


            $driver_status->user_id=$client->driver_id;
            $driver_status->status='Məşğul';
            $driver_status->save();





            Yii::$app->mailer->compose()
                ->setFrom(['ayiq.surucu_testing@mail.ru' => 'Ayiq Surucu Mobile App'])
                ->setTo($client_user->email)
                ->setSubject("Sifariş")
                ->setHtmlBody("<p><b>Sizin sifariş təsdiqləndi!!!</b></p>
                                   ")
                ->send();



            return array('status'=>true,'data'=>"Sifariş təsdiqləndi!!!");
        }else{
            return array('status'=>false, 'data'=>$driver->getErrors());
        }
    }

    public function actionUnConfirmOrder($id){

        $client=Client::findOne($id);

        $driver_status=DriverStatus::find()->where(['user_id'=>$client->driver_id])->one();
        $client_user=User::findOne($client->user_id);




        Yii::$app->response->format =Response::FORMAT_JSON;
        $driver = new Driver();
        $driver->scenario = Driver::SCENARIO_UNCONFRIM_ORDER;
        $driver->attributes = Yii::$app->request->post();

        $driver->user_id=$client->driver_id;
        $driver->client_id=$client->user_id;
        $driver->order_id=$client->id;
        $driver->status=0;
        if($driver->validate()) {
            $driver->save();




            $driver_status->user_id=$client->driver_id;
            $driver_status->status='Aktiv';
            $driver_status->save();





            Yii::$app->mailer->compose()
                ->setFrom(['ayiq.surucu_testing@mail.ru' => 'Ayiq Surucu Mobile App'])
                ->setTo($client_user->email)
                ->setSubject("Sifariş")
                ->setHtmlBody("<p><b>Sizin sifariş qəbul edilmədi!!!</b></p>
                                   ")
                ->send();



            return array('status'=>true,'data'=>"Sifariş qəbul edilmədi!!!");
        }else{
            return array('status'=>false, 'data'=>$driver->getErrors());
        }
    }





}