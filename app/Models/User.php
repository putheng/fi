<?php

namespace App\Models;

use Cartalyst\Sentinel\Users\EloquentUser;
use Illuminate\Database\Eloquent\SoftDeletes;


class User extends EloquentUser {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	protected $fillable = [
		'email',
		'username',
		'password',
		'last_name',
		'first_name',
		'permissions',
		'clinic_id',
        'site_id',
        'clinics_ids'
	];

	protected $guarded = ['id'];
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];

	/**
	* To allow soft deletes
	*/
	use SoftDeletes;

    protected $dates = ['deleted_at'];

	protected $loginNames = ['username'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function usersClinics()
    {
        return $this->hasMany('App\Models\UsersClinic');
    }

    public function setAttribute($key, $value)
    {
        if ($key == "site_id" && $value === '') {
            $value = null;
        }
        return parent::setAttribute($key, $value);
    }

}
