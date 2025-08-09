<?php      
    include('connection.php'); 
if(isset($_POST['lgn'])){ 
	$username = $_POST['Username'];  
        $Password = $_POST['Password'];  
    
        
try{
$sql = "SELECT * FROM(username,password)VALUES('$username','$Password')";
WHERE
$username = $_POST['Username'];  
        $Password = $_POST['Password']; 
}
catch(Exception $e){
}
        $result = mysqli_query($con, $sql);

}  

?>  

