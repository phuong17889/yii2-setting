<?php

namespace phuong17889\setting\controllers;

use phuong17889\base\Module;
use phuong17889\language\Translate;
use phuong17889\role\filters\RoleFilter;
use phuong17889\setting\models\search\SettingSearch;
use phuong17889\setting\models\Setting;
use Yii;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * ConfigController implements the CRUD actions for Setting model.
 */
class ConfigController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
        if (Module::hasUserRole()) {
            if (Module::hasMultiLanguage() && Yii::$app->getModule('setting')->enableMultiLanguage) {
                return ArrayHelper::merge($behaviors, [
                    'role' => [
                        'class' => RoleFilter::class,
                        'name' => Translate::setting(),
                        'actions' => [
                            'index' => Translate::index(),
                            'create' => Translate::create(),
                            'update' => Translate::update(),
                            'delete' => Translate::delete(),
                        ],
                    ],
                ]);
            } else {
                return ArrayHelper::merge($behaviors, [
                    'role' => [
                        'class' => RoleFilter::class,
                        'name' => 'Setting',
                        'actions' => [
                            'index' => Yii::t('setting', 'List'),
                            'create' => Yii::t('setting', 'Create'),
                            'update' => Yii::t('setting', 'Update'),
                            'delete' => Yii::t('setting', 'Delete'),
                        ],
                    ],
                ]);
            }
        } else {
            return $behaviors;
        }
    }

    /**
     * {@inheritDoc}
     * @param $action
     * @return bool
     * @throws NotFoundHttpException
     * @throws BadRequestHttpException
     */
    public function beforeAction($action)
    {
        if (YII_ENV_DEV) {
            return parent::beforeAction($action);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Lists all Setting models.
     * @return array|string
     */
    public function actionIndex()
    {
        if (Yii::$app->request->post('hasEditable')) {
            $id = Yii::$app->request->post('editableKey');
            $attribute = Yii::$app->request->post('editableAttribute');
            $model = Setting::findOne($id);
            $out = [
                'output' => '',
                'message' => '',
            ];
            $posted = current($_POST['Setting']);
            if ($model->updateAttributes([$attribute => $posted[$attribute]])) {
                $out = [
                    'output' => $posted[$attribute],
                    'message' => '',
                ];
            }
            Yii::$app->response->format = 'json';
            return $out;
        }
        $searchModel = new SettingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Setting model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate()
    {
        $model = new Setting();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect([
                'index',
            ]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Setting model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     *
     * @return Response|string
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect([
                'index',
            ]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Finds the Setting model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return Setting the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Setting::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Deletes an existing Setting model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return Response
     * @throws NotFoundHttpException
     * @throws StaleObjectException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->parent_id == 0 && $model->type == Setting::TYPE_GROUP) {
            $model->deleteAll(['parent_id' => $model->id]);
        }
        $model->delete();
        return $this->redirect(['index']);
    }
}
