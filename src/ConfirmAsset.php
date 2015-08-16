<?php
namespace icron\confirm;

use yii\web\AssetBundle;

class ConfirmAsset extends AssetBundle {
    public $sourcePath = '@vendor/icron/yii2-confirm-widget/src/assets';
    public $css = [
        'css/style.css'
    ];
    public $js = [
        'js/jquery.jconfirm.js'
    ];

    public $depends = [
        '\yii\web\JQueryAsset',
        '\yii\bootstrap\BootstrapAsset',
    ];
}