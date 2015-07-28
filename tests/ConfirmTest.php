<?php
namespace tests;

use icron\confirm\Confirm;
use Yii;

class ConfirmTest extends \PHPUnit_Framework_TestCase
{
    public function testGenerateCode()
    {
        $confirm = $this->getConfirm();
        $code = $confirm->generateCode(4);
        $this->assertEquals(4, strlen($code));
    }

    public function testSend()
    {
        $destination1 = 79297058409;
        $destination2 = 79267058409;
        $confirm = $this->getConfirm();
        $this->assertTrue($confirm->send($destination1));
        // TODO Проверка количетсва попыток отправки $destination1
        // TODO Проверка количетсва попыток отправки $destination2
    }

    protected function getConfirm()
    {
        return new Confirm();
    }


}
