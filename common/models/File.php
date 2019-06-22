<?php

namespace common\models;

use Yii;
use yii\web\UploadedFile;
use yii\helpers\BaseFileHelper;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;

/**
 * This is the model class for table "file".
 *
 * @property int $id
 * @property string $category
 * @property string $name
 * @property string $caption
 * @property string $filename
 * @property string $extension
 * @property int $size
 */
class File extends \yii\db\ActiveRecord {

    const UPLOAD_FOLDER = 'files';

    public $url;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'file';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['category', 'name', 'extension'], 'required'],
            [['size'], 'integer'],
            [['category', 'name', 'filename', 'type'], 'string', 'max' => 100],
            [['caption', 'path'], 'string', 'max' => 255],
            [['extension'], 'string', 'max' => 5],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('common/model', 'id'),
            'category' => Yii::t('common/model', 'category'),
            'name' => Yii::t('common/model', 'name'),
            'caption' => Yii::t('common/model', 'caption'),
            'filename' => Yii::t('common/model', 'filename'),
            'extension' => Yii::t('common/model', 'extension'),
            'type' => Yii::t('common/model', 'type'),
            'size' => Yii::t('common/model', 'size'),
            'path' => Yii::t('common/model', 'path'),
        ];
    }

    public static function initPreview($files, $type = '', $link = []) {
        $initial = [];


        if (is_array($files) && !empty($files)) {
            foreach ($files as $key => $file) {
                $f = self::findFile($file);
                $caption=empty($f->caption)?$f->filename:$f->caption;
                if ($type == 'config') {
                    $link['file'] = $f->id;
                    $initial[] = [
                        'caption' => $caption,
                        'width' => '120px',
                        'size' => $f->size,
                        'url' => Url::to($link),
                        'key' => $key
                    ];
                } else {
                    $initial[] = Html::img($f->url, ['class' => 'kv-preview-data file-preview-image', 'title' => $caption]);
                }
            }
        }
        return $initial;
    }

    public static function uploadMultiple($id, $model, $attribute, $files = []) {
        $path = self::getCategory() . '/' . $id . '/' . $attribute . '/';
        $uploads = UploadedFile::getInstances($model, $attribute);
        if ($uploads !== null) {
            $path_dir = self::createDir($path);
            foreach ($uploads as $file) {
                try {
                    $old_name = $file->basename . '.' . $file->extension;
                    $new_name = md5($file->basename . time()) . '.' . $file->extension;
                    if ($file->saveAs($path_dir . $new_name)) {
                        $f = new File;
                        $f->category = self::getCategory();
                        $f->name = $new_name;
                        $f->filename = $old_name;
                        $f->extension = $file->extension;
                        $f->type = $file->type;
                        $f->size = $file->size;
                        $f->path = $path;
                        if ($f->save()) {
                            $files[] = $f->id;
                        }
                    }
                } catch (Exception $e) {
                    
                }
            }
            return array_unique(array_filter($files));
        }
        return $files;
    }

    public function createDir($folder) {
        $path = self::getDir() . $folder;
        if (BaseFileHelper::createDirectory($path, 0777)) {
            return $path;
        } else {
            return false;
        }
    }

    public static function getDir() {
        return Yii::getAlias('@webroot') . '/' . self::UPLOAD_FOLDER . '/';
    }

    public static function getUrl() {
        return Url::base(true) . '/' . self::UPLOAD_FOLDER . '/';
    }

    public static function getCategory() {
        $category = Yii::$app->controller->id;
        if ($category == 'customer' || $category == 'supplier' || $category == 'manufacturer') {
            $category = 'company';
        }
        return $category;
    }

    protected function findFile($id) {
        if (($model = File::findOne($id)) !== null) {
            $model->url = self::getUrl() . $model->path . $model->name;
            return $model;
        }
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

}
