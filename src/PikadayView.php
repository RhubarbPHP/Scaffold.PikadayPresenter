<?php

namespace Rhubarb\PikadayPresenter;

use Rhubarb\Leaf\Presenters\Controls\Text\TextBox\TextBoxView;

class PikadayView extends TextBoxView
{
    public $useDefaultCss = true;
    protected $mode;

    public function __construct($mode)
    {
        parent::__construct('text');
        $this->mode = $mode;
    }

    public function printViewContent()
    {
        if ($this->mode == PikadayPresenter::MODE_TEXT_INPUT) {
            parent::printViewContent();
            return;
        }

        print '<span id="' . htmlentities($this->getIndexedPresenterPath()) . '" ' .
                'presenter-name="' . htmlentities($this->presenterName) . '"' .
                $this->getHtmlAttributeTags() . $this->getClassTag() . '>' .
            '<input type="hidden" name="' . htmlentities($this->getIndexedPresenterPath()) . '" value="' . htmlentities($this->text) . '">' .
            htmlentities($this->text) .
            '</span>';
    }

    protected function getClientSideViewBridgeName()
    {
        return "PikadayViewBridge";
    }

    public function getDeploymentPackage()
    {
        $package = parent::getDeploymentPackage();
        if ($this->useDefaultCss) {
            $package->resourcesToDeploy[] = __DIR__ . '/../../../rhubarbphp/pikaday/css/pikaday.css';
        }
        $package->resourcesToDeploy[] = __DIR__ . '/../../../../components/moment/min/moment.min.js';
        $package->resourcesToDeploy[] = __DIR__ . '/../../../rhubarbphp/pikaday/pikaday.js';
        $package->resourcesToDeploy[] = __DIR__ . '/PikadayViewBridge.js';

        return $package;
    }
}
