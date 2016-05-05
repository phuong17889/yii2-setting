<?php
namespace navatech\setting\controllers;

use navatech\language\Translate;
use navatech\role\filters\RoleFilter;
use navatech\setting\models\Setting;
use navatech\setting\Module;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

/**
 * {@inheritDoc}
 */
class DefaultController extends Controller {

	/**
	 * {@inheritDoc}
	 */
	public function behaviors() {
		$behaviors = [
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
		if (Module::hasUserRole()) {
			if (Module::hasMultiLanguage()) {
				return ArrayHelper::merge($behaviors, [
					'role' => [
						'class'   => RoleFilter::className(),
						'name'    => Translate::setting(),
						'actions' => [
							'index' => Translate::index(),
						],
					],
				]);
			} else {
				return ArrayHelper::merge($behaviors, [
					'role' => [
						'class'   => RoleFilter::className(),
						'name'    => 'Setting',
						'actions' => [
							'index' => 'Index',
						],
					],
				]);
			}
		} else {
			return $behaviors;
		}
	}

	public function actionIndex() {
		if (Yii::$app->request->isPost) {
			$setting = Yii::$app->request->post('Setting');
			foreach ($setting as $key => $value) {
				if ($value !== '') {
					Setting::updateAll(['value' => $value], ['code' => $key]);
				}
			}
			Yii::$app->session->setFlash('alert', [
				'body'    => 'Settings has been successfully saved',
				'options' => ['class' => 'alert-success'],
			]);
			$tabHash = Yii::$app->request->post('tabHash', '');
			return $this->refresh($tabHash);
		}
		if (Module::hasMultiLanguage()) {
			$title = Translate::setting();
		} else {
			$title = 'Setting';
		}
		return $this->render('index', [
			'title' => $title,
		]);
	}
}
