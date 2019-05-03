<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "message_source".
 *
 * @property int $id
 * @property string $category
 * @property string $message
 *
 * @property Message[] $messages
 */
class MessageSource extends \yii\db\ActiveRecord {

    public $lang_th, $lang_en;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'message_source';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['lang_th', 'lang_en'], 'string'],
            [['message', 'category'], 'required'],
            [['message', 'category'], 'string', 'max' => 255],
            [['message', 'category'], 'filter', 'filter'=>'strtolower'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'category' => Yii::t('app', 'Category'),
            'message' => Yii::t('app', 'Message'),
            'lang_th' => Yii::t('common/general', 'thai'),
            'lang_en' => Yii::t('common/general', 'english'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessage() {
        return $this->hasMany(Message::className(), ['id' => 'id']);
    }

    public function getMessageTranslate($code = 'th') {

        $message = Message::findOne(['id' => $this->id, 'language' => $code]);
        if (empty($message->translation)) {
            return '';
        } else {
            return $message->translation;
        }
    }

    public function getMessageLoad() {
        $this->lang_th = $this->getMessageTranslate('th');
        $this->lang_en = $this->getMessageTranslate('en');
    }
    
    public function saveMessage($code = 'en') {
       $message = Message::findOne(['id' => $this->id, 'language' => $code]);
        if (isset($message)) {
            $message->translation=$this['lang_'.$code];
            return $message->save();
        } else {
            $message = new Message();
            $message->id=$this->id;
            $message->language=$code;
            $message->translation=$this['lang_'.$code];
            return $message->save();
        }
    }

    public function afterSave($insert, $changedAttributes) {
        
        $this->saveMessage('th');
        $this->saveMessage('en');

        return parent::beforeSave($insert, $changedAttributes);
    }
    
    public function createMessage($category,$message){
        if (($model = MessageSource::findOne(['category'=>$category,'message'=>$message])) !== null) {
            // already create item
        } else if($category!='app'){
            // empty item
            $model = new MessageSource();
            $model->category=$category;
            $model->message=$message;
            $model->save();
        }
        
    }

}
