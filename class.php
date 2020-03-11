<?php

/**
* This project was made by Ahmad al Aghber
* This project for opensooq task
*
*
*
*
*
*
* interface for checking if the two strings are valid
*/
interface StringCorrect
{
  public function checkString($firstString , $secondString);
}

/**
  * class Levenshtein with attributes :
  *numOfInsert : shows the number of insert process
  *numOfReplace : shows the number of replace process
  *numOfSubstitution : shows the number of remove process
  *showProcess : shows the operations made on the two texts in string text
  *cellsForInsert : shows the cells that processed with insert method
  *cellsForReplace : shows the cells that processed with replace method
  *cellsForRemove : shows the cells that processed with remove method
  *cellsForEqualCharacters : shows the cells has the equal string at index row and col
  *distance : represents the dp table
  * with method check strings for checking if the two strings are correct and this is the body of checkstring in string correct interface
 */
class Levenshtein implements StringCorrect{
  private $numOInsert = 0;
  private $numOfReplace = 0;
  private $numOfSubstitution = 0;
  private $showProcess = '';
  private $cellsForInsert = array();
  private $cellsForReplace = array();
  private $cellsForRemove = array();
  private $cellsForEqualCharacters = array();
  private $distance = array();



 /**
  * The body of checkString() is provided here
  * check if the string size is greater than 0
  */
  public function checkString($firstString , $secondString){

    //get the length of the two strings
    $firstStringLen = strlen($firstString);
    $secondStringLen = strlen($secondString);

    //strcase for comparing between two strings if they are not equal
    if($firstStringLen > 0 && $secondStringLen > 0){
         return true;
    }


    return false;
  }

  //reset all values when we call the getLevenshteinDistance
  private function resetAttributes(){
    $this->numOfInsert = 0;
    $this->numOfReplace = 0;
    $this->numOfSubstitution = 0;
    $this->showProcess = '';
    $this->cellsForInsert = array();
    $this->cellsForReplace = array();
    $this->cellsForRemove = array();
    $this->cellsForEqualCharacters = array();
    $this->distance = array();
  }

  // this method uses DP with O(a * b) where a  = length of first string and b = length of second string
  public function getLevenshteinDistance($firstString , $secondString): int{
    //create matrix of a * b where a = length of firststring + 1 and b  = length of secondstring +1
    $rowlen = strlen($firstString) + 1;
    $collen = strlen($secondString) + 1;

    //reset all values when we call the getLevenshteinDistance
    $this->resetAttributes();

    //for row - > firststring length +1
    for($row = 0 ; $row < $rowlen ; $row++){
    //for col - > secondString length +1
      for($col = 0 ; $col < $collen ; $col++){


        //fill first row 0 - firststring length +1
        if($row == 0){
          $this->distance[0][$col] = $col;
          continue;
        }
        //fill first column 0 - secondstring length +1
        if($col == 0){
          $this->distance[$row][0] = $row;
          continue;
        }


        /**
        *if firststring character at row not equal  secondString character at column{
        *get <- value from table as a
        *get ↑ value from table as b
        *get ↖ value from table as c
        *pick the minimum number from a , b , c and add 1 to table[row][column] index
        *}
        *else{
        *get ↖ value from table and add it to table[row][column] index
        *}
        */
        if($firstString[$row - 1] != $secondString[$col - 1]){

          $topOfTheElement = $this->distance[$row - 1][$col];
          $leftOfTheElement = $this->distance[$row][$col - 1];
          $diagonalOfTheElement = $this->distance[$row - 1][$col - 1];

          $this->distance[$row][$col] = min($diagonalOfTheElement , $leftOfTheElement , $topOfTheElement) + 1;
          if($diagonalOfTheElement == ($this->distance[$row][$col] - 1)){
            $this->numOfReplace++;

          }elseif ($leftOfTheElement == ($this->distance[$row][$col] - 1)) {
            $this->numOfSubstitution++;

          }
          else {
            $this->numOfInsert++;

          }
        }
        else { $this->distance[$row][$col] = $this->distance[$row - 1][$col - 1]; }
      }
    }

    //call the method backtrace to see how dp table was made
    $this->backTrace( $firstString , $secondString);

    //the answer will be at the last index of row and column
    return $this->distance[$rowlen - 1][$col - 1];
  }


