<?php

namespace Rhubarb\Pikaday;

use Rhubarb\Crown\DateTime\RhubarbDate;
use Rhubarb\Crown\Events\Event;
use Rhubarb\Leaf\Leaves\Controls\Control;

/**
 * @property PikadayView $view
 * @property PikadayModel $model
 */
class Pikaday extends Control
{
    const DEFAULT_DATE_FORMAT = 'd/m/Y';

    public $getClassForDay;

    /**
     * @param string $name Leaf name
     * @param int $mode One of the PikadayModel::MODE_* constants
     * @param string $dateFormat A format to be used for displaying dates in the datepicker, and parsing them from request data
     * @throws \Rhubarb\Leaf\Exceptions\InvalidLeafModelException
     */
    public function __construct($name = "", $mode = PikadayModel::MODE_TEXT_INPUT, $dateFormat = self::DEFAULT_DATE_FORMAT)
    {
        $this->getClassForDay = new Event();

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
     * Set to true if you do not want days in the past to be selectable
     *
     * Default is false
     * @param $disablePast
     */
    public function setDisablePast($disablePast)
    {
        $this->model->disablePast = $disablePast;
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

    protected function onModelCreated()
    {
        parent::onModelCreated();

        $this->model->getClassesForDaysEvent->attachHandler(function($month, $year) {
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

            $dayClasses = [];
            for ($i = 1; $i <= $daysInMonth; $i++) {
                $classes = $this->getClassForDay->raise(RhubarbDate::createFromFormat('d/m/Y', "$i/$month/$year"));
                if (!is_array($classes)) {
                    $classes = ['is-available-day'];
                }
                $dayClasses[$i] = $classes;
            }

            return array_values($dayClasses);
        });
    }
}
