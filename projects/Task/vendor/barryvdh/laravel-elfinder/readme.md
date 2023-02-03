## elFinder Package for Laravel 4

### Note: The package recently changed from elfinder-bundle to laravel-elfinder. The ServiceProvider has also changed. Please update your configuration and re-publish the assets.

### Installation

Add this package to your composer.json and run composer update.
Add the ServiceProvider to the providers array in app/config/app.php

    'Barryvdh\Elfinder\ElfinderServiceProvider'

You need to copy the assets to the public folder, using the following artisan command:

    php artisan asset:publish barryvdh/laravel-elfinder
	
Remember to publish the assets after each update (or add the command to your post-update-cmd in composer.json)

You can now add the routes for elFinder to your routes.php

    Route::group(array('before' => 'auth'), function()
        {
            \Route::get('elfinder', 'Barryvdh\Elfinder\ElfinderController@showIndex');
            \Route::any('elfinder/connector', 'Barryvdh\Elfinder\ElfinderController@showConnector');
        });

Offcourse you can define your own filters/routes if you want.

### Configuration

The default configuration requires a directory called 'files' in the public folder. You can change this by publishing the config file.

    php artisan config:publish barryvdh/laravel-elfinder

In your app/config/packages/barryvdh/laravel-elfinder, you can change the default folder, the access callback or define your own roots.

### TinyMCE 4.x

You can add tinyMCE integration by adding the following route:

    \Route::get('elfinder/tinymce', 'Barryvdh\Elfinder\ElfinderController@showTinyMCE4');

In the TinyMCE init code, add the following line:

```javascript
file_browser_callback : elFinderBrowser
```

Then add the following function (change the `elfinder_url` to the correct path on your system):

```javascript
function elFinderBrowser (field_name, url, type, win) {
  tinymce.activeEditor.windowManager.open({
    file: '/elfinder/tinymce',// use an absolute path!
    title: 'elFinder 2.0',
    width: 900,
    height: 450,
    resizable: 'yes'
  }, {
    setUrl: function (url) {
      win.document.getElementById(field_name).value = url;
    }
  });
  return false;
}
```
 
### TinyMCE 3.x

You can add tinyMCE integration by adding the following route:

    \Route::get('elfinder/tinymce', 'Barryvdh\Elfinder\ElfinderController@showTinyMCE');

In the TinyMCE init code, add the following line:

```javascript
file_browser_callback : 'elFinderBrowser'
```

Then add the following function (change the `elfinder_url` to the correct path on your system):

```javascript
function elFinderBrowser (field_name, url, type, win) {
  var elfinder_url = '/elfinder/tinymce';    // use an absolute path!
  tinyMCE.activeEditor.windowManager.open({
    file: elfinder_url,
    title: 'elFinder 2.0',
    width: 900,
    height: 450,
    resizable: 'yes',
    inline: 'yes',    // This parameter only has an effect if you use the inlinepopups plugin!
    popup_css: false, // Disable TinyMCE's default popup CSS
    close_previous: 'no'
  }, {
    window: win,
    input: field_name
  });
  return false;
}
```
