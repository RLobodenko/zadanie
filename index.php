set_time_limit(7200);
ini_set("memory_limit", "3000M");

function parse_excel_file( $filename ){
require_once $_SERVER['DOCUMENT_ROOT'].'/PHPExcel-1.8/Classes/PHPExcel.php';
$result = array();

$file_type = PHPExcel_IOFactory::identify( $filename );
$objReader = PHPExcel_IOFactory::createReader( $file_type );
$objPHPExcel = $objReader->load( $filename ); 
$result = $objPHPExcel->getActiveSheet()->toArray(); 

return $result;
}

$res = parse_excel_file($_SERVER['DOCUMENT_ROOT'].'\pricelist.xls' );

$arr_result = count($res);

for ($i=0; $i<$arr_result; $i++) {
$arr = [implode(',', $res[$i])];
$arr_str = implode('', $arr);
$connect_string = "host=localhost port=5432 dbname=postgres user=postgres password=";
$dbconnect = pg_connect($connect_string);
$query = "INSERT INTO test_db (name, value, value-opt, availability-1, availability-2, country) VALUES ($i, '$arr_str') ";
$result = pg_query($dbconnect, $query);

pg_close($dbconnect);

}