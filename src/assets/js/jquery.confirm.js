+function($){
    'use strict';
    var  Confirm = function(element, options) {
        this.options = options;
    };

    Confirm.DEFAULTS = {
        'url': '',
        'btnSend': ''
    };

    Confirm.prototype.send = function()
    {
        $.ajax({
            url: this.options.uri + '?method=send',
            success: function(data){
                if(data['status'] == 'success') {
                    alert('Успешно!');
                }
            },
            dataType: 'json'
        });
    };

    Confirm.prototype.confirm = function(){
        $.ajax({
            url: this.options.uri + '?method=confirm',
            success: function(data){
                if(data['status'] == 'success') {
                    alert('Успешно!');
                }
            },
            dataType: 'json'
        });
    };

    function Plugin(options) {
        return this.each(function(){
            var $this = $(this);
            var data = $this.data('confirm');
            var options = $.extend({}, Confirm.DEFAULTS, typeof options == 'object' && options);
            if (!data) {
                $this.data('confirm', (data = new Confirm(this, options)));
                $this.parent().find(options.btnSend).on('click', function(e){
                    $this.data('confirm').send();
                    e.preventDefault();
                });
            }
            if (typeof options == 'string') {
                data[options]();
            }
        });
    }

    $.fn.confirm = Plugin;
    $.fn.confirm.Constructor = Confirm;

}(jQuery);