<?php
namespace icron\confirm;

use icron\confirm\providers\IProvider;
use yii\base\Component;

class Confirm extends Component
{
    const SESSION_ID = '';
    public $provider;
    /** @var  IProvider */
    private $_provider;

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
        //TODO Real data
        $destinationData = []; // Получение данных из сессии по $destination
        //TODO Real data
        $codes = []; // коды из сесси
        $data['last_send'] = date('Y-m-d H:i:s');
        $countSend = isset($destinationData['count_send']) ? $destinationData['count_send'] : 0;
        $data['count_send'] = $countSend + 1;
        $data['status'] = '';
        $code = $this->generateCode();
        if ($this->_provider->send($destination, $code)) {
            $data['codes'] = array_merge($codes, [$code]);
            //TODO Save data to session
            return true;
        }
        //TODO Update data session
        return false;
    }

    // TODO метод подтверждения кода confirm

    // TODO Вспомогтальеные методы getCodes
    //
}
 