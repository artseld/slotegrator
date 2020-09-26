<?php

namespace tests\unit\models;

use app\models\Prize;
use app\models\ProcessPrizeForm;
use app\models\UserPrize;
use Yii;

class ProcessPrizeFormTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    public $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    public function testModifyMoneyToPoints()
    {
        // Prepare mocks
    
        /** @var UserPrize $userPrize */
        $userPrize = $this->getMockBuilder(UserPrize::class)
            ->onlyMethods(['save'])
            ->getMock();
        $userPrize
            ->method('save')
            ->willReturn(true);

        /** @var ProcessPrizeForm $form */
        $form = $this->getMockBuilder(ProcessPrizeForm::class)
            ->onlyMethods(['validate', 'afterValidate', 'getUserPrize', 'convert'])
            ->getMock();
        $form
            ->method('validate')
            ->willReturn(true);
        $form
            ->method('afterValidate')
            ->willReturn(true);
        $form
            ->method('getUserPrize')
            ->willReturn($userPrize);

        // Initial data
        $userPrize->type = Prize::TYPE_MONEY;
        $userPrize->value = 100;
        Yii::$app->params['moneyToPointsRatio'] = 2;
        
        // Run method
        $form->process([
            'convert-button' => "",
        ]);

        // Check results
        $this->assertEquals($userPrize->type, Prize::TYPE_POINTS);
        $this->assertEquals($userPrize->value, 200);
    }
}
