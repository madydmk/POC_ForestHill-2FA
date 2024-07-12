var forestHill = forestHill || {};

forestHill.consts = (function($) {
    "use strict";
    var module = {};
    var $consts = {
        'bpMobile' : 320,
        'bpTablet' : 600,
        'bpDesktop' : 980,
        'bpWide' : 1250
    };

    module.getValue = function(value) {
        return $consts[value];
    };

    return module;
})(jQuery);