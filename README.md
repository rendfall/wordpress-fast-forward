#FastForward

DevApp for Wordpress Theme.

#Installation

Upload `app` folder with all files into your theme folder.

#Quick start

Add these lines in top of `functions.php`.

```php
require_once(TEMPLATEPATH. '/app/fastforward.php');
```

so you can access static methods:
* FastForward::Options()
* FastForward::Menus()
* FastForward::Posts();
* FastForward::Post();
* FastForward::Widgets()
* FastForward::PostTypes()
* FastForward::Shortcodes()

#Example configuration

You can add some custom features:

```php
require_once(TEMPLATEPATH. '/app/fastforward.php');
require_once(TEMPLATEPATH. '/app/clipboard.php');

global $FF;

$FF['options'] = FastForward::Options()
    ->setTimeZone('Europe/Warsaw')
    ->setTextDomain('FastForwardTextDomain')
    ->addThemeSupport('post-thumbnails')
    ->addThemeSupport('html5')
    ->registerCss(array(
        'vendors/bootstrap.min',
        'assets/main'
    ))
    ->addGoogleFont(array(
        'family' => 'Open+Sans:400,600,700|Roboto+Slab',
        'subset' => 'latin,latin-ext'
    ))
    ->registerJs(array(
        'bootstrap.min',
        'angular'
    ), true) // place scripts in footer
    ->addTagsToPages()
    ->addCategoriesToPages()
    ->allowUnattach();


$FF['menus'] = FastForward::Menus()
    ->registerMenus(array(
        'top_menu' => 'Menu Główne',
        'sidebar_menu' => 'Menu boczne',
        'footer_menu' => 'Menu w stopce',
    ));


$FF['posts'] = FastForward::Posts();
$FF['post'] = FastForward::Post();

$FF['widgets'] = FastForward::Widgets()
    ->registerSidebar('footer-3-cols')
    ->registerWidget('foot-column');    

$FF['posttypes'] = FastForward::PostTypes()
    ->registerPostType('product');

$FF['shortcodes'] = FastForward::Shortcodes()
    ->registerShortcode('googlemap/googlemap');
```
