Yii 2 Favicon widget
====================
[![License](https://poser.pugx.org/rmrevin/yii2-favicon-widget/license.svg)](https://packagist.org/packages/rmrevin/yii2-favicon-widget)
[![Latest Stable Version](https://poser.pugx.org/rmrevin/yii2-favicon-widget/v/stable.svg)](https://packagist.org/packages/rmrevin/yii2-favicon-widget)
[![Latest Unstable Version](https://poser.pugx.org/rmrevin/yii2-favicon-widget/v/unstable.svg)](https://packagist.org/packages/rmrevin/yii2-favicon-widget)
[![Total Downloads](https://poser.pugx.org/rmrevin/yii2-favicon-widget/downloads.svg)](https://packagist.org/packages/rmrevin/yii2-favicon-widget)

Installation
------------
```bash
composer require "rmrevin/yii2-favicon-widget:1.1.*"
```

Usage
-----
In layout view
```php
<html>
<head>
    // ...
    <?php
    echo \rmrevin\yii\favicon\Favicon::widget([
        'web' => '@web',
        'webroot' => '@webroot',
        'favicon' => '@webroot/favicon.png',
        'color' => '#2b5797',
        'viewComponent' => 'view',
    ]);
    
    // output 
    // <link type="image/png" href="/favicon-16x16.png" rel="icon" sizes="16x16">
    // <link type="image/png" href="/favicon-32x32.png" rel="icon" sizes="32x32">
    // <link type="image/png" href="/favicon-96x96.png" rel="icon" sizes="96x96">
    // <link type="image/png" href="/favicon-194x194.png" rel="icon" sizes="194x194">
    // <link type="image/png" href="/android-chrome-192x192.png" rel="icon" sizes="192x192">
    // <link href="/manifest.json" rel="manifest">
    // <link type="image/png" href="/apple-touch-icon-57x57.png" rel="apple-touch-icon" sizes="57x57">
    // <link type="image/png" href="/apple-touch-icon-60x60.png" rel="apple-touch-icon" sizes="60x60">
    // <link type="image/png" href="/apple-touch-icon-72x72.png" rel="apple-touch-icon" sizes="72x72">
    // <link type="image/png" href="/apple-touch-icon-76x76.png" rel="apple-touch-icon" sizes="76x76">
    // <link type="image/png" href="/apple-touch-icon-114x114.png" rel="apple-touch-icon" sizes="114x114">
    // <link type="image/png" href="/apple-touch-icon-120x120.png" rel="apple-touch-icon" sizes="120x120">
    // <link type="image/png" href="/apple-touch-icon-144x144.png" rel="apple-touch-icon" sizes="144x144">
    // <link type="image/png" href="/apple-touch-icon-152x152.png" rel="apple-touch-icon" sizes="152x152">
    // <link type="image/png" href="/apple-touch-icon-180x180.png" rel="apple-touch-icon" sizes="180x180">
    // <meta name="msapplication-TileColor" content="#2b5797">
    // <meta name="msapplication-TileImage" content="/mstile-144x144.png">
    // <meta name="theme-color" content="#2b5797">
    ?>
</head>
<body>
    // ...
</body>
</html>
```

Hint
----
You may add to `.gitignore` in webroot
```
/favicon-*.png
/android-chrome-*.png
/apple-touch-icon*.png
/mstile-*.png
/browserconfig.xml
/manifest.json
```