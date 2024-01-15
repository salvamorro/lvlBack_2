
<html>
    <head>
        <title>Notification</title>
        <style>
            .titulo{
                background-color: #16618cc0; 
                color: white; 
                font-weight: bold; 
                display: flex;
                justify-content: center;
                padding: 10px;
                width:75%;
                text-align: center;
            }
            .contenido{
                margin:20px;
            }
            .letraPequeña{
                color: grey; 
                font-size: small;
            }
            .button {
                padding: 15px;
                background-color: #16618cc0;
                color: white;
                border-radius: 10px;
                border: none;
            }
            body{
                display: flex;
                flex-direction: column;
                align-items: center;
            }
            footer{
                width: 75%
            }
            .inc{
                background-color: beige; 
                font-style: italic; 
                padding: 15px; 
                border-radius: 10px;
            }
        </style>
        
    </head>
    <body>
        <div class="titulo">
            Password change
        </div>
        <div class="contenido">
            <p>Please follow this link to change your password</p>
            
            <br>

        </div>
       <a type="button" class="button" href=<?php
            $app = getenv('GoToAPP');
         $direccion = $app."/#/rememberPass/" . $jwt; 
         
         echo $direccion;

         ?>>Change</a> 
    
    </body>
    {{-- <p><?php
        $app = getenv('GoToAPP');
     $direccion = $app."/#/rememberPass/" . $jwt; 
     
     echo $direccion;

     ?></p> --}}
    <footer class="letraPequeña">
        Please don´t answer to this mail
    <hr />

    © <?php $now = new DateTime();
            echo $now->format("Y")?> liostaff.com
    </footer>
</html>