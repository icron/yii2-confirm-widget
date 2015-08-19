<?php
namespace icron\confirm;

use yii\web\AssetBundle;

class JQueryCookieAsset extends AssetBundle {
    public $sourcePath = '@bower/jquery.cookie';
    public $js = [
        'jquery.cookie.js'
    ];

    public $depends = [
        '\yii\web\JQueryAsset',
    ];
}