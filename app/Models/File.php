<?php

namespace App\Models;

use App\Helpers\S3Util;
use Illuminate\Database\Eloquent\Model;

class File extends Model {

    protected $table = 'files';

    protected $fillable = [
        'fil_name',
        'fil_size',
    ];

	public $appends = [
        'url'
    ];

	public function getUrlAttribute() {
		return S3Util::getObject($this->fil_name);
	}
}
