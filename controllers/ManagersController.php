<?php

namespace app\controllers;

use Yii;
use app\models\Manager;
use app\models\User;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

/**
 * ManagersController implements the CRUD actions for Manager model.
 */
class ManagersController extends Controller
{
    public function behaviors()
    {
        return [
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

    /**
     * Lists all Manager models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Manager::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Manager model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $manager = $this->findModel($id);
        $post = Yii::$app->request->post();
        if (isset($post['Manager'])) {
            $manager->vacation_start = strtotime($post['vacation_start']);
            $manager->vacation_end = strtotime($post['vacation_end']);
            $manager->salary_date = $post['Manager']['salary_date'];
            $manager->advance_date = $post['Manager']['advance_date'];
            $manager->save();
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $manager->getReceipts(),
        ]);
        return $this->render('view', [
            'model' => $manager,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Manager model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $manager = new Manager();
        $manager->scenario = 'create';
        $user = new User();
        $post = Yii::$app->request->post();
        if (isset($post['Manager']['email'])) {
            $user->username = $post['Manager']['email'];
            $user->role = 'manager';
            $user->status = 10;
            $user->email = $post['Manager']['email'];
            $user->setPassword($post['Manager']['password']);
            if ($user->save()) {
                $manager->load($post);
                $manager->user_id = $user->id;
                if ($manager->save()) {
                    return $this->redirect(['view', 'id' => $manager->id]);
                }
            }
        }

        return $this->render('create', [
            'manager' => $manager,
            'user' => $user,
        ]);
    }

    /**
     * Updates an existing Manager model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Manager model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $manager = $this->findModel($id);
        $manager->user->delete();
        $manager->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Manager model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Manager the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Manager::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
