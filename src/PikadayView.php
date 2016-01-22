<?php

namespace Rhubarb\PikadayPresenter;

use Rhubarb\Leaf\Presenters\Controls\Text\TextBox\TextBoxView;

class PikadayView extends TextBoxView
{
    public $useDefaultCss = true;

    protected function getClientSideViewBridgeName()
    {
        return "PikadayViewBridge";
    }

    public function getDeploymentPackage()
    {
        $package = parent::getDeploymentPackage();
        if ($this->useDefaultCss) {
            $package->resourcesToDeploy[] = __DIR__ . '/../../../webmodules/pikaday/css/pikaday.css';
        }
        $package->resourcesToDeploy[] = __DIR__ . '/../../../webmodules/moment/min/moment.min.js';
        $package->resourcesToDeploy[] = __DIR__ . '/../../../webmodules/pikaday/pikaday.js';
        $package->resourcesToDeploy[] = __DIR__ . '/PikadayViewBridge.js';

        return $package;
    }
}
