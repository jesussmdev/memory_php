<?php
declare(strict_types=1);
session_start();
if (isset($_POST['parejasNumeroJugar'])) {
  session_unset();
  $_SESSION["groupsdiff"]= $_POST['parejasNumeroJugar'];
  require_once("redirect.php");
  redirect("index.html");

}

?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="Style/config.css">
    <title>Memory Game</title>

    <audio src="Audio/music.mp3" autoplay="autoplay" loop="loop"></audio>

  </head>
  <body class="vh-100 justify-content-center align-items-center d-flex flex-column">
    <div class="bg-index justify-content-center align-items-center d-flex flex-column fadehome">
        <div class="row">
            <div class="col-12">
                <div class="card text-center bg-transparent text-white">
                    <div class="card-header">
                    Configuracion
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Seleccione cantidad de parejas a jugar</h5>
                        <form action="" method="post" >
                            <div class="mb-3">
                                <label for="parejasNumeroJugar" class="form-label">Cantidad de parejas</label>
                                <input id="parejasNumeroJugar" name="parejasNumeroJugar" type="number" max="48" min="2">
                              </div>
                            

                            <button  class="btn btn-primary" type="submit">Actualizar</button>
                        </form>
                        
                    </div>
                    <div class="card-footer text-white-50">
                        Predefinido a 48 parejas
                    </div>
                </div>
            </div>
        </div>
    </div>
    


    
    



    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <script src="loader.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"
    integrity="sha512-A7AYk1fGKX6S2SsHywmPkrnzTZHrgiVT7GcQkLGDe2ev0aWb8zejytzS8wjo7PGEXKqJOrjQ4oORtnimIRZBtw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.2/dist/umd/popper.min.js" integrity="sha384-q9CRHqZndzlxGLOj+xrdLDJa9ittGte1NksRmgJKeCV9DrM7Kz868XYqsKWPpAmn" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    -->
  </body>
</html>
