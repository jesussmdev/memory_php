<?php
session_start();

/**
 * Clase Memory que contiene toda la lógica para el desarrollo del juego Memory o juego de las parejas
 * 
 */
class Memory {

    //Singleplayer
    //size variable tamaño de las parejas
    //turns variable para detectar los turnos
    //turnt variable para detectar los giros
    //turnt_image array de imagenes giradas
    //image_id array con los id de las imagenes
    //notTurnt_image imagen bocaabajo
    //allowedToSwitch variable que permite detectar si podemos girar una carta
    //points sitema de puntuacion para el modo de juego rankeds
    //avgturns media de turnos para el modo de juego rankeds
    //roundslimit limite de rondas para el modo de juego rankeds
    private $size, $turns = 0;
    private $turnt, $turnt_image, $image_id  = [];
    private $notTurnt_Image = '';
    private $allowedToSwitch = true;
    private $points, $avgturns = 0;
    private $roundslimit = 20;

    //Music (al final no incluí sonidos de acierto error porque al estar la logica todo en php al recargar 
    //las paginas los sonidos se escuchaban mal  con la musica de fondo)
    private $correctMusic = false;
    private $incorrectMusic = false;

    //Multiplayer 
    private $multiplayer = false;
    private $multiplayerP1Timer = 120;
    private $multiplayerP2Timer = 120;
    private $multiplayerP1Turn = 0;
    private $multiplayerP2Turn = 0;
    private $multiplayerP1Turnt = [];
    private $multiplayerP2Turnt = [];
    private $allowedToSwitchP1 = false;
    private $allowedToSwitchP2 = false;
    private $randomInitMultiplayers = true;


    /**
     * Constructor de la clase
     */
    function __construct(array $images = [] , $notTurnt_Image) {
        $this->turnt_image = array_merge($images, $images);
        $this->notTurnt_Image = $notTurnt_Image;
        $this->size = count($this->turnt_image);
        $this->image_id = $this->turnt = array_fill(0, $this->size, 0);
        $this->sessionExist('turnt_image', $this->turnt_image);
        $this->sessionExist('turns', $this->turns);
        $this->sessionExist('turnt' , $this->turnt);
        $this->needShaking();
    }

    
    /**
     * Funcion en la que comprobamos si se necesitan mostrar las cartas
     */
    private function needShaking() {
        if(isset($_SESSION['image_id'], $_SESSION['turnt_image'])){
            $this->image_id = $_SESSION['image_id'];
            $this->turnt_image = $_SESSION['turnt_image'];
        }else{ 
            $this->shakeCards();
        }
    }

    
    /**
     * Funcion para comprobar si exite la sesión, en caso de que no exista se genera el valor (inicializamos las sesiones)
     */
    private function sessionExist(string $nameSession, $value) {
        if(!isset($_SESSION[$nameSession]))
            $_SESSION[$nameSession] = $value;
    }

    
    /**
     * Funcion para mezclar las cartas de forma random
     */
    private function shakeCards() {
        $half = floor($this->size / 2);
        //Ponemos el identificador a las imagenes
        for ($i=0; $i < $half; $i++) {
            $this->image_id[$i] = $this->image_id[$half + $i] = $i;
        }

        $this->turnt_image = $this->changeImagePosition($this->turnt_image);
        $this->image_id = $this->imageIdSamePos($this->image_id);
        $this->sessionExist('image_id', $this->image_id);
        $this->sessionExist('turnt_image', $this->turnt_image);
    }

   
    /**
     * Mezclar las imagenes manteniendo la clave de las imagenes (clave valor)
     */
    private function changeImagePosition(array $list=[]) : array {
        $keys = array_keys($list);
        shuffle($keys);
        $random = [];
        foreach ($keys as $key) {
            $random[$key] = $list[$key];
        }
        return $random;
    }


    /**
     * Establece la clave de las imagenes como id de las mismas
     */
    private function imageIdSamePos(array $list=[]) : array {
        $keys = array_keys($this->turnt_image);
        $random = [];
        foreach($keys as $index) {
            array_push($random, $list[$index]);
        }
        return $random;
    }


    /**
     * Funcion para cargar el mazo total de las cartas a jugar
     */
    public function loadField() : string {
        $temp_array_ids = array_keys($this->image_id);
        $this->allowedToSwitch && isset($_SESSION['turnt']) ? $this->turnt = $_SESSION['turnt'] : $this->allowedToSwitch = true;

        $html = "<form action='game.php' method='post'>\n <div id='imageContainer' class='marginAuto w-100'>\n";
        for ($i=0; $i < $this->size; $i++) {
            $html .= "\n<div class='images'> \n";
            $html .= "<button class='image' name=".$temp_array_ids[$i] . ($this->turnt[$i] != 0 ? " disabled>" : ">");
            $html .= "\n   <img ".  getimagesize($this->turnt_image[$this->image_id[$temp_array_ids[$i]]])[3]     ." src='";
            $html .= ($this->turnt[$i] != 0 ? $this->turnt_image[$this->image_id[$temp_array_ids[$i]]] : $this->notTurnt_Image) . "'/>\n";
            $html .= "</button> \n</div>\n";
        }
        $html .="</div>\n<div id='button' class='marginAuto w-85 bg-transparent '>\n" . "<input type='submit' name='again' value='Restart' class='again w-100 game-button red'>" . "\n</div>\n </form>\n";
        return $html;
    }


