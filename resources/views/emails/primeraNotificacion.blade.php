
<html>
    <head>
        <title>{{ $title ?? 'Notification' }}</title>

        <link rel="stylesheet" href="{{ asset('app.css') }}" />
        {{-- <link href="/css/app.css" rel="stylesheet"> --}}

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
            New {{ $tipo }}
        </div>
        <div class="contenido">
            <p>You have a new {{$tipo}} from {{$user->nombre}} {{$user->apellidos}}.</p>
            <div class="inc">
                <strong>{{$tituloNotificacion}}</strong>
                <br>
                {{$mensaje}}
        </div>
            <p>Kind regards, Lio Team</p>
            <br>

        </div>
        {{-- <a type="button" class="button" href="www.google.com">Go To App</a> --}}
    
    </body>
    <footer class="letraPequeña">
        Please don´t answer to this mail
    <hr />

    © <?php $now = new DateTime();
            echo $now->format("Y")?> liostaff.com
    </footer>
</html>