<?php

/*
 * This file is part of Laravel Auto Presenter.
 *
 * (c) Shawn McCool <shawn@heybigname.com>
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace McCool\Tests\Stubs;

use Illuminate\Database\Eloquent\Model;
use McCool\LaravelAutoPresenter\HasPresenter;
use McCool\LaravelAutoPresenter\Traits\SerializesPresentedValuesTrait;

class ModelStubSerialize extends Model implements HasPresenter
{
	use SerializesPresentedValuesTrait;

	protected $table = 'stubs';

	protected $presented = ['foo'];

    public function getPresenterClass()
    {
        return ModelPresenterArrayAccess::class;
    }
}
