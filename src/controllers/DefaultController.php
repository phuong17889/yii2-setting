<?php
namespace navatech\setting\controllers;

use navatech\language\Translate;
use navatech\setting\models\Setting;
use navatech\setting\Module;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * {@inheritDoc}
 */
class DefaultController extends Controller {

	//TODO cần viết thêm role ở đây, viết thêm hàm update
	/**
	 * {@inheritDoc}
	 */
	public function behaviors() {
		return [
			'verbs'  => [
				'class'   => VerbFilter::className(),
				'actions' => [
					'delete' => ['post'],
				],
			],
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'allow' => true,
						'roles' => ['@'],
					],
				],
			],
		];
	}

	public function actionIndex() {
		if(Yii::$app->request->isPost) {
			$setting = Yii::$app->request->post('Setting');
			foreach($setting as $key => $value) {
				Setting::updateAll(['value' => $value], ['code' => $key]);
			}
			Yii::$app->session->setFlash('alert', [
				'body'    => \Yii::t('backend', 'Settings has been successfully saved'),
				'options' => ['class' => 'alert-success'],
			]);
			$tabHash = Yii::$app->request->post('tabHash', '');
			return $this->refresh($tabHash);
		}
		if(Module::hasMultiLanguage()) {
			$title = Translate::setting();
		} else {
			$title = 'Setting';
		}
		return $this->render('index', [
			'title' => $title,
		]);
	}
}
