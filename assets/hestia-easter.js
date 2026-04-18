(function () {
  var raw = localStorage.getItem('hestia_eggs');
  var found = [];
  try {
    found = raw ? JSON.parse(raw) : [];
    if (!Array.isArray(found)) {
      found = [];
    }
  } catch (e) {
    found = [];
  }

  window.hestiaEaster = {
    found: found,

    unlock: function (name) {
      if (!this.found.includes(name)) {
        this.found.push(name);
        localStorage.setItem('hestia_eggs', JSON.stringify(this.found));
      }
    }
  };
})();
