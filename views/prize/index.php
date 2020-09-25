<?php

/* @var $this yii\web\View */

use app\models\Prize;
use app\models\User;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\helpers\Url;

$this->title = 'Prizes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-prizes">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->session->hasFlash('processPrizeFormSubmitted')): ?>
        <div class="alert alert-success">
            Operation has been done.
        </div>
    <?php endif; ?>

    <?php if (Yii::$app->user->getIdentity()->role == User::ROLE_ADMIN): ?>
        <div class="row">
            <p><strong>(!)</strong> Please use CLI to operate users prizes.</p>
        </div>
    <?php else: ?>
        <div class=row"">
            <p>Try to <a href="<?= Url::toRoute('/prize/get') ?>">get</a> another one.</p>
        </div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>ID </th>
            <?php if (Yii::$app->user->getIdentity()->role == User::ROLE_ADMIN): ?>
                <th>User </th>
            <?php endif; ?>
                <th>Name </th>
                <th>Type </th>
                <th>Value </th>
                <th>Won at </th>
                <th>Status </th>
            <?php if (Yii::$app->user->getIdentity()->role == User::ROLE_USER): ?>
                <th>Actions </th>
            <?php endif; ?>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($prizes as $prize): ?>
            <tr>
                <td><?= $prize->id ?> </td>
            <?php if (Yii::$app->user->getIdentity()->role == User::ROLE_ADMIN): ?>
                <td><?= $prize->getUser()->username ?> </td>
            <?php endif; ?>
                <td><?= $prize->getPrize()->name ?> </td>
                <td><?= $prize->type ?> </td>
                <td><?= $prize->value ?> </td>
                <td><?= date('Y/m/d H:i:s', $prize->created_at) ?> </td>
                <td><?= $prize->status ?> </td>
            <?php if (Yii::$app->user->getIdentity()->role == User::ROLE_USER): ?>
                <td><?php if ($prize->isProcessable()): ?>
                    <?php $form = ActiveForm::begin(['id' => 'process-prize-form', 'action' => ['/prize/process']]); ?>
                    <input type="hidden" name="ProcessPrizeForm[userPrizeId]" value="<?= $prize->id ?>" />
                    <?= Html::submitButton('Deliver', ['class' => 'btn btn-success', 'name' => 'deliver-button']) ?>
                    &nbsp;
                <?php if ($prize->type == Prize::TYPE_MONEY): ?>
                    <?= Html::submitButton('Convert', ['class' => 'btn btn-warning', 'name' => 'convert-button']) ?>
                    &nbsp;
                <?php endif; ?>
                    <?= Html::submitButton('Drop', ['class' => 'btn btn-danger', 'name' => 'drop-button']) ?>
                    <?php ActiveForm::end(); ?>
                <?php else: ?>
                    -
                <?php endif; ?></td>
            <?php endif; ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <?= LinkPager::widget(['pagination' => $pagination]) ?>
    
</div>
