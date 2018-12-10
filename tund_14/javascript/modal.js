let modal;
let modalImg;
let captionText;
let span;
let photoDir = "../picuploads/";
let photoid;

window.onload = function(){
	//console.log("Hakkas peale");
	modal = document.getElementById("myModal");
	modalImg = document.getElementById("modalImg");
	captionText = document.getElementById("caption");
	span = document.getElementsByClassName("close")[0];
	let allThumbs = document.getElementById("gallery").getElementsByTagName("img");
	let thumbCount = allThumbs.length;
	for(let i=0;i < thumbCount; i ++) {
		allThumbs[i].addEventListener("click", openModal);
	}
	span.addEventListener("click", closeModal);
	modalImg.addEventListener("click", closeModal);
	document.getElementById("storeRating").addEventListener("click", storeRating);
}

function openModal(e) {
	modal.style.display = "block";
	modalImg.src = photoDir + e.target.dataset.fn;
	photoid = e.target.dataset.id;
	loadscore();
	captionText.innerHTML = "<p>" + e.target.alt + "</p>";
}

function closeModal() {
	modal.style.display = "none";
}
function loadscore() {
	let xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if(this.readyState == 4 && this.status == 200) {
			document.getElementById("avgRating").innerHTML = this.responseText;
		}
	}
	xmlhttp.open("GET", "loadscore.php?id=" + photoid, true);
	xmlhttp.send();
	//AJAX lõppes
}
function storeRating() {
	let rating = 0;
	for(let i = 1;i < 6;i ++) {
		if(document.getElementById("rate" + i).checked) {
			rating = document.getElementById("rate" + i).value;
		}
	}
	if(rating > 0) {
		//AJAX, loome ühenduse,päringu serverile
		let xmlhttp = new XMLHttpRequest();
		//sõlvutavlt päringu tulemusest tegutsen
		xmlhttp.onreadystatechange = function() {
			if(this.readyState == 4 && this.status == 200) {
				// kui päring õnnestus ja tuli vastus, paneme keskmise hinde nähtavale
				document.getElementById("avgRating").innerHTML = this.responseText;
				document.getElementById("score" + photoid).innerHTML = "Hinne: " + this.responseText;
			}
		}
		xmlhttp.open("GET", "savephotorating.php?id=" + photoid + "&rating=" + rating, true);
		xmlhttp.send();
		//AJAX lõppes
	}
}


