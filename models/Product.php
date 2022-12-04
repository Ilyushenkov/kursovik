<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property string $name
 * @property string|null $image
 * @property string $discreption
 * @property float $price
 *
 * @property Chart[] $charts
 * @property Post[] $posts
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'discreption', 'price'], 'required'],
            [['price'], 'number'],
            [['name'], 'string', 'max' => 100],
            [['discreption'], 'string', 'max' => 400],
            ['image', 'file', 'extensions' => ['png', 'jpg', 'gif'], 'skipOnEmpty' => false,
                'maxSize' => 2*1024*1024,  'checkExtensionByMimeType' => false],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'image' => 'Image',
            'discreption' => 'Discreption',
            'price' => 'Price',
        ];
    }

    /**
     * Gets query for [[Charts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCharts()
    {
        return $this->hasMany(Chart::className(), ['product_id' => 'id']);
    }

    /**
     * Gets query for [[Posts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(Post::className(), ['product_id' => 'id']);
    }

    public function beforeValidate()
    {
        $this->image = UploadedFile::getInstanceByName( 'image');
        return parent::beforeValidate();
    }
}
