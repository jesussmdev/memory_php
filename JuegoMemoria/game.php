<?php
declare(strict_types=1);
require_once 'memory.php';
//$_SESSION["groupsdiff"]= 5;

if(isset($_POST['again']) || Memory::maxTimeSessionExeeded()) {
  Memory::restart();

}
$images = glob('Images/Baraja/'.'*.{jpeg,jpg,gif,png}', GLOB_BRACE);

if(isset($_SESSION['groupsdiff'])) {
    $parejasnumero = $_SESSION['groupsdiff'];
    $posicion=0;
    $repetidos=array();
    $arrParejas = [];
    for($i=1; $i<=$parejasnumero; $i++){
        $repetidos[$posicion]=comprobarRepetido($repetidos,rand(0,47)) ;
        array_push($arrParejas,$images[$repetidos[$posicion]]);
        $posicion=$posicion+1;
        
    }

    
}else{
    $arrParejas = $images;
}

function comprobarRepetido($array, $numero):int{
    $repetido=false;

    for($j=0; $j<count($array) && $repetido==false; $j++){
        $repetido=($array[$j]==$numero)?true:false;
    }
    if($repetido==true)
        return comprobarRepetido($array,rand(0,47));
    else
        return $numero;
}

$background_imageUrl = 'Images/bocaabajo.png';
$memory = new Memory($arrParejas, $background_imageUrl);

for ($i=0; $i < $memory->getSize(); $i++) {
  if(isset($_POST[$i])){
    $memory->turn($_POST);
  }
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Memory Game</title>
    <link rel="stylesheet" href="Style/game.css">
    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
    <script src="memory.js"> </script>
    <audio src="Audio/music.mp3" autoplay="autoplay" loop="loop"></audio>
  </head>
  <body>
    <button class="game-button btn-size green" onclick="window.location='index.html'">Home</button>
    <h2 class="text-white">Juego de las parejas <?php echo $memory->wonTheGame(); ?></h2>
    <p class="text-white textCenter">Turnos : <?php echo $memory->getTurns(); ?> | Parejas encontradas: <?php echo $memory->getPairs(); ?> |  Porcentaje completado : <?php echo $memory->getCompletion(); ?><span class="showTime"><?php echo $memory->getTime(); ?></span></p>	

    <?php echo $memory->loadField(); ?>

</body>
</html>
<?php
?>