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
			form_select_db($conn, $dbname);
		}
		$conn->close();
		function form_select_db($conn, $dbname){
			$col ="";
			$sql="";
			if(isset($_POST['gene-submit'])){
				$col = $_POST["gene-table"];
				$condition = $_POST["cond"];
				if($col=="all"){
					$sql = "SELECT * FROM gene WHERE locus='$condition'";
				}else{
					$sql = "SELECT $col FROM gene WHERE locus='$condition'";
				}if (!empty($condition)){
					$result = $conn->query($sql);
					
				}else{
					echo "<script> alert('Please enter Gene Locus');</script>";
					return ;
				}

				// $result = $conn->query($sql);
				if($result){
					if($result->num_rows >0){
						if($col =="all"){
							echo "<h2 style='color: white'> Selected Results: </h2>";
							echo '<table style="color: white; border-collapse: collapse;"><tr><th style="border: 2px solid  white; padding: 8px;">locus</th><th style="border: 2px solid  white; padding: 8px;">geneType</th><th style="border: 2px solid  white; padding: 8px;">organism</th><th style="border: 2px solid  white; padding: 8px;">refSeq</th><th style="border: 2px solid  white; padding: 8px;">Sequence</th></tr>';							
							while ($row=  $result->fetch_assoc()){
								echo '<tr>';
								echo '<td style="border: 2px solid white; padding: 8px;font-weight:bold">'.$row["locus"].'</td>';
								echo '<td style="border: 2px solid white; padding: 8px;font-weight:bold">'.$row["geneType"].'</td>';
								echo '<td style="border: 2px solid white; padding: 8px;font-weight:bold">'.$row["organism"].'</td>';
								echo '<td style="border: 2px solid white; padding: 8px;font-weight:bold">'.$row["refSeq"].'</td>';
								echo '<td style="border: 2px solid white; padding: 8px;font-weight:bold">'.$row["seq"].'</td>';
								echo '</tr>';
							}   
						}else{
							echo "<h2 style='color: white'> Selected Results: </h2>";
							echo '<table style="color: white; border-collapse: collapse;"><tr> <th style="border: 2px solid  white; padding: 8px;"> '.$col.'</th></tr>';
							while ($row=  $result->fetch_assoc()){
								echo '<tr>';
								echo '<td style="border: 2px solid white; padding: 8px;font-weight:bold">'.$row[$col].'</td>';
								echo '</tr>';
							}
						}
						echo "</table>";
					}else{
						 echo "<script> alert('No Data');</script>";
					}
				}else {
					 echo "<script> alert('Error In');</script>".$sql."".$dbname->error;
				}
			}elseif(isset($_POST["Protein-submit"])){
				$col = $_POST["protein-table"];
				
				$condition = $_POST["condition"];
				if ($col=="all"){
					$sql = "SELECT * FROM protein WHERE accession='$condition'";
				}else{
					$sql = "SELECT $col FROM protein WHERE accession='$condition' ";
				}
				if (!empty($condition)){
					$result = $conn->query($sql);
					
				}else{
					echo "<script> alert('Please enter Protein Accession');</script>";
					return ;
				}
				
				if($result){
					if ($result->num_rows >0){
						if ($col =="all"){
							echo "<h2 style='color:white'> Selected Results: </h2>";
							echo '<table style="color: white; border-collapse: collapse;"><tr><th style="border: 2px solid  white; padding: 8px;">accession</th><th style="border:2px solid  white; padding: 8px;">pubmed</th><th style="border: 2px solid  white; padding: 8px;">organism</th><th style="border: 2px solid  white; padding: 8px;">geneLocus</th><th style="border: 2px solid  white; padding: 8px;">Sequence</th></tr>';							
							while ($row=  $result->fetch_assoc()){
								echo '<tr>';
								echo '<td style="border: 2px solid white; padding: 8px;font-weight:bold">'.$row["accession"].'</td>';
								echo '<td style="border: 2px solid white; padding: 8px;font-weight:bold">'.$row["pubmed"].'</td>';
								echo '<td style="border: 2px solid white; padding: 8px;font-weight:bold">'.$row["organism"].'</td>';
								echo '<td style="border: 2px solid white; padding: 8px;font-weight:bold">'.$row["geneLocus"].'</td>';
								echo '<td style="border: 2px solid white; padding: 8px;font-weight:bold">'.$row["seq"].'</td>';
								echo '</tr>';							}   
						}else{
							echo "<h2 style='color: white'> Selected Results: </h2>";
							echo '<table style="color: white; border-collapse: collapse;"><tr> <th style="border: 2px solid  white; padding: 8px;"> '.$col.'</th></tr>';
							while ($row=  $result->fetch_assoc()){
								echo '<tr>';
								echo '<td style="border: 2px solid white; padding: 8px;font-weight:bold">'.$row[$col].'</td>';
								echo '</tr>';							
							}
						}
						echo "</table>";
					}else{
						echo "<script> alert('No Data');</script>";
					}
				}else {
					echo "<script> alert('Error');</script>" .$sql."".$dbname->error;
				}
			}
		}
		function form_select_db_ret($conn, $dbname, $loc){
			$sql = "SELECT `seq` FROM `gene` WHERE `locus` = '$loc'";
			$result = $conn->query($sql);
			if($result){
				if($result->num_rows >0){
					while ($row=  $result->fetch_assoc()){
						return $row["seq"];
					}
				}else{
					echo "<script> alert('No Data');</script>";
				}
			}else {
				echo "<script> alert('Error In');</script>".$sql."".$dbname->error;
			}
		}
	?>