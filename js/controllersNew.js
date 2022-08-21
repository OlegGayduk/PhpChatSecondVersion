"use strict";

load = 0;


window.onload = function() {

    $.getJSON('//freegeoip.net/json/?callback=?', function(data) {
      //alert(JSON.stringify(data, null, 2));
    });

    $('.nano').nanoScroller();

    xhr({type:'POST',url:urls.showDialogsUrl + window.location.search,sendContent:0,elem:getElems.getClassElem('nano-content',0)});

    if(window.location.search != "") {
        if(window.load == true) {

            window.load = false;

            xhr({type:'POST',url:urls.showMsgsHistoryUrl + window.location.search,sendContent:0,elem:getElems.getClassElem('nano-content',1)});

            //checkNewMsgs = setInterval(function() {
            //    xhr({type:'POST',url:urls.checkNewMsgsUrl + window.location.search,sendContent:0,elem:0});
            //},2000);
        }
    } else {
        $(getElems.getClassElem('nano-content',1)).append("<span class='msgs-col-error'>Выберите диалог для начала общения</span>");
    }
};