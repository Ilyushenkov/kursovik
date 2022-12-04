<?php
namespace app\controllers;

use yii\filters\Cors;
use yii\helpers\ArrayHelper;
use yii\rest\Controller;
use yii\widgets\ActiveForm;
use yii\web\Response;
class FunctionController extends Controller {


    public function send($code, $data){
        $response=$this->response;
        $response->format = Response::FORMAT_JSON;

        $response->data=$data;
        $response->statusCode=$code;
        return $response;
    }
    public function validation($model){
$error=['content'=> ['code'=>422, 'message'=>'Validation error',
    'errors'=>ActiveForm::validate($model)]];
return $this->send(422, $error);
}
}