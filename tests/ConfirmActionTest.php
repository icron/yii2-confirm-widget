<?php
namespace tests;

use icron\confirm\Confirm;
use icron\confirm\ConfirmAction;
use Yii;
use yii\base\Controller;

class ConfirmActionTest extends \PHPUnit_Framework_TestCase
{
    public function testUnknownMethod()
    {
        $response = $this->getAction()->run('failed', '911');
        $this->assertEquals(ConfirmAction::STATUS_ERROR, $response['status']);
        $this->assertEquals('Неизвестный метод', $response['message']);
    }

    public function testSend()
    {
        $response = $this->getAction()->run(ConfirmAction::METHOD_SEND, '89297058409');
        $this->assertEquals(ConfirmAction::STATUS_SUCCESS, $response['status']);
    }

    /**
     * @param string $destination
     */
    public function testConfirm($destination = '89297058409')
    {
        $response = $this->getAction()->run(ConfirmAction::METHOD_CONFIRM, $destination, 'xxxx');
        $this->assertEquals(ConfirmAction::STATUS_ERROR, $response['status']);
        $this->assertEquals('Ошибка подтверждения кода', $response['message']);

        $this->testSend();
        $codes = $this->getConfirmComponent()->getCodes($destination);
        $response = $this->getAction()->run(ConfirmAction::METHOD_CONFIRM, $destination, reset($codes));
        $this->assertEquals(ConfirmAction::STATUS_SUCCESS, $response['status']);
    }

    /**
     * @return Confirm
     * @throws \yii\base\InvalidConfigException
     */
    protected function getConfirmComponent()
    {
        return Yii::$app->get('confirm');
    }

    protected function getAction()
    {
        $controller = new Controller('testController', Yii::$app);
        return new ConfirmAction('confirm', $controller);
    }
}