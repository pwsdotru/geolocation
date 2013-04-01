<?php

$insert_fields = array(
0=>"start",
1=>"end",
2=>"period",
3=>"country",
4=>"city_id"
);
$update_fields = array(
3=>"country",
4=>"city_id"
);
//country filter
$country_id=3;
$country_filter=array("RU");
//begin work
stream_set_blocking(STDIN, 0);
fwrite(STDOUT, "SET NAMES 'cp1251';\n");
fwrite(STDERR, "Start\n");
$cnt = 0;
while (!feof(STDIN)) {
	$str = fgets(STDIN);
	$data = explode("\t", trim($str));
	if (is_array($data) && count($data) > 0) {
		if (isset($country_filter) && is_array($country_filter)) {
			if (!in_array($data[$country_id], $country_filter)) {
				continue;
			}
		}
		$values = array();
		foreach ($insert_fields AS $num => $field) {
			if (isset($data[$num])) {
				$val = trim($data[$num]);
			} else {
				$val = '';
			}
			$values[$num] = "'".AddSlashes($val)."'";
		}
		$sql = "INSERT DELAYED INTO geo_ips(".implode(", ", $insert_fields).") VALUES (".implode(", ", $values).")";
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
	if ($cnt > 1000) {
		$cnt = 0;
		fwrite(STDERR, "*");
	}
}
fwrite(STDERR, "\nStop\n");
?>
