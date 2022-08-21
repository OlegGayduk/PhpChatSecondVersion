var load = {

    ready: function() {

        //window.history.pushState("", "", location.search);

        window.onpopstate = function(event) {

            alert(event.state);

            if(location.search == 0) {
                document.getElementsByClassName('nano-content')[1].innerHTML = "<span class='msgs-col-error'>Choose dialog to begin chat</span>";
            } else {

                //graphics.colorChange({id:this.sell,dialogColor:'#fff'});

                var sell = getUrlParam(location.search,'sell');

                load.showMsgsHistory(sell);
            }

            return;
        };

        /*window.addEventListener('popstate', function(e) {
            
            //alert(e.state);

            //alert(JSON.stringify(e.state));


            if(location.search == 0) {
                document.getElementsByClassName('nano-content')[1].innerHTML = "<span class='msgs-col-error'>Choose dialog to begin chat</span>";
            } else {

                graphics.colorChange({id:e.state,dialogColor:'#fff'});

                //alert(sell);

                var sell = getUrlParam(location.search,'sell');

                load.showMsgsHistory(sell);
            }

            return;
        });*/
        
        getDialogs({type:'GET',url:'../php/showDialogs.php',elem:document.getElementsByClassName('nano-content')[0]});
    
        if(location.search == 0) {
            document.getElementsByClassName('nano-content')[1].innerHTML = "<span class='msgs-col-error'>Choose dialog to begin chat</span>";
        } else {
            var sell = getUrlParam(location.search,'sell');

            this.sell = sell;

            msgsActs.showMsgsHistory(sell);
        }
        
        return;
    },

    showMsgsHistory: function(id) {

        this.sell = id;

        if(location.search != 0) {
            //this.sell = getUrlParam(location.search, 'sell');
            graphics.colorChange({id:getUrlParam(location.search, 'sell'),dialogColor:'#fff'});
        }

        window.history.pushState(id, "", '?sell='+ id);

        graphics.colorChange({id:id,dialogColor:'rgb(70,90,124)'});

        getMsgs({type:'GET',url:'../php/showMsgs.php'+ location.search,location:id,elem:document.getElementsByClassName('nano-content')[1]});
    },
};

