<?php
/**
 * Favicon.php
 * @author Revin Roman
 * @link https://rmrevin.ru
 */

namespace rmrevin\yii\favicon;

use Imagine\Image\Box;
use Imagine\Image\Color;
use Imagine\Image\Point;
use yii\helpers\Html;
use yii\imagine\Image;

/**
 * Class Favicon
 * @package rmrevin\yii\favicon
 */
class Favicon extends \yii\base\Widget
{

    /** @var bool */
    public $generate = true;

    /** @var bool */
    public $forceGenerate = false;

    /** @var string ONLY PNG */
    public $favicon = '@webroot/favicon.png';

    /** @var string */
    public $web = '@webroot';

    /** @var string name for manifest.json */
    public $appname = 'Cookyii CMF';

    /** @var string color for ms windows tile background & android theme color */
    public $color = '#2b5797';

    /** @var string|Color color for apple icons background fill (null - transparent) */
    public $fillColor = '#a4eeff';

    /** @var array */
    protected $tags = [];

    public function run()
    {
        if ($this->generate === true) {
            if (!$this->checkIconsExists() || $this->forceGenerate) {
                $this->generateFavicons();
                $this->generateApple();
                $this->generateAndroid();
                $this->generateManifestJson();
                $this->generateMicrosoftTiles();
                $this->generateBrowserConfigXml();
            }
        }

        foreach ([16, 32, 96, 194] as $s) {
            $filename = sprintf('favicon-%sx%s.png', $s, $s);

            $this->tags[] = Html::tag('link', null, [
                'rel' => 'icon',
                'type' => 'image/png',
                'href' => sprintf('/%s', $filename),
                'sizes' => sprintf('%sx%s', $s, $s),
            ]);
        }

        $this->tags[] = Html::tag('link', null, [
            'rel' => 'icon',
            'type' => 'image/png',
            'href' => '/android-chrome-192x192.png',
            'sizes' => sprintf('%sx%s', 192, 192),
        ]);

        $this->tags[] = Html::tag('link', null, [
            'rel' => 'manifest',
            'href' => '/manifest.json',
        ]);

        foreach ([57, 60, 72, 76, 114, 120, 144, 152, 180] as $s) {
            $filename = sprintf('apple-touch-icon-%sx%s.png', $s, $s);

            $this->tags[] = Html::tag('link', null, [
                'rel' => 'apple-touch-icon',
                'type' => 'image/png',
                'href' => sprintf('/%s', $filename),
                'sizes' => sprintf('%sx%s', $s, $s),
            ]);
        }

        $this->tags[] = Html::tag('meta', null, [
            'name' => 'msapplication-TileColor',
            'content' => $this->color,
        ]);

        $this->tags[] = Html::tag('meta', null, [
            'name' => 'msapplication-TileImage',
            'content' => '/mstile-144x144.png',
        ]);

        $this->tags[] = Html::tag('meta', null, [
            'name' => 'theme-color',
            'content' => $this->color,
        ]);

        foreach ($this->tags as $tag) {
            echo $tag . PHP_EOL;
        }
    }

    protected function generateFavicons()
    {
        $favicon_path = $this->getFaviconPath();
        $web_path = $this->getWebPath();

        foreach ([16, 32, 96, 194] as $s) {
            $filename = sprintf('favicon-%sx%s.png', $s, $s);

            Image::getImagine()
                ->open($favicon_path)
                ->resize(new Box($s, $s))
                ->save($web_path . DIRECTORY_SEPARATOR . $filename);
        }
    }

    protected function generateAndroid()
    {
        $favicon_path = $this->getFaviconPath();
        $web_path = $this->getWebPath();

        $variants = [
            [
                'resize' => new Box(28, 28),
                'canvas' => new Box(36, 36),
                'point' => new Point(4, 4),
                'mark' => [36, 36],
            ],
            [
                'resize' => new Box(40, 40),
                'canvas' => new Box(48, 48),
                'point' => new Point(4, 4),
                'mark' => [48, 48],
            ],
            [
                'resize' => new Box(62, 62),
                'canvas' => new Box(72, 72),
                'point' => new Point(5, 5),
                'mark' => [72, 72],
            ],
            [
                'resize' => new Box(86, 86),
                'canvas' => new Box(96, 96),
                'point' => new Point(5, 5),
                'mark' => [96, 96],
            ],
            [
                'resize' => new Box(132, 132),
                'canvas' => new Box(144, 144),
                'point' => new Point(6, 6),
                'mark' => [144, 144],
            ],
            [
                'resize' => new Box(178, 178),
                'canvas' => new Box(192, 192),
                'point' => new Point(7, 7),
                'mark' => [192, 192],
            ],
        ];

        foreach ($variants as $conf) {
            /** @var Box $resize */
            $resize = $conf['resize'];

            /** @var Box $canvas */
            $canvas = $conf['canvas'];

            /** @var Point $point */
            $point = $conf['point'];

            /** @var array $mark */
            $mark = $conf['mark'];

            $file = $web_path . DIRECTORY_SEPARATOR . sprintf('android-chrome-%sx%s.png', $mark[0], $mark[1]);

            $Icon = Image::getImagine()
                ->open($favicon_path)
                ->resize($resize);

            Image::getImagine()
                ->create($canvas, new Color('ffffff', 100))
                ->paste($Icon, $point)
                ->save($file);
        }
    }

