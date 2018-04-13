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




class mod_vpl_manage_view {








    public static function load_information_codes($vpl_id)
    {

        global $DB;
        $parms = array('vpl' => $vpl_id);
        $vpl_submissions = $DB->get_records('vpl_submissions', $parms);

        $codes = array();

        foreach ($vpl_submissions as $vpl_submission) {


            $vpl_submission = json_decode(json_encode($vpl_submission), True);
            $parms = array('id' => $vpl_submission['userid']);
            $user=$DB->get_records('user', $parms);


            $parms = array('vpl_submissions_id' => $vpl_submission['id'], 'status' => 1);
            $information_code=$DB->get_records('vpl_code', $parms);


            $information_code = json_decode(json_encode($information_code), True);
            $user = json_decode(json_encode($user), True);

            $code=new mod_vpl_code();

            foreach ($information_code as $item_of_information_code)
            {
                $code->id     = $item_of_information_code['id'];
                $code->title  = $item_of_information_code['title'];
                $code->time   = $item_of_information_code['time'];
                $code->vpl_submissions_id = $item_of_information_code['vpl_submissions_id'];
                foreach ($user as $item_of_user)
                {
                    $code->name = $item_of_user['firstname'] .' '. $item_of_user['lastname'];
                }

                $codes[]     =   $code;
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

}
?>