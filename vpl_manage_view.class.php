<?php
// This file is part of VPL for Moodle - http://vpl.dis.ulpgc.es/
//
// VPL for Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// VPL for Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with VPL for Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * VPL class definition
 *
 * @package mod_vpl
 * @copyright 2013 onwards Juan Carlos Rodríguez-del-Pino
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author Juan Carlos Rodríguez-del-Pino <jcrodriguez@dis.ulpgc.es>
 */
defined('MOODLE_INTERNAL') || die();


require_once(dirname(__FILE__).'/vpl_code.class.php');
require_once(dirname(__FILE__).'/vpl_subscriber_code.class.php');



class mod_vpl_manage_view {

    public static function load_information_codes($vpl_id,$userid)
    {
        $Observer   =   new mod_vpl_subscriber_code($userid);
        $subscriber =   $Observer->get_all_subscribes();

        global $DB;

        $user_ids = $DB->get_records_sql('SELECT distinct userid FROM {vpl_submissions} WHERE vpl = ? ', array( $vpl_id ));
        $user_ids = json_decode(json_encode($user_ids), True);

        $vpl_submissions=array();
        foreach ($user_ids as $user_id)
        {
            $parms = array('userid' => $user_id['userid'], 'id' => 'max(id)');
            $temps = $DB->get_records_sql('SELECT  * FROM {vpl_submissions} WHERE userid = ? AND vpl = ? ORDER BY id desc LIMIT 1', array( $user_id['userid'],$vpl_id ));
            foreach ($temps as $temp)
            {
                $vpl_submissions []=$temp;
            }

        }


        $codes = array();

        foreach ($vpl_submissions as $vpl_submission) {


            $vpl_submission = json_decode(json_encode($vpl_submission), True);

            $parms = array('id' => $vpl_submission['userid']);
            $user=$DB->get_records('user', $parms);


            $parms = array('vpl_submissions_id' => $vpl_submission['id'], 'status' => 1);
            $information_code = $DB->get_records('vpl_code', $parms);


            $information_code = json_decode(json_encode($information_code), True);
            $user = json_decode(json_encode($user), True);

            $code=new mod_vpl_code();
            $self_user=true;
            foreach ($information_code as $item_of_information_code)
            {
                $code->id     = $item_of_information_code['id'];
                $code->title  = $item_of_information_code['title'];
                $code->time   = $item_of_information_code['time'];
                $code->vpl_submissions_id = $item_of_information_code['vpl_submissions_id'];
                foreach ($user as $item_of_user)
                {
                    if($userid==$item_of_user['id'])
                    {
                        $self_user=false;
                    }
                    $code->name     =   $item_of_user['firstname'] .' '. $item_of_user['lastname'];
                    $code->userId   =   $item_of_user['id'];
                }
                if(in_array($code->userId, $subscriber))
                {
                    $code->subscribe=1;
                }
                else
                {
                    $code->subscribe=0;
                }
                if($self_user)
                {
                    $codes[]     =   $code;
                }
                $self_user=true;
                
            }


        }


        return $codes;
    }

    public static function print_submission_by_ID($submission_id) {
        global $DB;
        $subinstance2 = $DB->get_record( 'vpl_submissions', array (
                'id' => $submission_id
        ) );
         return $subinstance2;
    }
    
    public static function print_submission_Description($submission_id) {
        global $DB;
        $subinstance2 = $DB->get_record( 'vpl_code', array (
                'vpl_submissions_id' => $submission_id
        ) );
        
        $subinstance2 = json_decode(json_encode($subinstance2), True);
        return $subinstance2['discrption'];
    }


    public static function print_submission_Title($submission_id) {
        global $DB;
        $subinstance2 = $DB->get_record( 'vpl_code', array (
            'vpl_submissions_id' => $submission_id
        ) );

        $subinstance2 = json_decode(json_encode($subinstance2), True);
        return $subinstance2['title'];
    }
    public static function load_information_shared_codes($vpl_id,$userid)
    {   
        global $DB;
        $sharedCodesArr = array();
        $vpl_subscriber_code = new mod_vpl_subscriber_code($userid);
        $subscribers=$vpl_subscriber_code->get_all_subscribes();
            foreach($subscribers as $subscriber)
            {   
                $shared_codes = $DB->get_records( 'vpl_shared', array (
                'userid' => $subscriber
                ) );
                $userShared=$DB->get_record( 'user', array (
                'id' => $subscriber
                        ) );
                $userShared = json_decode(json_encode($userShared), True);
                $shared_codes = json_decode(json_encode($shared_codes), True);
                
                    foreach($shared_codes as $shared_code)
                        {  
                           $vpl_code = $DB->get_record( 'vpl_code', array (
                            'id' => $shared_code['vpl_code_id']
                            ) );
                        
                            $vpl_code = json_decode(json_encode($vpl_code), True);
                            $vpl_submission = $DB->get_record( 'vpl_submissions', array (
                            'id' => $vpl_code['vpl_submissions_id']
                            ) );
                            $vpl_submission = json_decode(json_encode($vpl_submission), True);
                            
                            echo 'sadsj';
                             $user = $DB->get_record( 'user', array (
                            'id' => $vpl_submission['userid']
                            ) );
                             

                            $user = json_decode(json_encode($user), True);
                            $code=new mod_vpl_code();
                            $code->userOwner= $user['firstname']." ".$user['lastname'];
                            $code->userShared= $userShared['firstname']." ".$userShared['lastname'];
                            $code->vpl_submissions_id=$vpl_submission['id'];
                            $code->userId=$user['id'];
                            $sharedCodesArr[]=$code;
                                                 
                            
                        }
            }
                            
                        
        
        return $sharedCodesArr;
    }
    public static function share_code($vpl_code_id,$userid)
    {
        global $DB;
        
        $vpl_shared = $DB->get_records( 'vpl_shared', array (
                            'userid' => $userid,
                            'vpl_code_id'=>$vpl_code_id
                            ) );
        
        if(count($vpl_shared)==0)
        {
            $record = new stdClass();
        $record->userid=$userid;//$userid
        $record->vpl_code_id = $vpl_code_id;//$vpl_code_id
        $DB->insert_record('vpl_shared', $record,TRUE);   
        }
           
         
    }
    public static function get_last_code_publish($vpl_id,$userid)
    {
        global $DB;
        $temps = $DB->get_records_sql('SELECT  * FROM {vpl_submissions} WHERE userid = ? AND vpl = ? ORDER BY id desc LIMIT 1', array( $userid , $vpl_id));
        foreach ($temps as $temp)
        {
            $vpl_submission =$temp;
        }

        $vpl_submission = json_decode(json_encode($vpl_submission), True);
        $parms = array('vpl_submissions_id' => $vpl_submission['id']);
        $information_code = $DB->get_records('vpl_code', $parms);


        $information_code = json_decode(json_encode($information_code), True);

        $code=new mod_vpl_code();
        foreach ($information_code as $item_of_information_code)
        {
            $code->id     = $item_of_information_code['id'];
            $code->title  = $item_of_information_code['title'];
            $code->time   = $item_of_information_code['time'];
            $code->vpl_submissions_id = $item_of_information_code['vpl_submissions_id'];
            $code->discrption=$item_of_information_code['discrption'];

        }
        return $code;
    }

}
?>