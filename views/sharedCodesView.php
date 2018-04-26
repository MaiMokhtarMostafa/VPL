<?php
function print_header_table_share()
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
                <th style="text-align: center;width: 80%;">Activity</th>
                
                <th style="text-align: center;">Action</th>
            </tr>
        </thead>
        <tbody>';
}


?>