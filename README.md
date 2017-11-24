# Introduction #

A   simple  but   hopefully   not  trivial   example   of  a   Symfony
application.  It  is  expected  to  serve as  an  example  of  several
important and common techniques.

- HTML5 single page applications.
- Symfony form validation.
- Symfony/Doctrine entity creation and migrations.
- Ajax APIs.
- Basic bootstrap and jquery.
- i18n  and l10n using  the JMSTranslationBundle for  translations and
  JMSI18nRoutingBundle to automatically generate locale dependant URL.
-  Authentication   and  access   control  using  Symfony   roles  and
  FOSUserBundle.

# General architecture #

The application has two functionalities:

- Visualization an edition of quotes.
- Visualization an edition of customers to whome quotes might be sent.

The  application could  have been  implemented as  a single  page HTML
application.  Instead,  we  chose  to   implement  each  of  the  main
functionalities as a  separate single page HTML5  application in order
to  show how  to develop  a  hybrid (single  page HTML5/classic  HTML)
application.

The  applications is  localized  using URL  parameters  to select  the
requests locale.

The application follows in general the best practices described in the
Synfomy documentation. There are a few important exceptions:

- It avoids the use of the  ORM. Instead it uses a thin DB abstraction
  layer which directly speaks SQL.

- The  application has separate  controllers for  the GUI and  for the
  REST API. The GUI controller just  generates the HTML and serves the
  required JS  and CSS  files.  All the  data access  and modification
  operations (CRUD) go through the API controller.

- It  uses  Symfony  forms  only for  validation,  not  for HTML  form
  generation. This follows from the fact the CRUD operations don't go
  through any HTML.

The application does use doctrine entities to define the application's
data model but  only insofar they are  useful as a tool  to generate a
solid schema and the migrations to develop it incrementally. It is the
experience of  the developers that  the ORM is  more of burden  than a
blessing.

The  code  is  not  portable  across  DBMS:  the  DB  layer  is  MySQL
dependant. On the other hand,  porting the application to another DBMS
would  only require  the rewriting  of some  of the  functions of  the
Db.php file.

## Javascript ##

All the Javascript code written for the application is in AMD modules.
You will  find all most  these modules  in the web/js  directory.  The
applications uses  the require.js'  shim configuration option  to load
the external Javascript  libraries also as AMD  modules, although this
is not possible  in every instance.

The  ``base.html.twig`` defines  a few  modules used  to publish  some
information available  in the  server side of  the application  to the
client (Javascript) side:

- The current locale.

- Some translations needed by the Javascript code.

- The base URL.

The external Javascript libraries reside in web/lib.

# Publishing with Apache #

For Apache  2.4 just add  this block inside the  ``VirtualHost`` block
you like.

```
Alias /quotes2017 <path to the project directory>/web
<Directory /var/www/quotes2017/web>
    Require all granted
    <IfModule mod_rewrite.c>
        Options -MultiViews
        RewriteEngine On
        # not required in all Apache versions, by doesn't hurt either
        RewriteBase /quotes2017
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteRule ^(.*)$ app.php [QSA,L]
    </IfModule>
</Directory>
```
