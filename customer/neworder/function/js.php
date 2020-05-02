<SCRIPT language=JavaScript src="../../js/form.js"></SCRIPT>
<SCRIPT language=JavaScript>
function checkForm(){
	var tmpFrm = document.forms[0];
    var charBag = "-0123456789.";
	if (!checkNotNull(form1.mc, "")) return false;
	if (!checkNotNull(form1.bm, "")) return false;
	if (!checkNotNull(form1.je, "")) return false;
	if (!checkStrLegal(form1.je, "", charBag)) return false;
	return true; }
function slc(obj) {
	var js=0,cs=1,xdj;
	var dj="<? echo $dj?>";
	var zz=form1.zz.value;
	if (zz.indexOf("-")>0) js=zz.substr(zz.indexOf("-")+1);
	var chicun=form1.chicun.value;
	if (chicun.indexOf("-")>0) cs=chicun.substr(chicun.indexOf("-")+1);
	if (dj.indexOf("-")>0) {
		var i;
		var aa=dj.split(";");
		if (parseFloat(aa[aa.length-1].substr(0,aa[aa.length-1].indexOf("-")))<=obj.value)
			xdj=aa[aa.length-1].substr(aa[aa.length-1].indexOf("-")+1);
		else {
		for (i = 0; i < aa.length-1; i++) {
			if (parseFloat(aa[i].substr(0,aa[i].indexOf("-")))<=obj.value && parseFloat(aa[i+1].substr(0,aa[i+1].indexOf("-")))>obj.value) xdj=aa[i].substr(aa[i].indexOf("-")+1);
		}
		}
		xdj=(parseFloat(xdj)+parseFloat(js))*parseFloat(cs)
		document.getElementById("dj").innerHTML=xdj;
		form1.zj.value=formatCurrency(xdj*obj.value);
	} else {
		xdj=(parseFloat(dj)+parseFloat(js))*parseFloat(cs)
		document.getElementById("dj").innerHTML=xdj;
		form1.zj.value=formatCurrency(xdj*obj.value);
	}
}
function formatCurrency(num) {
    if(isNaN(num))
    num = "0";
    num = num.toString().replace(/\$|\,/g,'');
    sign = (num == (num = Math.abs(num)));
    num = Math.floor(num*100+0.50000000001);
    cents = num%100;
    num = Math.floor(num/100).toString();
    if(cents<10)
    cents = "0" + cents;
    for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
    num = num.substring(0,num.length-(4*i+3))+','+
    num.substring(num.length-(4*i+3));
    return (((sign)?'':'-') + num + '.' + cents);
}

window.addEventListener('message',function(e){
            var data=e.data;
			if (data.substr(0,1)=="3")
            	if (data.substr(1)=="del") document.getElementById('file1').value=""; else document.getElementById('file1').value+=data.substr(1)+";";
			else
				if (data.substr(1)=="del") document.getElementById('file2').value=""; else document.getElementById('file2').value+=data.substr(1)+";";
        },false);

var hzx = new Array();
hzx[1] = "";
hzx[2] = "";
function set(n,v) {
	id = "hzx"+n;
	obj = document.getElementById(id);
	if(v == "单面"){
		hzx[n] = obj.value;
		obj.value="";
		obj.disabled=true;
	}else{
		obj.disabled=false;
		obj.value = hzx[n];
	}
}

</SCRIPT>