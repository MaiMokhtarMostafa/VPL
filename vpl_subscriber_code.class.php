<?php
defined('MOODLE_INTERNAL') || die();
require_once(dirname(__FILE__).'/vpl_subject.interface.php');

class mod_vpl_subscriber_code implements mod_vpl_subject
{
    public $id;
    public $desc;
    public function __construct($id)
    {
        $this->id=$id;
    }

    public function unSubscribeObserver($observer)
    {
        global $DB;
        $conditions=array('subscribee' => $this->id , 'subscriber' => $observer->id);
        $DB->delete_records('vpl_subscribe', $conditions);
    }

    public function subscribeObserver($observer)
    {
        global $DB;
        $record = new stdClass();
        $record->subscribee = $this->id;
        $record->subscriber=$observer->id;
        $DB->insert_record('vpl_subscribe', $record, TRUE);
    }

    public function setDesc($desc)
    {
        $this->desc=$desc;
        $this->notifyOpservers();
    }

    public function notifyOpservers()
    {

    }
    public function get_all_subscribes()
    {
        global $DB;
        $parms = array('subscriber' => $this->id);
        $users=$DB->get_records('vpl_subscribe', $parms);
        $users = json_decode(json_encode($users), True);
        $subscriber=array();
        foreach ($users as $user)
        {
            $subscriber[]= $user['subscribee'];
        }
        return $subscriber;
    }



}
