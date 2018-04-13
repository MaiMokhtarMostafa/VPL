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
 * View a submission
 *
 * @package mod_vpl
 * @copyright 2012 Juan Carlos Rodríguez-del-Pino
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author Juan Carlos Rodríguez-del-Pino <jcrodriguez@dis.ulpgc.es>
 */

require_once(__DIR__ . '/../../../config.php');
require_once(dirname(__FILE__).'/../locallib.php');
require_once(dirname(__FILE__).'/grade_form.php');
require_once(dirname(__FILE__).'/../vpl.class.php');
require_once(dirname(__FILE__).'/../vpl_submission.class.php');
require_once(dirname(__FILE__).'/../views/sh_factory.class.php');
require_once(dirname(__FILE__).'/../vpl_manage_view.class.php');
global $CFG, $USER;

require_login();
$id = required_param( 'id', PARAM_INT );
$userid = optional_param( 'userid', false, PARAM_INT );
$vpl = new mod_vpl( $id );


$PAGE->requires->css('/mod/vpl/css/jquery.dataTables.min.css',true);
$PAGE->requires->js('/mod/vpl/jscript/jquery-3.3.1.min.js',true);
$PAGE->requires->js('/mod/vpl/jscript/jquery.dataTables.min.js',true);


// Print header.
$vpl->print_header( get_string( 'submissionview', VPL ) );
$vpl->print_view_tabs( basename( __FILE__ ) );
// Display submission.

echo '
<div class="container">
    <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">

          <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Modal Header</h4>
                </div>
                <div class="modal-body">
                    <p>Some text in the modal.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="table-responsive">
    <table id="test" class="table table-hover table-bordered" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th style="text-align: center;">User name</th>
                <th style="text-align: center;">Title</th>
                <th style="text-align: center;">Submitted At</th>
                <th style="text-align: center;">Action</th>
            </tr>
        </thead>
        <tbody>';
         $codes=mod_vpl_manage_view::load_information_codes(1);
         foreach($codes as $code)
         {
             echo '<tr>';
             echo '<td>'.$code->name.'</td>';
             echo '<td>' .$code->title .'</td>';
             echo '<td>' . $code->time .'</td>';
             echo '<td id="action" style="text-align: center;">
                    <a data-toggle="modal" data-target="#myModal" href="javascript:void(0)" title="View"><img src="../icons/view.png" alt="view"></a>
                </td>
            </tr>';
             
         }
            
           
 echo'      </tbody>
    </table>
</div>
';

echo "
    <script>
        $(document).ready( function () {
            //$('#test').DataTable();
            console.log('Hello');
        });
    </script>
";

$vpl->print_footer();
