function clearCookie(name, domain, path) {
	var domain = domain || document.domain;
	var path = path || "/";
	document.cookie = name + "=; expires=Thu, 01 Jan 1970 00:00:00 GMT; domain=" + domain + "; path=" + path;
};

function deleteAllCookies() {

	document.cookie = 'eucookielaw=false;expires=Session;path=/';
	// ATTENZIONE (n fase di testing cambiare ".com" per "localhost"; e viceversa)
	clearCookie('_ga', '.info-alberghi.com', '/');
	clearCookie('_gid', '.info-alberghi.com', '/');
	clearCookie('_gat_UA-2007570-1', '.info-alberghi.com', '/');

}; 

// tasto per attivare la funzione deleteAllCookie
var cl_delete = document.getElementById("cl_delete");
if(cl_delete  != null ){
	cl_delete.addEventListener("click", deleteAllCookies, false); 
}

// Tasto per riaccettare tutti i cookie
var cl_accept = document.getElementById("cl_accept");
if(cl_accept  != null ) {
	cl_accept.addEventListener("click", function(){
		var now = new Date();
		var expireTime = now.getTime() + 2592000000; // 30 giorni
		now.setTime(expireTime);
		document.cookie = 'eucookielaw=true;expires=' + now.toGMTString() + ';path=/';
	}, false);
}