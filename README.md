# Czech Vocative Plugin for Mautic

A plugin for converting names to their vocative form in Czech, enabling personalized email greetings and dynamic content generation within Mautic.

This plugin, **Granam Czech Vocative**, has been tested and is compatible with **Mautic 5.1.1**. It is expected to work with other versions of Mautic 5 as well. Please follow the installation instructions carefully to ensure the plugin functions correctly.

If you are looking for compatibility with **Mautic 4** or earlier versions, please visit the original plugin repository here: [Granam Mautic Czech Vocative Plugin](https://github.com/granam/mautic-czech-vocative).



---

# Usage

In your Mautic insert into an email template this shortcode around *some name*
`[some name|vocative]`

- for example `[Karel|vocative]`
- or better example `[{leadfield=firstname}|vocative]`  
  hint: use `CTRL+SHIFT+V` to insert copied text without formatting, also check source code of your email template by
  ![Mautic source code icon](https://raw.githubusercontent.com/mautic/mautic/4.3.1/app/bundles/CoreBundle/Assets/js/libraries/ckeditor/plugins/sourcedialog/icons/sourcedialog.png)
  button for unwanted formatting
- also foreign and non-human names are converted to czech form `[Cassandra|vocative]` = `Cassandro`
  , `[android|vocative]` = `Androide`
- you can use it even in Subject of your email (unlike other shortcodes).
- **always test your email before sending it to real people**

### Aliases

You can also set aliases to be used (and vocalized) instead of the name.

- `[{leadfield=firstname}|vocative(sirius,andromeda,fill your name plase!)]` leading into
    - if `firstname` is male, let's say Roman, the result is `Siriusi`
    - if `firstname` is female, for example Gloria, the result is `Andromedo`
    - if `firstname` is empty, or from white characters only respectively, the result is `Fill your name please!`
- if you omit one of gender-dependent alias, the original name is used
    - `[richard|vocative(,For gentlemen only!)]` = `Richarde`
    - `[monika|vocative(,For gentlemen only!)]` = `For gentlemen only!` (because of the trailing non-character the
      string is untouched)
    - `[  |vocative(Karel,Monika)]` = ``
    - `[  |vocative(Karel,Monika,Batman)]` = `Batmane`

### Dynamic Web Content support

Thanks to [Zdeno Kuzmany](https://github.com/kuzmany/)
the [Dynamic Web Content](https://mautic.org/docs/en/dwc/index.html) is also supported and processed by vocative.

# Install

1. Let it install by `composer require granam/mautic-czech-vocative-bundle`
2. Clear Mautic cache by `./app/console cache:clear` or just delete the `app/cache` dir.
    - note: In some cases, not yet fully understood, the cache is not rebuilt fully automatically.
      In case of fatal error because of expected but missing file in the cache, rebuilt it manually:
        - `./app/console cache:warmup --no-optional-warmers`
3. Log in to your Mautic as an admin, open cogwheel menu in the right top corner and choose *Plugins*
4. Click *Install/Upgrade Plugins*

If everything goes well, you got new plugin *GranamVocative*.

### Additional Installation Steps

1. Modify the `composer.json` file in the root directory of your Mautic installation to include the dependency for `granam/czech-vocative`:
   ```json
   "require": {
       "composer/installers": "^1.11",
       "mautic/core-lib": "^5.0",
       "granam/czech-vocative": "^2.2"
   }
   ```
2. Navigate to the root directory of your Mautic installation:
   ```bash
   cd /path/to/your/mautic/root
   ```
3. Update dependencies:
   ```bash
   composer update granam/czech-vocative
   ```
4. Clear the cache and reload the plugins:
   ```bash
   sudo /usr/bin/php /path/to/your/mautic/root/bin/console cache:clear
   sudo /usr/bin/php /path/to/your/mautic/root/bin/console mautic:plugins:reload
   ```

### Verify Installation

- Ensure the plugin is installed correctly and verify there are no errors in the logs.
- If you encounter a `ClassNotFoundError` for `Granam\CzechVocative\CzechName`, confirm that the package is installed correctly in the root vendor folder.

### Important Notes

- Avoid keeping a separate `vendor` folder inside the plugin directory. Always rely on Mautic's root `vendor` folder to avoid conflicts.
- Use the root `autoload.php` in your plugin bundle class as shown below:

```php
namespace MauticPlugin\GranamCzechVocativeBundle;

use Mautic\PluginBundle\Bundle\PluginBundleBase;

class GranamCzechVocativeBundle extends PluginBundleBase
{
    public function boot()
    {
        // Use Mautic root autoload
        if (file_exists(__DIR__ . '/../../vendor/autoload.php')) {
            require_once __DIR__ . '/../../vendor/autoload.php';
        }
    }
}
```

This ensures the plugin can access shared dependencies from Mautic's root installation.

## Compatibility

### Mautic v4.* and v5.*

- Tested with Mautic 5.1 and compatible with Mautic 4.*

- If you are looking for compatibility with **Mautic 4** or earlier versions, please visit the original plugin repository here: [Granam Mautic Czech Vocative Plugin](https://github.com/granam/mautic-czech-vocative).


_Unknown, but possible compatibility with lower versions._

## Troubleshooting

If any error happens, first of all, have you **cleared the cache**?

Otherwise, check the logs for what happened:

1. Logs are placed in the `var/logs` directory of your Mautic, like `/var/www/mautic/var/logs/mautic_prod-2024-01-01.php`
2. For more fatal errors or if Mautic does not catch them (e.g., error 500), see your web-server logs, like `/var/log/apache2/error.log`

# Credits

The plugin has been created thanks to sponsor [svetandroida.cz](https://www.svetandroida.cz/)
and thanks to the author of free czech vocative library [`bigit/vokativ`](https://bitbucket.org/bigit/vokativ.git) Petr
Joachim.

Additional thanks to [vietnamisa.cz](http://www.vietnamisa.cz/) for their help with bug-fixes and improvements.

# Hint for mautic Twig plugin

If you are going to create a Mautic plugin for [Twig](https://twig.symfony.com/doc/2.x/), a good start can
be [mautic-twig-plugin-skeleton](https://github.com/dongilbert/mautic-twig-plugin-skeleton).

## Authors

- Iuri Jorbenadze - [jorbenadze2001@gmail.com](mailto:jorbenadze2001@gmail.com)
- Jaroslav Týc

