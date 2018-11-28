function isEmpty(obj){
    if (obj == 0 || obj == '' || obj == undefined || obj == false || obj.length == 0) {
        return true;
    }
    return false;
}
/**
 * @param url           目标url
 * @param arg           需要替换的参数名称
 * @param arg_val       替换后的参数的值091811080000
 * 09181108000020
 *
 * @returns url         参数替换后的url
 */
function changeURLArg(url,arg,arg_val){
    var pattern=arg+'=([^&]*)';
    var replaceText=arg+'='+arg_val;
    if(url.match(pattern)){
        var tmp='/('+ arg+'=)([^&]*)/gi';
        tmp=url.replace(eval(tmp),replaceText);
        return tmp;
    }else{
        if(url.match('[\?]')){
            return url+'&'+replaceText;
        }else{
            return url+'?'+replaceText;
        }
    }
    return url+'\n'+arg+'\n'+arg_val;
}

/**
 * 根据时间戳返回2018-11-30
 * @param str
 * @returns {string}
 */
function dateFormatToDay(str){
    let oDate = new Date(Number(str + '000')),
        oYear = oDate.getFullYear(),
        oMonth = oDate.getMonth() + 1,
        oDay = oDate.getDate(),
        oTime = oYear +'-'+ oMonth +'-'+ oDay;//最后拼接时间
    return oTime;
}

/**
 * 根据时间戳返回2018-11-13 15:26:29
 * @param str
 * @returns {string}
 */
function dateFormatToTime(str){
    let oDate = new Date(Number(str + '000')),
        oYear = oDate.getFullYear(),
        oMonth = oDate.getMonth() + 1,
        oDay = oDate.getDate(),
        oHour = oDate.getHours(),
        oMin = oDate.getMinutes(),
        oSen = oDate.getSeconds(),
        oTime = oYear + '-' + oMonth + '-' + oDay + ' '+ oHour + ':' + oMin +':'+ oSen;//最后拼接时间
    return oTime;
}

Array.prototype.remove = function(val) {
    let index = this.indexOf(val);
    if (index > -1) {
        this.splice(index, 1);
    }
};