<?php

namespace app\controllers;

use Yii;
use app\models\Operation;
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
    public function actionIndex($start_date = false, $end_date = false)
    {
        if (!$start_date) $start_date = strtotime(date('Y-m-01'));
        if (!$end_date) $end_date = strtotime(date('Y-m-t'));

        $timestamp = $start_date;
        $operations = [];
        $days = [];

        $total_income_nal = 0;
        $total_income_beznal = 0;
        $total_expense_nal = 0;
        $total_expense_beznal = 0;
        $total_summary = 0;

        while($timestamp <= $end_date) {
            $beginOfDay = strtotime("midnight", $timestamp);
            $endOfDay   = strtotime("tomorrow", $beginOfDay) - 1;

            $day_income_nal = 0;
            $day_income_beznal = 0;
            $day_expense_nal = 0;
            $day_expense_beznal = 0;
            $day_summary = 0;
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
                        $day_expense_nal += $day_operation->total_price;
                        $total_expense_nal += $day_operation->total_price;
                    } else {
                        $day_expense_beznal += $day_operation->total_price;
                        $total_expense_beznal += $day_operation->total_price;
                    }
                    $day_summary -= $day_operation->total_price;
                    $total_summary -= $day_operation->total_price;
                }
            }
            $operations[date('j', $timestamp)] = [
                'day_income_nal' => $day_income_nal,
                'day_income_beznal' => $day_income_beznal,
                'day_expense_nal' => $day_expense_nal,
                'day_expense_beznal' => $day_expense_beznal,
                'day_summary' => $day_summary,
            ];
            
            $days[] = $timestamp;
            $timestamp = strtotime("+1 day", $timestamp);
        }

        return $this->render('index', [
            'operations' => $operations,
            'days' => $days,
            'total' => [
                'total_income_nal' => $total_income_nal,
                'total_income_beznal' => $total_income_beznal,
                'total_expense_nal' => $total_expense_nal,
                'total_expense_beznal' => $total_expense_beznal,
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
