<?php
class Pbxmanagement extends \Forge\Controller {
	
	protected static $module = 'pbxmanagement';
	protected static $extension_management = 'extension_management';
	protected static $extra_telephone_features = 'extra_telephone_features';
	protected static $telephony_management = 'telephony_management';	
	
	public static function index() {
		return Blade::make(self::$module.'._menu');
	}
	
	// Extension Management
	public static function extensions() {	
		$ObjManagerModel = new ManagerModel();
		
		$dailplans = $ObjManagerModel->listDailplans();
		$callpickups = $ObjManagerModel->listCallpickup();
		$phonemodels = $ObjManagerModel->phoneModelStore();
		$callplans = $ObjManagerModel->listCallplan();
		$musiconhold = $ObjManagerModel->listMusicOnHold();
		
		$data = array();
		$data['dialplans'] = $dailplans;
		$data['callpickups'] = $callpickups;
		$data['phonemodels'] = $phonemodels;
		$data['callplans'] = $callplans;
		$data['musiconhold'] = $musiconhold;
		
		return Blade::make(self::$module.'.'.self::$extension_management.'.extensions')->with('data', $data);
	}
	
	public static function ajax_extension_list() {
		$page  	  = (!empty(Input::post('page')) ? Input::post('page') : 1);
		$keywords = (!empty(Input::post('keywords')) ? Input::post('keywords') : '');
		
		$ObjExtensionModel = new ExtensionModel();
        $record = $ObjExtensionModel->getAll(5, '', $page, $keywords);
		echo json_encode($record);
	}
	
	public static function ajax_get_account() {
		$account_id  = (!empty(Input::post('account_id')) ? Input::post('account_id') : '');
		
		if ($account_id == '') {
			return array('status' => 'ERROR', 'message' => 'Account ID can\'t be empty');
		}
		
		$ObjExtensionModel = new ExtensionModel();
		$ObjManagerModel = new ManagerModel();
		
		//$callforword = $ObjManagerModel->listCallforward(1, $account_id);
		//$callforword = $ObjManagerModel->listCallforward(1, 360);
		//$speeddial = $ObjManagerModel->listSpeeddial(1, 360);
		
		$record = $ObjExtensionModel->getAll(5, $account_id);
		
		//$record['callforward'] = $callforword;
		//$record['speeddial'] = $speeddial;
		
		echo json_encode($record);
	}
	
	public static function getFailoverApp() {
	
		$type = Input::post('type');
		$ObjManagerModel = new ManagerModel();
		
		$res = array();
		
		if ($type == 'ANNOUNCEMENT') {
			$res = $ObjManagerModel->listAnnouncement();
		} else if ($type == 'EXTEN') {
			$ObjExtensionModel = new ExtensionModel();
			$data = $ObjExtensionModel->getAll(Config::get('app._TENANT_'));
			$res = $data['rows'];
		} else if ($type == 'IVR') {
			$res = $ObjManagerModel->listIVR();
		} else if ($type == 'RINGGROUP') {
			$res = $ObjManagerModel->listRinggroup();
		} else if ($type == 'VOICEMAIL') {
			$res = $ObjManagerModel->listVoicemail();
		}
		echo json_encode($res);
	}
	
	// Call Group
	public static function call_group() {
		return Blade::make(self::$module.'.'.self::$extra_telephone_features.'.call_group');
	}
	
	public static function callgroup_list() {
		$page  	  = (!empty(Input::post('page')) ? Input::post('page') : 1);
		$keywords = (!empty(Input::post('keywords')) ? Input::post('keywords') : '');
		
		$ObjCallgroupModel = new CallgroupModel();
        $record = $ObjCallgroupModel->getAll(5, '', $page, $keywords);
		echo json_encode($record);
	}
	
	public static function get_callgroup() {
		$callpickup_id  = (!empty(Input::post('callpickup_id')) ? Input::post('callpickup_id') : '');
		
		if ($callpickup_id == '') {
			return array('status' => 'ERROR', 'message' => 'Call Pickup ID can\'t be empty');
		}
		
		$ObjCallgroupModel = new CallgroupModel();
		$record = $ObjCallgroupModel->getAll(5, $callpickup_id);
		echo json_encode($record);
	}
	
	public static function save_callgroup() {
		
		$data = Input::post();
		
		$data['customer_id'] = 5;
		$data['extenm'] = 15;
		
		$ObjCallgroupModel = new CallgroupModel();
		
		// update
		if (!empty($data['callpickup_id'])) {
			$res = $ObjCallgroupModel->update($data);
			echo json_encode($res);
			return;
		}
		
		// create
		$res = $ObjCallgroupModel->create($data);
		echo json_encode($res);
	}
	
	public static function delete_callgroup() { 
		$data = Input::post();
		$data['customer_id'] = 5;
		
		$ObjCallgroupModel = new CallgroupModel();
		$ObjCallgroupModel->delete($data);
	}
	
	
	
	public static function credit_management() {
		return Blade::make(self::$module.'.'.self::$extension_management.'.credit_management');
	}
	
	public static function phone_directory_management() {
		return Blade::make(self::$module.'.'.self::$extension_management.'.phone_directory_management');
	}
	
	// Extra Telephone Features
	public static function features_management() {
		return Blade::make(self::$module.'.'.self::$extra_telephone_features.'.features_management');
	}
	
	
	
	// Telephony Management
	public static function ring_group() {
		return Blade::make(self::$module.'.'.self::$telephony_management.'.ring_group');
	}
	
	
	// Announcement Management
	public static function announcement() {
		return Blade::make(self::$module.'.'.self::$telephony_management.'.announcement');
	}
	
	
	public static function ajax_announcement_list() {
		$page  	  = (!empty(Input::post('page')) ? Input::post('page') : 1);
		$keywords = (!empty(Input::post('keywords')) ? Input::post('keywords') : '');
		
		$ObjAnnouncementModel = new AnnouncementModel();
        $record = $ObjAnnouncementModel->getAll(5, '', $page, $keywords);
		echo json_encode($record);
	}
}