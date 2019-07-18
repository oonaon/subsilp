<?php

namespace common\components;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

class ControlBar extends Widget {

    public $pattern, $id, $class, $button;

    public function init() {
        parent::init();

        $this->id = Yii::$app->request->get('id');
        $this->class = Yii::$app->params['class_model'][Yii::$app->controller->id];

        $button_default = ['index', 'search', 'add', 'delete', 'first', 'previous', 'next', 'last'];
        if (empty($this->button)) {
            $this->button = $button_default;
        } else {
            $this->button = array_merge($this->button, $button_default);
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
                'link' => ['create'],
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
            'add_update' => [
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
            $btn['first']['link'] = [$action_id, 'id' => $id_first];
            $btn['last']['link'] = [$action_id, 'id' => $id_last];
            $btn['previous']['link'] = [$action_id, 'id' => $id_previous];
            $btn['next']['link'] = [$action_id, 'id' => $id_next];

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

        /*
          $buttons = [
          [$btn['index'], $btn['search']],
          [$btn['add'], $btn['update'], $btn['save'], $btn['cancel'], $btn['delete']],
          [$btn['first'], $btn['previous'], $btn['next'], $btn['last']],
          ];
         */
        $buttons = [];
        if (!empty($this->button)) {
            foreach ($this->button as $key => $item) {
                if (!is_array($item)) {
                    $name = $item;
                } else {
                    $name = $key;
                    foreach ($item as $prop => $val) {
                        $btn[$name][$prop] = $val;
                    }
                }

                $pos = $btn[$name]['position'];
                $buttons[$pos][] = $btn[$name];
            }
        }
        return $this->render('@app/widgets/controlbar', [
                    'buttons' => $buttons,
        ]);
    }

    public static function getPrefixCode() {
        $controller = Yii::$app->controller->id;
        $class = Yii::$app->params['class_model'][$controller];
        $org_prefix = strtoupper(substr(Yii::$app->session['organize'], 0, 1));
        if ($controller != 'product') {
            return strtoupper($org_prefix . $class['primary_prefix']);
        }
        return '';
    }

    public static function getNextCode() {
        $prefix = self::getPrefixCode();
        $controller = Yii::$app->controller->id;
        $class = Yii::$app->params['class_model'][$controller];

        if ($controller == 'customer' || $controller == 'supplier' || $controller == 'manufacturer') {
            $type = substr($controller, 0, 3);
            $model = $class['class']::find()->where('(' . $class['primary'] . ' like "' . $prefix . '%") and (type like :type) and (org like :org)', [':type' => '%' . $type . '%', ':org' => '%' . Yii::$app->session['organize'] . '%'])->orderBy(['abs(substring(' . $class['primary'] . ', 3))' => SORT_DESC])->one();
        } else {
            $model = $class['class']::find()->where('(' . $class['primary'] . ' like "' . $prefix . '%") and (org like :org)', [':org' => '%' . Yii::$app->session['organize'] . '%'])->orderBy(['abs(substring(' . $class['primary'] . ', 3))' => SORT_DESC])->one();
        }
        if (!empty($model)) {
            $num = (int) str_replace($prefix, '', $model[$class['primary']]);
            $num += 1;
            return $prefix . $num;
        } else if(!empty($prefix)){
            return $prefix.'1';
        } else {
            return '';
        }
        
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
