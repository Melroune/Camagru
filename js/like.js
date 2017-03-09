// j'ecoute le bouton like
//

(function() {
/*
*
*like
*
*/
function likeFunc(e){
	var target = e.target || e.srcElement;
	var oReq = getXMLHttpRequest();
	var postData = 'imgId=' + target.dataset.id +'&userId=' + target.dataset.userid;
	oReq.open("POST", "src/bac/like.php", true);
    oReq.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    oReq.send(postData);
    oReq.onreadystatechange = function(e) {
        if (oReq.readyState == 4 && oReq.status == 200) {
           	var response = JSON.parse(oReq.responseText);
              if(response.errorVote)//deja voter
              {
              		eCom = document.getElementsByClassName('comLike');
        	    	for(var i = 0;i < eCom.length; i++){
             			if(eCom[i].dataset.id == response.IDimg)
              			{
              				var rep = 'deja voter ('+ response.voteNb +')' ;
              				iLike = document.getElementsByClassName('like');
              				iLike[i].setAttribute('class', 'like');
              				iLike[i].setAttribute('data-id', response.IDimg);
              				iLike[i].innerHTML = rep;
              				eCom[i].appendChild(iLike[i]);
              			}
              		}
              }
              else{//deja voter

    	          eCom = document.getElementsByClassName('comLike');
        	      for(var i = 0;i < eCom.length; i++){
             	 		if(eCom[i].dataset.id == response.IDimg)
              			{
              				var rep = 'Like ('+ response.voteNb +')';
              				iLike = document.getElementsByClassName('like');
              				iLike[i].setAttribute('class', 'like');
              				iLike[i].setAttribute('data-id', response.IDimg);
              				iLike[i].innerHTML = rep;
              				eCom[i].appendChild(iLike[i]);
              			}
              		}
              }
          	}
		}
	}
var likeLoveList = document.getElementsByClassName('like');

for(var i = 0; i < likeLoveList.length; i++){
	var offsetlike = likeLoveList[i];
	likeLoveList[i].addEventListener('click', likeFunc, false);
}
})();
