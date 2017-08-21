<?
///array sort
//get array
$a1 = array
  (
  array("Volvo",22,18),
  array("BMW",15,13),
  array("Saab",5,2),
  array("Land Rover",17,15)
  );

	
////split to array
for($i=0;$i<3;$i++)
{	///get compare value
	$a2[$i]=$a1[$i][2];
	
	///get list of content
	$list[$i]=$a1[$i][2];
}

////sorting
sort($a2); //// 2 13 15 18
$a3 = array();
////set array new position
for($i=0;$i<3;$i++)
{
	///loop for find position
	for($j=0;$j<3;$j++)
	{
		///loop for check
		if($list[$j]==$a2[$i])
		{
			$a3[$i][0] = $a1[$j][0];
			$a3[$i][1] = $a1[$j][1];
			$a3[$i][2] = $a1[$j][2];
		}
}
var_dump($a3)
?>