# Translation

Translation is a PHP class used to create or manage multilanguage sites.

## Install

```
$ composer require manujoz/translation
```

## Use

You need to create the files that will contain the translations of the texts. Normally we will use a folder called "locales".

In this folder you will create a file for each language you want the translation of.

```
|ROOT
	|_	locales
	|		   |_ en-US.php
	|		   |_ es-ES.php
	|		   |_ fr-FR.php
	|_	index.php
```

Locale file example:

<span style="font-size:12px">locales/en-US.php</span>

```php
<?php

return [
	"hello" => "Hi!",
	"bye" => "Good bye",
	"login.messages.ok" => "You are logged",
	"signup.messages.welcome" => "Welcome to my site {name}"
]

?>
```

<span style="font-size:12px">locales/es-ES.php</span>

```php
<?php

return [
	"hello" => "¡Hola!",
	"bye" => "Adios",
	"login.messages.ok" => "Estás dentro",
	"signup.messages.welcome" => "Bienvenido a mi sitio {name} ({email})"
]

?>
```

Once you have created the translation files, the use is very simple:

<span style="font-size:12px">index.php</span>

```php
<?php

use Manujoz\Translation\Translation;

require( "vendor/autoload.php" );

$TRANS = new Translation();

$TRANS->lang = "en-EN";
echo $TRANS->of( "hello" ) . "<br>";
echo $TRANS->of( "signup.messages.welcome", [ "name" => "Manu Overa", "email" => "myemail@mydomain.com" ] ) . "<br>";
echo $TRANS->of( "bye" ) . "<br>";

$TRANS->lang = "es-ES";
echo $TRANS->of( "hello" ) . "<br>";
echo $TRANS->of( "signup.messages.welcome", [ "name" => "Manu Overa", "email" => "myemail@mydomain.com" ] ) . "<br>";
echo $TRANS->of( "bye" ) . "<br>";

?>

```

## Documentation

### Constructor

When the class is initialized you can pass as a parameter the path to the folder where your translation files are located. By default it will look in the root folder for a folder called "locales", but if you prefer to save it in different folders for example "translates/section1 /", "translates/section2", you can tell the class where to look for the translation files:


```
|ROOT
	|_	translates
	|		   |_ section1
	|		   |          |_ en-US.php
	|		   |          |_ es-ES.php
	|		   |          |_ fr-FR.php
	|		   |_ section2
	|		   |          |_ en-US.php
	|		   |          |_ es-ES.php
	|		   |          |_ fr-FR.php
	|_	index.php
```

```php
<?php

use Manujoz\Translation\Translation;

require( "vendor/autoload.php" );

$TRANS = new Translation( "translates/section1" );

?>
```

Keep in mind that you will have to enter the path relative to the root folder. In this way you can create translation files by sections of your site for example or organize them as you want.

### of() method

With the **_of()_** method we perform the translations, this method admits two parameters:

```php

$TRANS->of( $key, $params = array() );

```

#### $key

It is the key of the array that will search the file to return the corresponding text.

#### $params (Optional)

It is an array with the words that can be added to a text as seen in the example above. 

### set_enclosing_chars() method

By default, in the text of the translations file, use these keys {} as encapulation characters. But if you prefer to change the encapsulation characters for some reason you can do it with the following method:

```php
<?php

$TRANS->set_enclosing_chars( [ "[[", "]]" ] );

?>
```

<span style="font-size:12px">locales/en-US.php</span>

```php
<?php

return [
	"hello" => "Hi!",
	"bye" => "Good bye",
	"login.messages.ok" => "You are logged",
	"signup.messages.welcome" => "Welcome to my site {not will be replaced} [[willBeReplaced]]"
]

?>
```

