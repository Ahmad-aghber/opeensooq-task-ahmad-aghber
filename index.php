<!DOCTYPE html>
<html>
<script>
    if(typeof window.history.pushState == 'function') {
        window.history.pushState({}, "Hide", '<?php echo $_SERVER['PHP_SELF'];?>');
    }
</script>
<head>
	<meta charset="utf-8">
	<title>Opensooq task</title>
	<!-- Font-->
	<link rel="stylesheet" type="text/css" href="css/sourcesanspro-font.css">
	<!-- Main Style Css -->
  <link rel="stylesheet" href="css/style.css"/>
</head>
<!--php -->
<?php

	include 'class.php';

  //create instance and print the output for levenshtein
	function helper_L(){
		$firstText = '';
		$secondText = '';


		if(isset($_GET['first_string_L']) && isset($_GET['first_string_L'])){
				$firstText = $_GET['first_string_L'];
				$secondText = $_GET['second_string_L'];
		}

		$levenshteinOperation = new Levenshtein();
		$distance = $levenshteinOperation->getLevenshteinDistance($secondText , $firstText);
		echo '<p align="center">The answer is : ' . $distance .'</p>';
		$levenshteinOperation->showExplanationMessage($secondText , $firstText );
	}

  //create instance and print the output for hamming
  function helper_H(){
		$firstText = '';
		$secondText = '';


		if(isset($_GET['first_string_H']) && isset($_GET['first_string_H'])){
				$firstText = $_GET['first_string_H'];
				$secondText = $_GET['second_string_H'];
		}

		$hammingOperation = new Hamming();

		$distance = $hammingOperation->getHammingDistance($secondText , $firstText);

    if($distance != -1){

      if($distance !=0)
		    echo '<p align="center">The answer is : ' . $distance .'</p>';

		  $hammingOperation->showExplanationMessage($secondText , $firstText );
    }
    else {
        echo '<p style="color:red;">Please enter correct input<p>';
    }

	}

 ?>
<body class="formbody">
	<div class="page-content">
		<div class="form-body-content">
			<div class="form-left">
				<img src="images/opeensooq.jpg" alt="form">
			</div>
			<div class="form-right">
				<div class="tab">
					<div class="tab-inner">
						<button class="tablinks" onclick="openCity(event, 'Levenshtein')" id="LevenshteinOpen">Levenshtein</button>
					</div>
					<div class="tab-inner">
						<button class="tablinks" onclick="openCity(event, 'Hamming')" id="HammingOpen">Hamming</button>
					</div>
				</div>
				<form class="form-detail"  method="get"  >
					<div class="tabcontent" id="Levenshtein">
						<div class="form-row">
							<label class="form-row-inner">
								<input type="text" name="first_string_L" id="first_string_L" class="input-text" autocomplete="off" required>
								<span class="label">First text</span>
		  						<span class="border"></span>
							</label>
						</div>
						<div class="form-row">
							<label class="form-row-inner">
								<input type="text" name="second_string_L" id="second_string_L" class="input-text" autocomplete="off" required>
								<span class="label">Second text</span>
		  						<span class="border"></span>
							</label>
						</div>
						<div class="form-row-last">
							<input  type="submit" id="run_L"   name="run_L" class="register" value="RUN">

						</div>
						<!--php -->
						<?php
			        if(isset($_GET['run_L'])){
			            helper_L();
			        }

			        if(isset($_GET['run_H'])){
			            helper_H();
			        }
						?>
					</div>
				</form>
				<form  class="form-detail"  method="get" >
					<div class="tabcontent" id="Hamming">
						<div class="form-row">
							<label class="form-row-inner">
								<input type="text" name="first_string_H" id="first_string_H" class="input-text" autocomplete="off" required>
								<span class="label">First text</span>
		  						<span class="border"></span>
							</label>
						</div>
						<div class="form-row">
							<label class="form-row-inner">
								<input type="text" name="second_string_H" id="second_string_H" class="input-text" autocomplete="off" required>
								<span class="label">Second text</span>
		  						<span class="border"></span>
							</label>
						</div>
						<div class="form-row-last">
							<input   type="submit" id="run_H" name="run_H"  class="register" value="RUN">
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<script type="text/javascript">

		function openCity(evt, cityName) {
		    var i, tabcontent, tablinks;
		    tabcontent = document.getElementsByClassName("tabcontent");
		    for (i = 0; i < tabcontent.length; i++) {
		        tabcontent[i].style.display = "none";
		    }
		    tablinks = document.getElementsByClassName("tablinks");
		    for (i = 0; i < tablinks.length; i++) {
		        tablinks[i].className = tablinks[i].className.replace(" active", "");
		    }
		    document.getElementById(cityName).style.display = "block";
		    evt.currentTarget.className += " active";
		}
		// Get the element with id="defaultOpen" and click on it
		document.getElementById("LevenshteinOpen").click();

	</script>

</body>
</html>
