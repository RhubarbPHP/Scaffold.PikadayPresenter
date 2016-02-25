<?php

namespace Rhubarb\PikadayPresenter;

use Rhubarb\Leaf\Presenters\Controls\DateTime\Date;

/**
 * @property PikadayView $view
 */
class PikadayPresenter extends Date
{
    const MODE_TEXT_INPUT = 1;
    const MODE_LABEL = 2;

    /**
     * @var int
     */
    protected $mode;

    /**
     * PikadayPresenter constructor.
     * @param string $name
     * @param int $mode Either self::MODE_TEXT_INPUT for textbox with datepicker, or self::MODE_LABEL for display of clickable date without keyboard entry
     * @param null $defaultValue
     */
    public function __construct($name = "", $mode = self::MODE_TEXT_INPUT, $defaultValue = null)
    {
        parent::__construct($name, $defaultValue);
        $this->mode = $mode;
    }

    public function enableDefaultCss()
    {
        $this->view->useDefaultCss = true;
    }

    protected function disableDefaultCss()
    {
        $this->view->useDefaultCss = false;
    }

    protected function createView()
    {
        return new PikadayView($this->mode);
    }
}
