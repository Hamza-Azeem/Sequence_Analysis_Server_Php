<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Gene Data</title>
	<link rel="stylesheet" href="styles.css">
</head>
<body>
	<h1 class="title">Sequence Analysis Server</h1>
	<!-- <img src="DNA.jpg" alt="ay 7aga" > -->
	<form action="" method="POST">
        <fieldset>
		<legend>Gene Insertion</legend>
			<label for="locus">Locus:</label><br>
			<input id="locus" name="locus" type="text" required>
			<br> <br>
			<label for="geneType">Gene Type:</label><br>
			<input id="geneType" name="geneType" type="text">
			<br> <br>
			<label for="organism">Organism:</label><br>
			<input id="organism" name="organism" type="text">
			<br> <br>
			<label for="refSeq">Ref Sequence:</label><br>
			<input id="refSeq" name="refSeq" type="text">
			<br> <br>
			<label for="sequence">Sequence:</label><br>
			<input id="sequence" name="sequence" type="text" required>
			<br> <br>
			<input type="submit" value="Insert" name="Insert">
		</fieldset>
		<br>
		<br>
    </form>
	<form action="" method="POST">
		<fieldset>
		<legend>Update Genes</legend>
			<label for="Gene">Update Gene Table:</label><br>
			<input list="Genes" id="Gene" name="Gene">
			<datalist id="Genes" name="Genes">
				<option value="organism">
				<option value="geneType">
				<option value="refSeq">
				<option value="seq">
			</datalist>
			<br> <br>
			<label for="Genepk" >Gene Locus:</label><br>
			<input name="Genepk" id="Genepk" type="text">
			<br> <br>
			<label for="newvalue" >New value:</label><br>
			<input name="newvaluegene" id="newvaluegene" type="text">
			<br> <br>
			<input type="submit" name='GeneSubmit' id='GeneSubmit'>
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
			insert_gene($conn);
			UpdateTable($conn);
		}
		$conn->close();
		function insert_gene($conn){
			if(isset($_POST["Insert"])){
				$locus = $_POST["locus"];
				$geneType = $_POST["geneType"];
				$organism = $_POST["organism"];
				$refSeq = $_POST["refSeq"];
				$sequence = $_POST["sequence"];
				$sequence = strtoupper($sequence);
				$sequence = str_replace(' ', '', $sequence);
				if(empty($geneType)){
					$geneType = NULL;
				}
				if(empty($organism)){
                $organism = NULL;
				}
				if(empty($refSeq))
				{
					$refSeq = NULL;
				}
				$sql = "INSERT INTO gene(locus, geneType, organism, refSeq, seq) VALUES('$locus', '$geneType', '$organism', '$refSeq', '$sequence')";
				if($conn->query($sql)===TRUE){
					echo "<script> alert('New record created successfully');</script>";
					
				}else{
				
					 echo "<script> alert('Error');</script>". $sql. "<br>" . $conn->error;
				}   
			}
		}
		function UpdateTable($conn){
            if(isset($_POST['GeneSubmit'])){
				if($_POST["Gene"] == "seq")
				{
                	$sql="UPDATE gene SET ".$_POST['Gene']."='".str_replace(' ', '',strtoupper($_POST['newvaluegene']))."' WHERE locus='".$_POST['Genepk']."'";
				}
				else
				{
					$sql="UPDATE gene SET ".$_POST['Gene']."='".$_POST['newvaluegene']."' WHERE locus='".$_POST['Genepk']."'";
				}
                if($conn->query($sql)===TRUE){
                    echo  "<script> alert('Gene Updated Succsefully');</script>";
                }else{
                    echo "<script> alert('Error');</script>";
                }
            }

        }	
	?>
</body>
</head>
</html>