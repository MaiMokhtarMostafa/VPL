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
require_once(dirname(__FILE__).'/../vpl_code.class.php');
require_once(dirname(__FILE__).'/../views/sh_factory.class.php');
require_once(dirname(__FILE__).'/../vpl_manage_view.class.php');
include_once(dirname(__FILE__).'/../vpl_subscriber_code.class.php');
include_once(dirname(__FILE__).'/../vpl_subscribee_code.class.php');
include_once(dirname(__FILE__).'/../views/socialView.php');


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

if(isset($_POST['comm'])){
    $vpl_submissions_id = $_POST['comm'];
    $code = new mod_vpl_code();
    $code->vpl_submissions_id=$vpl_submissions_id;
    $code->load_comments();
    foreach($code->comments as $comment){
        $comment->user->profileimage = $OUTPUT->user_picture($comment->user, array('size'=>50));
    }


    echo json_encode($code->comments);
    die();
}

if(isset($_POST['comment_id_reply'])){
    $comment_id= $_POST['comment_id_reply'];
    $comment = new mod_vpl_comment();
    $comment->id=$comment_id;
    $comment->load_replies();
    foreach($comment->replies as $reply){
        $reply->user->profileimage = $OUTPUT->user_picture($reply->user, array('size'=>50));
    }


    echo json_encode($comment->replies);
    die();
}


if(isset($_POST['comment_id_delete'])){
    $comment_id= $_POST['comment_id_delete'];
    $comment = new mod_vpl_comment();
    $comment->id=$comment_id;
    $comment->delete_comment();
    echo json_encode('success');
    die();
}


if(isset($_POST['comment_id_Edit'])){
    $comment_id= $_POST['comment_id_Edit'];
    $comment = new mod_vpl_comment();
    $comment->id=$comment_id;
    $comment->content=$_POST['comment_content_Edit'];
    $comment->edit_comment();
    echo json_encode($comment);
    die();
}

if(isset($_POST['reply_id_Edit'])){
    $reply_id= $_POST['reply_id_Edit'];
    $reply = new mod_vpl_reply();
    $reply->id=$reply_id;
    $reply->content=$_POST['reply_content_Edit'];
    $reply->edit_reply();
    echo json_encode($reply);
    die();
}

if(isset($_POST['reply_id_delete'])){
    $reply_id= $_POST['reply_id_delete'];
    $reply = new mod_vpl_reply();
    $reply->id=$reply_id;
    $reply->delete_reply();
    echo json_encode('success');
    die();
}


if(isset($_POST['content_to_add'])){
    $content= $_POST['content_to_add'];
    $comment = new mod_vpl_comment();
    $comment->content=$content;
    $comment->user->id=$USER->id;
    $comment->vpl_submissions_id=$_POST['val_submission_add_comment'];
    $comment->add_comment();
    $comment->user->profileimage = $OUTPUT->user_picture($comment->user, array('size'=>50));
    echo json_encode($comment);
    die();
}

if(isset($_POST['content_reply_to_add'])){
    $content= $_POST['content_reply_to_add'];
    $reply = new mod_vpl_reply();
    $reply->content=$content;
    $reply->user->id=$USER->id;
    $reply->vpl_code_comment_id=$_POST['comment_id_to_add_reply'];
    $reply->add_reply();
    $reply->user->profileimage = $OUTPUT->user_picture($reply->user, array('size'=>50));
    echo json_encode($reply);
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
if(isset($_POST['vpl_code_id_to_share'])){
    $user_id = $_POST['user_id'];
    $vpl_code_id = $_POST['vpl_code_id_to_share'];
    echo mod_vpl_manage_view::share_code($vpl_code_id, $userid);
    die();
}


// Print header.
$vpl->print_header( get_string( 'submissionview', VPL ) );
$vpl->print_view_tabs( basename( __FILE__ ) );

// Display submission.


print_header_table();
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
                <a href="javascript:LoadCode(\'' . $desc . '\', ' . $code->vpl_submissions_id . ', ' . $id . ',' . $userid . ')" title="View"><img src="../icons/view.png" alt="view"></a>';

             if($code->subscribe){
                 echo  '<a id="sub-href-' . $code->userId . '" href="javascript:subscribe(' . $userid . ', ' . $code->userId . ', 0)" title="UnSubscribe"><img id="sub-image-' . $code->userId . '" src="../icons/unsubscribed.png" alt="UnSubscribe"></a>';
             } else {
                echo  '<a id="sub-href-' . $code->userId . '" href="javascript:subscribe(' . $userid . ', ' . $code->userId . ',1)" title="Subscribe"><img id="sub-image-' . $code->userId . '" src="../icons/subscribed.png" alt="Subscribe"></a>';
             }
             echo '<a href="javascript:share_code('. $code->id . ','. $userid .')" title="Share" style="padding:6px"><img src="../icons/share.png" alt="Share" ></a>';
            
            echo '</td>
            </tr>';  
         }
            
           
 echo'      </tbody>
    </table>
</div>
';

load_data();


    
$vpl->print_footer();