var graphics = {

    dialogsHtmlAppend: function(action,i,elem) {

        var str = "";

        (dialogs[i].status == 1) ? str = "id='dialog-last-msg'" : str = "id='dialog-last-msg' class='msg-unread'";

        if(action == "append") {
            $(elem).append("<div class='dialog-wrap' id=" + dialogs[i].id + " onclick='load.showMsgsHistory(" + dialogs[i].id + ")'>" +
            "<img class='dialog-ava-wrap' src='" + dialogs[i].ava + "'/>" +
            "<span id='dialog-seller'>" + dialogs[i].alias + "</span>" +
            "<span id='dialog-date'>" + dialogs[i].date_day + "</span>" +
            "<span " + str + ">" + dialogs[i].text + "</span>" +
            "</div>");
        } else if(action == "prepend") {
            $(elem).prepend("<div class='dialog-wrap' id=" + dialogs[i].id + " onclick='load.showMsgsHistory(" + dialogs[i].id + ")'>" +
            "<img class='dialog-ava-wrap' src='" + dialogs[i].ava + "'/>" +
            "<span id='dialog-seller'>" + dialogs[i].alias + "</span>" +
            "<span id='dialog-date'>" + dialogs[i].date_day + "</span>" +
            "<span " + str + ">" + dialogs[i].text + "</span>" +
            "</div>");
        } else {
            elem.innerHTML = "<div class='dialog-wrap' id=" + dialogs[i].id + " onclick='load.showMsgsHistory(" + dialogs[i].id + ")'>" + "<img class='dialog-ava-wrap' src='" + dialogs[i].ava + "'/>" + "<span id='dialog-seller'>" + action.alias + "</span>" + "<span id='dialog-date'>" + dialogs[i].date_day + "</span>" + "<span " + str + ">" + dialogs[i].text + "</span>" + "</div>";
        }

        return;
    },

    colorChange: function(params) {

        var dialog = document.getElementById(params.id);
        
        if(dialog != null) {
            var spans = dialog.getElementsByTagName('span');
            
            switch(params.dialogColor) {
                case 'rgb(70,90,124)':
                    dialog.style.background = params.dialogColor;
                    this.colorChangeCycle(spans,params.dialogColor);
                break;
            
                case '#fff':
                    //msgsActs.selectCancel();
    
                    dialog.style.background = 'transparent';
                    this.colorChangeCycle(spans,params.dialogColor);
                break;
            }
        }

        return;
    },

    colorChangeCycle: function(spans,color) {

        if(color == '#fff') {

            var colorValues = ["#000","#999","#666"];

            for(var i = 0; i < spans.length; i++) {  
                spans[i].style.color = colorValues[i];  
            }
        } else {

            for(var i = 0; i < spans.length; i++) {  
                spans[i].style.color = "#fff";  
            }
        }

        return;
    },

    msgsHistoryAppend: function(response,n,responseLength,location) {

        var con = document.getElementsByClassName('nano-content')[1].scrollHeight;
       
        /*if(n == 0) {
            lastMsg = response[n]['id'];
            if(unreadedMsgs[m + 1] == undefined) {
                z = 0;
                m++;
                unreadedMsgs[m] = [];
            }
        }
        if(n == responseLength) {
            //lastMsg = response[n]['id'];
            endMsg = response[n]['id'];
        }*/

        if(document.getElementsByClassName('msgs-history-selected')[0] == false) $(document.getElementsByClassName('nano-content')[1]).append("<div class='msgs-history-selected'></div>");
        if(document.getElementsByClassName('msgs-history-typing-wrap')[0] == false) $(document.getElementsByClassName('nano-content')[1]).append("<div class='msgs-history-typing-wrap'><span class='msgs-history-typing-wrap-text'></span></div>");

        //var dateRes = msgsActs.msgDateCheck(response[n]['date'],response[n]['date_day']);

        if(response[n]['status'] == 0) {

            //unreadedMsgs[m][++z] = response[n]['main_id'];

            $(document.getElementsByClassName('msgs-history-selected')[0]).append("<div class='msg-wrap' onclick='msgsActs.msgsSelect("+response[n]['main_id']+")' id="+response[n]['main_id']+" onmouseover='graphics.msgsMouseOver("+response[n]['main_id']+")' onmouseout='graphics.msgsMouseOut("+response[n]['main_id']+")'>"+
                "<div class='msg-wrap-big msg-unread'>"+
                    "<div class='msg-content'>"+
                        "<span class='msg-date'>"+response[n]['date']+"</span>"+
                        "<div class='msg-body msg-body-min'>"+
                            "<div class='msg-content-text'>"+
                                "<span class='msg-text'>"+response[n]['text']+"</span>"+
                            "</div>"+
                        "</div>"+
                    "</div>"+
                "</div>"); 
        } else {
            $(document.getElementsByClassName('msgs-history-selected')[0]).append("<div class='msg-wrap' onclick='msgsActs.msgsSelect("+response[n]['main_id']+")' id="+response[n]['main_id']+" onmouseover='graphics.msgsMouseOver("+response[n]['main_id']+")' onmouseout='graphics.msgsMouseOut("+response[n]['main_id']+")'>"+
                "<div class='msg-wrap-big'>"+
                    "<div class='msg-content'>"+
                        "<span class='msg-date'>"+response[n]['date']+"</span>"+
                        "<div class='msg-body msg-body-min'>"+
                            "<div class='msg-content-text'>"+
                                "<span class='msg-text'>"+response[n]['text']+"</span>"+
                            "</div>"+
                        "</div>"+
                    "</div>"+
                "</div>"); 
        }

        //this.msgGroupDateTitle(response[n]['id'],dateRes,response[n]['date'],response[n]['date_day']);

        //this.msgsHistorySelectedChange(con);

        return;
    },
};

