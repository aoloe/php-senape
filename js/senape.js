/**
 * js-doc syntax: https://developers.google.com/closure/compiler/docs/js-for-compiler
 *                https://en.wikipedia.org/wiki/JSDoc
 */
function Senape(settings) {
    this.settings = {
        'target-id': "senape",
        'data-url': null,
        'page': '',
        'site': '',
        title: null
    };
    this.extend(this.settings, settings);
    this.attach();
    this.ajax(this.settings['data-url'], 'GET', {page: this.settings['page'], site: this.settings['site']}, (function(message) {this.fillit(message);}).bind(this));
    // console.log(document.currentScript.src);
    console.log(this.settings);
}

Senape.prototype.fillit = function(message) {
    console.log('settings:', this.settings);
    console.log('message:', message);
    // IE 8, firefox 3.5, safari 4.0
    message = JSON.parse(message);
    console.log('parameter:', message['parameter']);
}

Senape.prototype.attach = function() {
    console.log(document.getElementById(this.settings['target-id']));
}

Senape.prototype.extend = function (a, b) {
    for(var key in b) {
        if(b.hasOwnProperty(key)) {
            a[key] = b[key];
        }
    }
    return a;
}

/**
 * http://stackoverflow.com/a/18078705/5239250 by Petah / Donal Lafferty
 * IE7+, Firefox, Chrome, Opera, Safari
 * @param {string} url
 * @param {string} method (POST/GET)
 * @param {json} data
 * @param {function} callback
 * @param {?} sync
 */
Senape.prototype.ajax = function (url, method, data, callback) {
    if (!window.XMLHttpRequest) {
        // TODO: manage IE too old error
    }
    var xmlhttp = new XMLHttpRequest();

    var query = [];
    for (var key in data) {
        query.push(encodeURIComponent(key) + '=' + encodeURIComponent(data[key]));
    }

    if (method === 'POST') {
        xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        data = query.join('&');
    } else if (method === 'GET') {
        url = url + (query.length ? '?' + query.join('&') : '');
        data = null;
    }
    
    xmlhttp.open(method, url);
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == XMLHttpRequest.DONE) {
            callback(xmlhttp.responseText)
        }
    };
    xmlhttp.send(data)
}
