<?php
session_start();
$user = "test";
$pass = "t3st3r123";
$db = "test";
$host = "localhost";
$link = mysqli_connect($host, $user, $pass, $db) or die("ei saanud ühendatud");

mysqli_query($link, "SET CHARACTER SET UTF8")or die( $sql. " - ". mysqli_error($link));

$loomad = array();

$sql = "SELECT id, nimi, pilt, treener_id FROM 0tsirkus";
$result = mysqli_query($link, $sql);
$ridu = mysqli_num_rows($result);
for ($i=0; $i<$ridu; $i++){
	$loomad[] = mysqli_fetch_assoc($result);
}
/*
while($rida = mysqli_fetch_assoc($result)){
	$loomad[]=$rida;
}*/

if (isset($_GET['lisa'])){
	$nimi = mysqli_real_escape_string($link, $_POST["nimi"]);
	$pilt = mysqli_real_escape_string($link, $_POST["pilt"]);
	$treener = mysqli_real_escape_string($link, $_POST["treener_id"]);
			
	$sql = "INSERT INTO 0tsirkus (nimi, pilt, treener_id) VALUES ('$nimi', '$pilt', $treener)";
	$result = mysqli_query($link, $sql);
	if ($result){
		$_SESSION['ridade_arv'] = mysqli_affected_rows($link);
		$_SESSION['uus_id'] = mysqli_insert_id($link);
		header("Location: ?");
		exit(0);
	} 
}

if (isset($_GET['muuda'])){
   // id-de turvamine
	$ids = array();
	foreach($_POST['id'] as $id){
		$ids[] = mysqli_real_escape_string($link, $id);
	}
	$treener = mysqli_real_escape_string($link, $_POST['treener']);
   
	$sql = "UPDATE 0tsirkus SET treener_id = $treener WHERE id in (".implode(',', $ids).")";
	$result = mysqli_query($link, $sql);
	if ($result){
		$_SESSION['ridade_arv'] = mysqli_affected_rows($link);
		header("Location: ?");
		exit(0);
	} 
}

?>

<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8" />
      <title>tsirkus</title>
      <style type="text/css">
      </style>
   </head>
   
   <body>
      <p style="color:red">
         <?php if (!empty($_SESSION['ridade_arv'])){ 
            echo 'Mõjutatud ridade arv: '.$_SESSION['ridade_arv']; 
            unset($_SESSION['ridade_arv']);
            }
         ?>
         <?php if (!empty($_SESSION['uus_id'])){ 
            echo 'Uue rea id: '.$_SESSION['uus_id']; 
            unset($_SESSION['uus_id']);
            }
         ?>
      </p>
      
      <table border="1">
         <tr>
            <th>ID</th>
            <th>NIMI</th>
            <th>PILT</th>
            <th>TREENER ID</th>
            <th>
               <form id="muutmine" action="?muuda" method="POST"><input type="submit" value="muuda"/></form>
            </th>
         </tr>
         <?php if (!empty($loomad)):
            foreach($loomad as $id=>$loom):
         ?>
         <tr>
            <td><?php echo htmlspecialchars($loom["id"]); ?></td>
            <td><?php echo htmlspecialchars($loom["nimi"]); ?></td>
            <td><img src="<?php echo htmlspecialchars($loom["pilt"]); ?>" alt="<?php echo htmlspecialchars($loom["nimi"]);?>" height="50"/></td>
            <td><?php echo htmlspecialchars($loom['treener_id']); ?></td>
            <td><input type="checkbox" name="id[]" value="<?php echo htmlspecialchars($loom["id"]); ?>" form="muutmine"/></td>
         </tr>
         <?php endforeach;
            endif;
         ?>
         <tr>
            <td colspan="4"><b>Ridu:</b> <?php echo $ridu; ?> </td>
            <td><input type="text" name="treener" form="muutmine" value="0"/></td>
         </tr>
         <tr>
            <td></td>
            <td><input type="text" name="nimi" placeholder="nimi" form="lisamine" /></td>
            <td>
               <select name="pilt" form="lisamine">
                  <option value="pildid/monkey.png" >Ahv</option>
                  <option value="pildid/rabbit.png" >Jänes</option>
                  <option value="pildid/bear.png" >Karu</option>
                  <option value="pildid/cat.png" >Kass</option>
                  <option value="pildid/cow.png" >Lehm</option>
               </select>
            </td>
            <td><input type="text" name="treener_id" placeholder="treener" form="lisamine"/></td>
         </tr>
      </table>
      
      <form action="?lisa" method="POST" id="lisamine">
         <input type="submit" value="lisa"/>
      </form>
      
   </body>
   
</html>