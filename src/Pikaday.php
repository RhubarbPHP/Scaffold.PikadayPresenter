<?php

namespace Rhubarb\Pikaday;

use Rhubarb\Leaf\Leaves\Controls\Control;

/**
 * @property PikadayView $view
 * @property PikadayModel $model
 */
class Pikaday extends Control
{
    const DEFAULT_DATE_FORMAT = 'd/m/Y';

    /**
     * @param string $name Leaf name
     * @param int $mode One of the PikadayModel::MODE_* constants
     * @param string $dateFormat A format to be used for displaying dates in the datepicker, and parsing them from request data
     * @throws \Rhubarb\Leaf\Exceptions\InvalidLeafModelException
     */
    public function __construct($name = "", $mode = PikadayModel::MODE_TEXT_INPUT, $dateFormat = self::DEFAULT_DATE_FORMAT)
    {
        parent::__construct($name);

        $this->model->mode = $mode;
        $this->model->dateFormat = $dateFormat;
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
