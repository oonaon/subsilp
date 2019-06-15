<?php

namespace common\components;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

class ControlBar extends Widget {

    public $classname, $pattern, $params, $id, $class;

    public function init() {
        parent::init();

        $this->id = Yii::$app->request->get('id');
        $this->class = Yii::$app->params['class_model'][$this->classname];
        
        $this->pattern = [
            'index' => [
                'link' => ['index'],
                'disabled' => false,
                'modal' => false,
            ],
            'add' => [
                'link' => ['create'],
                'disabled' => false,
                'modal' => false,
            ],
            'update' => [
                'link' => ['update','id' => $this->id],
                'disabled' => false,
                'modal' => false,
            ],
            'save' => [
                'link' => false,
                'disabled' => false,
                'modal' => false,
            ],
            'cancel' => [
                'link' => ['view','id' => $this->id],
                'disabled' => false,
                'modal' => false,
            ],
            'delete' => [
                'link' => ['delete', 'id' => $this->id],
                'disabled' => false,
                'modal' => false,
            ],
            'search' => [
                'link' => false,
                'disabled' => false,
                'modal' => 'search',
            ],
            'first' => [
                'link' => ['move', 'position' => 'first', 'id' => $this->id],
                'disabled' => false,
                'modal' => false,
            ],
            'previous' => [
                'link' => ['move', 'position' => 'previous', 'id' => $this->id],
                'disabled' => false,
                'modal' => false,
            ],
            'next' => [
                'link' => ['move', 'position' => 'next', 'id' => $this->id],
                'disabled' => false,
                'modal' => false,
            ],
            'last' => [
                'link' => ['move', 'position' => 'last', 'id' => $this->id],
                'disabled' => false,
                'modal' => false,
            ],
        ];
    }

    public function run() {
        $controller_id = Yii::$app->controller->id;
        $action_id = Yii::$app->controller->action->id;

        $btn = [];

        foreach ($this->pattern as $key => $pattern) {
            $btn[$key] = $pattern;
            $btn[$key]['name'] = $key;
        }

        if (empty($this->id)) {
            $btn['update']['disabled'] = true;
            $btn['delete']['disabled'] = true;
            $btn['first']['disabled'] = true;
            $btn['previous']['disabled'] = true;
            $btn['next']['disabled'] = true;
            $btn['last']['disabled'] = true;

            if ($action_id == 'create') {
                $btn['add']['disabled'] = true;
                $btn['save']['disabled'] = false;
                $btn['cancel']['disabled'] = false;
            } else {
                $btn['save']['disabled'] = true;
                $btn['cancel']['disabled'] = true;
            }
            $btn['cancel']['link'] = ['index'];
        } else {
            $id_first = $this->getFirstID();
            $id_last = $this->getLastID();
            $id_previous = $this->getPreviousID();
            $id_next = $this->getNextID();
            $btn['first']['link'] = ['view', 'id' => $id_first];
            $btn['last']['link'] = ['view', 'id' => $id_last];
            $btn['previous']['link'] = ['view', 'id' => $id_previous];
            $btn['next']['link'] = ['view', 'id' => $id_next];

            if ($id_first == $this->id) {
                $btn['first']['disabled'] = true;
            } else {
                $btn['first']['disabled'] = false;
            }
            if ($id_last == $this->id) {
                $btn['last']['disabled'] = true;
            } else {
                $btn['last']['disabled'] = false;
            }
            if ($id_next == $this->id) {
                $btn['next']['disabled'] = true;
            } else {
                $btn['next']['disabled'] = false;
            }
            if ($id_previous == $this->id) {
                $btn['previous']['disabled'] = true;
            } else {
                $btn['previous']['disabled'] = false;
            }

            $btn['update']['disabled'] = false;
            $btn['delete']['disabled'] = false;

            if ($action_id == 'update') {
                $btn['add']['disabled'] = true;
                $btn['save']['disabled'] = false;
                $btn['cancel']['disabled'] = false;
                $btn['delete']['disabled'] = true;
                $btn['update']['disabled'] = true;
                $btn['first']['disabled'] = true;
                $btn['previous']['disabled'] = true;
                $btn['next']['disabled'] = true;
                $btn['last']['disabled'] = true;
            } else {
                $btn['save']['disabled'] = true;
                $btn['cancel']['disabled'] = true;
                $btn['update']['disabled'] = false;
            }
        }


        $buttons = [
            [$btn['index'], $btn['search']],
            [$btn['add'], $btn['update'], $btn['save'], $btn['cancel'], $btn['delete']],
            [$btn['first'], $btn['previous'], $btn['next'], $btn['last']],
        ];
        return $this->render('@app/widgets/controlbar', [
                    'buttons' => $buttons,
        ]);
    }

    public function getNextCode($classname) {
        $org_prefix = strtoupper(substr(Yii::$app->session['organize'], 0, 1));
        $class = Yii::$app->params['class_model'][$classname];
        $model = $class['class']::find()->where('(type like :type) and (org like :org)', [':type' => '%'.$classname.'%', ':org' => '%'.Yii::$app->session['organize'].'%'])->orderBy(['abs(substring('.$class['primary'].', 3))' => SORT_DESC])->one();
        $num = (int) str_replace($org_prefix . $class['primary_prefix'], '', $model[$class['primary']]);
        $num += 1;
        return $org_prefix . $class['primary_prefix'] . $num;
    }

    private function getCurrentCode() {
        $model = $this->class['class']::findOne($this->id);
        return $model[$this->class['primary']];
    }

    private function getNextID() {
        $code = $this->getCurrentCode();
        $model = $this->class['class']::find()->where('(type like :type) and (org like :org) and (' . $this->class['primary'] . '>:code)', [':type' => '%'.$this->classname.'%', ':org' => '%'.Yii::$app->session['organize'].'%', ':code' => $code])->orderBy([$this->class['primary'] => SORT_ASC])->one();
        if (empty($model)) {
            return $this->id;
        } else {
            return $model->id;
        }
    }

    private function getPreviousID() {
        $code = $this->getCurrentCode();
        $model = $this->class['class']::find()->where('(type like :type) and (org like :org) and (' . $this->class['primary'] . '<:code)', [':type' => '%'.$this->classname.'%', ':org' => '%'.Yii::$app->session['organize'].'%', ':code' => $code])->orderBy([$this->class['primary'] => SORT_DESC])->one();
        if (empty($model)) {
            return $this->id;
        } else {
            return $model->id;
        }
    }

    private function getFirstID() {

        $model = $this->class['class']::find()->where('(type like :type) and (org like :org)', [':type' => '%'.$this->classname.'%', ':org' => '%'.Yii::$app->session['organize'].'%'])->orderBy([$this->class['primary'] => SORT_ASC])->one();
        if (empty($model)) {
            return $this->id;
        } else {
            return $model->id;
        }
    }

    private function getLastID() {

        $model = $this->class['class']::find()->where('(type like :type) and (org like :org)', [':type' => '%'.$this->classname.'%', ':org' => '%'.Yii::$app->session['organize'].'%'])->orderBy([$this->class['primary'] => SORT_DESC])->one();
        if (empty($model)) {
            return $this->id;
        } else {
            return $model->id;
        }
    }

}
