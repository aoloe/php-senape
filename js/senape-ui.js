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
    // by default hide the cancel button
    // TODO: attach the reply action to each .senape-reply-action
    // TODO: or rather attach a copy of the form, leaving the original one at its place?
    console.log('target-id', document.getElementById(this.settings['target-id']));
    // TODO: how to get .senape-add-comment inside of #senape?
    console.log('form-temporary', document.getElementById('form-temporary'));
    // TODO: detach the item from its current place
    // TODO: get the data-senape-comment-id for the item where the click occured
    // TODO: show the cancel button
    // add an hidden field with the id of the comment
 


    // TODO: remove the reply-id from the form
    // TODO: remove the cancel button
    // TODO: reattach the form to the place where it was (beginning or end of the senape div)
    // TODO: if there is a use case for it, we could allow the form and the list to be in different divs
}
