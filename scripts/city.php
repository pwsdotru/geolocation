<?php

$insert_fields = array(
0=>"id",
1=>"city_name",
2=>"area_name",
3=>"region_name",
4=>"lat",
5=>"lng"
);
$update_fields = array(
1=>"city_name",
2=>"area_name",
3=>"region_name",
4=>"lat",
5=>"lng"
);
//begin work
stream_set_blocking(STDIN, 0);
fwrite(STDOUT, "SET NAMES 'cp1251';\n");
fwrite(STDERR, "Start\n");
$cnt = 0;
while (!feof(STDIN)) {
	$str = fgets(STDIN);
	$data = explode("\t", trim($str));
	if (is_array($data) && count($data) > 0) {
		$values = array();
		foreach ($insert_fields AS $num => $field) {
			if (isset($data[$num])) {
				$val = trim($data[$num]);
			} else {
				$val = '';
			}
			$values[$num] = "'".AddSlashes($val)."'";
		}
		$sql = "INSERT INTO geo_cities(".implode(", ", $insert_fields).") VALUES (".implode(", ", $values).")";
		$update = array();
		foreach($update_fields AS $num => $field) {
			if (isset($data[$num])) {
                                $val = trim($data[$num]);
                        } else {
                                $val = '';
                        }
			$update[$num] = $field."='".AddSlashes($val)."'";

		}
		if (count($update) > 0) {
			$sql .= " ON DUPLICATE KEY UPDATE ".implode(", ", $update);
		}	
		fwrite(STDOUT, $sql.";\n");	
	}
	$cnt++;
	if ($cnt > 100) {
		$cnt = 0;
		fwrite(STDERR, "*");
	}
}
fwrite(STDERR, "\nStop\n");
?>
