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
include_once(dirname(__FILE__).'/../vpl_subscriber_code.class.php');
include_once(dirname(__FILE__).'/../vpl_subscribee_code.class.php');

global $CFG, $USER;

$context = context_system::instance();

require_login();

// sets the context
$PAGE->set_context($context);


$id = required_param( 'id', PARAM_INT );
$userid = optional_param( 'userid', false, PARAM_INT );
$vpl = new mod_vpl( $id );
$current_instance = $vpl->get_instance();

$url = new moodle_url('/mod/vpl/forms/socialCodes.php', array('id'=>$id, 'userid' => $userid));
$PAGE->set_url($url); 

$PAGE->requires->css('/mod/vpl/css/jquery.dataTables.min.css',true);
$PAGE->requires->js('/mod/vpl/jscript/jquery-3.3.1.min.js',true);
$PAGE->requires->js('/mod/vpl/jscript/jquery.dataTables.min.js',true);


if(isset($_POST['vpl_submissions_id'])){
    $vpl_submissions_id = $_POST['vpl_submissions_id'];
    $subinstance=mod_vpl_manage_view::print_submission_by_ID($vpl_submissions_id);
    $submission = new mod_vpl_submission( $vpl,$subinstance );
    $code = $submission->get_submitted_fgm()->print_files();
    die();
}

if(isset($_POST['current_user_id'])){
    $current_user_id = $_POST['current_user_id'];
    $user_id = $_POST['user_id'];
    $status = $_POST['status'];
    $subscriber= new mod_vpl_subscriber_code($user_id);
    $subscribee= new mod_vpl_subscribee_code($subscriber , $current_user_id);
    if($status){
        $subscribee->subscribe();
    } else {
        $subscribee->unsubscribe();
    }
    die();
}


// Print header.
$vpl->print_header( get_string( 'submissionview', VPL ) );
$vpl->print_view_tabs( basename( __FILE__ ) );

/*
$message = new \core\message\message();
$message->component = 'moodle';
$message->name = 'instantmessage';
$message->userfrom = $USER;
$message->userto = $toUser;
$message->subject = 'message subject 1';
$message->fullmessage = 'message body';
$message->fullmessageformat = FORMAT_MARKDOWN;
$message->fullmessagehtml = '<p>message body</p>';
$message->smallmessage = 'small message';
$message->notification = '0';
$message->contexturl = 'http://GalaxyFarFarAway.com';
$message->contexturlname = 'Context name';
$message->replyto = "ahmed.sherif.fcih@gmail.com";
$message->courseid = $id;



$messageid = message_send($message);
echo $messageid;*/



// Email section

/*$toUser = mod_vpl_manage_view::getUserObj(2);
$fromUser = mod_vpl_manage_view::getUserObj(3);
$subject = 'Welcome';
$messageHtml = '<h1>hello</h1>';
$log = email_to_user($fromUser, $toUser, $subject, $messageHtml);
echo $log;exit;*/

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
                    <h4 class="modal-title">View Code</h4>
                </div>
                <div class="modal-body">
                    <h2>Description:</h2>
                    <p id="Description"></p>
                    <h2>Code:</h2>
                    <div id="code"></div>
                </div> 
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
</div>

<div id="test"></div>

<div class="table-responsive">
    <table id="codes" class="table table-hover table-bordered" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th style="text-align: center;">User name</th>
                <th style="text-align: center;">Title</th>
                <th style="text-align: center;">Submitted At</th>
                <th style="text-align: center;">Action</th>
            </tr>
        </thead>
        <tbody>';
        // loading all public codes
         $codes = mod_vpl_manage_view::load_information_codes($current_instance->id, $userid);
         foreach($codes as $code)
         {
            $desc = mod_vpl_manage_view::print_submission_Description($code->vpl_submissions_id);
            echo '<tr>';
            echo '<td>'.$code->name.'</td>';
            echo '<td>' .$code->title .'</td>';
            echo '<td>' . $code->time .'</td>';
            echo '<td id="action" style="text-align: center;">
                <a href="javascript:LoadCode(\'' . $desc . '\', ' . $code->vpl_submissions_id . ')" title="View"><img src="../icons/view.png" alt="view"></a>';
             if($code->subscribe){
                 echo  '<a id="sub-href" href="javascript:subscribe(' . $userid . ', ' . $code->userId . ', 0)" title="UnSubscribe"><img id="sub-image" src="../icons/unsubscribed.png" alt="UnSubscribe"></a>';
             } else {
                echo  '<a id="sub-href" href="javascript:subscribe(' . $userid . ', ' . $code->userId . ',1)" title="Subscribe"><img id="sub-image" src="../icons/subscribed.png" alt="Subscribe"></a>';
             }
            echo '</td>
            </tr>';  
         }
            
           
 echo'      </tbody>
    </table>
</div>
';

echo "
    <script>
        function LoadCode(desc, vpl_submissions_id){
            $.ajax({
                type: 'POST',
                dataType: 'html',
                url: '',
                data:{vpl_submissions_id:vpl_submissions_id},
                success: function(data){
                    $('#Description').html(desc);
                    $('#code').html(data);
                    $('#myModal').modal('show'); 
                }
            });
        }
        
        function subscribe(current_user_id, user_id,status){
            $.ajax({
                type: 'POST',
                url: '',
                data:{current_user_id:current_user_id, user_id:user_id, status:status},
                success: function(data){
                    
                }
            });
            if(status == 1){
                $('#sub-image').attr('src', '../icons/unsubscribed.png');
                $('#sub-image').attr('alt', 'UnSubscribe');
                $('#sub-href').attr('href', 'javascript:subscribe(2, 3,0)');
                $('#sub-href').attr('title', 'UnSubscribe');
            } else {
                $('#sub-image').attr('src', '../icons/subscribed.png');
                $('#sub-image').attr('alt', 'Subscribe');
                $('#sub-href').attr('href', 'javascript:subscribe(2, 3,1)');
                $('#sub-href').attr('title', 'Subscribe');
            }
        }
    </script>
";

echo "
    <script>
        $(document).ready( function () {
            $('#codes').DataTable({
                'columns': [
                    { 'width': '20%' },
                    { 'width': '20%' },
                    { 'width': '20%' },
                    { 'width': '20%' }
                ]
            });
            $.ajax({
                type: 'POST',
                url: '../ajax/test',
                dataType: 'html',
            });
        });
    </script>
";

$vpl->print_footer();
