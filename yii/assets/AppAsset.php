<?php
/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;
use yii\helpers\Json;

class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [];
    public $js = [];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset',
    ];

    public function init()
    {
        parent::init();

        $manifestPath = \Yii::getAlias('@webroot/assets/bootstrap/manifest.json');

        if (file_exists($manifestPath)) {
            $manifest = Json::decode(file_get_contents($manifestPath));

            if (isset($manifest['main.css'])) {
                $this->css[] = $manifest['main.css'];
            }
            if (isset($manifest['main.js'])) {
                $this->js[] = $manifest['main.js'];
            }
        }
    }
}

