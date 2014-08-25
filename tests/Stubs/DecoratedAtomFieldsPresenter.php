<?php namespace McCool\Tests\Stubs;

class DecoratedAtomFieldsPresenter extends \McCool\LaravelAutoPresenter\BasePresenter
{
	protected $exposedFields = [
		'name',
		'address'
	];
}