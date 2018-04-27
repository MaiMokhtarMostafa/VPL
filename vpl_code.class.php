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
require_once(__DIR__ . '/../../config.php');
require_once(dirname(__FILE__) . '/locallib.php');
require_once(dirname(__FILE__) . '/vpl_subscriber_code.class.php');
require_once(dirname(__FILE__) . '/vpl_comment.class.php');

defined('MOODLE_INTERNAL') || die();

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
    public $comments;
    public $userShared;
    public $userOwner;

    public function __construct() {
        $this->comments = array();
    }

    public function add_code_db($title, $discrption, $status, $vpl_submissions_id, $userid) {
        global $DB;
        $record = new stdClass();
        $record->title = $title;
        $record->discrption = $discrption;
        $date = date('Y-m-d H:i:s', time());
        $record->time = $date;
        $record->status = $status;
        $record->vpl_submissions_id = $vpl_submissions_id;
        $code_id = $DB->insert_record('vpl_code', $record, TRUE);
        if ($status) {
            $subscriber = new mod_vpl_subscriber_code($userid);
        }
        $subscriber->setDesc('new code');
        if (!$code_id) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function load_comments() {
        global $DB;
        $parms = array('vpl_submissions_id' => $this->vpl_submissions_id);
        $comments = $DB->get_records('vpl_code_comment', $parms);
        $comments = json_decode(json_encode($comments), True);
        foreach ($comments as $comment) {
            $commentClass = new mod_vpl_comment();
            $commentClass->id = $comment['id'];
            $commentClass->content = $comment['content'];
            $parms = array('id' => $comment['userid']);
            $user = $DB->get_record('user', $parms);
            $user = json_decode(json_encode($user), True);
            $commentClass->user->id = $user['id'];
            $commentClass->user->firstname = $user['firstname'];
            $commentClass->user->lastname = $user['lastname'];
            $this->comments[] = $commentClass;
        }
    }

    /*
     * This function is NOT tested and the table is NOT created 
     * 
    public function share_code($vpl_code_id, $userid) {
        global $DB;
        $record = new stdClass();
        $record->userid = $userid;
        $record->vpl_code_id = $vpl_code_id;
        $shared_id = $DB->insert_record('vpl_shared_code', $record, TRUE);
        if (!$shared_id) {
            return FALSE;
        } else {
            return TRUE;
        }
    } 
     * 
     */

}

?>