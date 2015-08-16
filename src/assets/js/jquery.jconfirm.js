+function($){
    'use strict';
    var  Confirm = function(element, options) {
        this.options = options;
        this.$element = $(element);
        this.$msg = $(this.options.msgBox || '.inform');
    };

    Confirm.DEFAULTS = {
        'url': '',
        'btnSend': '',
        'btnConfirm': '',
        'inputCode': ''
    };

    Confirm.prototype.send = function(destination, _relatedTarget)
    {
        var self = this,
            e = $.Event('send.ic.modal', {relatedTarget: _relatedTarget});
        $.ajax({
            url: this.options.url,
            type: 'get',
            data: {'method': 'send', 'destination': destination},
            success: function(data){
                if(data['status'] == 'success') {
                }
                self.message(data['message']);
                self.$element.trigger(e);
            },
            dataType: 'json'
        });
    };

    Confirm.prototype.confirm = function(destination, code, _relatedTarget){
        var self = this,
            e = $.Event('confirm.ic.modal', {relatedTarget: _relatedTarget});
        $.ajax({
            url: this.options.url,
            type: 'get',
            data: {'method': 'confirm', 'destination': destination, 'code': code},
            success: function(data){
                if(data['status'] == 'success') {

                }
                self.message(data['message']);
                self.$element.trigger(e);
            },
            dataType: 'json'
        });
    };

    Confirm.prototype.message = function(msg){
        this.$msg.text(msg);
    };

    function Plugin(option, _relatedTarget) {
        return this.each(function(){
            var $this = $(this),
                data = $this.data('jconfirm'),
                options = $.extend({}, Confirm.DEFAULTS, typeof option == 'object' && option),
                $inputCode = $this.parent().find(options.inputCode);

            $inputCode.hide();
            if (!data) {
                $this.data('jconfirm', (data = new Confirm(this, options)));
                $this.parent().find(options.btnSend).on('click', function(e){
                    e.preventDefault();
                    $this.data('jconfirm').send($this.val(), _relatedTarget);
                });

                $this.parent().find(options.btnConfirm).on('click', function(e){
                    e.preventDefault();
                    $this.data('jconfirm').confirm($this.val(), $inputCode.val(), _relatedTarget);
                });
            }
            if (typeof option == 'string') {
                data[option]();
            }
        });
    }
    $.fn.jconfirm = Plugin;
    $.fn.jconfirm.Constructor = Confirm;

}(jQuery);