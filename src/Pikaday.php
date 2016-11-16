<?php

namespace Rhubarb\Pikaday;

use Rhubarb\Leaf\Controls\Common\DateTime\Date;

/**
 * @property PikadayView $view
 * @property PikadayModel $model
 */
class Pikaday extends Date
{
    /**
     * @param string $name Leaf name
     * @param int $mode One of the PikadayModel::MODE_* constants
     */
    public function __construct($name = "", $mode = PikadayModel::MODE_TEXT_INPUT)
    {
        parent::__construct($name);

        $this->model->mode = $mode;
    }

    public function enableDefaultCss()
    {
        $this->model->useDefaultCss = true;
    }

    public function disableDefaultCss()
    {
        $this->model->useDefaultCss = false;
    }

    public function setPickerCssClassName($pickerCssClassName)
    {
        $this->model->pickerCssClassName = $pickerCssClassName;
    }

    /**
     * @param int $mode One of the PikadayModel::MODE_* constants
     */
    protected function setMode($mode)
    {
        $this->model->mode = $mode;
    }

    protected function getViewClass()
    {
        return PikadayView::class;
    }

    protected function createModel()
    {
        return new PikadayModel();
    }
}