var dialogsActs = {

    parseJson: function(length,elem,action) {
        
        try { 
            for(var i = 0;i < length;i++) {
                //graphics.dialogsHtmlAppend(action,j,i,elem);
                graphics.dialogsHtmlAppend(action,i,elem);
            }
        } catch(e) {
            alert(e);
        }

        //getMsgs({type:'GET',url:'../php/showMsgs.php',elem:document.getElementsByClassName('nano-content')[0]});

        return;
    },

    scroll: function() {

        $('.nano').nanoScroller();

        return;
    },

    scrollMsgs: function() {

        this.scroll();

        document.getElementsByClassName('nano-content')[1].scrollTop = document.getElementsByClassName('nano-content')[1].scrollHeight;

        return;
    },
};

var msgsActs = {

    parseJson: function(action,j,location) {

            try {
                for(var i = 0;i < j.length;i++) {
                    (action == "append") ? graphics.msgsHistoryAppend(j,i,arrLength,location) : graphics.newMsgsPrepend(j,i,arrLength);               
                }
            } catch(e) {
                alert(e);
            }

        return;
    },   

};

(document.readyState == 'loading') ? document.addEventListener("DOMContentLoaded", load.ready()) : load.ready();

function getXhrType() {

    var xhr;

    try {
        xhr = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
        try {
            xhr = new ActiveXObject("Microsoft.XMLHTTP");
        } catch (E) {
            xhr = 0;
        }
    }

    if(!xhr && typeof XMLHttpRequest != 'undefined') xhr = new XMLHttpRequest();

    return xhr;
}

function getDialogs(params) {

    var x = getXhrType();

    if(x == 0) {
        alert("Fatal error");
        return;
    }

    x.onreadystatechange = function() {

        if(x.readyState != 4) return;
    
        if(this.status == 200) {
    
            if(x.responseText == 0) {
                document.getElementsByClassName('nano-content')[0].innerHTML = "<span class='empty-dialogs'>You haven't got any dialogs yet</span>";
            } else {

                var length = eachCycle(JSON.parse(x.responseText));

                if(length == 0) {
                    alert("Error! Try again later...");
                } else {
                    dialogsActs.parseJson(length,params.elem,"append");
                }
            }
    
        } else {
            alert("Unable to connect to server! Try again later...");
        }       
    };

    x.open(params.type,params.url,true);

    x.setRequestHeader("Cache-Control", "no-cache");

    x.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    x.send();

    return;
}

function getMsgs(params) {

    var x = getXhrType();

    if(x == 0) {
        alert("Fatal error");
        return;
    }

    x.onreadystatechange = function() {

        if(x.readyState != 4) return;
    
        if(this.status == 200) {
    
            //params.elem.innerHTML = "";
    
            //var sell = getUrlParam(location.search,'sell');
    
            if(x.responseText == 0) {
                document.getElementsByClassName('nano-content')[1].innerHTML = "<span class='msgs-col-error'>The chat is empty.</span>";
            } else {
                var msgsArr = eachCycle(JSON.parse(x.responseText));
    
                if(msgsArr == 0) {
                    alert("Error! Try again later...");
                } else {
                    msgsActs.parseJson(msgsArr,params.elem,"append");
                }
            }
    
        } else {
            alert("Unable to connect to server! Try again later...");
        }
    };

    x.open(params.type,params.url,true);

    x.setRequestHeader("Cache-Control", "no-cache");

    x.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    x.send();

    return;
}

let dialogs = {};

function eachCycle(j) {

    var a = 0;
    var arr = [];
    
    try {
        for(var key in j) {
            //dialogs[""+(a++)+""] = j[key];
        } 
    } catch(e) {
        return 0;
    }

    return a;
}

function getUrlParam(oTarget, sVar) { 
    return decodeURI(oTarget.replace(new RegExp("^(?:.*[&\\?]" + encodeURI(sVar).replace(/[\.\+\*]/g, "\\$&") + "(?:\\=([^&]*))?)?.*$", "i"), "$1"));
}


