# php-senape

A lightweight commenting system:

- you can install on your own server (with a modern version of php).
- you can include through javascript or php.
- looks more like a serious commenting system than a fancy forum.

It's optimized for simple sites with a few pages and few comments per pages and self hosting.

Currently you can add comments and retrieve them in a simple list from a PHP script.

Version 1.0 will be released as soon as it is good enough for my own current needs. Further development depends on your contribution and my future needs.

Verwsion 2.0 will be released as soon as all the features marked in the todo list below will be implemented (of course, the list of todos is not definitive).

## Tutorial

### Add Senape to your PHP application

#### Getting Senape

- Adding it to your application trough composer
- Downloading a `.zip`

#### Ading Senape to a single page

This is the most simple case, but probably also the least useful one.

~~~php
// some php code
~~~

#### Using a commong Senape setup for multiple pages

In most cases, you want to share the same setup among multiple pages and only duplicate the few lines of code that instantiating Senape and defining the current page.

~~~php
// some php code
~~~

Of course you can also get your own Senape class to guess the current page from the url and get to the very simple call:

~~~php
// some php code
~~~


### Create your own Senape server and use it through Javascript


### Peculiarities

- Senape should be rendered in its own DOM element with an id that you pass to the javascript classes.

## Contributing

For now, the best you can do is to have a look at the `TODO:` in the code.

Second best is choosing one of the _unchecked_ items in the todo list below.

On top of it we could need:

- fancy themes,
- some documentation and tutorials,
- unit testing.

Before working on a feature, please add a matching ticket in the issue tracker and announce that you will be working on it. Then fork the repository, create a new branch and make a pull request when you're done.

## Implementation details

### Javascript support

On the Javascript side, Senape does not depend (for now) on any Framework.

It should be possible to get Senape to work with any browser. This means that you can install and configure Senape in a way that all visitor will be able to submit comments.

But many non basic features that rely on Javascript will need a modern browser:

- Microsoft Interent Explorer 9
- A not yet specified modern vesion of Chrome, Firefox, Opera, or Safari.

Supporting older browsers -- eventually by using a framework like JQuery -- is possible but is not planned for now.

### Submitting a comment

- The "Add comment" form can be submitted only once and no second chance is given to change its content.
- All the validation must be performed before submission through javascript.
- On the PHP side, invalid comments are ignored (discarded) and an error message is returned.

Further ideas:

- Allow the user to change his own comment by adding a cookie with the hash of the comment.
- Eventually allow a login mechanism
- Eventually store the user credentials (name, email, website) and preferences (subscribe to the replies) in the cookie on a per site basis.
- Comments can only be edited through javascript "in place" where the comment is shown.
- If there is a way to login, the login form should be separated form the "Add comment" one.

## Why not Hashover

For a long time, I've been looking for a commenting system that is not bound to any proprietary service and can be installed on (pretty) any cheap shared hosting.

The day I discovered Hashover was a really happy day. A first look into its sources was not such a happy experience but, after a few clicks, I also discovered that Hashover-Next was in the workings and it looked much better.

I did setup a local demo site and it was pleasant to use. I've started configuring it to work with my sites and submitted a few patches. But I kept stepping into what are for me unusual choices made by the author of Hashover. While working on a simpler Json storage engine, I started looking for a workaround to the unusual comment-id sequence used in Hashover, and I said to myself: wouldn't it be easier to start from scratch?

Senape is my try at providing a commenting service. Deeply inspired by Hashover, but with no code borrowed from it (both because of the very different coding style and the incompatible license).

Here is a list of what I feel as the main difference between Senape and Hashover (as I know it):

- Using a liberal license.
- Keep dependencies to a minimum instead of refusing to have any.
- No generated javascript.
- Using Composer and Packagist.
- Following the PSR standards.
- All HTML generated through templates.
- Use the same code for generating js and php comments.
- Does not try to guess what is happening: always rely on explicit commands.
- Javascript only works with modern browsers.

Anyway, I've learned much from Hashover and I'm grateful for all the work Jacob has done!

## Todo

Short term tasks:

- why  it is impossible to submit the reply form? (on submit i get a "received no data" error from the server)
- in js add the form and the comments through template
  - the api should only return in the list the fields that are meant to be shown (no hash, email, ...)
- make sure that the comments do not contain invalid markup
- add the comments to a list of n latest comments (to be shown to the moderator)
- document the fields for the .json comments file
- when in js mode, do not submit the form but simply send the data through ajax.
  - optionally, allow the ajax submit also when in php mode (does it make sense?)
