<?php
function print_header_table()
{
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
                    
                    <h3 class="my-3">Comments</h3>
                    <ul class="list-group" id="commList">
                    </ul>
                    
               </div>
               <form method="POST" id="upload_comment" action="" enctype="multipart/form-data">
                  <textarea class="form-control dir-auto" id="comment" style="width: 60%;margin-left: 10%;margin-bottom: 3%;" name="comment" rows="3" placeholder="Write a comment.." required=""></textarea>
                  <input class="btn btn-default" id="addComm" type="submit" name="send" value="Write Comment" style="margin-left: 15%;margin-bottom: 2%;">
                  <a class="btn btn-default" href="../views/downloadsubmission.php" id="dwn-button" style="margin-left: 9%;margin-bottom: 2%;">Download Code</a>
               </form>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

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
}

function load_data()
{global  $USER;
    echo "
    <script>
        function LoadCode(desc, vpl_submissions_id, crid, userid){
            // Load Code
            $.ajax({
                type: 'POST',
                dataType: 'html',
                url: '',
                data:{vpl_submissions_id:vpl_submissions_id},
                success: function(data){
                    $('#dwn-button').attr('href', $('#dwn-button').attr('href') + '?id=' + crid + '&userid=' + userid + '&submissionid=' + vpl_submissions_id);
                    // Load Comments
                    $.ajax({
                        type: 'POST',
                        url: '',
                        dataType: 'json',
                        data:{comm:vpl_submissions_id},
                        success: function(comments){
                            var html_comm = '';
                            for(var i =0 ;i<comments.length;i++){
                                html_comm += `
                                    <li class='list-group-item' id='wholecomment-` + comments[i].id + `' style='border-radius: 3pc; margin-bottom:5px;'>
                                        ` + comments[i].user.profileimage + `
                                        <b style='margin:5px 0 10px 5px; position:absolute; color:black;'>` + comments[i].user.firstname + ` ` + comments[i].user.lastname + `</b></a><br>
                                        <div id='comment-` + comments[i].id+ `' style='margin: -28px 0px 0px 75px; width: 88%; overflow-wrap: break-word; color: black; white-space: pre;'>` + comments[i].content + `<br><span style='color:blue; cursor:pointer;' onclick='load_Replies(`+comments[i].id+`)')>Replies</span>
                                `;
                                if(comments[i].user.id==".$USER->id.")
                                    {
                                        html_comm +=`
                                  <span style='color:red; cursor:pointer; margin-left: 10px;' onclick='deleteComment(`+comments[i].id+`)')>Delete</span>
                                `;
                                    }
                                    html_comm +=`
                                  </div>
                                        <ul class='list-group' style='margin: 10px;' id='repcomment-`+comments[i].id+`'></ul>
                                    </li>
                                `;
                                    
                                
                            }
                            $('#commList').html(html_comm);
                        }
                    });
                    $('#Description').html(desc);
                    $('#code').html(data);
                    $('#myModal').modal('show'); 
                }
            });
        }
        function load_Replies(comment_id) {
          $.ajax({
                        type: 'POST',
                        url: '',
                        dataType: 'json',
                        data:{comment_id_reply:comment_id},
                        success: function(replies){
                            var html_reply = '';
                            for(var i =0 ;i<replies.length;i++){
                                html_reply += `
                                    <li class='list-group-item' id='Reply-` + replies[i].id + `' style='border-radius: 3pc; margin-bottom:5px;'>
                                        ` + replies[i].user.profileimage + `
                                        <b style='margin:5px 0 10px 5px; position:absolute; color:black;'>` + replies[i].user.firstname + ` ` + replies[i].user.lastname + `</b></a><br>
                                        <div id='comment-` + replies[i].id+ `' style='margin: -28px 0px 0px 75px; width: 88%; overflow-wrap: break-word; color: black; white-space: pre;'>` + replies[i].content + `
                                        
                                `;
                                if(replies[i].user.id==".$USER->id.")
                                    {
                                        html_reply +=`
                                  <span style='color:red; cursor:pointer; margin-left: 10px;' onclick='deleteReply(`+replies[i].id+`)')>Delete</span>
                                `;
                                    }
                                    html_reply +=`</div>
                                    </li>
                                `;
                                
                            }
                            $('#repcomment-'+comment_id).html(html_reply);
                        }
                    });
        }
        
        
        function deleteComment(comment_id) {
          $.ajax({
                        type: 'POST',
                        url: '',
                        dataType: 'json',
                        data:{comment_id_delete:comment_id},
                        success: function(data){
                            
                            }
                            
                        });  
          $('#wholecomment-'+comment_id).remove();
        }
        
        
        function deleteReply(reply_id) {
          $.ajax({
                        type: 'POST',
                        url: '',
                        dataType: 'json',
                        data:{reply_id_delete:reply_id},
                        success: function(data){
                            
                            }
                         });
                $('#Reply-'+reply_id).remove();

        }
        
        
        function subscribe(current_user_id, user_id,status){
            $.ajax({
                type: 'POST',
                url: '',
                data:{current_user_id:current_user_id, user_id:user_id, status:status}
            });
            if(status == 1){
                $('#sub-image-' + user_id).attr('src', '../icons/unsubscribed.png');
                $('#sub-image-' + user_id).attr('alt', 'UnSubscribe');
                $('#sub-href-' + user_id).attr('href', 'javascript:subscribe(' + current_user_id + ', ' + user_id + ',0)');
                $('#sub-href-' + user_id).attr('title', 'UnSubscribe');
            } else {
                $('#sub-image-' + user_id).attr('src', '../icons/subscribed.png');
                $('#sub-image-' + user_id).attr('alt', 'Subscribe');
                $('#sub-href-' + user_id).attr('href', 'javascript:subscribe(' + current_user_id + ', ' + user_id + ',1)');
                $('#sub-href-' + user_id).attr('title', 'Subscribe');
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


}
/*if(commentsreplies.length > 0){
                                    commentsreplies.forEach(function(reply){
                                        html_comm += `
                                            <li class='list-group-item' id='rep-` + reply.id + `' style='border-radius: 3pc; margin-bottom:5px;'>
                                                ` + commentsreplyusers[j].profileimage + `
                                                <b style='margin:5px 0 10px 5px; position:absolute; color:black;'>` + commentsreplyusers[j].firstname + ` ` + commentsreplyusers[j].lastname + `</b></a>
                                                <div id='reptext144' style='margin: -28px 0px 20px 75px; width: 88%; overflow-wrap: break-word; color: black; white-space: pre;'>` + reply.reply + `</div>
                                            </li>
                                        `;
                                        j += 1;
                                    });

                                }*/
?>