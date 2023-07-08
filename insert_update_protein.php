<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Protein Data</title>
	<link rel="stylesheet" href="styles.css">
</head>
<body>
	<h1 class="title">Sequence Analysis Server</h1>
	<!-- <img src="DNA.jpg" alt="ay 7aga" > -->
<form action="" method="POST">
		<fieldset>
		<legend>Protein Insertion</legend>
			<label for="accession"> Accession:</label><br>
			<input id="accession" name="accession" type="text" required>
			<br> <br>
			<label for="proteinOrganism"> Organism:</label><br>
			<input id="proteinOrganism" name="proteinOrganism" type="text">
			<br> <br>
			<label for="pubmed"> Pubmed:</label><br>
			<input id="pubmed" name="pubmed" type="text">
			<br> <br>
			<label for="geneLocus"> Gene Locus:</label><br>
			<input id="geneLocus" name="geneLocus" type="text">
			<br> <br>
			<label for="seq"> Sequence:</label><br>
			<input id="seq" name="seq" type="text" required>
			<br> <br>
			<input type="submit" value="Insert" name="InsertProtein">
		</fieldset>
    </form>
	<br>
	<br>
    
    <form action="" method="POST">
    <fieldset>
		<legend>Update Proteins</legend>
			<label for="U-Protein">Update Protein Table:</label><br>
			<input list="Proteins" id="U-Protein" name="U-Protein">
			<datalist id="Proteins" name="Proteins">
				<option value="organism">
				<option value="pubmed">
				<option value="geneLocus">
				<option value="seq">
			</datalist>
			<br> <br>
			<label for="Proteinpk" >Protein Accession:</label><br>
			<input name="Proteinpk" id="Proteinpk" type="text">
			<br> <br>
			<label for="newvalue" >New value:</label><br>
			<input name="newvalueprotein" id="newvalueprotein" type="text">
			<br> <br>
			<input type="submit" name='ProteinSubmit',id='ProteinSubmit'>
		</fieldset>
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
			insert_protein($conn); 
            UpdateTable($conn);
		}
		$conn->close();
		function insert_protein($conn){
			if(isset($_POST["InsertProtein"])){
				$accession = $_POST["accession"];
				$organism2 = $_POST["proteinOrganism"];
				$pubmed = $_POST["pubmed"];
				$geneLocus = $_POST["geneLocus"];
				$seq = $_POST["seq"];
				$seq = strtoupper($seq);
				$seq = str_replace(' ', '', $seq);
				if(empty($accession)){
					$accession = NULL;
				}
				if(empty($organism2)){
					$organism2 = NULL;
				}
				if(empty($geneLocus)){
					$geneLocus = NULL;
				}
				if(empty($pubmed)){
					$pubmed = NULL;
				}
				$sql = "INSERT INTO protein(accession, organism, pubmed, geneLocus, seq) VALUES('$accession', '$organism2', '$pubmed', '$geneLocus', '$seq')";
				if($conn->query($sql)===TRUE){
					echo "<script> alert('New record created successfully');</script>";
					
				}else{
					echo "<script> alert('Error');</script>". $sql. "<br>" . $conn->error;
				}   
			}
		}
        function UpdateTable($conn){
            if(isset($_POST['ProteinSubmit'])){
				if($_POST["U-Protein"] == "seq")
				{
					$sql2="UPDATE protein SET ".$_POST['U-Protein']."='". str_replace(' ', '', strtoupper($_POST['newvalueprotein']))."' WHERE accession='".$_POST['Proteinpk']."'";
				}
				else
				{
					$sql2="UPDATE protein SET ".$_POST['U-Protein']."='".$_POST['newvalueprotein']."' WHERE accession='".$_POST['Proteinpk']."'";
				}
                if($conn->query($sql2)===TRUE){
                    echo "<script> alert('Protein Updated Succsefully');</script>";
                }else{
                    echo "<script> alert('Error');</script>";
                }
            }
        }
	?>
</body>
</head>
</html>