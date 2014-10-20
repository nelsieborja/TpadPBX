<?php
use Forge\Validator;

class CallgroupModel extends \Model\Base {

    private $rules = array(
        'customer_id' => 'required', 
        'code' => 'required', 
        'description' => 'required', 
        'name' => 'required'
    );

    public function getAll($customer, $callpickup_id = '', $page = NULL, $keywords = '') {

        $result = array();
        $options = array();

        $limit = 15;

        if ($page) {
            if (!$page || $page < 0) {
                $page = 1;
            }
            $start = ($page - 1) * $limit;
            $options['start'] = $start;
            $options['limit'] = $limit;
        }

        $options['total'] = true;
        $options['keywords'] = $keywords;

        $res = \AD\Pbx\CallgroupAd::getAll($customer, $callpickup_id, $options);

        if ($res['status'] == 'ERROR') {
            return $res;
        }

        $result = array(
            'status' => 'SUCCESS', 
            'rows' => $res['data'], 
            'count' => count($res['data']), 
            'total' => $res['total'], 
            'start' => (isset($options['start']) ? $options['start'] : 0), 
            'num_pages' => ceil($res['total'] / $limit)
        );
        return $result;
    }

    public function update($data) {

        $this -> rules['callpickup_id'] = 'required';

        $validator = new Validator($data, $this -> rules);

        if ($validator -> fails()) {
            throw new \ValidationException($validator -> all());
            return array('status' => 'ERROR', 'message' => 'Validation error');
        }

        if (!\AD\Pbx\ManagerAd::pbxCheckNumberinfo($data['customer_id'], $data['code'], $data['callpickup_id'])) {
            return array('status' => "ERROR", 'message' => 'The number ' . $data['code'] . ' is already being used!');
        }

        $oldcode = '';

        $resCallgroup = \AD\Pbx\CallgroupAd::getAll($data['customer_id'], $data['callpickup_id']);

        if ($resCallgroup['status'] == 'SUCCESS') {
            
            $row = $resCallgroup['data'][0];

            if (!$row) {
                return array('status' => 'ERROR', 'message' => 'Record not found for call pickup.');
            }

            $oldcode = $row['callpickup_code'];
        } else {
            // in case of error
            return $resCallgroup;
        }

        if (!\AD\Pbx\ManagerAd::deleteAsteriskExtension($data['customer_id'], $data['extenm'])) {
            return array('status' => 'ERROR', 'message' => 'Unable to delete asteric extension.');
        }

        if (\AD\Pbx\CallgroupAd::updateCallgroup($data)) {
            \AD\Pbx\ManagerAd::pbxUpdateNumberinfo($data['customer_id'], $data['code'], 'PICKUP', $data['callpickup_id']);
            \AD\Pbx\ManagerAd::createAsteriskExtension($data['customer_id'], $data['code'], 'AGI', 'agi.php');

            return array('status' => 'SUCCESS', 'message' => 'Record updated successfully!');
        }
        return array('status' => 'ERROR', 'message' => 'Uknown error accoured while updating record!');
    }

    public function create($data) {

        $validator = new Validator($data, $this -> rules);

        if ($validator -> fails()) {
            throw new \ValidationException($validator -> all());
            return array('status' => 'ERROR', 'message' => 'Validation error');
        }

        if (!\AD\Pbx\ManagerAd::pbxCheckNumberinfo($data['customer_id'], $data['code'], $data['callpickup_id'])) {
            return array('status' => "ERROR", 'message' => 'The number ' . $data['code'] . ' is already being used!');
        }

        if (\AD\Pbx\CallgroupAd::createCallgroup($data) !== false) {
            
            \AD\Pbx\ManagerAd::pbxUpdateNumberinfo($data['customer_id'], $data['code'], 'PICKUP', $data['callpickup_id']);
            \AD\Pbx\ManagerAd::createAsteriskExtension($data['customer_id'], $data['code'], 'AGI', 'agi.php');

            return array('status' => 'SUCCESS', 'message' => 'Record created successfully!');
        }
        return array('status' => 'ERROR', 'message' => 'Uknown error accoured while creating record!');
    }

    public function delete($data) {

        $code = '';

        $resCallgroup = \AD\Pbx\CallgroupAd::getAll($data['customer_id'], $data['callpickup_id']);

        if ($resCallgroup['status'] == 'SUCCESS') {
            $row = $resCallgroup['data'][0];
            if (!$row) {
                return array('status' => 'ERROR', 'message' => 'Record not found for call pickup.');
            }

            $code = $row['callpickup_code'];
        } else {
            // in case of error
            return $resCallgroup;
        }
        if (!\AD\Pbx\ManagerAd::deleteAsteriskExtension($data['customer_id'], $code)) {
            return array('status' => 'ERROR', 'message' => 'Unable to delete asteric extension.');
        }

        $row = \AD\Pbx\ManagerAd::getCallpickupExtension($data['callpickup_id'], $data['customer_id']);

        if ($row) {
            $callpickup_id = $row['callpickup_id'];
        }

        \AD\Pbx\ManagerAd::deleteCallpickupExten($callpickup_id);

        if (\AD\Pbx\ManagerAd::deleteCallpickup($callpickup_id, $data['customer_id'])) {
            \AD\Pbx\ManagerAd::deleteNumberInfo($data['customer_id'], $data['callpickup_id'], 'PICKUP');
            return array('status' => 'SUCCESS', 'message' => 'Record deleted successfully!');
        }

    }

}
