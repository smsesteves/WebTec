<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['login'] == FALSE) {
    header('Location:index.php');
}
?>

<?php 

header('Content-Type: application/json');

/*require('searchProductsByFieldAux.php');

$error = false;
if (!isField($_GET['field'])) {
    $error = true;
    echo 'Campo ' . $_GET['field'] . ' não existe!';
}
if (!isOp($_GET['op'])) {
    $error = true;
    echo 'Operação ' . $_GET['op'] . ' não existe!';
}

if ($error) {
    exit(1);
}

*/

$db = new PDO('sqlite:../db/db_t1.db');

// verificar se o campo existe
$sql = "SELECT * FROM products WHERE " . $_GET['field'];

switch ($_GET['op']) {
    case "range":
        $sql .= " BETWEEN '" . $_GET['value'][0] . "' AND '" . $_GET['value'][1] . "'";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        break;
    case "equal":
        $sql .= " = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute(array($_GET['value'][0]));
        break;
    case "contains":
        $sql .= " LIKE '%" . $_GET['value'][0] . "%'";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        break;
    case "min":
        $sql .= " = (SELECT MIN(" . $_GET['field'] . ") FROM products)";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        break;
    case "max":
        $sql .= " = (SELECT MAX(" . $_GET['field'] . ") FROM products)";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        break;
}

$products = array();

while ($row = $stmt->fetch()) {
    $products[] = array('ProductCode' => $row['ProductCode'],
        'ProductDescription' => $row['ProductDescription'],
        'UnitPrice' => $row['UnitPrice']);
}

echo json_encode($products);

?>
