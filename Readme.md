# Geneo Blog Test

## Written in Symfony ❤️

## Tasks

- User authentication to allow a user to login and logout and ensure that only the logged in user can create, edit and delete their own posts.
- It should be clear on the list of posts and also when viewing the post itself who authored each post.
- There should be a page to show all users and the number of posts each user has authored should also be listed here.
- Users should be able to comment on their own and other user's posts.
- There should be two user types with different access levels: 
  - Admin users who can manage the access level of all other users. They should be able to toggle the access that other specific users have to certain features. We would like you to include toggles for the ability to comment on posts, the ability to create posts and any other features you think should be turn-off able. Admin users should also be able to promote other users to admins.
  - Basic users whose access permissions are determined by the configuration options selected by an admin user.

## How to setup

- clone repo
- composer install
- set 'DATABASE_URL' in .env.local file and .env.test file for the test
- run ```php bin/console doctrine:migrations:migrate``` to run migration and add ```--env=test``` flag for test migration
- run ```php bin/console doctrine:fixtures:load``` to load data fixtures add the test env flag to load for test db
- an admin account is setup already with detail
  - email: admin@geneo.com
  - password: password
- to run tests ```php bin/phpunit```


## Routes

- ```/login``` to login
- ```/logout``` to logout
- ```/register``` to create a user
- ```/dashboard``` to view posts authored by the loggedin user
- ```/dashboard/create``` to create a new post *** only logged in users can hit this route
- ```@{author}/{post-slug}``` to view a created post
- if user is authorised to edit post, you should have a dropdown menu on the right, populated with edit post, and delete post on the post page
- ```@{author}/{post-slug}``` at the bottom of every post you have a form to post comment
- ```/admin``` route is for admin users; it lists all the users and the posts count
- ```/admin/{user_id}``` clicking the manage user on the ```/admin``` page would load user management page to manage user permissions


### Notes

- There are only two user roles, admin and basic user. You can switch user role by going to the admin manage user page
- User's have permission, you can view user's permission on the admin manage user page. 
- Permissions are scopes binding user's access to some features an example is the <b>POST<b> entity for CRUD, you'd need create permission, view permission, update permission and delete permission which are stored as ```post.create```, ```post.view```, ```post.delete```, ```post.edit```. A similar example would the <a href="https://oauth.net/2/scope/"><strong> Oauth Scope </strong></a>
- You can also toggle user permissions (at the moment you can only toggle two permissions, ```post.create``` and ```comment.create```)
- Test Coverage is 60%
- To dockerise the app follow this tutorial <a href="https://knplabs.com/en/blog/how-to-dockerise-a-symfony-4-project">Link</a>
