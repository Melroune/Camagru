(function() {
    /*
     *[Images]
     *
     */
    var viTest = 0;
    var selectVignnet;
    var width = canvasOne.width = 800;
    var height = canvasOne.height = 600;
    window.onload = function() {
        var c = document.getElementById("canvasOne");
        var ctx = c.getContext("2d");
        //recupere les liste des image par clas class
        var listVignette = document.getElementsByClassName("vignette");
        for (var i = 0; i < listVignette.length; i++) {
            listVignette[i].addEventListener('click', vignette);
        }
        //ecrire et effacer la vignette
        function vignette(e) {
            ctx.clearRect(0, 0, width, height); //efface la vignette
            ctx.drawImage(e.target, 250, 120); //ecrit la vignet cliquer
            selectVignnet = e.target;
            selectVignnet = selectVignnet.getAttribute('src');
            viTest = 1;
        }

        function clearReset(e) {
            ctx.clearRect(0, 0, width, height);
            video = document.querySelector('#video');
            viTest = 0;
        }
        //on ecoute le bouton reset si il est presser on lance la fonction clearReste
        clear.addEventListener('click', clearReset);
    }

    /*
     * [CAPTURE]
     * Partie du code qui permet d'afficher la webcam.
     */
    var video = document.querySelector('#video');
    var canvas = document.querySelector('#canvas');
    var startbutton = document.querySelector('#startbutton');
    var photo = document.querySelector('#photo');


    var ctx1 = canvas.getContext('2d');
    var w = canvas.width = video.width = 800;
    var h = canvas.height = video.height = 600;

    var options = {
        audio: false,
        video: true,
    };

    var handleError = function(err) {
        video.check = 0;
    };

    var handleStream = function(fluxVideo) {
            if (navigator.mozGetUserMedia) {
                video.mozSrcObject = fluxVideo;
                video.play();
                var loop = function() {
                    ctx1.drawImage(video, 0, 0, w, h);
                    window.requestAnimationFrame(loop);
                }
                loop();
            } else {
                var toto = window.URL || window.webkitURL;
                video.src = toto.createObjectURL(fluxVideo);
                video.play();
                var loop = function() {
                    ctx1.drawImage(video, 0, 0, w, h);
                    window.requestAnimationFrame(loop);
                }
                loop();
            }
        }
        // handleStream();

    if (!navigator.vendor.includes('Google')) {
        navigator.mediaDevices.getUserMedia(options).then(handleStream).catch(handleError);
    } else {
        navigator.getMedia = (navigator.getUserMedia ||
            navigator.webkitGetUserMedia ||
            navigator.mozGetUserMedia ||
            navigator.msGetUserMedia);
        navigator.getMedia(options, handleStream, handleError);
    }
    /*
     *[prise de photo]
     *
     */

    function takepicture() {
        canvas.width = w;
        canvas.height = h;
        canvas.getContext('2d').drawImage(video, 0, 0, w, h);
        var data = canvas.toDataURL('image/png');
        //envois de l'image en ajax au traitement php

        setTimeout(function() {
            var xhrRecupImg = getXMLHttpRequest();

            xhrRecupImg.open("POST", "src/bac/montage1.php", true);
            xhrRecupImg.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhrRecupImg.send('imgData=' + data + '&selectVignnet=' + selectVignnet + '&width=' + w + '&height=' + h);
            //on atend une reponse a la send
            xhrRecupImg.onreadystatechange = function() {
                if (xhrRecupImg.readyState === 4) // si le etat de la reponse et a 4 tout c'est bien derouler
                {
                    // on set la source de la balise image ayent l'id minipic

                    jsonInfo = JSON.parse(xhrRecupImg.responseText);
                    /*
                     *
                     * ajouterla la div en css !   previewContain
                     *\
                     */
                    eCont = document.createElement('div');
                    eCont.setAttribute('class', "previewContain");

                    eSup = document.createElement('span');
                    eSup.setAttribute('data-id', jsonInfo.id);
                    eSup.setAttribute('class', "delPic");
                    eSup.setAttribute('onclick', "this.parentNode.remove();");
                    eSup.textContent = "X";
                    ele = document.createElement('img');
                    ele.setAttribute('data-id', jsonInfo.id);
                    ele.setAttribute('class', 'miniPic');
                    ele.setAttribute('src', jsonInfo.path);
                    //on insert a l'element css rightScren la photo en premiere position
                    var listphoto = document.querySelector("#rightScren");
                    listphoto.insertBefore(eCont, listphoto.childNodes[0]);
                    eCont.appendChild(eSup);
                    eCont.appendChild(ele);
                    eSup.addEventListener('click', delPicUser, false);
                }
            }
        }, 100);
    }


    //supresion d'image
    function delPicUser(e) {
        var target = e.target || e.srcElement;
        var oReq = getXMLHttpRequest();
        var postData = "idPost=" + target.dataset.id;
        oReq.open("POST", "src/bac/delPic.php", true);
        oReq.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        oReq.send(postData);
        oReq.onreadystatechange = function(e) {
            if (oReq.readyState == 4 && oReq.status == 200) {
                var response = JSON.parse(oReq.responseText);
                if (response.response == "ok") {
                    var demo = document.getElementsByClassName('miniPic');

                    var a = document.querySelector('#rightScren');

                } else {
                    //cache popIn et affiche message d'erreur
                    alert("un problem et survenu");
                }
            }
        }
    }
    var delPicList = document.getElementsByClassName('delPic');

    for (var i = 0; i < delPicList.length; i++) {
        delPicList[i].addEventListener('click', delPicUser, false);
    }
    //*****************************************

    startbutton.addEventListener('click', function(ev) {
        if (viTest == 1) {
            takepicture();
            ev.preventDefault();
        }
    }, false);
    // recuperation de l'image upload
    var uploadPictureIpt = document.querySelector('input[type=file]');
    var uploadPictureBtn = document.querySelector('#uploadPicture');
    var uploadPicturePvw = document.querySelector('#uploadPicturePvw');

    function getFile(event) {
        uploadPictureIpt.value = "";
        uploadPictureIpt.click();
        uploadPicturePvw.width + "px";
        uploadPicturePvw.height + "px";
        uploadPicturePvw.value = event.target.value;
        uploadPicturePvw.style.display = "flex";

    }
    uploadPictureBtn.addEventListener('click', getFile);
    uploadPictureIpt.addEventListener('change', previewFile);

    function previewFile() {
    if (uploadPictureIpt.value == "")
        return ;
    var file = uploadPictureIpt.files[0];
    var reader = new FileReader();
    if (file) {
        var extension = file.name.substring(file.name.lastIndexOf('.') + 1).toLowerCase();
        if (extension !== "png" && extension !== "jpg" && extension !== "jpeg") {
            alert("Fichier invalide. Fichiers accepter : '.png', '.jpg', '.jpeg'");
            return ;
        }
    }

    reader.onloadend = function () {
        img = new Image();
        img.src = reader.result;
        var tmp = video;
        video = img;

        img.onload = function() {
            ctx1.clearRect(0, 0, width, height);
            ctx1.drawImage(img, 0, 0, 800, 600);
        };
        img.onerror = function(event) {
            alert('Please Upload a Valid File');
            event.target.value = '';
            video = tmp;
            ctx1.clearRect(0, 0, width, height);
            ctx1.drawImage(video, 0, 0, 800, 600);
            return;
        };
    }
    if (file) {
        reader.readAsDataURL(file);
    } else {
       video = tmp;
      uploadPicturePvw.src = "";
    }
}
})();
