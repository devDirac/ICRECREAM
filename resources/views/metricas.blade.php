<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ URL::to('/') }}/css/bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ URL::to('/') }}/css/style.css" rel="stylesheet" type="text/css" />
    <title>DIRAC | Ingenieros Consultores</title>
    <link rel="icon" type="image/x-icon" href="{{ URL::to('/') }}/img/arjion1.png?v=1">
    <script src="{{ URL::to('/') }}/js/jquery.min.js"></script>
    <script src="{{ URL::to('/') }}/js/bootstrap5.js"></script>
    <script src="{{ URL::to('/') }}/js/metricas.js?v=0.1"></script>
    <script src="{{ URL::to('/') }}/js/index.js?v=0.1"></script>
</head>

<body>
    <nav class="navbar bg-body-tertiary content-nav">
        <div class="container">
            <p class="navbar-brand">
                <img src="{{ URL::to('/') }}/img/arjion1.png?v=1" alt="Bootstrap" width="55" height="55">
            </p>
            <p class="navbar-brand">Bienvenido al sistema de reporte de voz</p>
        </div>
    </nav>
    <br />
    <div class="container">
        <div class="row">
            <div class="col-md-12" style=" border:solid 10px red, w">
                <div class="card text-center">
                    <div class="card-header" style="background-color:white">
                        <ul class="nav nav-tabs card-header-tabs" role="tablist" id="${element?.id}"
                            style="background-color:white">

                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="imagen-tab${element?.id}" data-bs-toggle="tab"
                                    data-bs-target="#imagen-tab-pane${element?.id}" type="button" role="tab"
                                    aria-controls="imagen-tab-pane${element?.id}" aria-selected="true">Reporte de
                                    imagen</button>
                            </li>

                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="voz-tab${element?.id}" data-bs-toggle="tab"
                                    data-bs-target="#voz-tab-pane${element?.id}" type="button" role="tab"
                                    aria-controls="voz-tab-pane${element?.id}" aria-selected="true">Reporte de
                                    voz</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="home-tab${element?.id}" data-bs-toggle="tab"
                                    data-bs-target="#home-tab-pane${element?.id}" type="button" role="tab"
                                    aria-controls="home-tab-pane${element?.id}" aria-selected="true">Histórico de
                                    grabaciones </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="profile-tab${element?.id}" data-bs-toggle="tab"
                                    data-bs-target="#profile-tab-pane${element?.id}" type="button" role="tab"
                                    aria-controls="profile-tab-pane${element?.id}" aria-selected="false">Generar
                                    resúmenes de reportes globales</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="consulta-tab${element?.id}" data-bs-toggle="tab"
                                    data-bs-target="#consulta-tab-pane${element?.id}" type="button" role="tab"
                                    aria-controls="consulta-tab-pane${element?.id}" aria-selected="false">Consulta de
                                    resúmenes globales</button>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="imagen-tab-pane${element?.id}" role="tabpanel"
                                aria-labelledby="imagen-tab${element?.id}" tabindex="0">
                                <div class="row">
                                    <div class="col-md-12" style="text-align: center;">
                                        <p id="errorTxt"></p>
                                    </div>
                                    <div class="col-md-12" style="text-align: center;">
                                        <video id="theVideo" controls autoplay></video>
                                    </div>
                                    <div class="col-md-12">
                                        <button id="btnCapture" type="button" class="btn btn-primary">Tomar
                                            foto</button>
                                    </div>
                                    <div class="col-md-12" id="content_canvas_imagen" style="display:none;">
                                        <ol class="list-group" id="content_canvas_imagen_content">
                                        </ol>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade show" id="voz-tab-pane${element?.id}" role="tabpanel"
                                aria-labelledby="voz-tab${element?.id}" tabindex="0">
                                <div class="row">
                                    <div class="col-md-12" id="controls" style="text-align: center;">
                                        <label for="micSelect">
                                            Micrófonos disponibles
                                            <select name="micSelect" id="micSelect" class="form-control"></select>
                                        </label>
                                        <br>
                                        <br>
                                        <div class="btn-group" id="content-controls">
                                            <button type="button" class="btn btn-danger" id="btn-aceptar">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-record-circle-fill"
                                                    viewBox="0 0 16 16">
                                                    <path
                                                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-8 3a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                                                </svg>
                                                Iniciar grabación
                                            </button>
                                            <button style="display: none;" type="button" class="btn btn-warning"
                                                id="btn-detener">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-stop-btn-fill" viewBox="0 0 16 16">
                                                    <path
                                                        d="M0 12V4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2m6.5-7A1.5 1.5 0 0 0 5 6.5v3A1.5 1.5 0 0 0 6.5 11h3A1.5 1.5 0 0 0 11 9.5v-3A1.5 1.5 0 0 0 9.5 5z" />
                                                </svg>
                                                Detener grabación
                                            </button>
                                        </div>
                                        <br>
                                        <br>
                                        <div class="row">
                                            <div class="col-md-12" style="display: none; text-align: center;"
                                                id="procesando">
                                                <h5>Procesando solicitud, por favor espere...</h5>
                                            </div>
                                            <div class="col-md-12" style="display: none; text-align: center;"
                                                id="feetBack">
                                                <br>
                                                <div id="counter_content">
                                                    <h5 id="counter"></h5>
                                                </div>
                                                <div id="msg">
                                                    <h5>Grabando</h5>
                                                </div>
                                                <canvas width="500" height="120" id="canvas"></canvas>
                                            </div>
                                        </div>
                                        <div class="row" id="results"
                                            style="padding: 14px; text-align: center; justify-content: center;">
                                        </div>
                                    </div>
                                    <div class="col-md-12" id="error" style="display: none;">
                                        <div class="alert alert-warning" role="alert" id="alertaEstatusNotificacion">
                                            El usuario no es valido y es requerido
                                        </div>
                                    </div>
                                    <div class="col-md-12" id="error2" style="display: none;">
                                        <div class="alert alert-warning" role="alert" id="alertaEstatusNotificacion2">
                                            Proporcione permisos de acceso al micrófono y audio despues refresque su
                                            navegador y vuelva a
                                            intentarlo
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade show" id="home-tab-pane${element?.id}" role="tabpanel"
                                aria-labelledby="home-tab${element?.id}" tabindex="0">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div>
                                            <div class="row">
                                                <div class="col-md-12 col-sm-12 " style="text-align: left;">
                                                    <label for="fechaFin" class="form-label">Tipo de reporte</label>
                                                    <select id="tipo_reporte" class="form-select"
                                                        aria-label="Default select example">
                                                        <option selected value="">Seleccione una opción</option>
                                                        <option value="IMAGEN">Imagen</option>
                                                        <option value="VOZ">Voz</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6 col-sm-12 " style="text-align: left;">
                                                    <label for="fechaInicio" class="form-label">Fecha inicio</label>
                                                    <input type="date" id="fechaInicio" class="form-control">
                                                </div>
                                                <div class="col-md-6 col-sm-12 " style="text-align: left;">
                                                    <label for="fechaFin" class="form-label">Fecha fin</label>
                                                    <input type="date" id="fechaFin" class="form-control">
                                                </div>
                                            </div>
                                            <br>
                                            <br>
                                            <div class="col-md-12" id="listaPalabrasClave"></div>
                                            <div class="mb-3" style="text-align: left;">
                                                <label for="keyWords" class="form-label">Palabras clave</label>
                                                <input type="text" class="form-control" id="keyWords"
                                                    aria-describedby="keyWordsHelp">
                                                <div id="keyWordsHelp" class="form-text text-primary">
                                                    <strong>Ingrese la palabra y presione enter para agregar la palabra
                                                        a los parámetros de búsqueda.</strong>
                                                </div>
                                            </div>
                                            <br>
                                            <button type="button" id="btn-buscar"
                                                class="btn btn-primary">Buscar</button>
                                        </div>
                                    </div>
                                    <div class="col-md-12" id="controls" style="text-align: center;">
                                    </div>
                                    <div class="col-md-12" id="export_word_content"
                                        style="display:none; text-align: right;">
                                        <img src="{{ URL::to('/') }}/img/14783314.png?v=1"
                                            onclick="Export2WordContent()" alt="Bootstrap" width="55" height="55">
                                    </div>
                                    <div id="content_toExport"></div>
                                    <div class="col-md-12" style="text-align: center;" id="content"></div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="profile-tab-pane${element?.id}" role="tabpanel"
                                aria-labelledby="profile-tab${element?.id}" tabindex="0">


                                <div class="row">
                                    <div class="col-md-12" style="text-align:left;">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="d-none">
                                            <symbol id="info-fill" viewBox="0 0 16 16">
                                                <path
                                                    d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z">
                                                </path>
                                            </symbol>
                                        </svg>
                                        <svg class="bi flex-shrink-0 me-2" role="img" aria-label="Info:"
                                            style="width:20px; height:65px;">
                                            <use xlink:href="#info-fill" />
                                        </svg>
                                        Seleccionar rango de fechas para mostrar reportes
                                    </div>
                                    <div class="col-md-12" style="text-align: center;">
                                        <div class="row">
                                            <div class="col-md-6 col-sm-12 " style="text-align: left;">
                                                <label for="fechaInicio_periodo" class="form-label">Fecha inicio</label>
                                                <input type="date" id="fechaInicio_periodo" class="form-control">
                                            </div>
                                            <div class="col-md-6 col-sm-12" style="text-align: left;">
                                                <label for="fechaFin_periodo" class="form-label">Fecha fin</label>
                                                <input type="date" id="fechaFin_periodo" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 " style="text-align: center;">
                                        <br>
                                        <button type="button" id="btn-generar-reporte" class="btn btn-primary">Oprime
                                            para continuar</button>
                                    </div>
                                    <div class="col-md-12 col-sm-12 " style="text-align: center;"><br></div>
                                    <hr>
                                    <br>
                                    <div class="col-md-12" id="content_resultado_consulta_periodo"></div>
                                    <div class="col-md-12">
                                        <br>
                                        <button style="display:none;" type="button" id="generador-reporte-boton"
                                            class="btn btn-warning">Generar resumen global</button>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="consulta-tab-pane${element?.id}" role="tabpanel"
                                aria-labelledby="consulta-tab${element?.id}" tabindex="0">
                                <div class="row">
                                    <div class="col-md-12" style="text-align:left;">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="d-none">
                                            <symbol id="info-fill" viewBox="0 0 16 16">
                                                <path
                                                    d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z">
                                                </path>
                                            </symbol>
                                        </svg>
                                        <svg class="bi flex-shrink-0 me-2" role="img" aria-label="Info:"
                                            style="width:20px; height:65px;">
                                            <use xlink:href="#info-fill" />
                                        </svg>
                                        Seleccionar rango de fechas para mostrar los resúmenes disponibles
                                    </div>
                                    <div class="col-md-12" style="text-align: center;">
                                        <div class="row">
                                            <div class="col-md-6 col-sm-12 " style="text-align: left;">
                                                <label for="fechaInicio_periodo_consulta" class="form-label">Fecha
                                                    inicio</label>
                                                <input type="date" id="fechaInicio_periodo_consulta"
                                                    class="form-control">
                                            </div>
                                            <div class="col-md-6 col-sm-12" style="text-align: left;">
                                                <label for="fechaFin_periodo_consulta" class="form-label">Fecha
                                                    fin</label>
                                                <input type="date" id="fechaFin_periodo_consulta" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 " style="text-align: center;">
                                        <br>
                                        <button type="button" id="btn-consultar-reporte"
                                            class="btn btn-primary">Consultar reportes</button>
                                    </div>
                                    <div class="col-md-12 col-sm-12 " style="text-align: center;"><br></div>
                                    <hr>
                                    <br>
                                    <div class="col-md-12" id="export_word" style="display:none; text-align: right;">
                                        <img src="{{ URL::to('/') }}/img/14783314.png?v=1" onclick="Export2Word()"
                                            alt="Bootstrap" width="55" height="55">
                                    </div>
                                    <div class="col-md-12" id="content_resultado_consulta_periodo_consulta"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" tabindex="-1" id="modalLoader">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background-color: transparent; border: none;">
                <div class="modal-body" style="text-align: center;">
                    <div class="spinner-grow spinner-grow-sm" style="width: 3rem; height: 3rem;" role="status">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ URL::to('/') }}/js/imagen.js?v=0.1s"></script>
</body>

</html>