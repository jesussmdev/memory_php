<?php
    session_start();

    require_once ("./Entity/Carta.php") ;

    $cartasseleccionadas=obtenerMano();
    $mano = array_merge($cartasseleccionadas, $cartasseleccionadas);
    

    function obtenerMano(){
        $palos = ['bastos','copas','espadas','oros'];
        $i = 0;
        $mano =[];
        do {

            $nuevo=$palos[random_int(0,3)];
            $nuevo .= ";".random_int(1,12);
            $nuevo .= ";"."false";
            $cartaExplode = explode(";",$nuevo);

            if(!in_array($nuevo,$mano)){
                //$nuevo .= ";".$i;
                //$carta = new Carta($cartaExplode[0].$cartaExplode[1]);
                $i++;
                array_push($mano,$nuevo);
            }
        } while (count($mano)<5);
        return $mano;
    }

    function mostrarCartas(array $mano){
        
        foreach ($mano as $carta) {
            $cartaExplode = explode(";",$carta);
            $cartaValor= intval($cartaExplode[1])-1;
            mostrarNumImgen($carta);
        }
        
        
    }

    function mostrarCartasBocaAbajo(array $mano){
        
        foreach ($mano as $carta) {
            $cartaExplode = explode(";",$carta);
            //$cartaid= intval($cartaExplode[3]);
            echo "<img onclick='girarCarta(event) src='../Images/Cartas/bocaabajo.png'   />";
        }
        
        
    }

    function mostrarNumImgen(string $carta){
        $cartavalor = explode(";",$carta);
        echo "<img src='../Images/Cartas/",$cartavalor[0],"/",$cartavalor[0],$cartavalor[1],".png' />";
    }




    

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src="../script.js"></script>
    <title>Document</title>
</head>
<body>
    <style>
        .container{
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;

            
        }
        
    </style>
    <div class="container">
        <h1>Memory</h1>
        <div >
        
        <?php
        if (shuffle($mano)) {
            ?>
            <div class="content">
                <?php
                mostrarCartasBocaAbajo($mano);
                //mostrarCartas($mano)
                ?>
            </div>
        <?php    
        }
        ?>
        </div>

    

    </div>

    
</body>
</html>
