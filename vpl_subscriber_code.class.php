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
        $conditions=array('subscribee' =>$observer->id , 'subscriber' => $this->id );
        $DB->delete_records('vpl_subscribe', $conditions);
    }

    public function subscribeObserver($observer)
    {
        global $DB;
        $record = new stdClass();
        $record->subscribee = $observer->id;
        $record->subscriber=$this->id;
        $DB->insert_record('vpl_subscribe', $record, TRUE);
    }

    public function setDesc($desc)
    {
        $this->desc=$desc;
        $this->notifyOpservers();
    }

    public function notifyOpservers()
    {
        $subscribers=$this->get_all_subscribees();

        $self_user=$this->getUserObj($this->id);
        foreach($subscribers as $subscriber)
        {
            $message = new \core\message\message();
            $message->component = 'moodle';
            $message->name = 'instantmessage';
            $message->userfrom = $self_user;
            $message->userto = $this->getUserObj($subscriber);
            $message->subject = 'new public code has been submitted';
            $message->fullmessage = 'message body';
            $message->fullmessageformat = FORMAT_MARKDOWN;
            $message->fullmessagehtml = '<p>message body</p>';
            $message->smallmessage = $this->desc;
            $message->notification = '0';
            $message->contexturl = 'http://GalaxyFarFarAway.com';
            $message->contexturlname = 'Context name';
            $message->replyto = "info@example.com";
            //$message->courseid = $id;
            message_send($message);
        }
        
    }
    
    public function get_all_subscribes()
    {
        global $DB;
        $parms = array('subscribee' => $this->id);
        $users=$DB->get_records('vpl_subscribe', $parms);
        $users = json_decode(json_encode($users), True);
        $subscriber=array();
        foreach ($users as $user)
        {
            $subscriber[]= $user['subscriber'];
        }
        return $subscriber;
    }

    public function get_all_subscribees()
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
    
    
    private function getUserObj($userid){
        global $DB;
        $userObj = $DB->get_record( 'user', array (
                'id' => $userid
        ));
        if($userObj){
            return $userObj;
        }
        return false;
    }



}
