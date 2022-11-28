let photoDir = "photo_upload_normal/";

window.onload = function(){
	//kõik pisipildid paneme dialoogiakent avama
	let allThumbs = document.querySelector(".gallery").querySelectorAll(".thumbs");
	for(let i = 0; i < allThumbs.length; i ++){
		allThumbs[i].addEventListener("click", openModal);
	}
	document.querySelector("#modalclose").addEventListener("click", closeModal);
	document.querySelector("#modalimage").addEventListener("click", closeModal);
}

function openModal(e){
	document.querySelector("#modalimage").src = photoDir + e.target.dataset.filename;
	document.querySelector("#modalcaption").innerHTML = e.target.alt;
	document.querySelector("#modal").showModal();
}

function closeModal(){
	document.querySelector("#modal").close();
}