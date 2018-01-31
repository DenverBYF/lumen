<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Doctor extends Model implements AuthenticatableContract, AuthorizableContract
{
	use Authenticatable, Authorizable;
	protected $table = 'doctors';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'card', 'card_img', 'doctor_card_url'
	];
}
