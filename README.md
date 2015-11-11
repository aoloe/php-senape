# php-senape

A lightweight commenting system:

- you can install on your own server (with a modern version of php).
- you can include through javascript or php.
- looks more like a serious commenting system than a fancy forum.

It's optimized for simple sites with a few pages and few comments per pages and self hosting.

Version 1.0 will be released as soon as it is good enough for my own current needs. Future development depends on your contribution and my future needs.

## Implementation details

### Submitting a comment

- The "Add comment" form can be submitted only once and no second chance is given to change its content.
- All the validation must be performed before submission through javascript.
- On the PHP side, invalid comments are ignored (discarded) and an error message is returned.

Further ideas:

- Allow the user to change his own comment by adding a cookie with the hash of the comment.
- Eventually allow a login mechanism
- Eventually store the user credentials (name, email, website) and preferences (subsribe to the replies) in the cookie on a per site basis.
- Comments can only be edited through javascript "in place" where the comment is shown.
- If there is a way to login, the login form should be separated form the "Add comment" one.

## Why not Hashover

I've been looking for long time for a commenting system that is not bound to any proprietary servirce and can be installed on (pretty) any cheap shared hosting.

The day I discovered Hashover was a really happy day. Well a first look into the sources was not such a happy experience, but after a few clicks, I also disvered that Hashover-Next was in the workings and it looked much better.

I did setup a local demo site and it was pleasant to use. I've started configuring it to work with my sites and submitted a few patches. But I kept stepping into what are for me unusual choices that the author of Hashover has made. After having started programming a simpler json storage engine and having spent some time on a workaround to the unusual comment-id sequence uses in Hashover, I said to myself: wouldn't it be easies to start from scratch?

Here is a list of what I feel as the main difference between Senape and Hashover (as I know it):

- Using a liberal license (and not asking for attribution for all changes).
- Keep dependencies to a minimum but without refusing to have any.
- No generated javascript.
- Using Composer and Packagist.
- Following the PSR standards.
- All HTML generated through templates.
- Use the same code for generating js and php comments.
- Does not try to guess what is happening: always uses explicit commands.
- Javascript only works with modern browsers.

Still, I've learned much from Hashover and I'm grateful for all the work Jacob has done!

## Todo

Short term tasks:

- in js add the form and the no comments yet through template
- add the first comment
- document the fields for the .json comments file
- get a real list of comments from the Json class
- remove index.html
- when in js mode, do not submit the form but simply send the data through ajax.

- [x] create a main class
- [x] define the settings
  - [x] define default settings
  - [x] load the local settings
- [ ] create a `senape.js` file that can be load by the client
  - [x] create a js file that is able to manipulate the dom and to get json through ajax
  - [x] return json from php
  - [ ] use a js template engine
- [ ] create the main widgets
    - [x] test mustache for the php side
    - [x] test mustache for the js side
    - [x] create a template for the comment submission area
    - [ ] display the form before or after the list (settings)
    - [ ] dynamically add a form / move the form below the comment when in reply mode (js mandatory)
      - [ ] create the javascript to move / add the form
      - [ ] add a "reply to" hidden field in the form
    - [ ] create a template for the comments list
    - [ ] create a template for the comment
    - [ ] dynamically add the result to the html
    - [x] if the settings say so, add labels to the input boxes
- [x] render the widgets from php
- [ ] translation
  - [x] create a translation class
  - [ ] create and load the translation files
  - [ ] use the same code schema as Scribus (de, de-CH with a full translation for each)
  - [ ] the translation files should contain all the strings (if not translated then in english)
  - [ ] allow the user to add his own translation for strings he sets in the settings
  - [ ] import all translation files from hashove (public domain)
  - [ ] replace the keys in the translation files with the string in the code
  - [ ] add a `tr_count()` function that respect the numerals
- [ ] handle errors
  - [ ] show the errors from php
  - [ ] return the errors as json and display them.
- [ ] return the result as a list / as json.
  - [x] return an empty result
  - [ ] read the comments as json
  - [ ] read the comments as mysql
  - [ ] return real comments
- [ ] add comments
  - [ ] add the first comment
  - [ ] only allow to get notifications if the email field is filled (javascript mandatory)
  - [ ] for each first comment on the page, add the page information in the json file (the request should state the last seen comment)
  - [ ] make sure that json the file is locked (for read/write) between read and write
    - others should not fail when the file is currently locked
    - http://stackoverflow.com/questions/2450850/read-and-write-to-a-file-while-keeping-lock
  - [ ] spam protection
    - [ ] use fake hidden fields that only get filled by bots?
  - [ ] store the comments as json
  - [ ] store the comments as mysql
- [ ] add an http router/controller for processing the requests
- [ ] add a simple administration interface
  - [ ] show the url to be inserted in the javascript call
  - [ ] show the latest comments
  - [ ] allow moderation of comments by site and page
  - [ ] add an allowed site (with aliases) / an allowed base path
- [ ] add an installer
  - [ ] make sure that `data/` exists and is writable (if there is content it should also be writable)
  - [ ] create the mysql structure
  - [ ] check that the translation files are complete
- [ ] improve the settings management
  - [ ] how to document the default settings?
  - [ ] use json or a class for the default settings?
- [ ] implement the sort algorithms
  - [ ] check the php site for the algorithm behind the + - ranking
  - [ ] find an algorithm for a  + only ranking
  - [ ] submission time only ranking
  - [ ] should the visitor be able to sort the comments?
- [ ] add the avatars
  - [ ] find a way to display the rules for the avatars
  - [ ] if the name starts with '@' look for a twitter avatar
  - [ ] if the email is in open thing, display the related avatar

## Possible features

- the administrator gets an email for each comment
- the administrator can moderate the comments from the email
  - through a link with a hash (action without authentication)
  - by ansering to an email conto that is read by the script
- likes & co. as plugins that can attach themselves to events?
  - it should also be possible to use plugins for the storage?
- administrate the comments from the page itself
- create a converter enabling to export the comments or switch storage engine (by site(s))
