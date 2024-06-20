const urlApp_imagen = "https://diracapm.qubi.com.mx/";
const urlApp_file_imagen = "https://diracapm.qubi.com.mx/";
//const urlApp_imagen = 'http://localhost/APM/public/';
//const urlApp_file_imagen = 'http://localhost/APM/';
let imagenesEnviar = [];

var myModal = new bootstrap.Modal(document.getElementById('modalLoader'), {
    backdrop: "static", keyboard: false
});

$(document).ready(async () => {
    var videoWidth = 320;
    var videoHeight = 240;
    var videoTag = document.getElementById('theVideo');
    var btnCapture = document.getElementById("btnCapture");
    videoTag.setAttribute('autoplay', '');
    videoTag.setAttribute('muted', '');
    videoTag.setAttribute('playsinline', '')

    videoTag.setAttribute('width', videoWidth);
    videoTag.setAttribute('height', videoHeight);
    navigator.mediaDevices.getUserMedia({
        audio: false,
        video: {
            width: videoWidth,
            height: videoHeight,
            facingMode: { exact: "environment" },
        }
    }).then(stream => {
        videoTag.srcObject = stream;
    }).catch(e => {
        document.getElementById('errorTxt').innerHTML = 'ERROR: ' + e.toString();
    });
    btnCapture.addEventListener("click", () => {
        if (imagenesEnviar?.length >= 9) {
            alert('Ha llegado al maximo de imagenes, envie las grabaciones en lista o eliminelas para poder agregar mas imagenes a la lista');
            return;
        }
        let html = '';
        html += `<li class="list-group-item align-items-start" id="listaCanvas_${imagenesEnviar?.length}_content"> 
            <div class="row">
                <div class="col-md-12 col-sm-12" style="text-align:center;">
                        <canvas id="CursorLayer_${imagenesEnviar?.length}" width="320" height="240"></canvas>
                </div>
                <div class="col-md-12 col-sm-12" style="text-align:center;">
                    <button class="btn btn-success" type="button"  id="enviar_foto_${imagenesEnviar?.length}" onclick="return enviarFoto(${imagenesEnviar?.length})" aria-selected="false">Enviar foto</button>
                    <button class="btn btn-danger" type="button"  id="descartar_foto_${imagenesEnviar?.length}" onclick="return descartarFoto(${imagenesEnviar?.length})" aria-selected="false">Descartar foto</button>
                </div>
            </div>
        </li>`;
        $("#content_canvas_imagen_content").append(html);
        var canvasTag = document.getElementById(`CursorLayer_${imagenesEnviar?.length}`);
        const canvasContext_ = canvasTag.getContext('2d');
        canvasContext_.drawImage(videoTag, 0, 0, videoWidth, videoHeight);
        var dataURL = canvasTag.toDataURL();
        var blob = dataURLtoBlob(dataURL);
        imagenesEnviar.push({ id: imagenesEnviar?.length, imagen: blob });
        $('#content_canvas_imagen').show();
    });

    function dataURLtoBlob(dataURL) {
        var arr = dataURL.split(','),
            mime = arr[0].match(/:(.*?);/)[1],
            bstr = atob(arr[1]),
            n = bstr.length,
            u8arr = new Uint8Array(n);
        while (n--) {
            u8arr[n] = bstr.charCodeAt(n);
        }
        return new Blob([u8arr], {
            type: mime
        });
    }
});

const dataURLtoBlob = (dataURL) => {
    var arr = dataURL.split(','),
        mime = arr[0].match(/:(.*?);/)[1],
        bstr = atob(arr[1]),
        n = bstr.length,
        u8arr = new Uint8Array(n);
    while (n--) {
        u8arr[n] = bstr.charCodeAt(n);
    }
    return new Blob([u8arr], {
        type: mime
    });
}

const enviarFoto = (id) => {
    myModal.toggle()
    const canvasSelected = document.getElementById('CursorLayer_' + id);
    var dataURL = canvasSelected.toDataURL();
    var blob = dataURLtoBlob(dataURL);
    var data = new FormData();
    data.append("capturedImage", blob, "capturedImage.png");
    var reader = new FileReader();
    reader.readAsDataURL(blob);
    reader.onloadend = function () {
        var base64data = reader.result;
        $.ajax({
            type: "POST",
            url: urlApp_imagen + "api/analizaImagen",
            data: { image: base64data, user },
            Headers: {
                Accept: "application/json",
            },
            success: function (response) {
                alert('Exito al guardar');
                $('#listaCanvas_' + id + '_content').remove();
                imagenesEnviar = imagenesEnviar.filter(e => e?.id !== id);
                myModal.hide();
            },
            error: function (a, b, c) {
                myModal.hide();
                alert(a?.responseJSON?.message || "error al eliminar el registro");
            },
        });
    }
}

const descartarFoto = (id) => {
    if (window.confirm('¿Desea eliminar esta captura de imagen de la lista?')) {
        $('#listaCanvas_' + id + '_content').remove();
        imagenesEnviar = imagenesEnviar.filter(e => e?.id !== id);
    }
}