<?php
    include ('view/vh.php');
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
    <div class ="block-up"><h1 class ="up">WSM - Website Movers International @BaseCamp Edition</h1></div>
	<hr>
    <table class ="overview-head">
        <thead>
           <tr>
               <td><a href="./?message/create">Create Message</a></td>
               <td width="20px"></td>
               <td><a href="./?todolists/create">Create To-Do List</a></td>
               <td width="20px"></td>
               <td><a href="./?message/view">View Messages</a></td>
               <td width="20px"></td>
               <td><a href="./?todolists/view">View To-Do Lists</a></td>
               <td width="20px"></td>
               <td><a href="./?">Change Project</a></td>
               <td width="20px"></td>
               <td><a href="./?">Create Project</a></td>
           </tr>
        </thead>
    </table>
    <h1>File doesn't exists!</h1>
</body>
</html>