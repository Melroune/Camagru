
(function() {

/*
 *[Commentaire]
 *
 */

 var btnpostcoms = document.querySelector('#btnpostcoms');

function supRetLigne(strng) {
    return(strng.replace(/\n/g," "));
}

function commentaire(){
    var comValue = postcoms.value;
    var idimgValue = idimg.value;
    comValue = supRetLigne(comValue);
    String(comValue); // je convertit l'objet postcoms.value en string
 	var xhrRecupCom = getXMLHttpRequest();
 	xhrRecupCom.open("POST", "comtraitement.php", true);
    xhrRecupCom.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhrRecupCom.send('idimg=' + idimgValue +'&postcoms=' + comValue );

    xhrRecupCom.onreadystatechange = function() {
    if (xhrRecupCom.readyState == 4 && xhrRecupCom.status == 200)
    {
    	var jsonInfo = JSON.parse(xhrRecupCom.responseText);
    	// ajouterla la div en css !
    	// pour afficher le commentaire voir cam.js

    	eCom = document.getElementById('newCom');

        eBlocCom = document.createElement('div'); //creation du bloc et de l'affichage
        eBlocCom.setAttribute('class', 'blocCom');

        eBlocCom.textContent = jsonInfo.postcoms; //insert virtuelment le text a l'affichage

        eCom.appendChild(eBlocCom); //ajoute a l'affichage

        document.getElementById("postcoms").value = ''; // vide le chant de la text area

        if(jsonInfo.mail == "ok")
        {

        }
    }
 }
}

btnpostcoms.addEventListener('click', function(ev){
      commentaire();
    ev.preventDefault();
  }, false);
})();
// like
//
