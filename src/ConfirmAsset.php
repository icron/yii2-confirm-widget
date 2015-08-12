<?php
namespace icron\confirm;

use yii\web\AssetBundle;

class ConfirmAsset extends AssetBundle {
    public $css = [
        'assets/css/style.css'
    ];
    public $js = [
        'assets/js/jquery.confirm.js'
    ];

    public $depends = [
        '\yii\web\JQueryAsset',
        '\yii\bootstrap\BootstrapAsset',
    ];
}