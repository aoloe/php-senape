/**
 * Manage the DOM to:
 * - add the reply form to the comments
 *
 * compatibility:
 * - IE 8 (missing classList is detected)
 *   - https://gist.github.com/jonathantneal/3748027
 */

function SenapeUI(settings) {
    this.settings = {
        'target-id': "senape"
    };
    this.extend(this.settings, settings);
    this.attachReplyAction();
}

/**
 * private:
 */

SenapeUI.prototype.attachReplyAction = function() {
    senape = document.getElementById(this.settings['target-id']);
    action = senape.querySelectorAll('.senape-reply-action');
    if (this.replyAction === null) {
        this.replyAction = this.addReplyFormToComment.bind(this); // bind() returns a new function
    }
    for (var i = 0, n = action.length; i < n; i++) {
        action[i].addEventListener('click', this.replyAction, false);
    }
}

SenapeUI.prototype.addReplyFormToComment = function(event) {
    event.target.removeEventListener(event.type, this.replyAction, false); // run only once
    senape = document.getElementById(this.settings['target-id']);
    formSubmit = senape.getElementsByClassName('senape-add-comment');
    if (formSubmit.length != 1) {
        return false;
    }
    formSubmit = formSubmit[0];
    formReply = formSubmit.cloneNode(true);
    var commentId = event.target.getAttribute('data-senape-comment-id')
    var hidden = document.createElement("input");
    hidden.type = 'hidden';
    hidden.name = 'senape-form[reply-to-id]';
    hidden.value = commentId;
    formReply.appendChild(hidden);
    this.removeClass(formReply, 'senape-add-comment');
    this.addClass(formReply, 'senape-add-reply');
    var cancel = formReply.querySelectorAll('[name="senape-form[cancel]"]'); // probably not in IE9, but it's not so important...
    if (cancel.length == 1) {
        cancel[0].addEventListener('click', this.removeReplyFormFromComment.bind(this), false);
    }
    var container = this.getParentByClass(event.target, 'senape-comment');
    container.appendChild(formReply);
}

SenapeUI.prototype.removeReplyFormFromComment = function(event) {
    var container = this.getParentByClass(event.target, 'senape-comment');
    var form = this.getParentByClass(event.target, 'senape-add-reply');
    container.removeChild(form);
    this.attachReplyAction(); // TODO: make sure that it does not add a second event
}

/**
 * private:
 */

SenapeUI.prototype.extend = function (a, b) {
    for(var key in b) {
        if(b.hasOwnProperty(key)) {
            a[key] = b[key];
        }
    }
    return a;
}

SenapeUI.prototype.replyAction = null;

SenapeUI.prototype.addClass = function(element, name) {
    if (element.classList) {
        element.classList = element.classList.add(name);
    } else {
        element.className = element.className+' '+name;
    }
}

SenapeUI.prototype.removeClass = function(element, name) {
    if (element.classList) {
        element.classList = element.classList.remove(name);
    } else {
        element.className = (' '+element.className+' ').replace(' '+name+' ', ' ').trim();
    }
}

SenapeUI.prototype.hasClass = function(element, name) {
    return (' ' + element.className + ' ').indexOf(' ' + name + ' ') > -1;
}

SenapeUI.prototype.getParentByClass = function(element, name) {
    var parentNode = element.parentNode;
    if (this.hasClass(parentNode, name)) {
        return parentNode;
    } else {
        if (element.parentNode != document) {
            return this.getParentByClass(parentNode, name);
        } else {
            return false;
        }
    }
}
