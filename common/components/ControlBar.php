<?php

namespace common\components;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;
use common\components\Code;

class ControlBar extends Widget {

    public $pattern, $id, $class, $params;
    public $controller_id, $action_id;
    public $template = ['index', 'search', 'add', 'delete', 'first', 'previous', 'next', 'last', 'modal'];

    public function init() {
        parent::init();
        $this->id = Yii::$app->request->get('id');
        $this->class = Yii::$app->params['class_model'][Yii::$app->controller->id];
        $this->controller_id = Yii::$app->controller->id;
        $this->action_id = Yii::$app->controller->action->id;
        if (!empty($this->params['template'])) {
            $this->template = $this->params['template'];
        }
        if (!empty($this->params['template_add'])) {
            $this->template = array_merge($this->template, $this->params['template_add']);
        }

        $this->pattern = [
            'index' => [
                'link' => ['index'],
                'disabled' => false,
                'modal' => false,
                'position' => 'group_1',
                'button' => 'icon',
            ],
            'add' => [
                'link' => ['update'],
                'disabled' => false,
                'modal' => false,
                'position' => 'group_1',
                'button' => 'icon',
            ],
            'update' => [
                'link' => ['update', 'id' => $this->id],
                'disabled' => false,
                'modal' => false,
                'position' => 'right',
                'button' => '',
            ],
            'manage' => [
                'link' => ['update', 'id' => $this->id],
                'disabled' => false,
                'modal' => false,
                'position' => 'right',
                'button' => '',
            ],
            'save' => [
                'link' => false,
                'disabled' => false,
                'modal' => false,
                'position' => 'right',
                'button' => '',
            ],
            'cancel' => [
                'link' => ['view', 'id' => $this->id],
                'disabled' => false,
                'modal' => false,
                'position' => 'right',
                'button' => '',
            ],
            'delete' => [
                'link' => ['delete', 'id' => $this->id],
                'disabled' => false,
                'modal' => false,
                'position' => 'group_1',
                'button' => 'icon',
            ],
            'search' => [
                'link' => false,
                'disabled' => false,
                'modal' => 'modal-search',
                'position' => 'group_1',
                'button' => 'icon',
            ],
            'first' => [
                'link' => ['move', 'position' => 'first', 'id' => $this->id],
                'disabled' => false,
                'modal' => false,
                'position' => 'left',
                'button' => 'icon',
            ],
            'previous' => [
                'link' => ['move', 'position' => 'previous', 'id' => $this->id],
                'disabled' => false,
                'modal' => false,
                'position' => 'left',
                'button' => 'icon',
            ],
            'next' => [
                'link' => ['move', 'position' => 'next', 'id' => $this->id],
                'disabled' => false,
                'modal' => false,
                'position' => 'left',
                'button' => 'icon',
            ],
            'last' => [
                'link' => ['move', 'position' => 'last', 'id' => $this->id],
                'disabled' => false,
                'modal' => false,
                'position' => 'left',
                'button' => 'icon',
            ],
            'modal' => [
                'link' => ['index', '#' => 'modal-md'],
                'disabled' => false,
                'modal' => 'modal-ajax',
                'position' => 'group_3',
                'button' => 'icon',
            ],
        ];
    }

    public function run() {
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
            $btn['cancel']['link'] = ['index'];
        } else {
            $id_first = $this->getFirstID();
            $id_last = $this->getLastID();
            $id_previous = $this->getPreviousID();
            $id_next = $this->getNextID();
            $btn['first']['link'] = [$this->action_id, 'id' => $id_first];
            $btn['last']['link'] = [$this->action_id, 'id' => $id_last];
            $btn['previous']['link'] = [$this->action_id, 'id' => $id_previous];
            $btn['next']['link'] = [$this->action_id, 'id' => $id_next];

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

            if (in_array('save', $this->template)) {
                $btn['first']['disabled'] = true;
                $btn['previous']['disabled'] = true;
                $btn['next']['disabled'] = true;
                $btn['last']['disabled'] = true;
                $btn['delete']['disabled'] = true;
                $btn['add']['disabled'] = true;
            }
        }

