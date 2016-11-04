<?php

namespace Rhubarb\PikadayPresenter;

use Rhubarb\Leaf\Controls\Common\DateTime\Date;

/**
 * @property PikadayView $view
 * @property PikadayModel $model
 */
class Pikaday extends Date
{
    public function enableDefaultCss()
    {
        $this->model->useDefaultCss = true;
    }

    public function disableDefaultCss()
    {
        $this->model->useDefaultCss = false;
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
