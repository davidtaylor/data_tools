<?php
$MP=1;
require_once('../_header.php');
require($ABS_PATH.'monsterpants/boot.php');
$supporter_KEY = $_REQUEST['supporter_KEY'];

if ($supporter_KEY) {

	$result = $dia->get('supporter',$supporter_KEY);
	$s = new Supporter($result);
	$s->db_upsert();
	
	$model_array = Array('SupporterAction', 'Email', 'Donation', 'ContactHistory', 'SupporterEvent', 'SupporterCompany');
	foreach($model_array AS $model){
	  $table = $model::$table_name;
	  $results = $dia->get($table, Array('condition' => Array('supporter_KEY='.$supporter_KEY)));
	  foreach($results AS $result){
	    $instance = new $model($result);
	    $instance->db_upsert();
	  }
	}
	
	$db->query('DELETE FROM `tag_data` WHERE database_table_KEY=142 AND `table_key`='.$supporter_KEY);
	$results = $dia->get('tag_data', Array('condition' => Array('database_table_KEY=142', 'table_key='.$supporter_KEY)));
	foreach($results AS $result){
	  $instance = new TagData($result);
	  $instance->db_insert();
	}
	
	
	$db->query('DELETE FROM `supporter_groups` WHERE `supporter_KEY`='.$supporter_KEY);
	$results = $dia->get('supporter_groups', Array('condition' => Array('supporter_KEY='.$supporter_KEY)));
	foreach($results AS $result){
	  $instance = new SupporterGroups($result);
	  $instance->db_insert();
	}

echo $supporter_KEY. ' Updated';

}


?>
<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="get">
Supporter KEY: <input name="supporter_KEY" value="">
<input type="submit">

</form>