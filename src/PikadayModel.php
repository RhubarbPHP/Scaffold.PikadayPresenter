<?php

namespace Rhubarb\Pikaday;

use Rhubarb\Leaf\Leaves\Controls\ControlModel;

class PikadayModel extends ControlModel
{
    const MODE_TEXT_INPUT = 1; // for textbox with datepicker
    const MODE_LABEL = 2; // for display of clickable date to open calendar without keyboard entry
    const MODE_CALENDAR = 3; // for display of full calendar visible on page instead of a popup

    public $mode = self::MODE_TEXT_INPUT;

    public $useDefaultCss = true;

    public $pickerCssClassName;

    public $inputType;

    public $dateFormat;

    protected function getExposableModelProperties()
    {
        $properties = parent::getExposableModelProperties();
        $properties[] = 'mode';
        $properties[] = 'pickerCssClassName';
        return $properties;
    }
}
