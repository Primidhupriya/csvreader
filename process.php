<?php 
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "csvreader";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$sql1="create table ".$_POST["tbname"];
$s='(';
$passwordPresnt=false;
$passwordIndex=0;
for ($x = 0; $x < 1; $x++) 
{
  $s=$s.implode(" VARCHAR(30),",$_POST['data'][$x]).' VARCHAR(30))';
   for ($y = 0; $y < count($_POST['data'][$x]); $y++)
  {
    if(strtolower($_POST['data'][$x][$y])=="password")
    {
      $passwordPresnt=true;  
      $passwordIndex=$y;
      break;    
    }
  }
}
$ciphering = "AES-128-CTR";
$options = 0;
$encryption_iv = '1234567891011121';
$encryption_key = "priya";
if($passwordPresnt==true)
{
 for ($y = 1; $y < count($_POST['data']); $y++)
 {
   $encryption = openssl_encrypt(  $_POST['data'][$y][$passwordIndex], $ciphering,
            $encryption_key, $options, $encryption_iv);
   $_POST['data'][$y][$passwordIndex]=$encryption;
   
 }
}
$sql=$sql1.' '.$s;
if (mysqli_query($conn, $sql)) {
$count=0;
for ($i = 1; $i < count($_POST['data']); $i++) 
{
$sql2="INSERT INTO ".$_POST['tbname']." (".implode(',',$_POST['data'][0]).") VALUES ('";
$sql2=$sql2.implode("','",$_POST['data'][$i])."')";	
if (mysqli_query($conn, $sql2)) {
  $count=$count+1;
} else {
  echo "Error: " . $sql2 . "<br>" . mysqli_error($conn);
}

}
if($count==count($_POST['data'])-1)
{
 echo "successfully uploaded data";
}
} else {
  echo "Error creating table: " . mysqli_error($conn);
}

?>