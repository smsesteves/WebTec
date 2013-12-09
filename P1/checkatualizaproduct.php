	

<?php
/*
  if(!isset($_POST['ProductCode']))
  {
  header("Location:changeproduct.php");
  } */

$newproduct['ProductCode'] = $_POST['ProductCode'];

$newproduct['ProductDescription'] = $_POST['ProductDescription'];
$newproduct['UnitPrice'] = $_POST['UnitPrice'];
$newproduct['UnitMeasure'] = $_POST['UnitMeasure'];
$newproduct['ProductNumberCode'] = $_POST['ProductNumberCode'];




$json = json_encode($newproduct);


$_POST['newproduct'] = $json;



?>

<script>

var jqxhr = $.getJSON("api/updateproducts.php",parasms);

jqxhr.done(function(data) {
console.log(data);
});

</script>
