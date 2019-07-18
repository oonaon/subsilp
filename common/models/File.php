<?php

namespace common\models;

use Yii;
use yii\web\UploadedFile;
use yii\helpers\BaseFileHelper;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;
use common\components\CActiveRecord;
use yii\web\NotFoundHttpException;
use yii\imagine\Image;

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
class File extends CActiveRecord {

    const UPLOAD_FOLDER = 'files';
    const ORIGINAL_FOLDER = 'original';
    const IMAGE_SIZE = [
        'thumbnail' => ['w' => 200, 'h' => 150, 'ratio' => false],
        'medium' => ['w' => 400, 'h' => 400, 'ratio' => true],
        'large' => ['w' => 800, 'h' => 800, 'ratio' => true],
        'full' => ['w' => 3600, 'h' => 3600, 'ratio' => true],
    ];

    public $dir, $url, $link, $thumbnail, $images;

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

    public static function uploadMultiple($id, $model, $attribute, $old_files = []) {
        $path = self::getCategory() . '/' . $id . '/' . $attribute . '/';
        $uploads = UploadedFile::getInstances($model, $attribute . '_upload');
        self::updateCaption(Yii::$app->request->post(), $attribute);
        if (!empty($old_files)) {
            self::deleteFileDifferent($old_files, $model[$attribute]);
        }
        if ($uploads !== null) {
            $path_dir = self::createDir(self::ORIGINAL_FOLDER . '/' . $path);
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

    public function icon($group = 'file_preview', $options_img = [], $options_link = []) {
        if ($this->getFileType() == 'image' || $this->extension == 'pdf') {
            $options_link['data-fancybox'] = $group;
            $options_link['data-caption'] = $this->caption;
        } else {
            $options_link['download'] = empty($this->caption) ? true : $this->caption;
        }
        return Html::a(Html::img($this->thumbnail, $options_img), $this->link, $options_link);
    }

    public static function deleteFileAll($files) {
        $files = explode(',', trim($files));
        if (!empty($files)) {
            foreach ($files as $file) {
                self::deleteFile($file);
            }
        }
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
            if ($f->getFileType() == 'image') {
                foreach ($f->images as $image) {
                    unlink($image['dir']);
                }
            }
            if (unlink($f->dir)) {
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
        $path = self::getDir() . strtolower($folder);
        if (BaseFileHelper::createDirectory($path, 0777)) {
            return $path;
        } else {
            return false;
        }
    }

    public static function deleteDir($id) {
        $del = [];
        foreach (self::IMAGE_SIZE as $key => $size) {
            $del[] = self::getDir($key) . self::getCategory() . '/' . $id;
        }
        $del[] = self::getDir(self::ORIGINAL_FOLDER) . self::getCategory() . '/' . $id;
        foreach ($del as $path) {
            $d = dir($path);
            while (false !== ($entry = $d->read())) {
                if ($entry != '.' && $entry != '..') {
                    rmdir($path . '/' . $entry);
                }
            }
            $d->close();
            rmdir($path);
        }
    }

    public static function getDir($folder = '') {
        $out = Yii::getAlias('@webroot') . '/' . self::UPLOAD_FOLDER . '/';
        if (!empty($folder)) {
            $out .= strtolower($folder) . '/';
        }
        return $out;
    }

    public static function getUrl($folder = '') {
        $out = Url::base(true) . '/' . self::UPLOAD_FOLDER . '/';
        if (!empty($folder)) {
            $out .= strtolower($folder) . '/';
        }
        return $out;
    }

    public static function getCategory() {
        $category = Yii::$app->controller->id;
        if ($category == 'customer' || $category == 'supplier' || $category == 'manufacturer') {
            $category = 'company';
        }
        return $category;
    }

    public function getFileType() {
        $arr = explode('/', $this->type);
        return $arr[0];
    }

    public function getThumbnail() {
        if ($this->getFileType() == 'image') {
            return $this->images['thumbnail']['url'];
        } else {
            $ext = $this->extension;
            if ($ext == 'pdf') {
                $icon = 'pdf.png';
            } else if ($ext == 'doc' || $ext == 'docx') {
                $icon = 'doc.png';
            } else {
                $icon = $ext . '.png';
            }
            return Url::base(true) . '/images/icons/' . $icon;
        }
    }

    public function createImages() {
        if ($this->getFileType() == 'image') {
            foreach (self::IMAGE_SIZE as $key => $size) {
                $path_dir = self::createDir($key . '/' . $this->path);
                if ($size['ratio']) {
                    Image::resize($this->dir, $size['w'], $size['h'])->save($path_dir . $this->name);
                } else {
                    Image::thumbnail($this->dir, $size['w'], $size['h'])->save($path_dir . $this->name);
                }
            }
        }
    }

    public static function findFile($id) {
        if (($model = File::findOne($id)) !== null) {
            $model->dir = self::getDir(self::ORIGINAL_FOLDER) . $model->path . $model->name;
            $model->url = self::getUrl(self::ORIGINAL_FOLDER) . $model->path . $model->name;
            $model->link = $model->url;
            $images = [];
            if ($model->getFileType() == 'image') {
                foreach (self::IMAGE_SIZE as $key => $size) {
                    $path = $model->path . $model->name;
                    if (!file_exists(self::getDir($key) . $path)) {
                        $model->createImages();
                    }
                    $images[$key] = [
                        'url' => self::getUrl($key) . $path,
                        'dir' => self::getDir($key) . $path,
                    ];
                }
                $model->link = $images['full']['url'];
            }
            $model->images = $images;
            $model->thumbnail = $model->getThumbnail();
            return $model;
        }
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

}
