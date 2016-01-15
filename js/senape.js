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
        'theme': 'default',
        'title': null,
        'loadCachedTemplate': false // mandatory when loading from different domain names (cross site)
    };
    this.extend(this.settings, settings);
    this.template = Object.create(null); // a hash list for the loaded templates

    // TODO: eventually put base dir and template dir in the settings... and read it from there if they are defined
    var ownUrl = this.getScriptUrl();
    // console.log('ownUrl', ownUrl);
    this.baseDir = this.dirname(this.dirname(ownUrl));
    // console.log('baseDir', this.baseDir);
    this.templateDir = this.baseDir+'themes/'+this.settings['theme']+'/template/';

    // console.log(document.currentScript.src);
    // console.log(this.settings);
}

/**
 * start the process for filling the target dom element with the
 * comments:
 * - get the comments as json
 * - get the template(s)
 * - reference the callback that renders the template and attaches the the result to the dom
 * TODO: rename this method
 */
Senape.prototype.fillTargetWithComments = function() {
    // TODO: only get the template that are not in this.template yet
    templateList = [
        'comment-list',
        'comment-list-li'
    ];
    this.readDataAndTemplate(
        this.settings['data-url'],
        templateList,
        this.addComments.bind(this, this.settings['target-id'])
    );
}

Senape_i18n = function (settings) {
}

Senape_i18n.prototype.tr = function (text) {
    if (text) {
        return "«" + text + "»";
    } else {
        return function(text, render) {
            console.log('text', text);
            console.log('render', render);
            return "«" + text + "»";
        }
    }
}

/**
 * put the data into the mustache template and attach it to target_id
 */
Senape.prototype.addComments = function (target_id, data) {
    // console.log('target_id', target_id);
    // console.log('data', data);
    // console.log('template',this.template );
    // console.log('template[comment-list]',this.template['comment-list'] );
    console.log('template[comment-list-li]',this.template['comment-list-li'] );
    var rendered = Mustache.render(
        this.template['comment-list'],
        {
            'list': data['comment'],
            /*
            'i18n': function() {
                return function(text, render) {
                    return render(i18n.tr(text));
                };
            }
            */
            'i18n': new Senape_i18n
        },
        {'comment-list-li': this.template['comment-list-li']}
    );
    // console.log('rendered', rendered);

    var domItem = document.getElementById(target_id);
    domItem.innerHTML = rendered;
}

/**
 * @param string dataUrl the source for the data
 * @param array<string:''> templateList a list of templates to be read
 * @param function callback to the function that will consume the data and the templates
 */
Senape.prototype.readDataAndTemplate = function (dataUrl, templateList, callback) {
    this.ajax(
        dataUrl,
        'GET',
        {'senape-page-current': this.settings['page'], 'senape-site-curent': this.settings['site']},
        this.fillTemplateReceiveData.bind(this, templateList, callback)
    );
}

/**
 * receive the data and trigger the next step: getting the template
 */
Senape.prototype.fillTemplateReceiveData = function (templateList, callback, message) {
    // IE 8, firefox 3.5, safari 4.0
    message = JSON.parse(message);
    var data = message['parameter'];
    // var comment = data['comment'];
    // var comment = Object.keys(comment).map(function(k) { return comment[k] });
    // data['comment'] = comment;
    this.loadTemplate(templateList, data, callback);
}

/**
 * http://stackoverflow.com/a/950146/5239250
 */
Senape.prototype.loadTemplate = function (templateList, data, callback) {
    // TODO: first check if the template has already been read
    if (templateList.length == 0) {
        callback(data);
        return;
    }
    var template = templateList.shift();
    var url = this.getTemplatePath(template);
    if (this.settings['loadCachedTemplate']) {
        // TODO: implement a way to load templates with cross site request
        /*
        var script = document.createElement('script');
        script.type = 'text/x-mustache-template'; // does not work! in chromium only type=javascript can be loaded
        // script.type = 'text/javascript';
        script.src = url;

        // Then bind the event to the callback function.
        // There are several events for cross browser compatibility.
        script.onreadystatechange = callback;
        script.onload = callback;

        // Fire the loading
        var head = document.getElementsByTagName('head')[0];
        head.appendChild(script);
        */
    } else {
        // this will only work if senape is on the same domain as the page to be commented
        this.ajax(url, 'GET',{}, this.loadTemplateReceiveTemplate.bind(this, template, templateList, data, callback));
    }
}

Senape.prototype.loadTemplateReceiveTemplate = function (template, templateList, data, callback, message) {
    this.template[template] = message;
    this.loadTemplate(templateList, data, callback);
}

Senape.prototype.getTemplatePath = function (template) {
    return this.templateDir+template+'.mustache';
}

/**
 * General purpose tools
 */

Senape.prototype.extend = function (a, b) {
    for(var key in b) {
        if(b.hasOwnProperty(key)) {
            a[key] = b[key];
        }
    }
    return a;
}

/**
 * get the url of the script as it has been called.
 * it generates an error, catches it and relies on the fact that
 * the first url in a stack trace is the script itself.
 * (http://stackoverflow.com/a/22165218/5239250)
 */
Senape.prototype.getScriptUrl = function () {
    try {
        throw new Error();
    }
    catch(e) {
        return e.stack.replace(/\\n/g, "").match(/((http[s]?:\/\/.+\/)([^\/]+\.js)):/)[0];
    }
}

Senape.prototype.dirname = function (path) {
    return this.rtrim(path, '/').split("/").slice(0, -1).join("/")+"/";
}

/**
 * Check if the element is visible on screen
 * TODO: currently it just uses isElementInViewport to check if the
 * element is completely on screen. We need a more intelligent behavior:
 * - check if most of the element is on screen, not the full element
 * - do not check when the element cannot be fully on screen
 */
Senape.prototype.isElementEasilyVisible = function (element) {
    return this.isElementInViewport(element);
}

/**
 * http://stackoverflow.com/a/7557433/5239250
 */
Senape.prototype.isElementInViewport = function (element) {
    var rect = element.getBoundingClientRect();
    var viewport = {
        'height' : (window.innerHeight || document.documentElement.clientHeight),
        'width' : (window.innerWidth || document.documentElement.clientWidth)
    };

    return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= viewport.height &&
        rect.right <= viewport.width
    );
}



/**
 * http://stackoverflow.com/a/8141809/5239250
 */
Senape.prototype.rtrim = function (s, c) {
    for (i = s.length - 1; i >= 0; i--) {
        if (c != s.charAt(i)) {
            s = s.substring(0, i + 1);
            break;
        }
    } 
    return s;
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
            if (xmlhttp.status == 200) {
                callback(xmlhttp.responseText)
            } else {
                console.log('error status:', xmlhttp.status);
                // console.log('error:', xmlhttp.statusText);
            }
        }
    };
    xmlhttp.send(data)
}
