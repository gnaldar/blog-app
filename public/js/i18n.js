(function () {
  'use strict';

  window.t = function (key, vars) {
    var locale = window.LOCALE || {};
    var str    = Object.prototype.hasOwnProperty.call(locale, key) ? locale[key] : key;

    if (vars) {
      Object.keys(vars).forEach(function (k) {
        str = str.replace(new RegExp('\\{' + k + '\\}', 'g'), vars[k]);
      });
    }

    return str;
  };
})();