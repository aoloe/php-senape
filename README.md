# php-senape

A lightweight commenting system:

- that you can install on your own server (with a modern version of php)
- that you can include through javascript or php 

## why not hashover

- using a liberal license
- no generated js
- composer and packagist
- PSR-? standard
- all HTML generated through templates
- same code for generating js and php comments
- does not try to guess what is happening: always uses explicit commands
- js only works with modern browsers

## Todo

short term task:

- document the fields for the .json comments file
- get a real list of comments from the Json class
- in js get the json to be evaluated without the \"s
- move the settings ind index.php to a safer place and remove index.php
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
  - [ ] return the result as json.
  - [ ] dynamically add the result to the html
- [ ] store the comments as json
- [ ] store the comments as mysql
- [ ] add an http router/controller for processing the requests
- [ ] add a simple administration interface
  - [ ] show the url to be inserted in the javascript call
  - [ ] show the latest comments
  - [ ] allow moderation of comments by page
  - [ ] add an allowed site (with aliases) / an allowed base path
- [ ] add an installer
- [ ] how to document the default settings?
- [ ] use json or a class for the default settings?

## Possible features

- the administrator gets an email for each comment
- the administrator can moderate the comments from the email
  - through a link with a hash (action without authentication)
  - by ansering to an email conto that is read by the script
