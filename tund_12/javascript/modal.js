let modal;
let modalImg;
let captionText;
let span;
let photoDir = "../picuploads/";

window.onload = function(){
	console.log("Hakkas peale");
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
}

function openModal(e) {
	modal.style.display = "block";
	modalImg.src = photoDir + e.target.dataset.fn;
	captionText.innerHTML = "<p>" + e.target.alt + "</p>";
}

function closeModal() {
	modal.style.display = "none";
}