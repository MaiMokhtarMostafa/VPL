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
require_once(dirname(__FILE__).'/vpl_reply.class.php');

class mod_vpl_comment {

    public $id;
    public $content;
    public $replyes;
    public $user;

    public function __construct()
    {
        $this->user=new stdClass();
        $this->replyes=array();
    }

    public function load_replyes() {
        global $DB;
        $parms = array('vpl_code_comment_id' => $this->id);
        $replyes = $DB->get_records('vpl_code_reply', $parms);
        $replyes = json_decode(json_encode($replyes), True);
        foreach ($replyes as $reply)
        {
            $replyClass          =   new mod_vpl_reply();
            $replyClass->id      =   $reply['id'];
            $replyClass->content =   $reply['content'];
            $parms = array('id' => $reply['userid']);
            $user = $DB->get_record('user', $parms);
            $user = json_decode(json_encode($user), True);
            $replyClass->user->id=$user['id'];
            $replyClass->user->firstname=$user['firstname'];
            $replyClass->user->lastname=$user['lastname'];
            $this->replyes[]=$replyClass;
        }
    }

    public function delete_comment() {
        global $DB;
        $DB->delete_records('vpl_code_comment', array('id' => $this->id));

    }


    public function edit_comment() {


    }

}

?>