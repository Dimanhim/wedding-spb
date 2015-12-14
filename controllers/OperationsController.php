<?php

namespace app\controllers;

use Yii;
use app\models\Operation;
use app\models\OperationSearch;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OperationsController implements the CRUD actions for Operation model.
 */
class OperationsController extends Controller
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
        ];
    }

    /**
     * Lists all Operation models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OperationSearch();

        if (!isset($_GET['OperationSearch']['date_start'])) {
            $date_start = strtotime(date('Y-m-01'));
        } else {
            $date_start = strtotime($_GET['OperationSearch']['date_start']);
        }
        if (!isset($_GET['OperationSearch']['date_end'])) {
            $date_end = strtotime(date('Y-m-t'));
        } else {
            $date_end = strtotime($_GET['OperationSearch']['date_end']) + 86399;
        }

        $searchModel->date_start = date('d.m.Y', $date_start);
        $searchModel->date_end = date('d.m.Y', $date_end);

        $timestamp = $date_start;
        $operations = [];
        $days = [];

        $total_income_nal = 0;
        $total_income_beznal = 0;
        $total_expense_nal = 0;
        $total_expense_beznal = 0;
        $total_planned_nal = 0;
        $total_planned_beznal = 0;
        $total_summary = 0;

        while($timestamp <= $date_end) {
            $beginOfDay = strtotime("midnight", $timestamp);
            $endOfDay   = strtotime("tomorrow", $beginOfDay) - 1;

            $day_income_nal = 0;
            $day_income_beznal = 0;
            $day_expense_nal = 0;
            $day_expense_beznal = 0;
            $day_planned_nal = 0;
            $day_planned_beznal = 0;
            $day_summary = 0;
            //$day_operations = Operation::find()->where(['between', 'created_at', $beginOfDay, $endOfDay])->andWhere(['!=', 'repeated', 1])->all();
            $day_operations = Operation::find()->where(['between', 'created_at', $beginOfDay, $endOfDay])->all();
            foreach ($day_operations as $day_operation) {
                if ($day_operation->type_id == Operation::TYPE_INCOME) {
                    if ($day_operation->payment_type == Operation::PAY_CASH) {
                        $day_income_nal += $day_operation->total_price;
                        $total_income_nal += $day_operation->total_price;
                    } else {
                        $day_income_beznal += $day_operation->total_price;
                        $total_income_beznal += $day_operation->total_price;
                    }
                    $day_summary += $day_operation->total_price;
                    $total_summary += $day_operation->total_price;
                } else {
                    if ($day_operation->payment_type == Operation::PAY_CASH) {
                        if ($day_operation->repeated) {
                            $day_planned_nal += $day_operation->total_price;
                            $total_planned_nal += $day_operation->total_price;
                        } else {
                            $day_expense_nal += $day_operation->total_price;
                            $total_expense_nal += $day_operation->total_price;
                        }
                    } else {
                        if ($day_operation->repeated) {
                            $day_planned_beznal += $day_operation->total_price;
                            $total_planned_beznal += $day_operation->total_price;
                        } else {
                            $day_expense_beznal += $day_operation->total_price;
                            $total_expense_beznal += $day_operation->total_price;
                        }
                    }
                    //Плюсуем к итого, только если не запланирована
                    if (!$day_operation->repeated) {
                        $day_summary -= $day_operation->total_price;
                        $total_summary -= $day_operation->total_price;
                    }
                }
            }
            $operations[date('j', $timestamp)] = [
                'day_income_nal' => $day_income_nal,
                'day_income_beznal' => $day_income_beznal,
                'day_expense_nal' => $day_expense_nal,
                'day_expense_beznal' => $day_expense_beznal,
                'day_planned_nal' => $day_planned_nal,
                'day_planned_beznal' => $day_planned_beznal,
                'day_summary' => $day_summary,
            ];
            
            $days[] = $timestamp;
            $timestamp = strtotime("+1 day", $timestamp);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'operations' => $operations,
            'days' => $days,
            'total' => [
                'total_income_nal' => $total_income_nal,
                'total_income_beznal' => $total_income_beznal,
                'total_expense_nal' => $total_expense_nal,
                'total_expense_beznal' => $total_expense_beznal,
                'total_planned_nal' => $total_planned_nal,
                'total_planned_beznal' => $total_planned_beznal,
                'total_summary' => $total_summary,
            ]
        ]);
    }

    /**
     * Displays a single Operation model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Operation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Operation();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionCronCreate()
    {
        return Operation::createFromCron();
    }

    /**
     * Updates an existing Operation model.
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
     * Deletes an existing Operation model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Operation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Operation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Operation::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
