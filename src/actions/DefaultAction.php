<?php

namespace phuongdev89\setting\actions;

use phuongdev89\language\Translate;
use phuongdev89\setting\models\Setting;
use phuongdev89\setting\Module;
use Yii;
use yii\base\Action;
use yii\web\UploadedFile;

/**
 * Created by PhpStorm.
 * User: phuon
 * Date: 9/1/2016
 * Time: 10:25 AM
 */
class DefaultAction extends Action
{

    /**
     * @return string
     */
    public function run()
    {
        if (Yii::$app->request->isPost) {
            $setting = Yii::$app->request->post('Setting');
            if (isset($_FILES['Setting'])) {
                foreach ($_FILES['Setting']['name'] as $key => $value) {
                    $model = Setting::findOne(['code' => $key]);
                    $model->file = UploadedFile::getInstance($model, $key);
                    if ($model->file != null && $model->upload()) {
                        $model->updateAttributes(['value' => $value]);
                    }
                }
            }
            if ($setting != null) {
                foreach ($setting as $key => $value) {
                    if ($value !== '') {
                        if (is_array($value)) {
                            Setting::updateAll(['value' => implode(",", $value)], ['code' => $key]);
                        } else {
                            Setting::updateAll(['value' => $value], ['code' => $key]);
                        }
                    }
                }
            }
            Yii::$app->session->setFlash('alert', [
                'body' => Yii::t('setting', 'Settings has been successfully saved'),
                'options' => ['class' => 'alert-success'],
            ]);
        }
        if (Module::hasMultiLanguage() && $this->controller->module->enableMultiLanguage) {
            $title = Translate::setting();
        } else {
            $title = Yii::t('setting', 'Setting');
        }
        $currentSetting = Setting::findOne(['code' => $this->id]);
        return $this->controller->render('index', [
            'title' => $title,
            'code' => $currentSetting !== null ? $currentSetting->code : null,
        ]);
    }
}
