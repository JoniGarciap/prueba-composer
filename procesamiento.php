<?php
    session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Guardar partida</title>
		<meta charset="UTF-8"/>
		<meta name="author" content="Jonathan"/>
	</head>
	<style>
		table, th, td {
  			border: 1px solid black;
			text-align: right;
			padding: 5px;
			border-collapse: collapse;
		}
		th{
			text-align: left;
		}
		input.i{
			width:85.9%;
		}
        p.error{
            color:red;
        }
	</style>
	<body>
    <?php
    $doc = new DOMDocument('1.0', 'utf-8');
    if(isset($_POST['save'])||isset($_POST['savedb'])){
        for ($x = 1; $x < 4; $x++) {
            if(empty($_POST["personaje".$x])) echo "<p class='error'>Advertencia: El personaje numero ". $x." no tiene nombre!</p>";
            if(empty($_POST["raza".$x])) echo "<p class='error'>Advertencia: El personaje numero ". $x." no tiene raza!</p>";
            if(empty($_POST["clase".$x])) echo "<p class='error'>Advertencia: El personaje numero ". $x." no tiene clase!</p>";
            if(empty($_POST["pg".$x])) echo "<p class='error'>Advertencia: El personaje numero ". $x." no tiene puntos de golpe!</p>";
            if(empty($_POST["ac".$x])) echo "<p class='error'>Advertencia: El personaje numero ". $x." no tiene clase de armadura!</p>";
        }    
        $doc->formatOutput = true;
        $root = $doc->createElement('partida');
        $root = $doc->appendChild($root);
        for ($x = 1; $x < 4; $x++) {

            $personaje = $doc->createElement('personaje');
            $personaje = $root->appendChild($personaje);
        
            $nombre = $doc->createAttribute('name');
            $nombre->value = $_POST["personaje".$x];
            $nombre = $personaje->appendChild($nombre);
        
            $raza = $doc->createElement('raza');
            $raza = $personaje->appendChild($raza);
        
            $text = $doc->createTextNode($_POST["raza".$x]);
            $text = $raza->appendChild($text);
        
            $clase = $doc->createElement('clase');
            $clase = $personaje->appendChild($clase);
        
            $text = $doc->createTextNode($_POST["clase".$x]);
            $text = $clase->appendChild($text);
        
            $pg = $doc->createElement('puntos_de_golpe');
            $pg = $personaje->appendChild($pg);
        
            $text = $doc->createTextNode($_POST["pg".$x]);
            $text = $pg->appendChild($text);
        
            $ac = $doc->createElement('clase_de_armadura');
            $ac = $personaje->appendChild($ac);
            
            $text = $doc->createTextNode($_POST["ac".$x]);
            $text = $ac->appendChild($text);
            }
    }
    if(isset($_POST['save'])){
       echo "<p>Los datos se han guardado en un fichero .xml</p>";
       echo "<p>Se han escrito: " . $doc->save("test.xml") . " bytes</p>";
    }
    if(isset($_POST['load'])){
        $doc->load('test.xml');   
        $_SESSION['xml'] = $doc->saveXML();
        header('Location: index.php');
    }
    if(isset($_POST['savedb'])){
        pg_connect("host=localhost port=5432 dbname=Partidas user=postgres password=admin")
        or die("No se ha podido conectar a la base de datos".pg_last_error());
        $xml = $doc->saveXML();
        $query = "INSERT INTO mispartidas (partidas) VALUES ('$xml')";
        $result = pg_query($query);
        echo "<p>Se han introducido los datos en la base de datos</p>";
    }
    if(isset($_POST['loaddb'])){
        pg_connect("host=localhost port=5432 dbname=Partidas user=postgres password=admin")
        or die("No se ha podido conectar a la base de datos".pg_last_error());
        $query = "SELECT partidas FROM mispartidas ORDER BY id DESC LIMIT 1;";
        $result = pg_query($query);
        $doc->loadXML(pg_fetch_result($result,0,0));
        $_SESSION['xml'] = $doc->saveXML();
        header('Location: index.php');
    }

    ?>
	</body>
</html>	