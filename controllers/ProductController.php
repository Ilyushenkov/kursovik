<?php
namespace app\controllers;
use app\models\Post;
use app\models\Product;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\web\UploadedFile;


class ProductController extends FunctionController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'only'=>['create']
        ];
        return $behaviors;
    }




    public $modelClass = 'app\models\Product';
    public function actionCreate(){
        $request=Yii::$app->request->post();
        $product=new Product($request);
        $product->image=UploadedFile::getInstanceByName('image');
//die($product->image->extension);
        if (!$product->validate()) return $this->validation($product);
        $hash=hash('sha256', $product->image->baseName) . '.' . $product->image->extension;
        $product->image->saveAs(Yii::$app->basePath. '/web/assets/product_image/' . $hash);

        $product->image='/web/assets/product_image/' . $hash;
        $product->save(false);
        return $this->send(201, ['content'=>['code'=>201,'product_id'=>$product->id]]);
    }

    public function actionShow(){
        $products=Product::find()->all();
        return $this->send(200, ['content'=>['products'=>$products]]);
    }

    public function actionProduct($id){
        $product=Product::findOne($id);
        if (!$product) return $this->send(404, ['code'=>404, 'content'=>['message'=>'Product not Found']]);
        $posts=$product->getPosts()->all();
        $comments=[];
        foreach ($posts as $post){
            $post=new Post($post);
            $user=$post->getUser()->one()->login;
            $comments[]=['date/time'=>$post->date, 'user'=>$user,'feedback'=>$post->feedback];
        }
        return $this->send(200, ['content'=>['code'=>200, 'product'=>$product, 'feedbacks'=>$comments]]);



    }
}