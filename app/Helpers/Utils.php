<?php

namespace App\Helpers;

use App\Models\File;
use App\Helpers\S3Util;

class Utils {

	static public function addAttachment($file) {
		$imageName = time() . "-" . rand(1000, 1000000) . '.' . $file->getClientOriginalExtension();

		$att = new File;

		$att->fil_name = $imageName;
		$att->fil_size = filesize($file);

		S3Util::putObject($att->fil_name, file_get_contents($file));

		$att->save();

		return $att;
	}
}
