<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = 'Get Prize';
$this->params['breadcrumbs'][] = ['label' => 'Prizes', 'url' => ['prize/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->session->hasFlash('getPrizeFormSubmitted')): ?>

        <?php if ($prize): ?>
        
        <div class="alert alert-success">
            You won a <strong><?= $prize->getPrize()->name ?></strong> (<?= $prize->value ?>)!
        </div>

        <div class="row">
            <p>Check your <a href="<?= Url::toRoute('/prize/index') ?>">Prizes list</a>.</p>
        </div>
        
        <?php else: ?>
        
        <div class="alert alert-warning">
            Oops! Maybe there are no prizes in our system. Please try later or contact administrator.
        </div>

        <?php endif; ?>

    <?php else: ?>

        <p>
            Are you really lucky?
        </p>

        <div class="row">
            <div class="col-lg-5">

                <?php $form = ActiveForm::begin(['id' => 'get-prize-form']); ?>

                    <?php /* at least one field to pass form validation */ ?>
                    <input type="hidden" name="GetPrizeForm[userId]" value="<?= Yii::$app->user->getIdentity()->id ?>" />

                    <div class="form-group">
                        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'get-prize-button']) ?>
                    </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>

    <?php endif; ?>
</div>
