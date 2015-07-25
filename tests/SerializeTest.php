<?php

namespace McCool\Tests;


use McCool\Tests\Stubs\ModelStubSerialize;

class SerializeTest extends AbstractTestCase
{

	public function testSerializeHasPresentedValue(){
		$model = new ModelStubSerialize();
		$model->foo = 'hello';

		$arr = $model->toArray();
		$this->assertSame('hello there', $arr['foo']);
	}
}
