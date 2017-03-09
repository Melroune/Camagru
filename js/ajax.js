
function getXMLHttpRequest() {
    var xhr = null;
    if (window.XMLHttpRequest)
    {
        xhr = new XMLHttpRequest();

    }
    else {
        alert("Votre navigateur ne supporte pas l'objet XMLHTTPRequest...");
        return;
    }
    return xhr;
}

