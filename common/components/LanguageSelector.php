<?php
namespace common\components;

use Yii;
use yii\base\BootstrapInterface;
use yii\web\Cookie;
use yii\base\Exception;

class LanguageSelector implements BootstrapInterface
{
    public $supportedLanguages = [];

    public function bootstrap($app)
    {
        $languageNew = $app->request->get('language'); //ตรวจสอบว่ามีการ request language หรือเปล่า

        if($languageNew !== null)
        {
            if (!in_array($languageNew, $this->supportedLanguages)) { //ตรวจสอบว่า language ที่ส่งมาตรงกับที่ตั้งค่าไว้หรือเปล่า
                throw new Exception('Invalid your selected language.'); //ถ้าไม่มี language ในรายการก็ exception
            }
                $cookies = Yii::$app->response->cookies; //กำหนด cookie
                $cookies->add(new Cookie([
                    'name' => 'language',
                    'value' => $languageNew,
                    'expire' => time() + 60 * 60 * 24 * 30, // 30 days
                ])); //สร้าง cookie language ใหม่ให้มีระยะเวลา 30 วัน ตรงนี้ตั้งค่าได้ตามต้องการ
                $app->language = $languageNew; //กำหนดค่าภาษาให้กับ app หลัก
                //echo 'test1';

        }
        else
        {
            $cookies = Yii::$app->request->cookies;
            $preferedLanguage = $cookies->getValue('language','th');
            if (!in_array($preferedLanguage, $this->supportedLanguages)) { //ตรวจสอบว่า language ที่ส่งมาตรงกับที่ตั้งค่าไว้หรือเปล่า
                throw new Exception('Invalid your selected language.'); //ถ้าไม่มี language ในรายการก็ exception
            }
            $app->language = $preferedLanguage; //กำหนดภาษาเริ่มต้นให้ app ในที่นี้คือ th-TH
        }
    }
}