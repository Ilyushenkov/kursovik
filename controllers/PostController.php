<?php
namespace app\controllers;
use app\models\Post;
use Yii;
use yii\filters\auth\HttpBearerAuth;


class PostController extends FunctionController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            //'only'=>['delete']
        ];
        return $behaviors;
    }

    public function actionCreate($product_id){
        $post=new Post(Yii::$app->request->post());
        $post->product_id=$product_id;
        $post->user_id=Yii::$app->user->identity->id;
        if (!$post->validate()) return $this->validation($post);
        $post->save(false);
        return $this->send(201, ['content'=>['code'=>201, 'comment_id'=>$post->id]]);
    }

    public function actionDelete($id){
        $post=Post::findOne($id);
        if (!$post) return $this->send(404, ['content'=>['code'=>404, 'message'=>'Post not Found']]);
        $post->delete();
        return $this->send(200, ['content'=>['code'=>200, 'message'=>'Post is removed']]);
    }

}