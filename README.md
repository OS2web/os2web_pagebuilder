# OS2Web Pagebuilder Drupal module

## Module purpose

Page builder is a core module for [OS2Web solution](https://github.com/os2web/os2web8).
Module provides initial configuration of Indholdside content type with its
fields. To organize flexible page builing process there is used Paragraphs module
with [predefined paragraph bundles](https://github.com/OS2web/os2web_pagebuilder/tree/master/modules/os2web_paragraphs).
Take a look at the list on proposed paragraph bundles and install it on demand
if you miss any of them.

Other OS2Web modules has implemented paragraph bundles that could be used in
Indholdside content type by reference.

## Install

1. Module is available to download via composer.
    ```
    composer require os2web/os2web_pagebuilder
    drush en os2web_pagebuilder
    ```
2. Enable [OS2Web paragraph submodules](https://github.com/OS2web/os2web_pagebuilder/tree/master/modules/os2web_paragraphs/modules) on demand.


## Update
Updating process for OS2Web Pagebuilder module is similar to usual composer package.
Use Composer's built-in command for listing packages that have updates available:

```
composer outdated os2web/os2web_pagebuilder
```

## Contribution

Project is opened for new features and os course bugfixes.
If you have any suggestion or you found a bug in project, you are very welcome
to create an issue in github repository issue tracker.
For issue description there is expected that you will provide clear and
sufficient information about your feature request or bug report.

### Code review policy
See [OS2Web code review policy](https://github.com/OS2Web/docs#code-review)

### Git name convention
See [OS2Web git name convention](https://github.com/OS2Web/docs#git-guideline)
