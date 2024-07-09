<?php 


require_once 'zklibrary.php';
$zk = new ZKLibrary();
$zk->connect();

$islem = strip_tags($_GET['islem']);


if ($islem == 'delete_user') {
	$uid = $_POST['uid'];
	try {
		$result = $zk->deleteUser($uid);
		echo json_encode(['success' => true]);
	} catch (Exception $e) {
		echo json_encode(['success' => false]);
	}
	exit();
}


if ($islem == 'get_user') {
	$uid = $_GET['uid'];
	try {
		$user = $zk->getUser()[$uid];
		echo json_encode($user);
	} catch (Exception $e) {
		echo json_encode();
	}
	exit();
}



if ($islem == 'clear_log') {
	try {
		$zk->clearAttendance();
		echo json_encode(['success' => true]);
	} catch (Exception $e) {
		echo json_encode(['success' => false]);

	}

	exit();
}
if ($islem == 'test_voice') {
	try {
		$zk->testVoice();
		echo json_encode(['success' => true]);
	} catch (Exception $e) {
		echo json_encode(['success' => false]);
	}
	exit();
}
if ($islem == 'set_time') {
	date_default_timezone_set('Europe/Istanbul');
	$newTime = date('Y-m-d H:i:s');

	$newTime = date('Y-m-d H:i:s',strtotime($_POST['time']));

	try {
		$zk->setTime($newTime);
		echo json_encode(['success' => true]);
	} catch (Exception $e) {
		echo json_encode(['success' => false]);
		
	}


	exit();
}
if ($islem == 'set_devicename') {
	$cihaz_adi = $_POST['cihaz_adi'];

	try {
		$zk->setDeviceName($cihaz_adi);
		echo json_encode(['success' => true]);
	} catch (Exception $e) {
		echo json_encode(['success' => false]);

	}

	exit();
}


if ($islem == 'save_user') {

	$uid = $_POST['uid'];
	$userid = $_POST['userid'];
	$name = $_POST['name'];
	$password = $_POST['password'];
	$role = $_POST['role'];

	try {

		if (!$userid) {
			$existingUsers = $zk->getUser();
			$maxId = 0;
			foreach ($existingUsers as $user) {
				if ($user[0] > $maxId) {
					$maxId = $user[0];
				}
			}
			$userid = $maxId + 1;
		}

		$zk->setUser($uid, $userid, $name, $password, $role);
		echo json_encode(['success' => true]);
	} catch (Exception $e) {
		echo json_encode(['success' => false]);

	}
	exit();
}