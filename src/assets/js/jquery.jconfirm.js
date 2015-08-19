+function($){
    'use strict';
    var  Confirm = function(element, options) {
        this.options = options;
        this.$element = $(element);
        this.$btnSend = this.$element.parent().find(options.btnSend);
        this.$btnConfirm = this.$element.parent().find(options.btnConfirm);
        this.$inputCode = this.$element.parent().find(options.inputCode);
        this.$msg = $(this.options.msgBox || '.inform');
    };

    Confirm.DEFAULTS = {
        'url': '',
        'btnSend': '',
        'btnConfirm': '',
        'inputCode': '',
        'lifeTimeCookie': 86400
    };

    Confirm.prototype.send = function(destination)
    {
        var self = this,
            ajaxData = {'method': 'send', 'destination': destination};
        self.message('Пожалуйста подождите, идет отправка.');
        $.ajax({
            url: self.options.url,
            type: 'get',
            data: ajaxData,
            success: function(data){
                if(data['status'] == 'success') {
                    var cookie = self.getCookie();
                    cookie['send'].push(destination);
                    $.cookie('icron.confirm', cookie, { expires: self.lifeTimeCookie });
                    self.toggleConfirm(true);
                }
                self.$element.trigger($.Event('send.ic.modal', {'confirmData': data}));
                self.message(data['message']);
            },
            error: function(){
                self.message('На сервере произошла ошибка, попробуйте еще раз.');
            },
            dataType: 'json'
        });
    };

    Confirm.prototype.confirm = function(destination, code){
        var self = this,
            ajaxData = {'method': 'confirm', 'destination': destination, 'code': code};
        $.ajax({
            url: self.options.url,
            type: 'get',
            data: ajaxData,
            success: function(data){
                if(data['status'] == 'success') {
                    var cookie = self.getCookie();
                    cookie['confirm'].push(destination);
                    $.cookie('icron.confirm', cookie, { expires: self.lifeTimeCookie });
                }
                self.$element.trigger($.Event('confirm.ic.modal', {'confirmData': data}));
                self.message(data['message']);
            },
            dataType: 'json'
        });
    };

    Confirm.prototype.message = function(msg){
        this.$msg.text(msg);
    };

    Confirm.prototype.getCookie = function(){
        return $.cookie('icron.confirm') || {'send': [], 'confirm': []};
    };

    Confirm.prototype.toggleConfirm = function (toggleCondition) {
        this.$btnConfirm.toggle(toggleCondition);
        this.$inputCode.toggle(toggleCondition);
    };

    function Plugin(option) {
        return this.each(function(){
            var $this = $(this),
                data = $this.data('jconfirm'),
                options = $.extend({}, Confirm.DEFAULTS, typeof option == 'object' && option),
                $inputCode = $this.parent().find(options.inputCode);

            if (!data) {
                $this.data('jconfirm', (data = new Confirm(this, options)));
                var cObj = data.getCookie();
                $this.on('keyup', function(e){
                    data.toggleConfirm(cObj['send'].indexOf($(this).val()) !== -1);
                }).keyup();

                $this.parent().find(options.btnSend).on('click', function(e){
                    e.preventDefault();
                    $this.data('jconfirm').send($this.val());
                });

                $this.parent().find(options.btnConfirm).on('click', function(e){
                    e.preventDefault();
                    $this.data('jconfirm').confirm($this.val(), $inputCode.val());
                });
            }
            if (typeof option == 'string') {
                data[option]();
            }
        });
    }

    $.cookie.json = true;
    $.fn.jconfirm = Plugin;
    $.fn.jconfirm.Constructor = Confirm;

}(jQuery);