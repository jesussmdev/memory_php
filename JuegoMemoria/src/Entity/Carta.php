<?php
    class Carta {
        private $id, $nombre;

        function __construct($nombre)
        {
            $this->nombre = $nombre;
        }

        function __set($name, $value)
        {
            $this->$name = $value;
        }

        function __get($name)
        {
            return $this->$name;
        }
    }
    
?>