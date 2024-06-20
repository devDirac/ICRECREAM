<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <body>
    <div style="text-align:center">
        <img src="{{ URL::to('/') }}/img/logo_correo.png" alt="" width="400">    
    </div>
    {!! $titulo !!}
    {!! $html !!}
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <div style="height: 250px; background-color: #3E3E3E; margin-top: auto;">
        <div style="position: relative; top: 25px; text-align: center; width: 100%; color: white; background-color: #3E3E3E; font-family: var(--bs-font-sans-serif);">
            ® DIRAC | Ingenieros Consultores<br />
        </div>
    </div>
    </body>
</html>