    protected function generateApple()
    {
        $favicon_path = $this->getFaviconPath();
        $web_path = $this->getWebPath();

        /** @var integer $default size fo default icon */
        $default = 180;

        $Point = new Point(0, 0);

        $Color = empty($this->fillColor)
            ? new Color('ffffff', 100)
            : is_string($this->fillColor)
                ? new Color(str_replace('#', '', $this->fillColor))
                : $this->color;

        foreach ([57, 60, 72, 76, 114, 120, 144, 152, 180] as $s) {
            $Icon = Image::getImagine()
                ->open($favicon_path)
                ->resize(new Box($s, $s));

            Image::getImagine()
                ->create(new Box($s, $s), $Color)
                ->paste($Icon, $Point)
                ->save($web_path . DIRECTORY_SEPARATOR . sprintf('apple-touch-icon-%sx%s.png', $s, $s));

            if ($default === $s) {
                Image::getImagine()
                    ->create(new Box($s, $s), $Color)
                    ->paste($Icon, $Point)
                    ->save($web_path . DIRECTORY_SEPARATOR . 'apple-touch-icon.png');
            }
        }
    }

    protected function generateMicrosoftTiles()
    {
        $favicon_path = $this->getFaviconPath();
        $web_path = $this->getWebPath();

        Image::getImagine()
            ->open($favicon_path)
            ->resize(new Box(144, 144))
            ->save($web_path . DIRECTORY_SEPARATOR . sprintf('mstile-%sx%s.png', 144, 144));

        $variants = [
            [
                'resize' => new Box(95, 95),
                'canvas' => new Box(128, 128),
                'point' => new Point(16, 16),
                'mark' => [70, 70],
            ],
            [
                'resize' => new Box(126, 126),
                'canvas' => new Box(270, 270),
                'point' => new Point(72, 50),
                'mark' => [150, 150],
            ],
            [
                'resize' => new Box(126, 126),
                'canvas' => new Box(558, 270),
                'point' => new Point(216, 50),
                'mark' => [310, 150],
            ],
            [
                'resize' => new Box(256, 256),
                'canvas' => new Box(551, 551),
                'point' => new Point(147, 126),
                'mark' => [310, 310],
            ],
        ];

        foreach ($variants as $conf) {
            /** @var Box $resize */
            $resize = $conf['resize'];

            /** @var Box $canvas */
            $canvas = $conf['canvas'];

            /** @var Point $point */
            $point = $conf['point'];

            /** @var array $mark */
            $mark = $conf['mark'];

            $file = $web_path . DIRECTORY_SEPARATOR . sprintf('mstile-%sx%s.png', $mark[0], $mark[1]);

            $Icon = Image::getImagine()
                ->open($favicon_path)
                ->resize($resize);

            Image::getImagine()
                ->create($canvas, new Color('ffffff', 100))
                ->paste($Icon, $point)
                ->save($file);
        }
    }

    protected function generateBrowserConfigXml()
    {
        file_put_contents(
            $this->getWebPath() . DIRECTORY_SEPARATOR . 'browserconfig.xml',
            $this->getViewContent('browserconfig.xml')
        );
    }


    protected function generateManifestJson()
    {
        file_put_contents(
            $this->getWebPath() . DIRECTORY_SEPARATOR . 'browserconfig.xml',
            $this->getViewContent('manifest.json')
        );
    }

    /**
     * @param string $filename
     * @param array $params
     * @return string
     */
    protected function getViewContent($filename, $params = [])
    {
        $filepath = $this->getViewPath() . DIRECTORY_SEPARATOR . $filename;

        $replace = array_merge($this->getViewParams(), $params);

        $replace_keys = array_map(function ($val) { return sprintf('${%s}', $val); }, array_keys($replace));
        $replace_values = array_values($replace);

        return str_replace(
            $replace_keys,
            $replace_values,
            file_get_contents($filepath)
        );
    }

    /**
     * @return array
     */
    protected function getViewParams()
    {
        return [
            'appname' => $this->appname,
            'color' => $this->color,
        ];
    }

    /**
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    protected function checkIconsExists()
    {
        $result = true;

        $icons = ['android-chrome-36x36.png', 'apple-touch-icon-57x57.png', 'favicon-16x16.png', 'mstile-70x70.png'];

        $web_path = $this->getWebPath();

        foreach ($icons as $icon) {
            $result = $result && file_exists($web_path . DIRECTORY_SEPARATOR . $icon);
        }

        return $result;
    }

    /**
     * @return bool|string
     * @throws \yii\base\InvalidConfigException
     */
    protected function getFaviconPath()
    {
        $favicon_path = \Yii::getAlias($this->favicon);

        if (empty($favicon_path) || !file_exists($favicon_path) || !is_file($favicon_path)) {
            throw new \yii\base\InvalidConfigException('Favicon file not exists.');
        }

        if (!is_readable($favicon_path)) {
            throw new \yii\base\InvalidConfigException('Favicon file is not readable.');
        }

        $size = @getimagesize($favicon_path);

        if (empty($size) || $size === false) {
            throw new \yii\base\InvalidConfigException('Favicon file is not an image.');
        }

        if ($size['mime'] !== 'image/png') {
            throw new \yii\base\InvalidConfigException('Favicon file must be a png image format.');
        }

        if ($size[0] !== $size[1]) {
            \Yii::warning('Favicon is not square.', __CLASS__);
        }

        if ($size[0] < 250) {
            \Yii::warning('The recommended size for favicon is 250x250.', __CLASS__);
        }

        return $favicon_path;
    }

    /**
     * @return bool|string
     * @throws \yii\base\InvalidConfigException
     */
    protected function getWebPath()
    {
        $web_path = \Yii::getAlias($this->web);

        if (empty($web_path) || !file_exists($web_path) || !is_dir($web_path)) {
            throw new \yii\base\InvalidConfigException('Web path not exists.');
        }

        if (!is_writable($web_path)) {
            throw new \yii\base\InvalidConfigException('Web path is not writable.');
        }

        return $web_path;
    }
}