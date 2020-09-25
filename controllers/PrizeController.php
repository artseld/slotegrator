<?php

namespace app\controllers;

use Yii;
use yii\web\Response;
use app\models\GetPrizeForm;
use app\models\ProcessPrizeForm;
use app\models\UserPrize;

class PrizeController extends AbstractController
{
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays list.
     *
     * @return Response|string
     */
    public function actionIndex()
    {
        if ($this->isGuest()) {
            return $this->goHome();
        }

        return $this->render('index',
            UserPrize::getList(10, $this->isUser() ? $this->getIdentity()->id : null)
        );
    }

    /**
     * Get prize action.
     *
     * @return Response|string
     */
    public function actionGet()
    {
        if ($this->isGuest() || $this->isAdmin()) {
            return $this->goHome();
        }

        $model = new GetPrizeForm();
        if ($model->load(Yii::$app->request->post())) {
            $prize = $model->get();
            Yii::$app->session->setFlash('getPrizeFormSubmitted');

            return $this->render('get', [
                'prize' => $prize,
            ]);
        }
        return $this->render('get');
    }

    /**
     * Process action.
     *
     * @return Response|string
     */
    public function actionProcess()
    {
        $data = Yii::$app->request->post();
    
        if ($this->isGuest() || $this->isAdmin() || empty($data)) {
            return $this->goHome();
        }

        $model = new ProcessPrizeForm();
        if ($model->load($data) && $model->process($data)) {
            Yii::$app->session->setFlash('processPrizeFormSubmitted');
        }
        return $this->redirect([ '/prize/index' ]);
    }
}
