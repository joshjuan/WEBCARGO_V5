<?php

namespace frontend\controllers;

use frontend\models\Audit;
use Yii;
use frontend\models\BorderPort;
use frontend\models\BorderPortSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BorderPortController implements the CRUD actions for BorderPort model.
 */
class BorderPortController extends Controller
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
     * Lists all BorderPort models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BorderPortSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        Audit::setActivity(Yii::$app->user->identity->username . ' ameangalia taarifa za Border Port', 'BorderPort', 'Index', '', '');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionBorder()
    {
        $searchModel = new BorderPortSearch();
        $dataProvider = $searchModel->searchBorder(Yii::$app->request->queryParams);

        Audit::setActivity(Yii::$app->user->identity->username . ' ameangalia taarifa za Border ', 'BorderPort', 'Index', '', '');

        return $this->render('border', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionPort()
    {
        $searchModel = new BorderPortSearch();
        $dataProvider = $searchModel->searchPort(Yii::$app->request->queryParams);

        Audit::setActivity(Yii::$app->user->identity->username . ' ameangalia taarifa za Border ', 'BorderPort', 'Index', '', '');

        return $this->render('port', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BorderPort model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        Audit::setActivity(Yii::$app->user->identity->username . ' ameangalia taarifa ya hii (' . $model->name . ') Border Port', 'BorderPort', 'View', '', '');

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionViewBorder($id)
    {
        $model = $this->findModel($id);

        Audit::setActivity(Yii::$app->user->identity->username . ' ameangalia taarifa ya hii (' . $model->name . ') Border Port', 'BorderPort', 'View', '', '');

        return $this->render('viewBorder', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionViewPort($id)
    {
        $model = $this->findModel($id);

        Audit::setActivity(Yii::$app->user->identity->username . ' ameangalia taarifa ya hii (' . $model->name . ') Border Port', 'BorderPort', 'View', '', '');

        return $this->render('viewPort', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new BorderPort model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (Yii::$app->user->can("addBorder") || Yii::$app->user->can("admin")) {

            $model = new BorderPort();


            if ($model->load(Yii::$app->request->post()) && $model->save()) {

                Audit::setActivity('Location hii ' . $model->name . ' mpya imeongezwa ', 'BorderPort', 'Create', '', '');

                return $this->redirect(['view', 'id' => $model->id]);
            }

            return $this->render('create', [
                'model' => $model,
            ]);

        } else {
            Yii::$app->session->setFlash('', [
                'type' => 'danger',
                'duration' => 1500,
                'icon' => 'fa fa-warning',
                'message' => Yii::t('app', 'You do not have permission'),
                'positonY' => 'top',
                'positonX' => 'right'
            ]);
            return $this->redirect(['index']);
        }

    }

    public function actionCreateBorder()
    {
        if (Yii::$app->user->can("addBorder") || Yii::$app->user->can("admin")) {

            $model = new BorderPort();
            $model->location = "1";
            if ($model->load(Yii::$app->request->post()) && $model->save()) {

                Audit::setActivity('Taarifa hii ' . $model->name . ' mpya imeongezwa ', 'BorderPort', 'Create', '', '');

                return $this->redirect(['view-border', 'id' => $model->id]);
            }

            return $this->render('createBorder', [
                'model' => $model,
            ]);

        } else {
            Yii::$app->session->setFlash('', [
                'type' => 'danger',
                'duration' => 1500,
                'icon' => 'fa fa-warning',
                'message' => Yii::t('app', 'You do not have permission'),
                'positonY' => 'top',
                'positonX' => 'right'
            ]);
            return $this->redirect(['border']);
        }

    }

    public function actionCreatePort()
    {
        if (Yii::$app->user->can("addBorder") || Yii::$app->user->can("admin")) {

            $model = new BorderPort();
            $model->location = "2";

            $branch = Yii::$app->user->identity->branch;
            $model->branch = $branch;

            if ($model->load(Yii::$app->request->post()) && $model->save()) {

                Audit::setActivity('Taarifa hii ' . $model->name . ' mpya imeongezwa ', 'BorderPort', 'Create', '', '');

                return $this->redirect(['view-port', 'id' => $model->id]);
            }

            return $this->render('createPort', [
                'model' => $model,
            ]);

        } else {
            Yii::$app->session->setFlash('', [
                'type' => 'danger',
                'duration' => 1500,
                'icon' => 'fa fa-warning',
                'message' => Yii::t('app', 'You do not have permission'),
                'positonY' => 'top',
                'positonX' => 'right'
            ]);
            return $this->redirect(['port']);
        }

    }

    /**
     * Updates an existing BorderPort model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {

        if (Yii::$app->user->can("addBorder") || Yii::$app->user->can("admin")) {

            $model = $this->findModel($id);

            if ($model->load(Yii::$app->request->post()) && $model->save()) {

                Audit::setActivity('Location hii ' . $model->name . ' imekuwa updated', 'BorderPort', 'Update', '', '');

                return $this->redirect(['view', 'id' => $model->id]);
            }

            return $this->render('update', [
                'model' => $model,
            ]);

        } else {
            Yii::$app->session->setFlash('', [
                'type' => 'danger',
                'duration' => 1500,
                'icon' => 'fa fa-warning',
                'message' => Yii::t('app', 'You do not have permission'),
                'positonY' => 'top',
                'positonX' => 'right'
            ]);
            return $this->redirect(['index']);
        }

    }

    public function actionUpdateBorder($id)
    {

        if (Yii::$app->user->can("addBorder") || Yii::$app->user->can("admin")) {

            $model = $this->findModel($id);

            if ($model->load(Yii::$app->request->post()) && $model->save()) {

                Audit::setActivity('Location hii ' . $model->name . ' imekuwa updated', 'BorderPort', 'Update', '', '');

                return $this->redirect(['view-border', 'id' => $model->id]);
            }

            return $this->render('updateBorder', [
                'model' => $model,
            ]);

        } else {
            Yii::$app->session->setFlash('', [
                'type' => 'danger',
                'duration' => 1500,
                'icon' => 'fa fa-warning',
                'message' => Yii::t('app', 'You do not have permission'),
                'positonY' => 'top',
                'positonX' => 'right'
            ]);
            return $this->redirect(['border']);
        }

    }

    public function actionUpdatePort($id)
    {

        if (Yii::$app->user->can("addBorder") || Yii::$app->user->can("admin")) {

            $model = $this->findModel($id);

            if ($model->load(Yii::$app->request->post()) && $model->save()) {

                Audit::setActivity('Location hii ' . $model->name . ' imekuwa updated', 'BorderPort', 'Update', '', '');

                return $this->redirect(['view-port', 'id' => $model->id]);
            }

            return $this->render('updatePort', [
                'model' => $model,
            ]);

        } else {
            Yii::$app->session->setFlash('', [
                'type' => 'danger',
                'duration' => 1500,
                'icon' => 'fa fa-warning',
                'message' => Yii::t('app', 'You do not have permission'),
                'positonY' => 'top',
                'positonX' => 'right'
            ]);
            return $this->redirect(['port']);
        }

    }

    /**
     * Deletes an existing BorderPort model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if (Yii::$app->user->can("admin") || Yii::$app->user->can("deleteBorder")) {
            $this->findModel($id)->delete();

            return $this->redirect(['index']);
        } else {
            Yii::$app->session->setFlash('', [
                'type' => 'danger',
                'duration' => 1500,
                'icon' => 'fa fa-warning',
                'message' => Yii::t('app', 'You do not have permission'),
                'positonY' => 'top',
                'positonX' => 'right'
            ]);
            return $this->redirect(['index']);
        }

    }

    public function actionDeleteBorder($id)
    {
        if (Yii::$app->user->can("admin") || Yii::$app->user->can("deleteBorder")) {
            $this->findModel($id)->delete();

            return $this->redirect(['border']);
        } else {
            Yii::$app->session->setFlash('', [
                'type' => 'danger',
                'duration' => 1500,
                'icon' => 'fa fa-warning',
                'message' => Yii::t('app', 'You do not have permission'),
                'positonY' => 'top',
                'positonX' => 'right'
            ]);
            return $this->redirect(['border']);
        }

    }

    public function actionDeletePort($id)
    {
        if (Yii::$app->user->can("admin") || Yii::$app->user->can("deleteBorder")) {
            $this->findModel($id)->delete();

            return $this->redirect(['port']);
        } else {
            Yii::$app->session->setFlash('', [
                'type' => 'danger',
                'duration' => 1500,
                'icon' => 'fa fa-warning',
                'message' => Yii::t('app', 'You do not have permission'),
                'positonY' => 'top',
                'positonX' => 'right'
            ]);
            return $this->redirect(['port']);
        }

    }

    /**
     * Finds the BorderPort model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BorderPort the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BorderPort::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
