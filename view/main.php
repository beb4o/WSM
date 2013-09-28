<?php
    include ('vh.php');
    $request = \wsm\view\VH::getInitData();
?>
<!DOCTYPE html>

<html>
<head>
    <title>Main</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="css/main.css">
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
    <table class ="overview-body">
        <tr>
           <td><h1 style = "color:greenyellow">AweSome 4Paws</h1></td>
         </tr>
    </table>
</body>
</html>