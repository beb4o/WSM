<?php
    include ('vh.php');
    $request = \wsm\view\VH::getInitData();
    $request_message = \wsm\view\VH::getPageViewData();    
    $feedback = \wsm\view\VH::getFeedBack();
?>
<!DOCTYPE html>

<html>
<head>
    <title>Create Item</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/listView.css">
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>

<body>
    <div class ="block-up"><h1 class ="up"><?= $request['company_name']; ?> @BaseCamp Edition</h1></div>
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
    <form class="contact_form" method="post" name="contact_form">
        <input type="hidden" size="24" name = "type" value ="createitem" />
        <input type="hidden" size="24" name = "list_item" value ="<?= \wsm\base\ViewData::getNo()?>" />
        <input type="hidden" size="24" name = "project_id" value ="<?= $request['project_id']?>" />
        <ul>
            <li>
                <h2>Create Your new Item</h2>
            </li>
            <li>
                <label for="message">Description:</label>
                <textarea name="message" cols="40" rows="6" required ></textarea>
            </li>
            <li>
        	<button class="submit" type="submit">POST</button>
            </li>
        </ul>
    </form>
    <p align="center"><?php echo $feedback;?></p>
</body>
</html>