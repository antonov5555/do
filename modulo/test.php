<?
echo "asdasdasd";
$enlace =  mysql_connect('localhost', 'root', '');
if (!$enlace) {
    die('No pudo conectarse: ' . mysql_error());
}
echo 'Conectado satisfactoriamente';
mysql_close($enlace);

echo "asdasddasd";
?>