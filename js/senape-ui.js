function SenapeUI(settings) {
    this.settings = {
        'target-id': "senape"
    };
    this.extend(this.settings, settings);
    this.attach();
}

SenapeUI.prototype.extend = function (a, b) {
    for(var key in b) {
        if(b.hasOwnProperty(key)) {
            a[key] = b[key];
        }
    }
    return a;
}

SenapeUI.prototype.attach = function() {
    console.log(document.getElementById(this.settings['target-id']));
}