    /**
     * Comprueba que las dos imagenes son iguales
     */
    public function turn(array $post) {
        $_SESSION['turnt'][key($post)] = 1;
        $this->turnt = $_SESSION['turnt'];
        $this->turns = $_SESSION['turns']++;

        if(isset($_SESSION['lastNumber']) && $this->image_id[key($post)] != $this->image_id[$_SESSION['lastNumber']]) {
            $this->allowedToSwitch = false;
            $_SESSION['turnt'][key($post)] = $_SESSION['turnt'][$_SESSION['lastNumber']] = 0;
            

        }
        $_SESSION['lastNumber'] = isset($_SESSION['lastNumber']) ? null : key($post);
    }

    
    /**
     * Comprobamos que el jugador ha ganado la partida
     */
    public function wonTheGame(): string{
        if(isset($_SESSION['turnt']) && count(array_keys($_SESSION['turnt'], 0)) === 0){
            return ', FELICIDADES HAS GANADO';
        }
         else{
            return "";
         }   
    }

    
    /**
     * Calculamos el porcentaje de la partida
     */
    public function getCompletion() : string {
        $ret = count(array_keys($_SESSION['turnt'], 0)) % 2 == 0 ?
            round(($this->size - count(array_keys($_SESSION['turnt'], 0))) / $this->size * 100 , 1) :
            round(($this->size - count(array_keys($_SESSION['turnt'], 0)) -1) / $this->size * 100 , 1);
        return ($ret > 0 ? $ret : 0) . '%';
    }

    /**
     * Calcular cantidad de parejas encontradas
     */
    public function getPairs() : string {
        $aux = ($this->size - count(array_keys($_SESSION['turnt'], 0)));
        $ret = count(array_keys($_SESSION['turnt'], 0)) % 2 == 0 ?
        round(($this->size - count(array_keys($_SESSION['turnt'], 0))) / $this->size * 100 , 1) :
        round(($this->size - count(array_keys($_SESSION['turnt'], 0)) -1) / $this->size * 100 , 1);
        if ($ret <= 0) {
            $pairs = 0;
        }else{
            $pairs = (($this->size/2)*$ret)/100;

        }
        return $pairs;
    }

    /**
     * Si la actividad es mayor a una hora destruimos la sesión
     */
    public static function maxTimeSessionExeeded() : bool {
        if (isset($_SESSION['lastActivity']) && (time() - $_SESSION['lastActivity'] > 3600))
            return true;
        
        $_SESSION['lastActivity'] = time();
        return false;
    }

    /**
     * Reiniciamos el juego
     */
    public static function restart() {
        $valor = $_SESSION["groupsdiff"];
        setcookie('seconds', null, -1, '/');
        setcookie('minutes', null, -1, '/');
        session_unset();
        $_SESSION["groupsdiff"]= $valor;
    }


    /**
     * Obtenemos el tiempo de las cookies
     */
    public function getTime() : string { 
        if(isset($_COOKIE['minutes']))
            return " | " . sprintf("%02d", $_COOKIE['minutes']) . ":"  .sprintf("%02d", $_COOKIE['seconds']); 
        else
            return " | " . 00.00 . ":"  . 00.00; 
    }

    /**
     * Obtenemos el valor de los turnos
     */
    public function getTurns() : int { 
        return $this->turns; 
    }


    /**
     * Obtenemos el tamaño
     */
    public function getSize() : int { 
        return $this->size; 
    }

    //TODO Multiplayer y sistema de ranking (no necesario para la entrega, solo se pedia 1 jugador)

    /**
     * Obtenemos los puntos
     */
    public function getPoints() : int { 
        return $this->points; 
    }

    /**
     * Obtenemos la media
     */
    public function avgturns() : int { 
        return $this->avgturns; 
    }

    /**
     * Calculamos puntos TODO
     */
    public function pointSystemRanked(int $limit ,int $turns, int $time, int $cards, bool $win) : int { 

        //Para si me sobran rondas, por ronda que sobre sumar +10 puntos
        //Si el tiempo es superior a 2 min restar -5
        //si has ganado sumar 50 puntos
        //si ha perdido restar 15 puntos
        //

        return $pointsWin; 
    }


}
?>