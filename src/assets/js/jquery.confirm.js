+function($){
    'use strict';
    var  Confirm = function(element, options) {
        this.options = options;
    };
    // TODO
    function Plugin(options) {
        return this.each(function(){
            // TODO
        });
    }

    $.fn.confirm = Plugin;
    $.fn.confirm.Constructor = Confirm;

}(jQuery);

$('.a').confirm({});