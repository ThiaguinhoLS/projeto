function load() {
	if (window.location.href !== "http://www.vovoprojeto.com/") {
		console.log(window.location.href);
		var element = document.getElementsByClassName("header");
		element[0].style.borderBottom = "10px solid #2A544B";
	}
};

document.addEventListener("DOMContentLoaded", load, false);