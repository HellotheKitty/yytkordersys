/*******************************
** 表单验证
使用说明:
在使用表单的页面填入如下script
<script language="JavaScript" type="text/javascript" src="../jslib/form.js">
</script>
<script language="JavaScript" type="text/JavaScript">
function checkForm(theForm) { //v2.0
  if (!checkNotNull(theForm.name,"姓名")) return false;
  if (!checkNumber(theForm.age,"年龄")) return false;
  ...
  return true;
}
</script>

注意:checkNumber(),checkLength()等方法并不检查是否为空

<FORM name="form1" acton="..." onSubmit="return checkForm(document.form1)">
...
<input type="submit" name="Submit" value="Submit">
</FORM>

********************************/

// 是否为空，非空返回真，不非为空返回假
function isBlank(str) {
	var blankFlag = true;
	if (str.length == 0) return true;
	for (var i = 0; i < str.length; i++) {
		if ((str.charAt(i) != "") && (str.charAt(i) != " ")) {
			blankFlag = false;
			break;
		}
	}
	return blankFlag;
}

function checkNotNull(theField, fieldName) {
	
	if(isBlank(theField.value)){
		alert(fieldName + "不可为空！");
		theField.focus();
		return false;
	}

	return true;
}

// 是否为数字
function checkNumber(theField, fieldName) {
 	var pattern = /^([0-9]|(-[0-9]))[0-9]*((\.[0-9]+)|([0-9]*))$/;

 	if(theField.value == "") return true;
 	if (!pattern.test(theField.value)) {
 		alert(fieldName + "必须为合法数字");
 		theField.focus();
 		theField.select();
 		return false;
 	}

	return true;
}

// 是否为指定范围数字
function checkNumberRange(theField, fieldName, min, max) {
	if(theField.value == "") return true;
	if (!checkNumber(theField, fieldName)) return false;

	if ((min != "") && (theField.value < min)) {
		alert(fieldName + "不可小于" + min + "！");
		theField.focus();
		theField.select();
		return false;
	}

	if ((max != "") && (theField.value > max)) {
		alert(fieldName + "不可超过" + max + "！");
		theField.focus();
		theField.select();
		return false;
	}

	return true;
}

// 是否为整数
function checkInteger(theField, fieldName) {
	var pattern = /^(\d|(-\d))\d*$/;

	if(theField.value == "") return true;
	if (!pattern.test(theField.value)) {
		alert(fieldName + "必须为整数！");
		theField.focus();
		theField.select();
		return false;
	}

	return true;
}

// 是否为指定范围内整数
function checkIntegerRange(theField, fieldName, min, max) {
	if(theField.value == "") return true;
	if (!checkInteger(theField, fieldName)) return false;

	if ((min != "") && (theField.value < min)) {
		alert(fieldName + "不可小于" + min + "！");
		theField.focus();
		theField.select();
		return false;
	}

	if ((max != "") && (theField.value > max)) {
		alert(fieldName + "不可超过" + max + "！");
		theField.focus();
		theField.select();
		return false;
	}

	return true;
}

// 是否为正数
function checkPositiveNumber(theField, fieldName) {
	if(theField.value == "") return true;
	if (theField.value.charAt(0) == '-') {
		alert(fieldName + "必须为正数！");
		theField.focus();
		return false;
	}

	return true;
}

// 限制字串最大长度
function checkLength(theField, fieldName, maxLength) {
	if(theField.value == "") return true;
	if (theField.value.length > maxLength) {
		alert(fieldName + "的字数最多为" + maxLength + "字！");
		theField.select();
		theField.focus();
		return false;
	}

	return true;
}

// 限制字串长度,注意参数顺序
function checkLength2(theField, fieldName, maxLength, minLength) {
	if(theField.value == "") return true;
	if (theField.value.length > maxLength) {
		alert(fieldName + "的字数最多为" + maxLength + "字！");
		theField.focus();
		return false;
	}

	if ((minLength != "") && (theField.value.length < minLength)) {
		alert(fieldName + "的字数最少为" + minLength + "字！");
		theField.focus();
		return false;
	}

	return true;
}

// 所输入字符串是否均为合法字符
// charBag中为包含所有合法字符的字符串
function checkStrLegal(theField, fieldName, charBag) {
	if(theField.value == "") return true;
   	for (var i = 0; i < theField.value.length; i++) {
        var c = theField.value.charAt(i);
        if (charBag.indexOf(c) == -1) {
     		alert(fieldName + "含有非法字符(" + c + ")！");
     		theField.focus();
     		return false;
        }
    }

    return true;
}

// 所输入字符串是否均为合法字符
// charBag中为包含非法字符的字符串
function checkStrLegal2(theField, fieldName, charBag) {
	if(theField.value == "") return true;
   	for (var i = 0; i < theField.value.length; i++) {
        var c = theField.value.charAt(i);
        if (charBag.indexOf(c) > -1) {
     		alert(fieldName + "含有非法字符(" + c +")！");
     		theField.focus();
     		return false;
        }
    }

    return true;
}

// 电子邮件验证
function checkEmail(theField) {
	var pattern = /^.+@.+\..+$/;

	if(theField.value == "") return true;
	if (!pattern.test(theField.value)) {
		alert("请输入合法的电子邮件地址");
		theField.focus();
		theField.select();
		return false;
	}

	return true;
}

// 是否为只读域（如file，text等域只接受右边按钮选择传回的结果）

function checkReadField(obj) {
	var obj=eval(obj);
	obj.blur();	
	alert("请点击后面的图标进行选择!");
	
}
