<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Locus Matcher</title>
	<link rel="stylesheet" href="styles.css">
</head>
<body>
	<h1 class="title">Sequence Analysis Server</h1>
    <br>
    <br>
	<!-- <img src="DNA.jpg" alt="ay 7aga" > -->
	<form action="" method="POST">
        <fieldset class='Otherfieldstyle'>
		<legend>Gene Protein Matching</legend>
			<label for="locus">Locus:</label><br>
			<input id="locus" name="locus" type="text" required>
            <br>
            <br>
			<input type="submit" value="Insert" name="Insert">
		</fieldset>
		<br>
		<br>
    </form>
	<?php
		$servername = "localhost";
		$username = "root";
		$password = "usbw";
		$dbname = "sequenceserver";
		$conn = new mysqli($servername, $username, $password, $dbname);
		if($conn->connect_error){
			die("Connection failed ". $conn->connect_error);
		}
		else
		{
            match_protein_gene($conn, $dbname);
		}
		$conn->close();
		function match_protein_gene($conn, $dbname){
			if(isset($_POST["Insert"])){
				$locus = $_POST["locus"];
                if(empty($locus))
                {
                    echo "<p style=color:white;>You didn't enter a locus</p>";
                    $_POST= array();
                    return;
                }
				$sql = "SELECT gene.seq FROM gene, protein WHERE gene.locus = '$locus' and protein.geneLocus ='$locus' ";
				$result = $conn->query($sql);
                if($result){
                    						
                    if($result->num_rows >0){
                        echo"<h2 style='color:white;'>Gene Sequence:</h2>";
                        echo "<br>";
                        echo '<table <th style="border: 2px solid  white; padding: 8px; color:white; font-size: 20px;" >Sequence</th></tr>';

                        while ($row=$result->fetch_assoc()){
                            echo '<tr> <td>' ;
                            echo$row["seq"];
                            echo '</td></tr>';
                        }
                    echo "</table>";
                    }else{
                        echo "<script> alert('No Data');</script>";
                        return;
                    }
                    echo "<br> ";
                    echo"<h2 style='color:white;'>Protein Sequence:</h2>";

                    echo "<br>";
                    echo '<table <th style="border: 2px solid  white; padding: 8px; color:white; font-size: 20px;">Sequence</th></tr>';
                    							
                    $sql = "SELECT seq FROM protein WHERE geneLocus = '$locus' ";
                    $result = $conn->query($sql);
                    if($result->num_rows >0){
                        while ($row=$result->fetch_assoc()){
                            echo '<tr> <td>' ;
                            echo $row["seq"];
                            echo '</td></tr>';
                        }
                        echo "</table>";
                    }else{
                        echo "<script> alert('No Data');</script>";
                        return;
                    }
                }else {
                    echo "<script> alert('Error In');</script>".$sql."".$dbname->error;
                }
		}
        }
	?>
</body>
</html>