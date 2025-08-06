<?php

namespace backend\controllers;

use backend\models\AuthItem;
use common\models\LoginForm;
use Yii;
use backend\models\User;
use backend\models\UserSearch;
use yii\base\Model;
use yii\bootstrap\ActiveForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
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
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest) {
        $searchModel = new UserSearch();
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

    public function actionIndexBillCustomer()
    {
        if (!Yii::$app->user->isGuest) {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->searchBillCustomer(Yii::$app->request->queryParams);

        return $this->render('indexBillCustomer', [
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
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionViewBillCustomer($id)
    {
        return $this->render('viewBillCustomer', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {

            $model->mobile = $_POST['User']['mobile'];
            $model->save();

            Yii::$app->authManager->assign(Yii::$app->authManager->getRole($model->role), $model->id);
            return $this->redirect(['view', 'id' => $model->id]);

        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }


    public function actionCreateBillCustomer()
    {
        $model = new User();

        $model->user_type = User::BILL_STAFF;
        $model->role='BillCustomer';
        $model->username='BillCustomer';
        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $model->username=$model->company_name;
            Yii::$app->authManager->assign(Yii::$app->authManager->getRole($model->role), $model->id);

            $model->mobile = $_POST['User']['mobile'];
            $model->save();


            return $this->redirect(['view-bill-customer', 'id' => $model->id]);
        }

        return $this->render('createBillCustomer', [
            'model' => $model,
        ]);


    }


    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->authManager->revokeAll($id);
            Yii::$app->authManager->assign(Yii::$app->authManager->getRole($model->role), $id);
            $model->mobile = $_POST['User']['mobile'];
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionUpdateBillCustomer($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            Yii::$app->authManager->revokeAll($id);
            Yii::$app->authManager->assign(Yii::$app->authManager->getRole($model->role), $id);

            $model->mobile = $_POST['User']['mobile'];
            $model->save();

            return $this->redirect(['view-bill-customer', 'id' => $model->id]);
        }

        return $this->render('updateBillCustomer', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
      // $this->findModel($id)->delete();

     //   return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionProfile()
    {
        if (!Yii::$app->user->isGuest) {
            $id = Yii::$app->user->identity->id;
            $model = $this->findModel($id);

            //$emp = $this->findEmpModel($model->emp_id);
            $model->setScenario('admin-update');
            if ($model->load(Yii::$app->request->post())) {
                //   Yii::$app->authManager->revokeAll($id);
                //  Yii::$app->authManager->assign(Yii::$app->authManager->getRole($model->role), $id);
                $model->save();

                Yii::$app->getSession()->setFlash(' ', [
                    'type' => 'success',
                    'duration' => 5000,
                    'icon' => 'fa fa-check',
                    'message' => 'You have successfully changed your password.',
                    'title' => 'Notification ....!!',
                    'positonY' => 'top',
                    'positonX' => 'right'
                ]);
                return $this->redirect(['profile', 'id' => $model->id]);

            } else {
                return $this->render('profile', [
                    'model' => $this->findModel($id),
                ]);
            }

        } else {
            $model = new LoginForm();
            return $this->render('site/login', [
                'model' => $model,
            ]);
        }
    }
}
