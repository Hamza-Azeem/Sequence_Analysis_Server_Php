<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Retrieve Data</title>
	<link rel="stylesheet" href="styles.css">
</head>
<body>
	<h1 class="title">Sequence Analysis Server</h1>
	<br>

	<!-- <img src="DNA.jpg" alt="ay 7aga" > -->
<form action="" method="POST">
        <fieldset class="Otherfieldstyle">
		<legend>Retrieve Genes</legend>
		
			<select id="gene" name="gene-table">
				<option value="all">all </option>
				<!-- <option value="locus">locus</option> -->
				<option value="geneType">geneType </option>
				<option value="organism">organism</option>
				<option value="refSeq">refSeq </option>
				<option value="seq">sequence</option>
			</select><br> <br>
			<label for="gen" >Locus</label><br>
			<input type="text" name="cond" id="gen"><br> <br>
			<input type="submit" name="gene-submit" value="Show">
		</fieldset>
		<br>
		<br>
		<fieldset class="Otherfieldstyle">
        <legend>Retrieve proteins</legend>
			<select id="protein" name="protein-table">
				<option value="all">all </option>
				<!-- <option value="accession">accession</option> -->
				<option value="organism">organism </option>
				<option value="pubmed">pubmed</option>
				<option value="geneLocus">geneLocus </option>
				<option value="seq">sequence</option>
			</select><br><br>
			<label for="Pro" >Accession</label><br>
			<input type="text" name="condition" id="Pro"><br> <br>
			<input type="submit" name="Protein-submit" value="Show">
		</fieldset>
	</form>
	<?php
		require "import.php";
	?>
</body>
</head>
</html>