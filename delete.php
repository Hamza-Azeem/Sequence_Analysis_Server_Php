<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Delete Data</title>
	<link rel="stylesheet" href="styles.css">
</head>
<body>
	<h1 class="title">Sequence Analysis Server</h1>
	<!-- <img src="DNA.jpg" alt="ay 7aga" > -->
    <form action="" method="POST">
        <fieldset class="Otherfieldstyle">

		<legend>Delete</legend>
			<label for="table">Table to delete from:</label><br>
            <select name="table" id="table" onchange="toggleInput1()">
				<option value="" selected disabled hidden></option>
                <option value="gene">Gene Table</option>
                <option value="protein">Protein Table</option>
            </select>
			<br> 
			<div id="DlocusInput" style="display: none;">
            <label for="Dlocus">Locus:</label><br>
            <input type="text" name="Dlocus" id="Dlocus">
			</div>
			<div id="accessionInput" style="display: none;">
            <label for="Daccession">Accession:</label><br>
            <input type="text" name="Daccession" id="Daccession">
			</div>
			<br>
            <input type="submit" value="Delete" name="Delete">
        </fieldset>
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
			form_delete($conn);
		}
		$conn->close();
        function form_delete($conn){
			if(isset($_POST['Delete'])){
				$table = $_POST["table"];
				$locus = $_POST["Dlocus"];
				$accession_number = $_POST["Daccession"];
				if(!(empty($table) && empty($locus))){
					$sql_check = "SELECT * FROM $table WHERE locus = '$locus'";
					$result_check = $conn->query($sql_check);
					if($result_check){
						if($result_check->num_rows == 0) {
							echo "<script> alert('Error: locus $locus not found in $table table');</script>";
						}else{
							$sql_delete = "DELETE FROM $table WHERE locus = '$locus'";
							if($conn->query($sql_delete)===TRUE){
								echo "<script> alert('Deleted locus $locus from $table table.');</script>";
							}
							else{
								echo "<script> alert('Error');</script>" . $conn->error;
							}
						}
					}
				}
				if(!(empty($table) && empty($accession_number))){
					$sql_check = "SELECT * FROM $table WHERE accession = '$accession_number'";
					$result_check = $conn->query($sql_check);
					if($result_check){
						if($result_check->num_rows == 0) {
							echo"<script> alert('Accession number $accession_number not found in $table table');</script>";
						}else{
							$sql_delete = "DELETE FROM $table WHERE accession = '$accession_number'";
							if($conn->query($sql_delete)===TRUE){
								echo "<script> alert('Deleted accession number $accession_number from $table table.');</script>";
							}else{
								echo "<script> alert('Error');</script>" . $conn->error;
							}
						}
					}
				}
			}
		}
	?>
    	<script>
	function toggleInput1() {
        var table = document.getElementById("table").value;
        var DlocusInput = document.getElementById("DlocusInput");
        var accessionInput = document.getElementById("accessionInput");

        if (table === "gene") {
            DlocusInput.style.display = "block";
            accessionInput.style.display = "none";
        } else if (table === "protein") {
            DlocusInput.style.display = "none";
            accessionInput.style.display = "block";
        }
    }
    function toggleInput2() {
        var option = document.getElementById("option").value;
        var sequenceInput = document.getElementById("sequenceInput");
        var locusInput = document.getElementById("locusInput");
		var checkboxes = document.getElementById("CheckBox");

        if (option === "insert") {
            sequenceInput.style.display = "block";
            locusInput.style.display = "none";
			checkboxes.style.display = "block";
        } else if (option === "retrieve") {
            sequenceInput.style.display = "none";
            locusInput.style.display = "block";
			checkboxes.style.display = "block";
        }
    }
</script>
</body>
</head>
</html>