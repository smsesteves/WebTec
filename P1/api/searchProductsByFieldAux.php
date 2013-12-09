<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['login'] == FALSE) {
    header('Location:index.php');
}
?>

<?php 

function productsFields(){
    return array('ProductCode', 'ProductDescription', 'UnitPrice', 'UnitMeasure');
}

function isField($field) {
    return in_array($field, productsFields());
}

function isOp($op) {
    $ops = array('range', 'equal', 'contains', 'min', 'max');

    return in_array($op, $ops);
}

// baseado no codigo de http://stackoverflow.com/questions/353379/how-to-get-multiple-parameters-with-same-name-from-a-url-in-php
function getMultValues($query_string) {
    $query = explode('&', $query_string);
    $values = array();

    foreach ($query as $param) {
        list($name, $value) = explode('=', $param);
        if($name == 'value'){
            $values[] = urldecode($value);
        }
    }
    
    return $values;
}

?>
