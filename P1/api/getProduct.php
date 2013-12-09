<?php 
  header('Content-Type: application/json');
  require("db.php");
 
  $db = getDB();
  
  $stmt = $db->prepare('SELECT * FROM products WHERE ProductCode = ?');
  $stmt->execute(array($_GET['ProductCode']));
  $result = $stmt->fetch();
          
  if($result != null){
    $product = array('ProductCode' => $result['ProductCode'], 
                      'ProductDescription' => $result['ProductDescription'], 
                      'UnitPrice' => $result['UnitPrice'], 
                      'UnitOfMeasure' => $result['UnitOfMeasure'],
                      'ProductNumberCode' => $result['ProductNumberCode']);
    echo json_encode($product);
  }else{
    echo '{"error":{"code":404,"reason":"Product not found"}}';
  }
?>
