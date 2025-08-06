<?php

namespace frontend\controllers;

use frontend\models\BorderPort;
use frontend\models\User;
use common\models\LoginForm;
use Yii;
use frontend\models\BorderPortUser;
use frontend\models\BorderPortUserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BorderPortUserController implements the CRUD actions for BorderPortUser model.
 */
class BorderPortUserController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all BorderPortUser models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest) {
        $searchModel = new BorderPortUserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
        } else {
            $model = new LoginForm();
            return $this->redirect(['site/login',
                'model' => $model,
            ]);
        }
    }

    public function actionPort()
    {
        if (!Yii::$app->user->isGuest) {
        $searchModel = new BorderPortUserSearch();
        $dataProvider = $searchModel->searchPort(Yii::$app->request->queryParams);

        return $this->render('indexPort', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
        } else {
            $model = new LoginForm();
            return $this->redirect(['site/login',
                'model' => $model,
            ]);
        }
    }

    public function actionBorder()
    {
        if (!Yii::$app->user->isGuest) {
        $searchModel = new BorderPortUserSearch();
        $dataProvider = $searchModel->searchBorder(Yii::$app->request->queryParams);

        return $this->render('indexBorder', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
        } else {
            $model = new LoginForm();
            return $this->redirect(['site/login',
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays a single BorderPortUser model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if (!Yii::$app->user->isGuest) {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }

        else {
            $model = new LoginForm();
            return $this->redirect(['site/login',
                'model' => $model,
            ]);
        }
    }

    public function actionViewPort($id)
    {
        if (!Yii::$app->user->isGuest) {
            return $this->render('viewPort', [
                'model' => $this->findModel($id),
            ]);
        }

        else {
            $model = new LoginForm();
            return $this->redirect(['site/login',
                'model' => $model,
            ]);
        }
    }

    public function actionViewBorder($id)
    {
        if (!Yii::$app->user->isGuest) {
            return $this->render('viewBorder', [
                'model' => $this->findModel($id),
            ]);
        }

        else {
            $model = new LoginForm();
            return $this->redirect(['site/login',
                'model' => $model,
            ]);
        }
    }

    /**
     * Creates a new BorderPortUser model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (!Yii::$app->user->isGuest) {
            $model = new BorderPortUser();

            if ($model->load(Yii::$app->request->post())) {

                $user = BorderPortUser::find()->where(['name' => $model->name])->one();

                if ($user == '') {
                    $model->assigned_date = date('y-m-d H:i:s');
                    $model->assigned_by = Yii::$app->user->identity->id;
                    $model->save();
                    return $this->redirect(['index']);
                } else {
                    Yii::$app->session->setFlash('', [
                        'type' => 'danger',
                        'duration' => 2000,
                        'icon' => 'fa fa-check',
                        'message' => 'One user can not be assigned two places',
                        'positonY' => 'top',
                        'positonX' => 'right'
                    ]);

                    return $this->redirect(['index']);
                }


            }

            return $this->render('create', [
                'model' => $model,
            ]);

        }
        else {
            $model = new LoginForm();
            return $this->redirect(['site/login',
                'model' => $model,
            ]);
        }

    }

    public function actionCreatePort()
    {
        if (!Yii::$app->user->isGuest) {
            $model = new BorderPortUser();

            if ($model->load(Yii::$app->request->post())) {

                $user = BorderPortUser::find()->where(['name' => $model->name])->one();

                if ($user == '') {
                    $model->assigned_date = date('y-m-d H:i:s');
                    $model->assigned_by = Yii::$app->user->identity->id;
                    $model->type = BorderPortUser::PORT_USER;
                    $model->save();
                    return $this->redirect(['port']);
                } else {
                    Yii::$app->session->setFlash('', [
                        'type' => 'danger',
                        'duration' => 2000,
                        'icon' => 'fa fa-check',
                        'message' => 'One user can not be assigned two places',
                        'positonY' => 'top',
                        'positonX' => 'right'
                    ]);

                    return $this->redirect(['port']);
                }


            }

            return $this->render('createPort', [
                'model' => $model,
            ]);

        }
        else {
            $model = new LoginForm();
            return $this->redirect(['site/login',
                'model' => $model,
            ]);
        }

    }

    public function actionCreateBorder()
    {
        if (!Yii::$app->user->isGuest) {
            $model = new BorderPortUser();

            if ($model->load(Yii::$app->request->post())) {

                $user = BorderPortUser::find()->where(['name' => $model->name])->one();

                if ($user == '') {
                    $model->assigned_date = date('y-m-d H:i:s');
                    $model->assigned_by = Yii::$app->user->identity->id;
                    $model->type = BorderPortUser::PORT_USER;
                    $model->save();
                    return $this->redirect(['border']);

                } else {

                    Yii::$app->session->setFlash('', [
                        'type' => 'danger',
                        'duration' => 2000,
                        'icon' => 'fa fa-check',
                        'message' => 'One user can not be assigned two places',
                        'positonY' => 'top',
                        'positonX' => 'right'
                    ]);

                    return $this->redirect(['border']);
                }


            }

            return $this->render('createBorder', [
                'model' => $model,
            ]);

        }
        else {
            $model = new LoginForm();
            return $this->redirect(['site/login',
                'model' => $model,
            ]);
        }

    }

    /**
     * Updates an existing BorderPortUser model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if (!Yii::$app->user->isGuest) {
            $model = $this->findModel($id);

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }

            return $this->render('update', [
                'model' => $model,
            ]);
        }
        else {
            $model = new LoginForm();
            return $this->redirect(['site/login',
                'model' => $model,
            ]);
        }
    }

    public function actionUpdatePort($id)
    {
        if (!Yii::$app->user->isGuest) {
            $model = $this->findModel($id);

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view-port', 'id' => $model->id]);
            }

            return $this->render('updatePort', [
                'model' => $model,
            ]);
        }
        else {
            $model = new LoginForm();
            return $this->redirect(['site/login',
                'model' => $model,
            ]);
        }
    }

    public function actionUpdateBorder($id)
    {
        if (!Yii::$app->user->isGuest) {
            $model = $this->findModel($id);

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view-border', 'id' => $model->id]);
            }

            return $this->render('updateBorder', [
                'model' => $model,
            ]);
        }
        else {
            $model = new LoginForm();
            return $this->redirect(['site/login',
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing BorderPortUser model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the BorderPortUser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BorderPortUser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BorderPortUser::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