  /*
  *backtrace function show us how the dp table was made by checking each cell from the answer until position 0 0
  *tell the type of the cell by checking from the where did the value come
  *if the value came from  <- then we have to delete the second string (column) of index row and col
  *if the value came from  ↑ then we have to add the first string (column)  of index row and col
  *if the value came from  ↖ then we have to repalce the second string (column)  of index row and col   with the first string (column)  of index row and col
  */
  private function backTrace(  $firstString , $secondString){

    $row = strlen($firstString) ;
    $col = strlen($secondString) ;

    //start from the answer until index  0 0
    while ( $row !=0 && $col !=0 ) {
      if($firstString[$row - 1] == $secondString[$col - 1]){
        array_push($this->cellsForEqualCharacters , $row .'+' . $col);
        --$row;
        --$col;
        continue;
      }
      if($this->distance[$row][$col] == $this->distance[$row - 1][$col - 1] + 1){
        $this->numOfReplace++;
        array_push($this->cellsForReplace , $row .'+' . $col);
        $this->showProcess .=  '- Replace ' . $secondString[--$col] .' -> '. $firstString[--$row] . '<br>';
      }
      elseif ($this->distance[$row][$col] == $this->distance[$row][$col - 1] + 1) {
        $this->numOfSubstitution++;
        array_push($this->cellsForRemove , $row .'+' . $col);
        $this->showProcess .=  '- Remove ' . $secondString[--$col] . '<br>';
      }
      else {
        $this->numOfInsert++;
        array_push($this->cellsForInsert , $row .'+' . $col);
        $this->showProcess .=  '- Insert ' . $firstString[--$row] . '<br>';
      }
    }
    if($this->distance[$row][$col] !=0){
      if($col == 1){
        $this->numOfSubstitution++;
        array_push($this->cellsForRemove , $row .'+' . $col);
        $this->showProcess .=  '- Remove ' . $secondString[--$col] . '<br>';
      }
      else {
        $this->numOfInsert++;
        array_push($this->cellsForInsert , $row .'+' . $col);
        $this->showProcess .=  '- Insert ' . $firstString[--$row] . '<br>';
      }
    }
  }

  /*
  *showExplanationMessage function show us how the Levenshtein distance work and to print the explanation message
  *this function shows us the operation made for the first and second string
  *1- insert
  *2- delete
  *3- replace
  */
	function showExplanationMessage($firstText , $secondText){

		echo "<p>The Levenshtein distance between two words is the minimum number of single-character edits (insertions, deletions or substitutions) required to change one word into the other.</p>";

		echo "The operations made for first and second text are : <br>";
    //showProcess contains all of the process made for the two strings
		echo $this->showProcess;
    //showTable contains all of the table data from the dp table with nice design
		$this->showTable($firstText , $secondText );
	}

  /*
  *showTable is made to represent the table made from dp table
  *the orange represents the replace process
  *the green represents the insert process
  *the red represents the delete process
  */
  private function showTable( $firstText , $secondText ){
		echo '<div class="form-row">';
		echo '<table border="2" width="400">';
		echo '<tr>';
		echo '<td>', ' ', '</td>';
		echo '<td bgcolor="#394C45">', '#', '</td>';


		//print column 0 with second text characters
		for($i = 0 ; $i < strlen($secondText) ; $i++){
			echo '<td bgcolor="#394C45">', $secondText[$i] ,'</td>';
		}


		echo '</tr>';

    //start from row 0 until strlen + 1 which is the index of the answer
		for($row = 0 ; $row < strlen($firstText) + 1 ;$row++) {
				echo '<tr>';
        //print the first row with the first string
				if($row != 0){
					echo '<td bgcolor="#394C45">', $firstText[$row - 1], '</td>';
				}
				else {
					echo '<td bgcolor="#394C45">', "#", '</td>';
				}
        /*
        *start from col 0 until strlen + 1 which is the index of the answer
        *print the data cells and their color and the color depend on the type of the operation
        */
				for($col = 0 ; $col < strlen($secondText) + 1 ; $col++) {
					$this->checkCellsType( $row , $col );
				}
				echo '</tr>';
		}


		echo '</table>';
		echo "<br>";
    //show the user what each color represents
		echo "<div><div class='box red'></div>  Remove </div>
					<br>
					<div><div class='box green'></div>  Insert </div>
					<br>
					<div><div class='box orange'></div>  Replace</div>
					<br>
					<div><div class='box lightgrey'></div>  Same Characters</div>
					";
		echo '</div>';
	}

