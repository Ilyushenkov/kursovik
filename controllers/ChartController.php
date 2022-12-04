<?php
namespace app\controllers;
use app\models\Chart;
use app\models\Product;
use app\models\User;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\ContentNegotiator;
use yii\filters\Cors;
use yii\helpers\ArrayHelper;
use yii\web\Response;


class ChartController extends FunctionController
{


    public $enableCsrfValidation = false;


    public function behaviors(){

        $behaviors = parent::behaviors();



       // $behaviors['authenticator'] = $auth;
        $behaviors['authenticator']['only']=['create', 'delete'];
        $behaviors['authenticator']['class']=HttpBearerAuth::class;
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)


        return $behaviors;


    }




    public function actionCreate($product_id){
        $user=new User(Yii::$app->user->identity);
      //die($user->id)    ;
        $chart=['user_id'=>3, 'product_id'=>$product_id];
        $chart=new Chart($chart);
        if (!$chart->validate()) return $this->validation($chart);
        $chart->save(false);
        return $this->send(201, ['content'=>['code'=>200, 'chart_id'=>$chart->id]]);

    }

    public function actionDelete($id){

        $chart=Chart::findOne($id);

        if (!$chart) return $this->send(404, ['content'=>['code'=>404, 'message'=>'Item not found']]);
        $chart->delete();
        return $this->send(200, ['content'=>['code'=>200, 'message'=>'Item is deleted']]);
    }
}