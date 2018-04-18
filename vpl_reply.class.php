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

class mod_vpl_reply {

    public $id;
    public $content;
    public $user;

    public function __construct() {
        $this->user = new stdClass();
    }

    public function delete_reply() {
        global $DB;
        $DB->delete_records('vpl_code_reply', array('id' => $this->id));
    }

    public function edit_reply($Editedcontent) {
        global $DB;
        $record = new stdClass();
        $record->content = $Editedcontent;
        $DB->update_record('vpl_code_reply', $record);
        
    }

}

?>