- add `sample.php` and `senape.php` as samples to the repository
- add a `render()` method that shows the list and the form and respects the order from the settings

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
   - [x] create a template for the comments list
   - [x] create a template for the comment
   - [x] render the indented replies in mustache
     - http://stackoverflow.com/questions/31885263/mustache-js-how-to-create-a-recursive-list-with-an-unknown-number-of-sub-lists
   - [ ] dynamically add the result to the html
   - [x] if the settings say so, add labels to the input boxes
- [ ] add comments
  - [x] add the first comment
  - [ ] for each first comment on the page, add the page information in the json file (the request should state the last seen comment)
  - [x] make sure that json the file is locked (for read/write) between read and write
    - only block user that want to read for writing
    - http://stackoverflow.com/questions/2450850/read-and-write-to-a-file-while-keeping-lock
  - [ ] setting for the filesystem access rights to the generated files
  - [x] store the comments as json
  - [ ] store the comments as mysql
  - [ ] create permalinks for the comments
    - [x] create the placeholder in the template
    - [ ] create a permalink
  - [ ] allow comments editing
    - in place through javascript (in the same ways as for the replies)
    - only if a specific cookie is set on the visitor computer
    - eventually through an email they get
- [ ] filter the comments for invalid content
  - simply use DOMDocument and the XML parser to simply discard all HTML tags?
- [x] add the avatars
  - [x] show the own avatar or the gravatar by email
  - [x] use the settings for the avatars
  - [x] correctly detect and use https
  - [ ] if the name starts with '@' look for a twitter avatar
    - https://dev.twitter.com/rest/reference/get/users/show
    - needs the comments service to be registered with Twitter
  - [ ] find a way to display the rules for the avatars
- [ ] allow replies
  - [x] store the replies
   - [x] create the javascript for moving around the form
   - [x] add a "reply to" hidden field in the form
   - [x] store the reply coming from the form
- [x] render the widgets from php
- [ ] enable the likes
  - [ ] show the likes when enabled
  - [ ] show the count of likes
  - [ ] eventually list who liked
- [ ] translation
  - [x] create a translation class
  - [ ] create and load the translation files
  - [ ] use the same code schema as Scribus (de, de-CH with a full translation for each)
  - [ ] the translation files should contain all the strings (if not translated then in english)
  - [ ] allow the user to add his own translation for strings he sets in the settings
  - [ ] import all translation files from hashover (public domain)
  - [ ] replace the keys in the translation files with the string in the code
  - [ ] add a `tr_count()` function that respect the numerals
- [ ] handle errors
  - [ ] show the errors from php.
  - [ ] return the errors as json and display them.
  - [x] errors are handled by throwing an exception.
  - [x] if a module wants, it can try to catch it, otherwise the main module builds an errors list with the one that have to be reported to the end user.
  - [ ] the error are translatable.
  - [ ] the errors can be logged.
  - [ ] the renderer gets the public error list and renders them.
- [ ] return the result as a list / as json.
  - [x] return an empty result
  - [x] read the comments as json
  - [ ] read the comments as mysql
  - [x] return real comments
- [ ] send notifications
  - [ ] only allow to get notifications if the email field is filled (javascript mandatory)
- [ ] spam protection
  - [ ] use fake hidden fields that only get filled by bots?
- [ ] add an http router/controller for processing the requests
- [ ] add a simple administration interface
  - [ ] show the url to be inserted in the javascript call
  - [ ] show the latest comments
  - [ ] allow moderation of comments by site and page
  - [ ] add an allowed site (with aliases) / an allowed base path
  - [ ] moderation through email
    - needs the message hash and an hash specific to the moderator (or we create two hashes, one for edit and one for moderation)
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
- [ ] recover when the json files are invalid
  - [ ] comments file
  - [ ] settings file
- [ ] in the "add comments" form, add a way to set hidden fields to be filled with the state of the hosting framework (`page_id`, ...)
- [ ] add a backlink to Senape (site? +) github

## Possible features

- the administrator can moderate the comments from the email
  - through a link with a hash (action without authentication)
  - by ansering to an email conto that is read by the script
- likes & co. as plugins that can attach themselves to events?
  - it should also be possible to use plugins for the storage?
- administrate the comments from the page itself
- create a converter enabling to export/backup the comments or switch storage engine (by site(s))
- implement the storage as a plugin reacting to events (and let people add their own storage engines)
