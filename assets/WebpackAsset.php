<?php
/**
 * Created by PhpStorm.
 * User: Daniyar
 */

namespace app\assets;

use sweelix\webpack\WebpackAssetBundle;
use yii\bootstrap\BootstrapAsset;
use yii\bootstrap\BootstrapPluginAsset;
use yii\web\YiiAsset;

class WebpackAsset extends WebpackAssetBundle
{

    /**
     * @var bool enable caching system (default to false)
     */
    public $cacheEnabled = false;

    /**
     * @var \yii\caching\Cache cache name of cache to use, default to `cache`
     */
    public $cache = 'cache';

    /**
     * @var string base webpack alias (do not add /src nor /dist, they are automagically handled)
     */
    public $webpackPath = '@app/frontend';

    /**
     * @var array list of webpack bundles to publish (these are the entries from webpack)
     * the bundles (except for the manifest one which should be in first position) must be defined
     * in the webpack-yii2.json configuration file
     */
    public $webpackBundles = [
        'manifest',
    ];

    public function init() {
        parent::init();
        $this->depends = [
            YiiAsset::class,
            BootstrapPluginAsset::class,
            BootstrapAsset::class,
        ];
    }
}