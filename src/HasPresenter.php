<?php namespace McCool\LaravelAutoPresenter;

interface HasPresenter
{
    /**
     * Get the presenter class.
     *
     * @return string
     */
    public function getPresenterClass();
}
