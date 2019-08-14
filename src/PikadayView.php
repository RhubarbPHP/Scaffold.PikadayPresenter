<?php

namespace Rhubarb\Pikaday;

use Rhubarb\Crown\DateTime\RhubarbDate;
use Rhubarb\Crown\Request\WebRequest;
use Rhubarb\Leaf\Leaves\Controls\ControlView;
use Rhubarb\Leaf\Leaves\LeafDeploymentPackage;

/**
 * @property PikadayModel $model
 */
class PikadayView extends ControlView
{
    public function printViewContent()
    {
        switch ($this->model->mode) {
            case PikadayModel::MODE_TEXT_INPUT:
                print '<input type="text" leaf-name="' . $this->model->leafName . '" name="' . $this->model->leafPath . '" ' .
                    'id="' . $this->model->leafPath . '" ' . $this->model->getClassAttribute() . $this->model->getHtmlAttributes() .
                    ' value="' . $this->getFormattedValue() . '" />';
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
            $value = $value->format($this->model->dateFormat);
        }

        return htmlentities($value);
    }

    protected function getViewBridgeName()
    {
        return "PikadayViewBridge";
    }

    public function getDeploymentPackage()
    {
        $package = new LeafDeploymentPackage();

        $package->resourcesToDeploy[] = __DIR__ . '/../../../moment/moment/min/moment.min.js';
        $package->resourcesToDeploy[] = __DIR__ . '/../../../rhubarbphp/pikaday/pikaday.js';
        $package->resourcesToDeploy[] = __DIR__ . '/PikadayViewBridge.js';

        if ($this->model->useDefaultCss) {
            $package->resourcesToDeploy[] = __DIR__ . '/../../../rhubarbphp/pikaday/css/pikaday.css';
        }

        return $package;
    }

    protected function parseRequest(WebRequest $request)
    {
        $path = $this->model->leafPath;

        // By default if a control can be represented by a single HTML element then the name of that element
        // should equal the leaf path of the control. If that is true then we can automatically discover and
        // update our model.

        $value = $request->post($path);
        if ($value !== null) {
            $this->model->setValue(RhubarbDate::createFromFormat($this->model->dateFormat, $value));
        }

        // Now we search for indexed data. We can't unfortunately guess what the possible indexes are so we
        // have to use a regular expression to find and extract any indexes. Note that it's not possible to
        // have both un-indexed and indexed versions of the same leaf on the parent. In that case the indexed
        // version will create an array of model data in place of the single un-indexed value.
        $postData = $request->postData;

        foreach ($postData as $key => $value) {
            if (preg_match('/' . preg_quote($this->model->leafPath) . '\(([^)]+)\)$/', $key, $match)) {
                $this->setControlValueForIndex($match[1], RhubarbDate::createFromFormat($this->model->dateFormat, $value));
            }
        }
    }
}
