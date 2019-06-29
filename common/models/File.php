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

    public static function uploadMultiple($id, $model, $attribute) {
        $path = self::getCategory() . '/' . $id . '/' . $attribute . '/';
        $uploads = UploadedFile::getInstances($model, $attribute . '_upload');
        self::updateCaption(Yii::$app->request->post(), $attribute);
        if ($uploads !== null) {
            $path_dir = self::createDir($path);
            $files = explode(',', $model[$attribute]);
            foreach ($uploads as $file) {
                try {
                    $next_id = File::find()->max('id') + 1;
                    $old_name = $file->basename . '.' . $file->extension;
                    $new_name = $next_id . '_' . md5($file->basename . time()) . '.' . $file->extension;
                    if ($file->saveAs($path_dir . $new_name)) {
                        $f = new File;
                        $f->id = $next_id;
                        $f->category = self::getCategory();
                        $f->name = $new_name;
                        $f->filename = $old_name;
                        $f->extension = $file->extension;
                        $f->caption = $file->basename;
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
            return implode(',', array_unique(array_filter($files)));
        }
        return $model[$attribute];
    }

    public static function img($url, $options = []) {
        $arr = explode('.', $url);
        $ext = end($arr);
        $path_icon = Url::base(true) . '/images/icons/';
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'bmp', 'gif'])) {
            
        } else if ($ext == 'rar') {
            $url = $path_icon . 'zip.png';
        } else if ($ext == 'docx') {
            $url = $path_icon . 'doc.png';
        } else {
            $url = $path_icon . $ext . '.png';
        }
        return Html::img($url, $options);
    }

    public static function icon($url, $link = '', $caption='', $preview_group='file_preview', $options_img = [], $options_link = []) {
        $arr = explode('.', $url);
        $ext = end($arr);
        $path_icon = Url::base(true) . '/images/icons/';
        
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'bmp', 'gif'])) {
            $download = false;
        } else if ($ext == 'pdf') {
            $url = $path_icon . 'pdf.png';
            $download = false;
        } else if ($ext == 'doc' || $ext == 'docx') {
            $url = $path_icon . 'doc.png';
            $download = true;
        } else {
            $url = $path_icon . $ext . '.png';
            $download = true;
        }
        if ($download) { // download
            $options_link['download'] = empty($caption)?true:$caption;
        } else {        // preview with fancybox
            $options_link['data-fancybox']=$preview_group;
            $options_link['data-caption']=$caption;
        }
        return Html::a(Html::img($url, $options_img), $link, $options_link);
    }

    public static function deleteFileDifferent($old_files, $new_files) {
        $old = explode(',', trim($old_files));
        $new = explode(',', trim($new_files));
        $diffs = array_diff($old, $new);
        if (!empty($diffs)) {
            foreach ($diffs as $diff) {
                self::deleteFile($diff);
            }
        }
    }

    public static function deleteFile($id) {
        if (!empty($id)) {
            $f = self::findFile($id);
            $path_file = self::getDir() . $f->path . $f->name;
            if (unlink($path_file)) {
                $f->delete();
            }
        }
    }

    private static function updateCaption($post = [], $attribute) {
        $captions = empty($post[$attribute . '_file_caption']) ? '' : $post[$attribute . '_file_caption'];
        if (!empty($captions)) {
            foreach ($captions as $key => $caption) {
                $f = self::findFile($key);
                $f->caption = trim($caption);
                $f->save();
            }
        }
    }

    public static function format($bytes) {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }
        return $bytes;
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

    public static function findFile($id) {
        if (($model = File::findOne($id)) !== null) {
            $model->url = self::getUrl() . $model->path . $model->name;
            return $model;
        }
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

}
