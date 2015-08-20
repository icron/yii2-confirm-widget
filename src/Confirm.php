<?php
namespace icron\confirm;

use icron\confirm\providers\IProvider;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

class Confirm extends Component
{
    const SESSION_ID = 'icron\confirm';
    const STATUS_CONFIRMED = 'confirmed'; // Подтверждено
    const STATUS_PENDING = 'pending'; // В ожидании

    public $provider;

    public $limitCountSend = 10;
    public $repeatInterval = 15; // sec
    /** @var  IProvider */
    private $_provider;

    public function init()
    {
        if (!$this->provider) {
            throw new InvalidConfigException('Provider must be declared.');
        }
        $this->_provider = \Yii::createObject($this->provider);
    }

    /**
     * Confirm destination.
     * @param $destination
     * @param $code
     * @return bool
     */
    public function confirm($destination, $code)
    {
        $data = [];
        if (in_array($code, $this->getCodes($destination))) {
            $data['status'] = self::STATUS_CONFIRMED;
            $this->setSessionData($data, $destination);
            return true;
        }

        return false;
    }

    public function generateCode($length = 4)
    {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[mt_rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function send($destination)
    {
        $data = [];
        $destinationData = $this->getDestinationData($destination);
        $data['last_send'] = date('Y-m-d H:i:s');
        $countSend = isset($destinationData['count_send']) ? $destinationData['count_send'] : 0;
        $data['count_send'] = $countSend + 1;
        $data['status'] = self::STATUS_PENDING;
        $code = $this->generateCode();
        if ($this->_provider->send($destination, $code)) {
            $data['codes'] = array_merge($this->getCodes($destination), [$code]);
            $this->setSessionData($data, $destination);
            return true;
        }

        $this->setSessionData($data, $destination);
        return false;
    }

    /**
     * Checks valid codes
     * @param $destination
     * @param $code
     * @return bool
     */
    public function checkCode($destination, $code)
    {
        return in_array($code, $this->getCodes($destination));
    }
    public function getStatus($destination)
    {
        $data = $this->getDestinationData($destination);
        return $data['status'];
    }
    public function getCodes($destination)
    {
        $data = $this->getDestinationData($destination);
        return isset($data['codes']) ? $data['codes'] : [];
    }
    public function getDestinationData($destination)
    {
        $data = $this->getSessionData();
        return isset($data[$destination]) ? $data[$destination] : [];
    }

    public function getConfirmedDestinations()
    {
        $destinations = [];
        $data = $this->getSessionData();
        foreach ($data as $destination => $item) {
            if ($item['status'] == self::STATUS_CONFIRMED) {
                $destinations[] = $destination;
            }
        }

        return $destinations;
    }

    /**
     * Gets session data with following format
     * ```php
     * [
     * '<destination_1>' => [
     *      'codes' => [1234],
     *      'status' => 'confirmed',
     *      'last_send' => '2014-01-01 12:11:11',
     *      'count_send' => 10,
     * ],
     * //...
     * ]
     * ```
     * @return mixed
     */
    protected function getSessionData()
    {
        return $this->getSession()->get(self::SESSION_ID, []);
    }

    protected function setSessionData($data, $destination = null)
    {
        if ($destination) {
            $destinationData = $this->getDestinationData($destination);
            $destinationData = array_merge($destinationData, $data);
            $sessionData =  $this->getSession()->get(self::SESSION_ID, []);
            $sessionData[$destination] = $destinationData;
            $this->getSession()->set(self::SESSION_ID, $sessionData);
        } else {
            $this->getSession()->set(self::SESSION_ID, $data);
        }
    }

    /**
     * @return \yii\web\Session
     */
    public function getSession()
    {
        return \Yii::$app->get('session');
    }
}
 