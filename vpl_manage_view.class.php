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
            $temps = $DB->get_records_sql('SELECT  * FROM {vpl_submissions} WHERE userid = ? ORDER BY id desc LIMIT 1', array( $user_id['userid'] ));
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
        $arr = array();
        $vpl_submission_id=$vpl_code_id= $DB->get_records_sql('SELECT  subscriber FROM {vpl_subscribe} WHERE subscribee = ? ',array( $userid ));
        $vpl_submission_id = json_decode(json_encode($vpl_submission_id), True);
        foreach($vpl_submission_id as $code)
         {
            //echo $code['subscriber'];
            $shared_codes= $DB->get_records_sql('SELECT vpl_code_id FROM {vpl_share} WHERE userid = ?',array( $code['subscriber']));
            $shared_codes = json_decode(json_encode($shared_codes), True);
            foreach($shared_codes as $shared_code)
             {
                //echo ($shared_code['vpl_code_id']) ;  
                $vpl_submission_ids=$vpl_code_id= $DB->get_records_sql('SELECT  vpl_submissions_id FROM {vpl_code} WHERE id = ? ',array( $shared_code['vpl_code_id']));
                $vpl_submission_ids = json_decode(json_encode($vpl_submission_ids), True);
                foreach($vpl_submission_ids as $vpl_submission_id)
                 {
                    //echo ($vpl_submission_id['vpl_submissions_id']) ;  
                    $userids= $DB->get_records_sql('SELECT userid  FROM {vpl_submissions} WHERE id = ? and vpl = ?',array( $vpl_submission_id['vpl_submission_id'],$vpl_id));
                    $userids = json_decode(json_encode($userids), True);
                    foreach($userids as $userid)
                     {
                        //echo ($userid['userid']) ;  
                        $users= $DB->get_records_sql('SELECT firstname , lastname  FROM {user} WHERE id = ? ',array( $userid['userid']));
                        $users = json_decode(json_encode($users), True);
                        foreach($users as $user)
                         {
                            echo ($user['firstname'].$user['lastname']) ;   
                        }
                    }
                 }
             }
         }
         
        
    }
    public static function share_code($vpl_code_id,$userid)
    {
        global $DB;
        $records= $DB->get_records_sql('SELECT  * FROM {vpl_share} WHERE userid = ? and vpl_code_id = ?',array( $userid,$vpl_code_id ));
        $records = json_decode(json_encode($records), True);
        if(sizeof($records)==0)
        {
            $record = new stdClass();
            $record->userid=$userid;//$userid
            $record->vpl_code_id = $vpl_code_id;//$vpl_code_id
            $DB->insert_record('vpl_shared', $record,TRUE);  
        }     
         
    }

}
?>