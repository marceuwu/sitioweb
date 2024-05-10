<?php include('../template/cabecera.php'); ?>
<?php 
$txtID = (isset($_POST['txtID'])) ? $_POST['txtID'] : "";
$txtNombre = (isset($_POST['txtNombre'])) ? $_POST['txtNombre'] : "";
$txtImagen = "";

if(isset($_FILES['txtImg']) && $_FILES['txtImg']['error'] === UPLOAD_ERR_OK) {
    $txtImagen = $_FILES['txtImg']['name'];
}

// El resto del código permanece igual

$accion = (isset($_POST['accion'])) ? $_POST['accion'] : "";

include('../config/bd.php');

switch ($accion) {
    case 'Agregar':
        //INSERT INTO `libros` (`id`, `nombre`, `imagen`) VALUES (NULL, 'Libro de php', 'imagen.png');
        $sql = $conexion->prepare("INSERT INTO libros (id,nombre, imagen) VALUES (:id, :nombre, :imagen);");
        $sql->bindParam(':id', $txtID);
        $sql->bindParam(':nombre', $txtNombre);

        $fecha = new DateTime();
        $nombreArchivo=($txtImagen!="")?$fecha->getTimestamp()."_".$_FILES["txtImg"]["name"]:"imagen.jpg";
        
        $tmpImagen = $_FILES["txtImg"]["tmp_name"];

        if($tmpImagen!=""){
            move_uploaded_file($tmpImagen,"../../img/".$nombreArchivo);
        }

        $sql->bindParam(':imagen', $nombreArchivo);
        $sql->execute();
        //echo "Presionado botón Agregar";
        break;
    case 'Modificar':
        echo "Presionado botón Modificar";
        $sql = $conexion->prepare("UPDATE libros SET nombre=:nombre WHERE id=:id;");
        $sql->bindParam(':nombre', $txtNombre);
        $sql->bindParam(':id', $txtID);
        $sql->execute();

        if($txtImagen != ""){
            $fecha = new DateTime();
            $nombreArchivo=($txtImagen!="")?$fecha->getTimestamp()."_".$_FILES["txtImg"]["name"]:"imagen.jpg";
            $tmpImagen = $_FILES["txtImg"]["tmp_name"];

            move_uploaded_file($tmpImagen,"../../img/".$nombreArchivo);

            $sql = $conexion->prepare("SELECT imagen FROM libros WHERE id=:id;");
            $sql->bindParam(':id', $txtID);
            $sql->execute();
            $libro= $sql->fetch(PDO::FETCH_LAZY);

            if(isset($libro['imagen']) && ($libro['imagen']!="imagen.jpg")){
                if(file_exists("../../img/".$libro['imagen'])){
                    unlink("../../img/".$libro['imagen']);
                }
            }
            
            $sql = $conexion->prepare("UPDATE libros SET imagen=:imagen WHERE id=:id;");
            $sql->bindParam(':imagen', $nombreArchivo);
            $sql->bindParam(':id', $txtID);
            $sql->execute();
        }


        break;
    case 'Cancelar':
        header('Location: productos.php');
        break;
    case 'Seleccionar':
        echo "Presionado botón Seleccionar";
        $sql = $conexion->prepare("SELECT * FROM libros WHERE id=:id;");
        $sql->bindParam(':id', $txtID);
        $sql->execute();
        $libro= $sql->fetch(PDO::FETCH_LAZY);

        $txtNombre = $libro['nombre'];
        $txtImagen = $libro['imagen'];
        break;
    case 'Borrar':
        //echo "Presionado botón Borrar";
        $sql = $conexion->prepare("SELECT imagen FROM libros WHERE id=:id;");
        $sql->bindParam(':id', $txtID);
        $sql->execute();
        $libro= $sql->fetch(PDO::FETCH_LAZY);

        if(isset($libro['imagen']) && ($libro['imagen']!="imagen.jpg")){
            if(file_exists("../../img/".$libro['imagen'])){
                unlink("../../img/".$libro['imagen']);
            }
        }
        
        $sql = $conexion->prepare("DELETE FROM libros WHERE id=:id;");
        $sql->bindParam(':id', $txtID);
        $sql->execute();
        break;
}

$sql = $conexion->prepare("SELECT * FROM libros;");
$sql->execute();
$listarLibros = $sql->fetchAll(PDO::FETCH_ASSOC);

// Procesamiento de datos del formulario aquí...
?>
<div class="col-md-5">
    <div class="card">
        <div class="card-header">
            Datos del Libro
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="txtID">ID</label>
                    <input type="text" class="form-control" value="<?php echo $txtID;?>" name="txtID" id="txtID" placeholder="ID">
                </div>
                
                <div class="form-group">
                    <label for="txtNombre">Nombre</label>
                    <input type="text" class="form-control" value="<?php echo $txtNombre;?>" name="txtNombre" id="txtNombre" placeholder="nombre">
                </div>
                <div class="form-group">
                    <label for="imagen">Imagen</label>
                    <br/>
                    <?php if($txtImagen!=""){ ?>
                        <img class="img-thumbnail rounded" src="../../img/<?php echo $txtImagen;?>" width="50" alt="">
                    <?php } ?>
                    <input type="file" class="form-control" name="txtImg" id="txtImg" placeholder="imagen">
                </div>
                
                <div class="btn-group" role="group" aria-label="">
                    <button type="submit" name="accion" <?php echo($accion == "Seleccionar")?"disabled":"";?> value="Agregar" class="btn btn-success">Agregar</button>
                    <button type="submit" name="accion" <?php echo($accion != "Seleccionar")?"disabled":"";?> value="Modificar" class="btn btn-warning">Modificar</button>
                    <button type="submit" name="accion" <?php echo($accion != "Seleccionar")?"disabled":"";?> value="Cancelar" class="btn btn-info">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="col-md-7">
    <a href="reportes.php">Reporte pdf</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Imagen</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <?php foreach($listarLibros as $libro) { ?>
            <tr>
                <td><?php echo $libro['id']; ?></td>
                <td><?php echo $libro['nombre']; ?></td>
                <td>
                   <img class="img-thumbnail rounded" src="../../img/<?php echo $libro['imagen']; ?>" width="50" alt="" srcset="">
                </td>
                
                <td>
                
                    <form method="POST">
                        <input type="hidden" name="txtID" id="txtID" value="<?php echo $libro['id']; ?>"/>

                        <input type="submit" name="accion" value="Seleccionar" class="btn btn-primary"/>

                        <input type="submit" name="accion" value="Borrar" class="btn btn-danger"/>
                        
                    </form>
                </td>
            </tr>
        <?php } ?>
        <tbody>
            <tr>
                <td>2</td>
                <td>Aprende</td>
                <td>imagen.jpg</td>
                <td>Seleccionar | Borrar</td>
            </tr>
        </tbody>
    </table>
</div>
<?php include('../template/pie.php'); ?>
