<?php
    include ('vh.php');
    $request = \wsm\view\VH::getInitData();
    $request_message = \wsm\view\VH::getPageViewData();
    //print_r($request_message);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Items</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/listView.css">
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>
        <div class = "block-up"><h1 class ="up"><?= $request['company_name']; ?> @BaseCamp Edition</h1></div>
        <div class = "block-up"><h2><a href="?"><?= $request['project_name'] ?></a></h2></div>        
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
    <div class ="info-block">
        <div class="rightHead">
            <h2>Last Items (to get the items - click the button "Sync with Basecamp")</h2>
        </div>
        <div class = "messageList">
            <ol class="rectangle-list">
                <?php foreach ($request_message['items'] as $index => $value): ?>
                    <li><a href="./?item/comment/<?= $index;?>"><b><?=$request_message['items'][$index]['item_name']?></b></a>
                    <form action="" method="post">
                             <input type="hidden" size="24" name = "type" value ="deletetodoitem" />
                             <input type="hidden" size="24" name = "item_id" value ="<?= $index?>" />
                             <input type="image" src="css/images/trash.png" alt="OK">
                         </form></li>
                <?php endforeach; ?>
            </ol>
        </div>
        <div class = "updateButton">
            <a class="menu" href="./?items/update">Sync with Basecamp</a>
            <a class="menu" href="./?items/create/<?= $request_message['list_id']?>">Create Item</a>
        </div>
    </div>
</body>