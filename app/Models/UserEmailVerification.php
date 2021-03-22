<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserEmailVerification
 * 
 * @property int $id
 * @property string $name
 * @property string $email
 * @property int $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class UserEmailVerification extends Model
{
	const STATUS_NEW = 0;	
	const STATUS_SEND = 1;	
	const STATUS_READ = 2;

	protected $table = 'user_email_verifications';

	protected $casts = [
		'status' => 'int'
	];

	protected $fillable = [
		'name',
		'email',
		'status'
	];
}
