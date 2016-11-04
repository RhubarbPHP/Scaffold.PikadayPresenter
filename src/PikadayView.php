<?php

namespace Rhubarb\PikadayPresenter;

use Rhubarb\Leaf\Controls\Common\Text\TextBoxView;
use Rhubarb\Leaf\Leaves\LeafDeploymentPackage;

/**
 * @property PikadayModel $model
 */
class PikadayView extends TextBoxView
{
    public function printViewContent()
    {
        if ($this->model->mode == PikadayModel::MODE_TEXT_INPUT) {
            parent::printViewContent();
            return;
        }

        print '<span id="' . htmlentities($this->model->leafPath) . '" ' .
                'leaf-name="' . htmlentities($this->model->leafName) . '"' .
                $this->model->getHtmlAttributes() . $this->model->getClassAttribute() . '>' .
            '<input type="hidden" name="' . htmlentities($this->model->leafPath) . '" value="' . htmlentities($this->model->value) . '">' .
            htmlentities($this->model->value) .
            '</span>';
    }

    protected function getClientSideViewBridgeName()
    {
        return "PikadayViewBridge";
    }

    public function getDeploymentPackage()
    {
        /** @var LeafDeploymentPackage $package */
        $package = parent::getDeploymentPackage();

        $package->resourcesToDeploy[] = __DIR__ . '/../../../../components/moment/min/moment.min.js';
        $package->resourcesToDeploy[] = __DIR__ . '/../../../rhubarbphp/pikaday/pikaday.js';
        $package->resourcesToDeploy[] = __DIR__ . '/PikadayViewBridge.js';

        if ($this->model->useDefaultCss) {
            $package->resourcesToDeploy[] = __DIR__ . '/../../../rhubarbphp/pikaday/css/pikaday.css';
        }

        return $package;
    }
}