        if (!empty($this->params['button'])) {
            foreach ($this->params['button'] as $key => $items) {
                if (is_array($items)) {
                    foreach ($items as $prop => $item) {
                        $btn[$key][$prop] = $item;
                    }
                }
            }
        }
        $buttons = [];
        if (!empty($this->template)) {
            foreach ($this->template as $name) {
                $pos = empty($btn[$name]['position']) ? 'left' : $btn[$name]['position'];
                $buttons[$pos][] = $btn[$name];
            }
        }

        return $this->render('@app/widgets/controlbar', [
                    'buttons' => $buttons,
                    'prefix' => Code::prefixCode(),
        ]);
    }

    private function getCurrentCode() {
        $model = $this->class['class']::findOne($this->id);
        return $model[$this->class['primary']];
    }

    private function getNextID() {
        $code = $this->getCurrentCode();
        $controller = Yii::$app->controller->id;
        if ($controller == 'customer' || $controller == 'supplier' || $controller == 'manufacturer') {
            $type = substr($controller, 0, 3);
            $model = $this->class['class']::find()->where('(type like :type) and (org like :org) and (' . $this->class['primary'] . '>:code)', [':type' => '%' . $type . '%', ':org' => '%' . Yii::$app->session['organize'] . '%', ':code' => $code])->orderBy([$this->class['primary'] => SORT_ASC])->one();
        } else {
            $model = $this->class['class']::find()->where('(org like :org) and (' . $this->class['primary'] . '>:code)', [':org' => '%' . Yii::$app->session['organize'] . '%', ':code' => $code])->orderBy([$this->class['primary'] => SORT_ASC])->one();
        }
        if (empty($model)) {
            return $this->id;
        } else {
            return $model->id;
        }
    }

    private function getPreviousID() {
        $code = $this->getCurrentCode();
        $controller = Yii::$app->controller->id;
        if ($controller == 'customer' || $controller == 'supplier' || $controller == 'manufacturer') {
            $type = substr($controller, 0, 3);
            $model = $this->class['class']::find()->where('(type like :type) and (org like :org) and (' . $this->class['primary'] . '<:code)', [':type' => '%' . $type . '%', ':org' => '%' . Yii::$app->session['organize'] . '%', ':code' => $code])->orderBy([$this->class['primary'] => SORT_DESC])->one();
        } else {
            $model = $this->class['class']::find()->where('(org like :org) and (' . $this->class['primary'] . '<:code)', [':org' => '%' . Yii::$app->session['organize'] . '%', ':code' => $code])->orderBy([$this->class['primary'] => SORT_DESC])->one();
        }
        if (empty($model)) {
            return $this->id;
        } else {
            return $model->id;
        }
    }

    private function getFirstID() {
        $controller = Yii::$app->controller->id;
        if ($controller == 'customer' || $controller == 'supplier' || $controller == 'manufacturer') {
            $type = substr($controller, 0, 3);
            $model = $this->class['class']::find()->where('(type like :type) and (org like :org)', [':type' => '%' . $type . '%', ':org' => '%' . Yii::$app->session['organize'] . '%'])->orderBy([$this->class['primary'] => SORT_ASC])->one();
        } else {
            $model = $this->class['class']::find()->where('(org like :org)', [':org' => '%' . Yii::$app->session['organize'] . '%'])->orderBy([$this->class['primary'] => SORT_ASC])->one();
        }
        if (empty($model)) {
            return $this->id;
        } else {
            return $model->id;
        }
    }

    private function getLastID() {
        $controller = Yii::$app->controller->id;
        if ($controller == 'customer' || $controller == 'supplier' || $controller == 'manufacturer') {
            $type = substr($controller, 0, 3);
            $model = $this->class['class']::find()->where('(type like :type) and (org like :org)', [':type' => '%' . $type . '%', ':org' => '%' . Yii::$app->session['organize'] . '%'])->orderBy([$this->class['primary'] => SORT_DESC])->one();
        } else {
            $model = $this->class['class']::find()->where('(org like :org)', [':org' => '%' . Yii::$app->session['organize'] . '%'])->orderBy([$this->class['primary'] => SORT_DESC])->one();
        }
        if (empty($model)) {
            return $this->id;
        } else {
            return $model->id;
        }
    }

}
