<?php
/**
 * Created by PhpStorm.
 * User: SADIQ
 * Date: 02.12.2018
 * Time: 16:36
 */

namespace app\modules\api\controllers;
use app\modules\api\models\Client;

use app\modules\api\models\User;
use app\modules\api\models\DriverStatus;
use Yii;
use yii\web\Response;

class ClientController extends \yii\web\Controller
{

    public $enableCsrfValidation=false;


    public function actionIndex()
    {


        Yii::$app->response->format =Response::FORMAT_JSON;
        $client = new Client();
        $client->scenario = Client::SCENARIO_CREATE_ORDER;
        $client->attributes = Yii::$app->request->post();
        if($client->validate()) {
            $client->save();

            $user_client=User::findOne($client->attributes['user_id']);
            $user_driver=User::findOne($client->attributes['driver_id']);

            Yii::$app->mailer->compose()
                ->setFrom(['ayiq.surucu_testing@mail.ru' => 'Ayiq Surucu Mobile App'])
                ->setTo($user_client->email)
                ->setSubject("Sifariş")
                ->setHtmlBody("<p><b>Sizin sifariş qəbul edildi.Təsdiq olunmasını gözləyin!!!</b></p>
                                   ")
                ->send();

            Yii::$app->mailer->compose()
                ->setFrom(['ayiq.surucu_testing@mail.ru' => 'Ayiq Surucu Mobile App'])
                ->setTo($user_driver->email)
                ->setSubject("Sifariş")
                ->setHtmlBody("<p><b>Sizin yeni sifarişiniz var.Xahiş olunur təsdiq edəsiniz!!!</b></p>
                                   <p>
                                   <p>{$client->attributes['address']}</p>
                                   
                                   <button class='btn btn-success'>
                                   <a href='http://ayiq-surucu/api/driver/confirm-order/?id={$client->id}'>
                                   Qəbul edin
                                   </a>
                                   </button>
                                   
                                   <button class='btn btn-danger'>
                                   <a href='http://ayiq-surucu/api/driver/unconfirm-order/?id={$client->id}'>
                                   İmtina edin
                                   </a>
                                   </button>
                                   
                                   
                                   
</p>")
                ->send();








            return array('status'=>true,'data'=>"Yeni sifariş əlavə edildi!!!");
        }else{
            return array('status'=>false, 'data'=>$client->getErrors());
        }
    }

}