
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
            .message{
                background-color: beige; 
                font-style: italic; 
                padding: 15px; 
                border-radius: 10px;
            }
        </style>
        
    </head>
    <body>
        <div class="titulo">
            You have a New Message "{{ $tipo }}"
        </div>
        <div class="contenido">
            <p class="message">
                {{$mensaje}}
            </p>
            
            <br>

        </div>
       <a type="button" class="button" href=<?php
         $direccion = "http://localhost:4200/"; 
         
         echo $direccion;

         ?>>Go to App</a> 
    
    </body>
    <footer class="letraPequeña">
        Please don´t answer to this mail
    <hr />

    © <?php $now = new DateTime();
            echo $now->format("Y")?> liostaff.com
    </footer>
</html>