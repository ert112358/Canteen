const ticket = getParam("ticket");
const user = getParam("user");

const itemName   = 0;
const itemPrice  = 1;
const itemAmount = 2;

function setIDs(){
	var rows = drinks.rows.length;
	var qty;
	var prc;
	
	updatePrice();
	
	//DRINKS
	for(var i=1; i<rows; i++){
		drinks.rows[i].cells[itemAmount].onchange = function(){updatePrice();}
		drinks.rows[i].onmouseover                = Function("drinks.rows["+i+"].bgColor='lightgrey'");
		drinks.rows[i].onmouseleave               = Function("drinks.rows["+i+"].bgColor='white'");
	}
	
	
	//FOOD
	rows = food.rows.length;
	
	for(var i=1; i<rows; i++){
		food.rows[i].cells[itemAmount].onchange   = function(){updatePrice();}
		food.rows[i].onmouseover                  = Function("food.rows["+i+"].bgColor='lightgrey'");
		food.rows[i].onmouseleave                 = Function("food.rows["+i+"].bgColor='white'");
	}
	
}

function order(){
	
	if(checkForMistakes() != 0)
		return; 
	
	var orders = {"orders":[]}, val, id;
	var rows = drinks.rows.length;
	
	function createOrder(itemType){
		var rows = itemType.rows.length;
	
		for(var i=1; i<rows; i++){
			val = itemType.rows[i].cells[itemAmount].firstChild.value;
			id  = itemType.rows[i].cells[itemName].id;
			
			if(parseInt(val) != 0)
				orders.orders.push(JSON.parse("{\""+id+"\":\""+val+"\"}")); // {"banana":"5"}
		}
	}
	
	createOrder(drinks);
	createOrder(food);
	
	if(orders.orders.length == 0){
		alert("Carrello vuoto!");
		return;
	}
	
	if(!confirm("Confermare ordine?")) // Dialogo di conferma dell'ordine
		return;
	
	orders = btoa(JSON.stringify(orders)); // Riscrivo l'oggetto JSON come stringa e la codifico in base64
	
	goTo("order.php","ticket="+ticket+"&data="+orders); // order.php?ticket=abcd&data=efgh
}

function updatePrice(){
	var prc = 0, val, price;
	
	function check(itemType){
		var rows = itemType.rows.length;
		
		for(var i=1; i<rows; i++){
			val   = parseInt(itemType.rows[i].cells[itemAmount].firstChild.value); // quantità
			price = parseFloat(itemType.rows[i].cells[itemPrice].innerText);       // prezzo
			
			if(isNaN(val) || isFloat(val)){
				itemType.rows[i].cells[itemAmount].firstChild.value = "0";         // se la quantità è invalida, imposta a zero
				continue;
			}
			
			if(isString(itemType.rows[i].cells[itemAmount].firstChild.value)){
				itemType.rows[i].cells[itemAmount].firstChild.value = "0";         // se la quantità è del tipo: "0abcd", imposta a zero
				continue;
			}
			
			if(val != 0)
				prc += val * price;                                              // quantità * prezzo = prezzo complessivo
		
		}
	}
	
	check(drinks);
	check(food);

	prc = prc.toFixed(2);
	
	document.getElementById("price").innerText  = "Costo complessivo: " + prc + "€";
	document.getElementById("price2").innerText = "Costo complessivo: " + prc + "€";

}

function checkForMistakes(){
	var val, errorCode = 0;
	
	function check(itemType){
		var rows = itemType.rows.length;
		
		for(var i=1; i<rows; i++){
			val = itemType.rows[i].cells[itemAmount].firstChild.value; // quantità
			
			itemType.rows[i].cells[itemAmount].firstChild.style.backgroundColor = "white";
			
			if(isFloat(val)) { // se ha la virgola, non permettere all'utente di ordinare
				errorCode = 1;
				itemType.rows[i].cells[itemAmount].firstChild.style.backgroundColor = "red"; // imposta la quantità errata con sfondo rosso
			}
			
			val = parseInt(val);
			
			if(val > 10 || val < 0) { // se quantità > 10 o quantità < 0, non permettere all'utente di ordinare
				errorCode = 2;
				
				itemType.rows[i].cells[itemAmount].firstChild.style.backgroundColor = "red"; // imposta la quantità errata con sfondo rosso
			}
		}
	}
	
	check(drinks);
	check(food);

	switch(errorCode){
		case 1: alert("Uno o più articoli presentano quantità con numeri decimali o esponenziali."); break;
		case 2: alert("Uno o più articoli presentano quantità errate (maggiore di 10 o valori negativi.)"); break;
	}
	
	return errorCode;
}


function isFloat(n){
	return (parseInt(n) - n != 0);
}
function isString(y){
	return !(parseInt(y).toString().length == y.length);
}