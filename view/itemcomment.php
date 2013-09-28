<?php
    include ('vh.php');
    $request = \wsm\view\VH::getInitData();
    $request_message = \wsm\view\VH::getPageViewData();    
?>  
<!DOCTYPE html>

<html>
<head>
    <meta charset="utf-8">
    <title>Item Comment</title>
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/listView.css">
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>
    <div class ="block-up"><h1 class ="up"><?= $request['company_name']; ?> @BaseCamp Edition</h1></div>
        <div class = "block-up"><h2><a href=""><?= $request['project_name'] ?></a></h2></div>        
        <div class = "block-up"><div class="<?= $request['status'] ?>"><?= $request['status']?></div></div>
	<hr>
    <table class ="overview-head">
        <thead>
           <tr>
               <td><a href="./">Main</a></td>
               <td width="20px"></td>
               <td><a href="./?message/create">Create Message</a></td>
               <td width="20px"></td>
               <td><a href="./?todolists/create">Create To-Do List</a></td>
               <td width="20px"></td>
               <td><a href="./?message/view">View Messages</a></td>
               <td width="20px"></td>
               <td><a href="./?todolists/view">View To-Do Lists</a></td>
               <!--<td width="20px"></td>
               <td><a href="./?">Change Project</a></td>
               <td width="20px"></td>
               <td><a href="./?">Create Project</a></td>-->
           </tr>
        </thead>
    </table>   
    <div class="rightHead">
        <h2>Post comment to item</h2>
    </div>
    <div class ="messagecomment">
        <ol class="rounded-list" style = "list-style-type:none;">
            <?php foreach ($request_message['item_data'] as $i => $d):?>
                <li><a href="#" target="_blank"><b>TITLE:</b><?= $d['item_name'] ?></a>
                    <ol>
                        <?php foreach ($request_message['comments_data'] as $comment_id => $value):?>
                            <li><a href ="#"><i><?php echo $value['comment_body']?></a>
                            <form action="" method="post">
                                 <input type="hidden" size="24" name = "item_id" value ="<?= $i ?>" />
                                 <input type="hidden" size="24" name = "type" value ="deleteitemcomment" />
                                 <input type="hidden" size="24" name = "comment_id" value ="<?= $comment_id?>" />
                                 <input type="image" src="css/images/trash.png" alt="OK">
                             </form></i>
                        <?php endforeach;?>
                    </ol>
                </li>
            <?php endforeach;?>
        </ol>
        <form class="contact_form" method="post" name="contact_form">
            <ul style="width:350px;">
                <input type="hidden" size="24" name = "type" value ="itemcomment" />
                <input type="hidden" size="24" name = "item_id" value ="<?= \wsm\base\ViewData::getNo()?>" />
                <input type="hidden" size="24" name = "project_id" value ="<?= $request['project_id']?>" />
                <li>
                    <label for="comment">Comment:</label>
                    <textarea name="comment_body" cols="30" rows="6" required ></textarea>
                </li>
                <li>
                    <button class="submit" type="submit">Leave comment</button>
                </li>
            </ul>
        </form>
    </div>
</body>
</html>   