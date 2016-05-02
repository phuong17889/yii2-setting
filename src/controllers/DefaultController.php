<?php

namespace navatech\setting\controllers;

use navatech\setting\models\Setting;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use Yii;

class DefaultController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ]
            ],
        ];
    }

    public function actionIndex()
    {
        //if(!Yii::$app->user->can('readPost')) throw new HttpException(403, 'No Auth');

        if(Yii::$app->request->isPost)
        {
            $setting = Yii::$app->request->post('Setting');
            foreach($setting as $key => $value) {
                Setting::updateAll(['value' => $value], ['code' => $key]);
            }
            Yii::$app->session->setFlash('alert', [
                'body'=>\Yii::t('backend', 'Settings has been successfully saved'),
                'options'=>['class'=>'alert-success']
            ]);
            $tabHash = Yii::$app->request->post('tabHash', '');
            return $this->refresh($tabHash);
        }

        $settingParent = Setting::find()->where(['parent_id' => 0])->orderBy(['sort_order' => SORT_ASC])->all();
        return $this->render('index', [
            'settingParent' => $settingParent,
        ]);
    }
}
