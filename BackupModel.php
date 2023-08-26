<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BackupModel extends model
{
	public static function tablesToSQL(array|string $tables = '*') {
		//get all the tables
		if($tables == '*') {
			$tables = array();
			$result = DB::select("SHOW TABLES");
			foreach ($result as $val) {
				foreach ($val as $row) {
					$tables[] = $row;
				}
			}
		} else {
			$tables = is_array($tables) ? $tables : explode(',', $tables);
		}
		
		$return = '';
		//loop through the tables
		foreach ($tables as $table) {
			$result = DB::table($table)->get()->toArray();
			
			$result2 = DB::select("SHOW CREATE TABLE $table");
			$i = 0;
			foreach ($result2[0] as $val) {
				if($i == 1)
					$return .= "\n" . $val . ";\n\n";
				$i++;
			}
			
			foreach ($result as $rows) {
				$return .= "INSERT INTO $table VALUES(";
				$i = 0;
				foreach ($rows as $val) {
					if($i > 0)
						$return .= ',';
					$return .= '"' . $val . '"';
					$i++;
				}
				$return .= ");\n";
			}
			$return .= "\n\n";
		}
		
		//save file
		$handle = fopen('db-backup-' . date('Y-m-d_h-i-s') . '.sql', 'w+');
		fwrite($handle, $return);
		fclose($handle);
		
		echo("فایل با موفقیت ساخته شد.");
	}
	
}
