<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ImportFileTransaction
 * 
 * @property int $id
 * @property string $file_name
 * @property int $import_status
 * @property string|null $error
 * @property string $company_id
 * @property string $created_by
 * @property string|null $updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Company $company
 *
 * @package App\Models
 */
class ImportFileTransaction extends Model
{
	const STATUS_HAS_BEEN_UPLOADED = 0;
	const STATUS_ON_PROGRESS_FETCH_DATA = 1;
	const STATUS_DATA_HAS_BEEN_RECORDED = 2;
	const STATUS_FAILED_IMPORT_DATA = 3;
	
	protected $table = 'import_file_transactions';

	protected $casts = [
		'import_status' => 'int'
	];

	protected $fillable = [
		'file_name',
		'import_status',
		'error',
		'company_id',
		'created_by',
		'updated_by'
	];

	public function company()
	{
		return $this->belongsTo(Company::class);
	}
}
