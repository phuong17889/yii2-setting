<?php
/**
 * Created by Navatech.
 * @project yii2-basic
 * @author  Phuong
 * @email   phuong17889[at]gmail.com
 * @date    5/5/2016
 * @time    3:10 PM
 */
namespace navatech\setting\controllers;

use navatech\base\Module;
use navatech\language\Translate;
use yii\web\Controller;

class ConfigController extends Controller {

	public function actionIndex() {
		if (Module::hasMultiLanguage()) {
			$title = Translate::setting();
		} else {
			$title = 'Setting';
		}
		return $this->render('index', ['title' => $title]);
	}
}