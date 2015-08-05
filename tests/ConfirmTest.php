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

    /**
     * @dataProvider sendDataProvider
     */
    public function testSend($destination, $output)
    {
        $confirm = $this->getConfirm();
        $this->assertTrue($confirm->send($destination));
        $this->assertTrue($confirm->send($destination));
        $data1 = $confirm->getDestinationData($destination);
        $this->assertEquals($output, $data1['count_send'], 'Неверный count_send.');
    }

    public function sendDataProvider()
    {
        return [
            ['79297058409', 2], // Данные 1
            ['79263324399', 2], // Данные 2
        ];
    }

    /**
     * @dataProvider providerSendConfirm
     * @param $destination
     */
    public function testConfirm($destination)
    {
        $confirm = $this->getConfirm();
        $confirm->send($destination);
        $confirm->send($destination);
        $codes = $confirm->getCodes($destination);
        $this->assertEquals(2, count($codes));
        foreach ($codes as $code) {
            $this->assertTrue($confirm->confirm($destination, $code));
        }
    }

    public function providerSendConfirm()
    {
        return [
            ['79297058409'], // Данные 1
            ['79263324399'], // Данные 2
        ];
    }

    public function testCheckCode()
    {
        $destination = '79297058409';
        $confirm = $this->getConfirm();
        $confirm->send($destination);
        $confirm->send($destination);
        $codes = $confirm->getCodes($destination);
        $this->assertEquals(2, count($codes));
        foreach ($codes as $code) {
            $this->assertTrue($confirm->checkCode($destination, $code));
        }
    }

    public function testGetStatus()
    {
        $destination = '79297058409';
        $confirm = $this->getConfirm();
        $confirm->send($destination);
        $confirm->send($destination);
        $codes = $confirm->getCodes($destination);
        $this->assertEquals(2, count($codes));
        $this->assertEquals(Confirm::STATUS_PENDING, $confirm->getStatus($destination));
        $this->assertTrue($confirm->confirm($destination, reset($codes)));
        $this->assertEquals(Confirm::STATUS_CONFIRMED, $confirm->getStatus($destination));
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject | Confirm
     * @throws \yii\base\InvalidConfigException
     */
    protected function getConfirm()
    {
        $mock = $this->getMockBuilder('\icron\confirm\Confirm')
            ->setConstructorArgs([['provider' => '\tests\TestProvider']])
            ->setMethods(['getSession'])
            ->getMock();

        $mock->expects($this->any())->method('getSession')->will($this->returnValue(Yii::$app->get('session')));

        return $mock;
    }


}
