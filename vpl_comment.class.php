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
require_once(dirname(__FILE__) . '/vpl_reply.class.php');

class mod_vpl_comment {

    public $id;
    public $content;
    public $replies;
    public $user;
    public $vpl_submissions_id;

    public function __construct() {
        $this->user = new stdClass();
        $this->replies = array();
    }

    public function load_replies() {
        global $DB;
        $parms = array('vpl_code_comment_id' => $this->id);
        $replies = $DB->get_records('vpl_code_reply', $parms);
        $replies = json_decode(json_encode($replies), True);
        foreach ($replies as $reply) {
            $replyClass = new mod_vpl_reply();
            $replyClass->id = $reply['id'];
            $replyClass->content = $reply['content'];
            $parms = array('id' => $reply['userid']);
            $user = $DB->get_record('user', $parms);
            $user = json_decode(json_encode($user), True);
            $replyClass->user->id = $user['id'];
            $replyClass->user->firstname = $user['firstname'];
            $replyClass->user->lastname = $user['lastname'];
            $this->replies[] = $replyClass;
        }
    }

    public function delete_comment() {
        global $DB;
        $DB->delete_records('vpl_code_comment', array('id' => $this->id));
    }

    public function edit_comment() {
        global $DB;
        $record = new stdClass();
        $record->content = $this->content;
        $record->id = $this->id;
        $DB->insert_records('vpl_code_comment', $record);
    }

    public function add_comment() {
        global $DB;
        $record = new stdClass();
        $record->contant = $this->content;
        $record->userid = $this->user;
        $record->vpl_submissions_id = $this->vpl_submissions_id;

        $comment_id = $DB->insert_record('vpl_code_comment', $record, TRUE);
        if (!$comment_id) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

}

?>