<?php

namespace icron\confirm;

use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;

class ConfirmWidget extends InputWidget
{
    public $clientOptions = [];

    public $clientEvents = [];

    public function run()
    {
        if (empty($this->options['class'])) {
            $this->options['class'] = '';
        }
        $this->options['class'] .= ' btn btn-lg';

        if ($this->hasModel()) {
            $field = Html::activeTextInput($this->model, $this->attribute, $this->options);
        } else {
            $field = Html::textInput($this->name, $this->value, $this->options);
        }

        echo $this->renderTemplate($field);
        $this->registerClientScript();
    }

    public function renderTemplate($field)
    {
        $view = $this->getViewPath() . '/confirm.php';
        return $this->getView()->renderFile($view, ['field' => $field]);
    }

    public function registerClientScript()
    {
        $view = $this->getView();
        ConfirmAsset::register($view);
        $id = $this->options['id'];
        // ['a' => 1]
        // {'a' : 1}
        $options = !empty($this->clientOptions) ? Json::encode($this->clientOptions) : '';
        $js[] = ";jQuery('#$id').confirm({$options});";
        if (!empty($this->clientEvents)) {
            foreach ($this->clientEvents as $event => $handler) {
                $js[] = ";jQuery('#$id').on('$event', $handler);";
            }
        }
        $view->registerJs(implode("\n", $js));
    }
}
