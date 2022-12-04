<?php
namespace app\controllers;
use app\models\Chart;
use app\models\Product;
use app\models\User;

use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\web\IdentityInterface;


class UserController extends FunctionController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'only'=>['account']
        ];
        return $behaviors;
    }

    function actionAccount(){
        $user=Yii::$app->user->identity;
        $charts=$user->getCharts()->all();
        $order=[];
        foreach ($charts as $chart){
            $chart=new Chart($chart);
            $product=new Product($chart->getProduct()->one());
            $order[]=$product;
        }
        return $this->send(200, ['content'=>['code'=>200, 'user'=>$user, 'order'=>$order]]);
    }


}