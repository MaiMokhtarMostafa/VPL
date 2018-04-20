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
                  <textarea class="form-control dir-auto" id="comment" style="width: 60%;margin-left: 10%;margin-bottom: 3%;" name="comment" rows="3" placeholder="Write a comment.." required=""></textarea>
                  <input class="btn btn-default" id="addComm" type="button" name="send" value="Write Comment" onclick="addComment()" style="margin-left: 15%;margin-bottom: 2%;">
                  <input   type="button"  style="display: none;" id="val_submission_id">
                  <a class="btn btn-default" href="../views/downloadsubmission.php" id="dwn-button" style="margin-left: 9%;margin-bottom: 2%;">Download Code</a>

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
{
    global  $USER;
    echo "
    <script>
    
    
        function addComment() {
          var content=$('#comment').val();
          var id=$('#val_submission_id').val();
          $.ajax({
                        type: 'POST',
                        url: '',
                        dataType: 'json',
                        data:{content_to_add:content ,val_submission_add_comment:id},
                        success: function(comment){
                            var html_comm = '';
                            html_comm += `
                                    <li class='list-group-item' id='wholecomment-` + comment.id + `' style='border-radius: 3pc; margin-bottom:5px;'>
                                        ` + comment.user.profileimage + `
                                        <b style='margin:5px 0 10px 5px; position:absolute; color:black;'>` + comment.user.firstname + ` ` + comment.user.lastname + `</b></a><br>
                                        <div id='comment-` + comment.id+ `' style='margin: -28px 0px 0px 75px; width: 88%; overflow-wrap: break-word; color: black;'><pre id='contentComment-`+comment.id+`'>` + comment.content + `</pre><br><span style='color:blue; cursor:pointer;' onclick='load_Replies(`+comment.id+`)')>Replies</span>
                                `;
                                if(comment.user.id==".$USER->id.")
                                    {
                                        html_comm +=`
                                  <span style='color:red; cursor:pointer; margin-left: 10px;' onclick='deleteComment(`+comment.id+`)')>Delete</span>
                                  <span style='color:green; cursor:pointer; margin-left: 10px;' id='editComment-`+comment.id+`' onclick='editComment(`+comment.id+`)')>Edit</span>
                                `;
                                    }
                                    html_comm +=`
                                  </div>
                                        <ul class='list-group' style='margin: 10px;' id='repcomment-`+comment.id+`'></ul>
                                    </li>
                                `;

                                $('#commList').append(html_comm);
                                $('#comment').val('');
                            }
                        }); 
          
        }
        
        
        function addReply(comment_id) {
          var content=$('#Reply_add-'+comment_id).val();
          console.log(content);
          var id=comment_id ;
          
          $.ajax({
                        type: 'POST',
                        url: '',
                        dataType: 'json',
                        data:{content_reply_to_add:content ,comment_id_to_add_reply:id},
                        success: function(reply){
                            load_Replies(comment_id);
                            }                            
                        }); 
          
          
        }
        
        
        
        function LoadCode(desc, vpl_submissions_id, crid, userid){
            // Load Code
            $('#val_submission_id').val(vpl_submissions_id);
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
                                        <div id='comment-` + comments[i].id+ `' style='margin: -28px 0px 0px 75px; width: 88%; overflow-wrap: break-word; color: black;'><pre id='contentComment-`+comments[i].id+`'>` + comments[i].content + `</pre><br><span style='color:blue; cursor:pointer;' onclick='load_Replies(`+comments[i].id+`)')>Replies</span>
                                `;
                                if(comments[i].user.id==".$USER->id.")
                                    {
                                        html_comm +=`
                                  <span style='color:red; cursor:pointer; margin-left: 10px;' onclick='deleteComment(`+comments[i].id+`)')>Delete</span>
                                  <span style='color:green; cursor:pointer; margin-left: 10px;' id='editComment-`+comments[i].id+`' onclick='editComment(`+comments[i].id+`)')>Edit</span>
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
                                        <div id='comment-` + replies[i].id+ `' style='margin: -28px 0px 0px 75px; width: 88%; overflow-wrap: break-word; color: black; '><pre id='contentReply-`+replies[i].id+`'>` + replies[i].content + `</pre>
                                        
                                `;
                                if(replies[i].user.id==".$USER->id.")
                                    {
                                        html_reply +=`
                                  <br> <span style='color:red; cursor:pointer;' onclick='deleteReply(`+replies[i].id+`)')>Delete</span>
                                  <span style='color:green; cursor:pointer; margin-left: 10px;' id='editReply-`+replies[i].id+`' onclick='editReply(`+replies[i].id+`)')>Edit</span>
                                `;
                                    }
                                    html_reply +=`</div>
                                    
                                    </li>
                                `;
                                
                            }
                            html_reply+=`
                                    <textarea class='form-control dir-auto' id='Reply_add-`+comment_id+`' style='width: 60%;margin-left: 10%;margin-bottom: 3%;' name='comment' rows='3' placeholder='Write a reply..' required=''></textarea>
                                    <input class='btn btn-default' id='addComm' type='button' name='send' value='Write Reply' onclick='addReply(`+comment_id+`)' style='margin-left: 15%;margin-bottom: 2%;'>
                                    `;
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
        
        
        function editComment(commentId ) {
            var span=$('#editComment-'+commentId)
            var p=$('#contentComment-'+commentId)
            var content;
            
            if(span.text()==='Edit')
                {
                   span.text('Save'); 
                   content=p.text();
                   p.html('<textarea class=\"form-control dir-auto\" id=\"commentArea-'+commentId+'\" style=\"width: 60%;\" name=\"comment\" rows=\"3\"  required=\"\">'+content+'</textarea>')
                }
                
            else 
                {
                   span.text('Edit');
                   content=$('#commentArea-'+commentId).val();
                   $.ajax({
                        type: 'POST',
                        url: '',
                        dataType: 'json',
                        data:{comment_id_Edit:commentId,comment_content_Edit:content},
                        success: function(data){
                            }
                        });
                   p.html(content);
                }
        }
        
        
        function editReply(replyId) {
            var span=$('#editReply-'+replyId)
            var p=$('#contentReply-'+replyId)
            var content;
            
            if(span.text()==='Edit')
                {
                   span.text('Save'); 
                   content=p.text();
                   p.html('<textarea class=\"form-control dir-auto\" id=\"replyArea-'+replyId+'\" style=\"width: 60%;\" name=\"comment\" rows=\"3\"  required=\"\">'+content+'</textarea>')
                }
                
            else 
                {
                   span.text('Edit');
                   content=$('#replyArea-'+replyId).val();
                   $.ajax({
                        type: 'POST',
                        url: '',
                        dataType: 'json',
                        data:{reply_id_Edit:replyId,reply_content_Edit:content},
                        success: function(data){
                            }
                        });
                   p.html(content);
                }
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

?>