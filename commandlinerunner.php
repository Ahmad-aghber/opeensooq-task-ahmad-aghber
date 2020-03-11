<?php
  include 'class.php';

  //read first text
  echo "Please enter the first text: ";
  $firstText = readline();
  //read second text
  echo "Please enter the second text: ";
  $secondText = readline();

  //read type of the operation
  echo "Please enter H for hamming / L for levenshtein: ";

  $typeOfOperation = readline();

  if($typeOfOperation == 'L'){
    //create instance of Levenshtein
    $levenshteinOperation = new Levenshtein();

    //output answer
    echo 'The answer is : ' .  $levenshteinOperation->getLevenshteinDistance($secondText , $firstText);
  }
  elseif ($typeOfOperation == 'H') {
    //create instance of Hamming
    $hammingOperation = new Hamming();

    //get the distance
    $distance = $hammingOperation->getHammingDistance($secondText , $firstText);

    //check if the answer not equal -1 and print it
    if($distance != -1)
      echo 'The answer is : ' .  $distance;
    else
      echo 'Please enter correct data ';
  }

?>
