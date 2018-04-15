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
require_once(dirname(__FILE__).'/vpl_subscriber_code.class.php');

class mod_vpl_code {

    public $id;
    public $title;
    public $discrption;
    public $status;
    public $vpl_submissions_id;
    public $time;
    public $name;
    public $userId;
    public $subscribe;

    public function add_code_db($title, $discrption, $status, $vpl_submissions_id,$userid) {

        global $DB;

        $record = new stdClass();
        $record->title = $title;
        $record->discrption = $discrption;
        $date = date ('Y-m-d H:i:s', time());
        $record->time=$date;
        $record->status=$status;
        $record->vpl_submissions_id = $vpl_submissions_id;
        $code_id =  $DB->insert_record('vpl_code', $record, TRUE);
        if($status){
            $subscriber=new mod_vpl_subscriber_code($userid);
        }
        $subscriber->setDesc('new code');
        if (!$code_id) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

}

?>