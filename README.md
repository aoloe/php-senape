# php-senape

A lightweight commenting system:

- you can install on your own server (with a modern version of php).
- you can include through javascript or php.
- looks more like a serious commenting system than a fancy forum.

It's optimized for simple sites with a few pages and few comments per pages and self hosting.

Version 1.0 will be released as soon as it is good enough for my own current needs. Future development depends on your contribution and my future needs.

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

- in php add the form and the no comments yet through template
- in js add the form and the no comments yet through template
- add the first comment
- document the fields for the .json comments file
- get a real list of comments from the Json class
- remove index.html

- [x] create a main class
- [x] define default settings
- [x] load the local settings
- [ ] create a `senape.js` file that can be load by the client
  - [x] create a js file that is able to manipulate the dom and to get json through ajax
  - [x] return json from php
  - [ ] use a js temlate engine
    - [x] test mustache for the php side
    - [x] test mustache for the js side
    - [ ] create a template for the comment area
    - [ ] create a template for the comment
    - [ ] create a template for the comment submission
  - [ ] return the errors as json and display them.
  - [ ] dynamically add the result to the html
- [ ] return the result as list / json.
  - [x] return an empty result
  - [ ] return real comments
- [ ] add comments
  - [ ] add the first comment
  - [ ] if there is no comment, add a page (the request should state the last seen comment)
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
- [ ] how to document the default settings?
- [ ] use json or a class for the default settings?
- [ ] translation
- [ ] implement the sort algorithms
  - [ ] check the php site for the algorithm behind the + - ranking
  - [ ] find an algorithm for a  + only ranking
  - [ ] submission time only ranking
  - [ ] should the visitor be able to sort the comments?

## Possible features

- the administrator gets an email for each comment
- the administrator can moderate the comments from the email
  - through a link with a hash (action without authentication)
  - by ansering to an email conto that is read by the script
- likes & co. as plugins that can attach themselves to events?
  - it should also be possible to use plugins for the storage?
- administrate the comments from the page itself
- create a converter enabling to export the comments or switch storage engine (by site(s))
