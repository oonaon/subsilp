<?php

namespace common\components;

use Yii;
use yii\base\BootstrapInterface;
use yii\web\Cookie;
use yii\base\Exception;

class OrganizeSelector implements BootstrapInterface {

    public $supportedOrganize = [];

    public function bootstrap($app) {
      
        $session = Yii::$app->session;
        $orgNew = Yii::$app->request->get('organize');

       

        if ($orgNew !== null) {
            if (!in_array($orgNew, $this->supportedOrganize)) {
                throw new Exception('Invalid your selected language.');
            }
            $cookies = Yii::$app->response->cookies;
            $cookies->add(new Cookie([
                'name' => 'organize',
                'value' => $orgNew,
                'expire' => time() + 60 * 60 * 24 * 30, // 30 days
            ]));
            $session['organize']=$orgNew;
        } else {
            $cookies = Yii::$app->request->cookies;
            $orgPrefered = $cookies->getValue('organize','easy');
            if (!in_array($orgPrefered, $this->supportedOrganize)) {
                throw new Exception('Invalid your selected language.');
            }
            $session['organize']=$orgPrefered;
        }
    }

}
