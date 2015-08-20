[![Build Status](https://travis-ci.org/icron/yii2-confirm-widget.svg)](https://travis-ci.org/icron/yii2-confirm-widget)
[![Code Coverage](https://scrutinizer-ci.com/g/icron/yii2-confirm-widget/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/icron/yii2-confirm-widget/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/icron/yii2-confirm-widget/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/icron/yii2-confirm-widget/?branch=master)

[![Latest Version](https://img.shields.io/github/tag/icron/yii2-confirm-widget.svg?style=flat-square&label=release)](https://github.com/icron/yii2-confirm-widget/tags)
[![Software License](https://img.shields.io/badge/license-BSD-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/icron/yii2-confirm-widget.svg?style=flat-square)](https://packagist.org/packages/icron/yii2-confirm-widget)

# yii2-confirm-widget
Yii2 Confirm Widget (e.g. SMS)

For example:
```php
\icron\confirm\ConfirmWidget::widget([
            'model' => (new \app\models\TestModel()),
            'attribute' => 'phone',
            'clientOptions' => [
                'url' => \yii\helpers\Url::toRoute('site/confirm'),
                'btnSend' => '.btn-send',
                'btnConfirm' => '.btn-confirm',
                'inputCode' => '.input-code'
            ],
            'clientEvents' => [
                'send.ic.modal' => 'function(e){
                    console.log(e.confirmData);
                }',
                'confirm.ic.modal' => 'function(e){
                    var data = e.confirmData || {};
                    console.log(e.confirmData);
                    if (data["status"] == "success") {
                        document.location.href = "' . \yii\helpers\Url::toRoute('site/private') . '";
                    }

                }',
            ],
        ]); 
```

```php
    public function actionPrivate()
    {
        /** @var Confirm $confirm */
        $confirm = Yii::$app->confirm;
        return $this->render('private', ['items' => $confirm->getConfirmedDestinations()]);
    }
```