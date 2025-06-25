<?php

namespace App\Assets\Packages;

use yii\jui\JuiAsset;
use yii\web\AssetBundle;

class ContextMenuAsset extends AssetBundle
{
    public $sourcePath = '@bower/ui-contextmenu';

    public $js = [
        'jquery.ui-contextmenu.min.js',
    ];

    public $depends = [JuiAsset::class];
}
