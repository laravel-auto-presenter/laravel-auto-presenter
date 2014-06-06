<?php namespace McCool\LaravelAutoPresenter;

interface PresenterInterface
{
    /**
     * Get the presenter class.
     *
     * @return string The class path to the presenter.
     */
    public function getPresenter();
}
