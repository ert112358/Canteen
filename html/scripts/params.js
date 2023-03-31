// Questo dovrebbe funzionare con tutti i browser!

function getParam(str){
	param = "";
	arr = "";
	len = "";
	tmp = "";
	
	param = location.search.slice(1);
	arr = param.split("&");
	len = arr.length;
		
	for(var i=0; i<len; i++){
		tmp = arr[i].split("=");
		arr[i] = Array(2);
		arr[i][0] = tmp[0];
		arr[i][1] = tmp.slice(1).join("=");
	}
	
	for(var i=0; i<len; i++)
		if(arr[i][0] == str) return arr[i][1];
	return "";
	
}