<?php

namespace Rhubarb\PikadayPresenter;

use Rhubarb\Leaf\Presenters\Controls\DateTime\Date;

/**
 * @property PikadayView $view
 */
class PikadayPresenter extends Date
{
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
        return new PikadayView();
    }
}
