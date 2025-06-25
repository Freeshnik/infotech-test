<?php

namespace App\Assets\Packages;

use yii\jui\JuiAsset;
use yii\web\AssetBundle;

class FancyTreeAsset extends AssetBundle
{
    public $sourcePath = '@bower/fancytree/dist';
    public $css = [
        'skin-vista/ui.fancytree.min.css',
    ];

    public $js = [
        'jquery.fancytree-all.min.js',
    ];

    public $depends = [JuiAsset::class];
}
