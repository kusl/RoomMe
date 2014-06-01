<?php

function getUserInfo($username) {

	$engine = EngineAPI::singleton();

	$engine->dbConnect("server","157.182.150.27");
	$engine->dbConnect("username","remote");
	$engine->dbConnect("password",'My$QLnb.UP??');
	$remoteDB = $engine->dbConnect("database","authentication",FALSE);

	$sql = sprintf("SELECT master.* FROM accountUsernames LEFT JOIN master on master.uid=accountUsernames.uid WHERE accountUsernames.username='%s'",
		$remoteDB->escape($username)
		);
	
	$sqlResult = $remoteDB->query($sql);

	if (!$sqlResult['result']) {
		errorHandle::newError(__METHOD__."() - ".$sqlResult['error'], errorHandle::DEBUG);
		return(FALSE);
	}

	if ($sqlResult['numrows'] < 1) {
		return(FALSE);
	}
	else if ($sqlResult['numrows'] > 1) {
		errorHandle::newError(__METHOD__."() - More than one user returned.", errorHandle::DEBUG);
		return(FALSE);
	}

	$row = mysql_fetch_array($sqlResult['result'],  MYSQL_ASSOC);

	$userInfo             = array();
	$userInfo['initials'] = substr($row['firstname'],0,1).substr($row['lastname'],0,1);
	$userInfo['idNumber'] = $row['uid'];

	return($userInfo);

}

?>