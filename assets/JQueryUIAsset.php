<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 03.05.2018
 * Time: 16:37
 */

namespace app\assets;


use yii\web\AssetBundle;
use yii\web\View;

class JQueryUIAsset extends AssetBundle
{
    public $sourcePath = '@app/assets/js/jquery-ui-1.11.4';
    public $css = [];
    public $js = [
        'jquery-ui.min.js'
    ];
    public $jsOptions = ['position' => View::POS_END];
    public $publishOptions = [];
    public $depends = [
        'app\assets\AppAsset'
    ];

    public function init()
    {
        parent::init();
    }
}