  /*
  *return colored cell and it's depend on it's operation
  *the orange represents the replace process
  *the green represents the insert process
  *the red represents the delete process
  */
	private function checkCellsType($row , $col){
		if(in_array(($row . '+'. $col) , $this->cellsForRemove)){
      // distance from the table
			echo '<td bgcolor="#FF0000">' . $this->distance[$row][$col] . '</td>';

		}elseif (in_array(($row . '+'. $col) , $this->cellsForReplace)) {
      // distance from the table
			echo '<td bgcolor="#fc8b18">' . $this->distance[$row][$col] . '</td>';

		} elseif (in_array(($row . '+'. $col) , $this->cellsForInsert)) {
      // distance from the table
			echo '<td bgcolor="#1d4d01">' . $this->distance[$row][$col] . '</td>';

		} elseif (in_array(($row . '+'. $col) , $this->cellsForEqualCharacters)) {
      // distance from the table
			echo '<td bgcolor="#C0C0C0">' . $this->distance[$row][$col] . '</td>';

		} else{
      // distance from the table
			echo '<td>' . $this->distance[$row][$col] . '</td>';
		}
	}


}


/**
  * class Hamming with attributes :
  *numOfSubstitution : shows the number of remove process
  *showProcess : shows the operations made on the two texts in string text
  *finalString : represents the final string from the two strings
 */
class Hamming implements StringCorrect{
  private $numOfSubstitution = 0;
  private $showProcess = '';
  private $finalString = '';


  private function resetAttributes(){
    $this->showProcess = '';
    $this->numOfSubstitution = 0;
    $this->finalString = '';
  }
  /**
   * The body of checkString() is provided here
   * check if the two strings size are greater than 0 and the two strings equal in size to each other
   */
  public function checkString($firstString , $secondString){

     //get the length of the two strings
     $firstStringLen = strlen($firstString);
     $secondStringLen = strlen($secondString);

     //strcase for comparing between two strings if they are  equal
     if($firstStringLen > 0 && $secondStringLen > 0 && $firstStringLen == $secondStringLen ){
          return true;
     }

     return false;
  }


  // this method uses DP with O(a) where a  = length of two strings and they must be the same size
  public function getHammingDistance($firstString , $secondString): int{
    $this->resetAttributes();
    //check if the string is correct
    if($this->checkString($firstString , $secondString)){
      //reset each time we call the function to 0 for new result
      $this->numOfSubstitution = 0;

      //loop for each character in string check if the character at index i at firststring not equal
      //the character at index i at secondstring
      for($i = 0 ; $i < strlen($firstString) ; $i++){

        if( !ctype_space($firstString[$i]) ){
          if($firstString[$i] != $secondString[$i]){
            $this->numOfSubstitution++;
            $this->showProcess .= '- Remove ' . $firstString[$i] . ' from the first text and remove ' . $secondString[$i] .' from the second text.<br>';
          }
          else {
            $this->finalString .= $firstString[$i];
          }
        }

      }

      //the answer will be at the last index of row and column
      return $this->numOfSubstitution;
    }
    else {
      return -1;
    }
  }


  public function showExplanationMessage(){
    echo "<p>The Hamming distance is the number of positions with same symbol in both strings. Only defined for strings of equal length.</p>";

    if(strlen($this->finalString) != 0 )
      echo 'The final text is : ' . $this->finalString . '.<br>';

    echo "The operations made for the first and the second text are : <br>";

    echo $this->showProcess;
  }

}





 ?>
