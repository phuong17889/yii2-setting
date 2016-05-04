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
		if (Yii::$app->request->isPost) {
			$setting = Yii::$app->request->post('Setting');
			foreach ($setting as $key => $value) {
				Setting::updateAll(['value' => $value], ['code' => $key]);
			}
			Yii::$app->session->setFlash('alert', [
				'body'    => \Yii::t('backend', 'Settings has been successfully saved'),
				'options' => ['class' => 'alert-success'],
			]);
			$tabHash = Yii::$app->request->post('tabHash', '');
			return $this->refresh($tabHash);
		}
		/**@var $settings Setting[] */
		$settings  = Setting::find()->where(['parent_id' => 0])->orderBy(['sort_order' => SORT_ASC])->all();
		$parentTab = [];
		foreach ($settings as $setting) {
			if (Module::hasMultiLanguage()) {
				$code                    = $setting->code;
				$parentTab[$setting->id] = [
					'label'   => Translate::$code(),
					'content' => $setting->getContent(),
				];
			} else {
				$parentTab[$setting->id] = [
					'label'   => $setting->name,
					'content' => $setting->getContent(),
				];
			}
		}
		return $this->render('index', [
			'parentTab' => $parentTab,
		]);
	}
}
