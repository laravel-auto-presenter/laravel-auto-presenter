<?php

namespace McCool\Tests\Decorators;

use McCool\Tests\Stubs\BaseDecoratorStub;

class BaseDecoratorTest extends \PHPUnit_Framework_TestCase
{
	public function testObjectCreationShouldReturnAppropriateDecorator()
	{
		$decorator = new BaseDecoratorStub;
		$class = get_class($decorator->createDecorator('Atom'));

		$this->assertEquals('McCool\LaravelAutoPresenter\Decorators\AtomDecorator', $class);
	}

	/**
	 * @expectedException McCool\LaravelAutoPresenter\Decorators\DecoratorNotFoundException
	 */
	public function testCreationOfANonExistentDecorator()
	{
		$decorator = new BaseDecoratorStub;
		$decorator->createDecorator('bulbous');
	}
}
