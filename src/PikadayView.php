<?php

namespace Rhubarb\Pikaday;

use Rhubarb\Leaf\Controls\Common\Text\TextBoxView;
use Rhubarb\Leaf\Leaves\LeafDeploymentPackage;

/**
 * @property PikadayModel $model
 */
class PikadayView extends TextBoxView
{
    protected $requiresStateInput = true;

    public function printViewContent()
    {
        switch ($this->model->mode) {
            case PikadayModel::MODE_TEXT_INPUT:
                parent::printViewContent();
                return;
            case PikadayModel::MODE_CALENDAR:
                print '<div id="' . htmlentities($this->model->leafPath) . '" ' .
                    'leaf-name="' . htmlentities($this->model->leafName) . '"' .
                    $this->model->getHtmlAttributes() . $this->model->getClassAttribute() . '>' .
                    '<input type="hidden" name="' . htmlentities($this->model->leafPath) . '" value="' . $this->getFormattedValue() . '">' .
                    '</div>';
                return;

            default:
                print '<span id="' . htmlentities($this->model->leafPath) . '" ' .
                    'leaf-name="' . htmlentities($this->model->leafName) . '"' .
                    $this->model->getHtmlAttributes() . $this->model->getClassAttribute() . '>' .
                    '<input type="hidden" name="' . htmlentities($this->model->leafPath) . '" value="' . $this->getFormattedValue() . '">' .
                    $this->getFormattedValue() .
                    '</span>';
        }
    }

    protected function getFormattedValue()
    {
        $value = $this->model->value;

        if ($value instanceof \DateTime) {
            $value = $value->format('d/m/Y');
        }

        return htmlentities($value);
    }

    protected function getViewBridgeName()
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
