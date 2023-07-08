<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Analysis</title>
	<link rel="stylesheet" href="styles.css">
</head>
<body>
	<h1 class="title">Sequence Analysis Server</h1>
	<!-- <img src="DNA.jpg" alt="ay 7aga" > -->
    <form action="" method="POST">
        <fieldset>
		<legend>Sequence Analysis</legend>
			<label for="option">Select an option:</label><br>
			<select name="option" id="option" onchange="toggleInput2()">
				<option value="" selected disabled hidden></option>
				<option value="insert">Insert Sequence</option>
				<option value="retrieve">Retrieve Sequence</option>
			</select>
			<br><br>
			<div id="sequenceInput" style="display: none;">
            <label for="sequence">Sequence:</label><br>
            <input type="text" name="sequence" id="sequence">
			</div>
			<div id="locusInput" style="display: none;">
            <label for="locus">Locus:</label><br>
            <input type="text" name="locus" id="locus">
			</div>
			<br>
			<div id="CheckBox" style="display: none;">
			<input type="checkbox" name="function1" id="function1">
			<label for="function1">Protein Translation</label>
			<br>
			<input type="checkbox" name="function2" id="function2">
			<label for="function2">Most Repeated K-mer</label>
			<br>
			<input type="checkbox" name="function3" id="function3">
			<label for="function3">Complementary DNA</label>
			<br>
			<input type="checkbox" name="function4" id="function4">
			<label for="function4">GC Content</label>
			<br>
			<input type="checkbox" name="function5" id="function5">
			<label for="function5">Transcription</label>
			<br>
			<input type="checkbox" name="function6" id="function6">
			<label for="function6">N-bases</label>
			<br> <br>
			</div>
			<input type="submit" value="Analyze" name="Analyze">
			<input type="submit" value="Save" name="Save">
        </fieldset>
        <br>
    </form>
	
    <?php
        require "import.php";
		$servername = "localhost";
		$username = "root";
		$password = "usbw";
		$dbname = "sequenceserver";
		$conn = new mysqli($servername, $username, $password, $dbname);
		if($conn->connect_error){
			die("Connection failed ". $conn->connect_error);
		}else{
			protein_translation($conn, $dbname);
			most_repeated_kmer($conn, $dbname);
			DNA_Complementary($conn, $dbname);
			GC_Content($conn, $dbname);
			transcription($conn, $dbname);
			N_bases($conn, $dbname);
		}
		$conn->close();
		function protein_translation($conn, $dbname){
			if(isset($_POST['Analyze']) && isset($_POST['function1'])){
				$seq = $_POST['sequence'];
				$loc = $_POST['locus'];
				if(!empty($seq)){
					$seq = strtoupper($seq);
					if(strpos($seq, "U") !== false && strpos($seq, "T") !== false){
						echo "<script> alert('Invalid Sequence');</script>";
						return;
					}
					if(strpos($seq, "T") !== false && strpos($seq, "U") === false){
						$rna = "";
						$dna = str_split($seq);
						foreach($dna as $chr){
							if ($chr =="T"){
								$rna = $rna.'U';
								continue;
							}
							$rna = $rna.$chr;
						}
						$codon_to_residue = array("A"=> array("GCU", "GCC", "GCA", "GCG"), "R"=> array("CGU", "CGC", "CGA", "CGG", "AGA", "AGG") ,"N"=> array("AAU", "AAC")
						, "D"=> array("GAU", "GAC"), "C"=> array("UGU", "UGC"), "Q"=> array("CAA", "CAG"), "E"=> array("GAA", "GAG"), "G"=> array("GGU", "GGC", "GGA", "GGG")
						, "H"=> array("CAU", "CAC"), "I"=> array("AUU", "AUC", "AUA"), "L"=> array("UUA", "UUG", "CUU", "CUC", "CUA", "CUG"), "K"=> array("AAA", "AAG"), "M"=> array("AUG")
						, "F"=> array("UUU", "UUC"), "P"=> array("CCU", "CCC", "CCA", "CCG"), "S"=> array("UCU", "UCC", "UCA", "UCG", "AGU", "AGC"), "T"=> array("ACU", "ACC", "ACA", "ACG")
						, "W"=> array("UGG"), "Y"=> array("UAU", "UAC"), "V"=> array("GUU", "GUC", "GUA", "GUG"), "STOP"=> array("UGA", "UAA", "UAG"));
						$seq = $rna;
						$seq_size = strlen($seq);
						$start_flag = false;
						$end_flag = false;
						$protein="";
						for($i = 0; $i<$seq_size; $i+=3){
							$target_codon = substr($seq,$i, 3);
							foreach($codon_to_residue as $codons){
								$flag = false;
								foreach($codons as $codon){
									if($codon == $target_codon){
										$flag = true;
										break;
									}
								}
								$residue = array_search($codons, $codon_to_residue);
								if($residue == "STOP" && $flag){
									$end_flag = true;
									break;
								}
								if($residue == "M"){
									$start_flag = true;
								}
								if($start_flag && $flag){
									$protein = $protein. $residue;
								}
							}
							if($end_flag == true){
								break;
							}
						}
						echo "<h2 style='color:white;'>Translated Protein:</h2>";
						echo  "<h3 style='color:white;'>$protein</h3>"."<br>";
						return;
					} 
					$codon_to_residue = array("A"=> array("GCU", "GCC", "GCA", "GCG"), "R"=> array("CGU", "CGC", "CGA", "CGG", "AGA", "AGG") ,"N"=> array("AAU", "AAC")
					, "D"=> array("GAU", "GAC"), "C"=> array("UGU", "UGC"), "Q"=> array("CAA", "CAG"), "E"=> array("GAA", "GAG"), "G"=> array("GGU", "GGC", "GGA", "GGG")
					, "H"=> array("CAU", "CAC"), "I"=> array("AUU", "AUC", "AUA"), "L"=> array("UUA", "UUG", "CUU", "CUC", "CUA", "CUG"), "K"=> array("AAA", "AAG"), "M"=> array("AUG")
					, "F"=> array("UUU", "UUC"), "P"=> array("CCU", "CCC", "CCA", "CCG"), "S"=> array("UCU", "UCC", "UCA", "UCG", "AGU", "AGC"), "T"=> array("ACU", "ACC", "ACA", "ACG")
					, "W"=> array("UGG"), "Y"=> array("UAU", "UAC"), "V"=> array("GUU", "GUC", "GUA", "GUG"), "STOP"=> array("UGA", "UAA", "UAG"));
					$seq_size = strlen($seq);
					$start_flag = false;
					$end_flag = false;
					$protein="";
					for($i = 0; $i<$seq_size; $i+=3){
						$target_codon = substr($seq,$i, 3);
						foreach($codon_to_residue as $codons){
							$flag = false;
							foreach($codons as $codon){
								if($codon == $target_codon){
									$flag = true;
									break;
								}
							}
							$residue = array_search($codons, $codon_to_residue);
							if($residue == "STOP" && $flag){
								$end_flag = true;
								break;
							}
							if($residue == "M"){
								$start_flag = true;
							}
							if($start_flag && $flag){
								$protein = $protein. $residue;
							}
						}
						if($end_flag == true){
							break;
						}
					}
					echo "<h2 style='color:white;'>Translated Protein:</h2>";
					echo  "<h3 style='color:white;'>$protein</h3>"."<br>";
				}
				if(!empty($loc)){
					$seq = form_select_db_ret($conn, $dbname, $loc);
					$seq = strtoupper($seq);
					if(strpos($seq, "U") !== false && strpos($seq, "T") !== false){
						echo "<script> alert('Invalid Sequence');</script>";
						return;
					}
					if(strpos($seq, "T") !== false && strpos($seq, "U") === false){
						$rna = "";
						$dna = str_split($seq);
						foreach($dna as $chr){
							if ($chr =="T"){
								$rna = $rna.'U';
								continue;
							}
							$rna = $rna.$chr;
						}
						$codon_to_residue = array("A"=> array("GCU", "GCC", "GCA", "GCG"), "R"=> array("CGU", "CGC", "CGA", "CGG", "AGA", "AGG") ,"N"=> array("AAU", "AAC")
						, "D"=> array("GAU", "GAC"), "C"=> array("UGU", "UGC"), "Q"=> array("CAA", "CAG"), "E"=> array("GAA", "GAG"), "G"=> array("GGU", "GGC", "GGA", "GGG")
						, "H"=> array("CAU", "CAC"), "I"=> array("AUU", "AUC", "AUA"), "L"=> array("UUA", "UUG", "CUU", "CUC", "CUA", "CUG"), "K"=> array("AAA", "AAG"), "M"=> array("AUG")
						, "F"=> array("UUU", "UUC"), "P"=> array("CCU", "CCC", "CCA", "CCG"), "S"=> array("UCU", "UCC", "UCA", "UCG", "AGU", "AGC"), "T"=> array("ACU", "ACC", "ACA", "ACG")
						, "W"=> array("UGG"), "Y"=> array("UAU", "UAC"), "V"=> array("GUU", "GUC", "GUA", "GUG"), "STOP"=> array("UGA", "UAA", "UAG"));
						$seq = $rna;
						$seq_size = strlen($seq);
						$start_flag = false;
						$end_flag = false;
						$protein="";
						for($i = 0; $i<$seq_size; $i+=3){
							$target_codon = substr($seq,$i, 3);
							foreach($codon_to_residue as $codons){
								$flag = false;
								foreach($codons as $codon){
									if($codon == $target_codon){
										$flag = true;
										break;
									}
								}
								$residue = array_search($codons, $codon_to_residue);
								if($residue == "STOP" && $flag){
									$end_flag = true;
									break;
								}
								if($residue == "M"){
									$start_flag = true;
								}
								if($start_flag && $flag){
									$protein = $protein. $residue;
								}
							}
							if($end_flag == true){
								break;
							}
						}
						echo "<h2 style='color:white;'>Translated Protein:</h2>";
						echo  "<h3 style='color:white;'>$protein</h3>"."<br>";
						return;
					} 
					$codon_to_residue = array("A"=> array("GCU", "GCC", "GCA", "GCG"), "R"=> array("CGU", "CGC", "CGA", "CGG", "AGA", "AGG") ,"N"=> array("AAU", "AAC")
					, "D"=> array("GAU", "GAC"), "C"=> array("UGU", "UGC"), "Q"=> array("CAA", "CAG"), "E"=> array("GAA", "GAG"), "G"=> array("GGU", "GGC", "GGA", "GGG")
					, "H"=> array("CAU", "CAC"), "I"=> array("AUU", "AUC", "AUA"), "L"=> array("UUA", "UUG", "CUU", "CUC", "CUA", "CUG"), "K"=> array("AAA", "AAG"), "M"=> array("AUG")
					, "F"=> array("UUU", "UUC"), "P"=> array("CCU", "CCC", "CCA", "CCG"), "S"=> array("UCU", "UCC", "UCA", "UCG", "AGU", "AGC"), "T"=> array("ACU", "ACC", "ACA", "ACG")
					, "W"=> array("UGG"), "Y"=> array("UAU", "UAC"), "V"=> array("GUU", "GUC", "GUA", "GUG"), "STOP"=> array("UGA", "UAA", "UAG"));
					$seq_size = strlen($seq);
					$start_flag = false;
					$end_flag = false;
					$protein="";
					for($i = 0; $i<$seq_size; $i+=3){
						$target_codon = substr($seq,$i, 3);
						foreach($codon_to_residue as $codons){
							$flag = false;
							foreach($codons as $codon){
								if($codon == $target_codon){
									$flag = true;
									break;
								}
							}
							$residue = array_search($codons, $codon_to_residue);
							if($residue == "STOP" && $flag){
								$end_flag = true;
								break;
							}
							if($residue == "M"){
								$start_flag = true;
							}
							if($start_flag && $flag){
								$protein = $protein. $residue;
							}
						}
						if($end_flag == true){
							break;
						}
					}
					echo "<h2 style='color:white;'>Translated Protein:</h2>";
					echo  "<h3 style='color:white;'>$protein</h3>"."<br>";
				}
			}	
		}
		function most_repeated_kmer($conn, $dbname){
			if(isset($_POST['Analyze']) && isset($_POST['function2'])){
				$seq = $_POST['sequence'];
				$loc = $_POST['locus'];
				if(!empty($seq)){
					$seq = strtoupper($seq);
					if(strpos($seq, "U") !== false && strpos($seq, "T") !== false){
						echo "<script> alert('Invalid Sequence');</script>";
						return;
					}
					$k = 3;
					$most_repeated_kmer = "";
					$max = -999999;
					$temp_arr = array();
					for($i = 0; $i < strlen($seq) - $k + 1; $i++){
						$kmer = substr($seq, $i, 3);
						$count = 0;
						if(in_array($kmer, $temp_arr)){
							continue;
						}
						for($j = 0; $j < strlen($seq) - $k + 1; $j++){
							$temp = substr($seq, $j, 3);
							if($kmer == $temp){
								$count++;
							}
						}
						if($count > $max) {
							$max = $count;
							$most_repeated_kmers = array($kmer);
						} elseif ($count == $max) {
							array_push($most_repeated_kmers, $kmer);
						}
						array_push($temp_arr, $kmer);
					}
					echo "<h2 style='color:white;'>Most Repeated K-MERS:</h2>";
					foreach ($most_repeated_kmers as $kmer) {
		
						echo  "<h3 style='color:white;'>$kmer</h3>"."<br>";
						
					}
					echo "<br>";
				}
				if(!empty($loc)){
					$seq = form_select_db_ret($conn, $dbname, $loc);
					$seq = strtoupper($seq);
					if(strpos($seq, "U") !== false && strpos($seq, "T") !== false){
						echo "<script> alert('Invalid Sequence');</script>";
						return;
					}
					$k = 3;
					$most_repeated_kmer = "";
					$max = -999999;
					$temp_arr = array();
					for($i = 0; $i < strlen($seq) - $k + 1; $i++){
						$kmer = substr($seq, $i, 3);
						$count = 0;
						if(in_array($kmer, $temp_arr)){
							continue;
						}
						for($j = 0; $j < strlen($seq) - $k + 1; $j++){
							$temp = substr($seq, $j, 3);
							if($kmer == $temp){
								$count++;
							}
						}
						if($count > $max) {
							$max = $count;
							$most_repeated_kmers = array($kmer);
						} elseif ($count == $max) {
							array_push($most_repeated_kmers, $kmer);
						}
						array_push($temp_arr, $kmer);
					}
					echo "<h2 style='color:white;'>Most Repeated K-MERS:</h2>";
					foreach ($most_repeated_kmers as $kmer) {
						echo  "<h3 style='color:white;'>$kmer</h3>"."<br>";
					}
					echo "<br>";
				}
			}	
		}
		function DNA_Complementary($conn, $dbname){
			if(isset($_POST['Analyze']) && isset($_POST['function3'])){
				$seq = $_POST['sequence'];
				$loc = $_POST['locus'];
				if(!empty($seq)){
					$seq = strtoupper($seq);
					if(strpos($seq, "U") !== false && strpos($seq, "T") !== false){
						echo "<script> alert('Invalid Sequence');</script>";
						return;
					}
					if(strpos($seq, "U") !== false){
					
						echo "<script> alert('enter DNA to get its complementary');</script>";
						return;
					}
					$newseq="";
					foreach(str_split($seq) as $i){
						if($i=="A"){
							$newseq=$newseq."T";
						}
						if($i=="T"){
							$newseq=$newseq."A";
						}
						if($i=="G"){
							$newseq=$newseq."C";
						}
						if($i=="C"){
							$newseq=$newseq."G";
						}
						if($i=="N"){
							$newseq=$newseq."N";
						}
					}
					echo "<h2 style='color:white;'>Complementary DNA:</h2>";
					echo  "<h3 style='color:white;'>$newseq</h3>"."<br>";
				}
				if(!empty($loc)){
					$seq = form_select_db_ret($conn, $dbname, $loc);
					$seq = strtoupper($seq);
					if(strpos($seq, "U") !== false && strpos($seq, "T") !== false){
						echo "<script> alert('Invalid Sequence');</script>";
						return;
					}
					if(strpos($seq, "U") !== false){
						echo "<script> alert('enter DNA to get its complementary');</script>";
						return;
					}
					$newseq="";
					foreach(str_split($seq) as $i){
						if($i=="A"){
							$newseq=$newseq."T";
						}
						if($i=="T"){
							$newseq=$newseq."A";
						}
						if($i=="G"){
							$newseq=$newseq."C";
						}
						if($i=="C"){
							$newseq=$newseq."G";
						}
						if($i=="N"){
							$newseq=$newseq."N";
						}
					}
					echo "<h2 style='color:white;'>Complementary DNA:</h2>";
					echo  "<h3 style='color:white;'>$newseq</h3>"."<br>";
				}
			}
		}
		function GC_Content($conn, $dbname){
			if(isset($_POST['Analyze']) && isset($_POST['function4'])){
				$seq = $_POST['sequence'];
				$loc = $_POST['locus'];
				if(!empty($seq)){
					$totalN = strlen($seq);
					$Count = 0;
					$seq = strtoupper($seq);
					if(strpos($seq, "U") !== false && strpos($seq, "T") !== false){
						echo "<script> alert('Invalid Sequence');</script>";
						return;
					}
					for ($i = 0; $i < $totalN; $i++){ 
						if ($seq[$i] === 'G' || $seq[$i] === 'C'){
							$Count++;
						}
					}
					$gcContent = ($Count / $totalN) * 100;
					echo "<h2 style='color:white;'>GC-Content Percentage:</h2>";
					echo  "<h3 style='color:white;'>$gcContent.%</h3>";
				}
				if(!empty($loc)){
					$seq = form_select_db_ret($conn, $dbname, $loc);
					$seq = strtoupper($seq);
					$totalN = strlen($seq);
					$Count = 0;
					if(strpos($seq, "U") !== false && strpos($seq, "T") !== false){
						echo"<script> alert('Invalid Sequence');</script>";
						return;
					}
					for ($i = 0; $i < $totalN; $i++){ 
						if ($seq[$i] === 'G' || $seq[$i] === 'C'){
							$Count++;
						}
					}
					$gcContent = ($Count / $totalN) * 100;
					echo "<h2 style='color:white;'>GC-Content Percentage:</h2>";
					echo  "<h3 style='color:white;'>$gcContent.%</h3>"."<br>";
					
				}
			}
		}
		function transcription($conn, $dbname){
			if(isset($_POST['Analyze']) && isset($_POST['function5'])){
				$seq = $_POST['sequence'];
				$loc = $_POST['locus'];
				if(!empty($seq)){
					$seq = strtoupper($seq);
					if(strpos($seq, "U") !== false && strpos($seq, "T") !== false){
						echo "<script> alert('Invalid Sequence');</script>";
						return;
					}
					if(strpos($seq, "U") !== false){
						
						echo "<script> alert('enter DNA to get its transcript');</script>";
						return;
					}
					$rna = "";
					$dna = str_split($seq);
					foreach($dna as $chr){
						if ($chr =="T"){
							$rna = $rna.'U';
							continue;
						}
						$rna = $rna.$chr;
					}
					echo "<h2 style='color:white;'>Transcribed RNA:</h2>";
					echo  "<h3 style='color:white;'>$rna</h3>"."<br>";
				}
				if(!empty($loc)){
					$seq = form_select_db_ret($conn, $dbname, $loc);
					$seq = strtoupper($seq);
					if(strpos($seq, "U") !== false && strpos($seq, "T") !== false){
						echo "<script> alert('Invalid Sequence');</script>";
						return;
					}
					if(strpos($seq, "U") !== false){
						echo "<script> alert('enter DNA to get its transcript');</script>";
						return;
					}
					$rna = "";
					$dna = str_split($seq);
					foreach($dna as $chr){
						if ($chr =="T"){
							$rna = $rna.'U';
							continue;
						}
						$rna = $rna.$chr;
					}
					echo "<h2 style='color:white;'>Transcribed RNA:</h2>";
					echo  "<h3 style='color:white;'>$rna</h3>"."<br>";
				}
			}
		}
		function N_bases($conn, $dbname){
			if(isset($_POST['Analyze']) && isset($_POST['function6'])){
				$seq = $_POST['sequence'];
				$loc = $_POST['locus'];
				if(!empty($seq)){
					$seq = strtoupper($seq);
					if(strpos($seq, "U") !== false && strpos($seq, "T") !== false){
						echo "<script> alert('Invalid Sequence');</script>";
						return;
					}
					$len=strlen($seq);
					$sum=0;
					foreach(str_split($seq) as $i){
						if($i=='N'){
							$sum++;
						}
					}
					$NPercent=($sum / $len)*100;
					echo "<h2 style='color:white;'>N-Bases Percentage:</h2>";
					echo  "<h3 style='color:white;'>$NPercent.%</h3>";
				}
				if(!empty($loc)){
					$seq = form_select_db_ret($conn, $dbname, $loc);
					$seq = strtoupper($seq);
					if(strpos($seq, "U") !== false && strpos($seq, "T") !== false){
						echo "<script> alert('Invalid Sequence');</script>";
						return;
					}
					$len=strlen($seq);
					$sum=0;
					foreach(str_split($seq) as $i){
						if($i=='N'){
							$sum++;
						}
					}
					$NPercent=($sum / $len)*100;
					echo "<h2 style='color:white;'>N-Bases Percentage:</h2>";
					echo  "<h3 style='color:white;'>$NPercent.%</h3>";
				}
			}
		}
	?>
<script>
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