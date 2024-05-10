<?php

ob_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
    <body>
        <?php
        include("../config/bd.php");

        $sql = $conexion->prepare("SELECT * FROM libros;");
        $sql->execute();
        $listarLibros = $sql->fetchAll(PDO::FETCH_ASSOC);

        ?>

        <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Imagen</th>
                    </tr>
                </thead>
                <?php foreach($listarLibros as $libro) { ?>
                    <tr>
                        <td><?php echo $libro['id']; ?></td>
                        <td><?php echo $libro['nombre']; ?></td>
                        <td>
                        <img class="img-thumbnail rounded" src="http://<?php echo $_SERVER['HTTP_HOST'];?>/sitioweb/img/<?php echo $libro['imagen']; ?>" width="50" alt="" srcset="">
                        </td>
                        
                        
                    </tr>
                <?php } ?>
                
        </table>
    </body>
</html>

<?php
$html = ob_get_clean();
//echo $html;

require_once "../../libreria/dompdf/autoload.inc.php";
use Dompdf\Dompdf;
$dompdf = new Dompdf();

$options = $dompdf->getOptions();
$options->set(array('isRemoteEnabled' => true));
$dompdf->setOptions($options);

$dompdf->loadHtml($html);

$dompdf->setPaper('letter');
//$dompdf->setPaper('A4', 'landscape');
$dompdf->render();

$dompdf->stream("reporte.pdf", array("Attachment" => true));


?>
