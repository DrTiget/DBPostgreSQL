<?php
class DB_class 
{
	private $pg;
	function __construct($db_name,$db_user,$db_pass)
	{
        $pg = @ pg_connect("dbname=$db_name user=$db_user password=$db_pass");
        if ($pg === false) {
            echo "ERROR CONNECTION";
        }else{
            $this->pg = $pg;
        }
	}

	function select($rows,$select,$from,$where = null,$debug = false)	{
		if ($where != NULL) {
			$where = "WHERE ".$where;
		}
		$select = implode(',', $select);
		$sql = "SELECT ".$select." FROM ".$from." ".$where;
		if ($debug == true) {
		    print_r($sql);
		}
        $result = @ pg_fetch_all(pg_query($this->pg,$sql));
        if ($result === false) {
            $result = 0;
        }else{
            if (!$rows) {
                $result = $result[count($result)-1];
            }
        }
		return $result;
	}
	
	function insert($from,$insert,$debug = false)
	{
		$insert_query = array_keys($insert);
		for ($i=0;$i<count($insert_query);$i++) {
			$insert_query[$i] = $insert_query[$i];
		}
		$insert_query = implode(',',$insert_query);
		$values_query = array_values($insert);
		for ($i=0;$i<count($values_query);$i++) {
			$values_query[$i] = "'".$values_query[$i]."'";
		}
		$values_query = implode(',',$values_query);
		$insert_sql = "INSERT INTO ".$from." (".$insert_query.") VALUES (".$values_query.")";
		if ($debug == true) {
		    print_r($insert_sql);
		}
		$result = @ pg_query($this->pg,$insert_sql);
	}

	function update($from,$set,$where,$debug = false)
	{
		if ($where != NULL) {
			$where = "WHERE ".$where;
		}
		if (is_array($set)) {
			$array_keys = array_keys($set);
			$set_query = "";
			for($i=0;$i<count($set);$i++) {
				if ($i+1 != count($set)) { 
					$set_query .= $array_keys[$i]."='".$set[$array_keys[$i]]."', ";
				}else{
					$set_query .= $array_keys[$i]."='".$set[$array_keys[$i]]."' ";					
				}
			}
		}else{
			$set_query = $set;
		}
		$update_sql = "UPDATE ".$from." SET ".$set_query." ".$where."";
		if ($debug == true) {
		    print_r($update_sql);
		}
		$result = @ pg_query($this->pg,$update_sql);
	}

	function delete($from,$where,$debug = false)
	{
		if ($where != NULL) {
			$where = "WHERE ".$where;
		}
		$delete_sql = "DELETE FROM ".$from." ".$where."";
		if ($debug == true) {
		    print_r($delete_sql);
		}
        $result = pg_query($this->pg,$delete_sql);
	}

	function __destruct()
	{
		pg_close($this->pg);
	}
}
?>