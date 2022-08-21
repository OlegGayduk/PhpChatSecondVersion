//var getId=function(e){return document.getElementById(e)};window.onload=function(){getId("login").value="",getId("login").focus()};var checkValue=function(){if(""==getId("login").value&&""==getId("pass").value)getId("login").focus();else if(""!=getId("login").value)if(""!=getId("pass").value){getId("loading").style.visibility="visible";var e="login="+encodeURIComponent(getId("login").value)+"&pass="+encodeURIComponent(getId("pass").value);ajaxRequest(e)}else getId("pass").focus();else getId("login").focus()},log=function(e){e.preventDefault(),checkValue()},ajaxRequest=function(e){var t=new XMLHttpRequest;t.onprogress=function(){"visible"==getId("error-log").style.visibility&&"hidden"==getId("error-log").style.visibility,document.getElementsByClassName("ball")[0].style.visibility="visible"},t.open("POST","../php/logIn.php",!0),t.setRequestHeader("Content-Type","application/x-www-form-urlencoded"),t.send(e),t.onreadystatechange=function(){4==t.readyState&&(200!=t.status?alert(t.status+": "+t.statusText):"true"==t.responseText?window.location.href="//test/test3/index/messages.new.php":(document.getElementsByClassName("ball")[0].style.visibility="hidden",getId("come-button").disabled=!1,getId("error-log").style.visibility="visible",getId("error-log").innerHTML=t.responseText))},getId("come-button").disabled=!0};

var attempts = 0;

var attempt = function(attempts) {
    switch(attempts) {
        case 3:
            alert("Try again throw the 5 minutes!");
        break;
        case 6:
            alert("Try again throw the 15 minutes!");
        break;
        case 9:
            alert("Try again throw the 45 minutes!");
        break;
    }

    return;
};

var getId = function(id) {
    return document.getElementById(id);
};

window.onload = function() {

    getId("login").value = "";
    getId("login").focus();

    return;
};

var checkValue = function() {

    var login = sanitize(getId('login').value);
    var pass = sanitize(getId('pass').value);

    if(login == false && pass == false) {
        getId('login').focus();
    } else {
        if(login != false) {
          if(pass != false) {
      
              var params = 'login=' + encodeURIComponent(login) + '&pass=' + encodeURIComponent(pass);

              //getId('loading').style.visibility = 'visible';

              document.getElementsByClassName('ball')[0].style.visibility = 'visible';

              ajaxRequest(params);
              //setTimeout(ajaxRequest, 1000, params);
          
          } else {
              getId('pass').focus();
          }
        } else {
            getId('login').focus();
        }
    } 

    return;
};

function sanitize(text) {

    if(text.value === 0) {
        return false;
    } else {
        text.replace(/<[^>]+>/g, '');
        return text;
    }

    return;
}

var log = function(e) {

    e.preventDefault();
    checkValue();

    return;
};

var ajaxRequest = function(params) {
  
    var xhr = new XMLHttpRequest();
  
    /*xhr.onprogress = function(event) {
        //getId('loading').innerHTML = parseInt(event.loaded / event.total * 100, 10) + '%';
        if(getId('error-log').style.visibility == 'visible') getId('error-log').style.visibility == 'hidden';
    
        document.getElementsByClassName('ball')[0].style.visibility = 'visible';
    };*/
      
    xhr.open("POST",'../php/login.php',true);
  
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.send(params);
    
    xhr.onreadystatechange = function() {
        if (xhr.readyState != 4) return;
    
        if (xhr.status != 200) {
            //alert(xhr.status + ': ' + xhr.statusText);
            alert("Unable to connect to server! Try again later...");
        } else {
            document.getElementsByClassName('ball')[0].style.visibility = 'hidden';
            //alert(xhr.responseText);
            if(xhr.responseText == 'true') {
                window.location.href = 'mess.php';
            } else {
                ++attempts;
        
                switch(attempts) {
                  case 3:
                      alert("Try again throw the 5 minutes!");
                  break;
                  case 6:
                      alert("Try again throw the 15 minutes!");
                  break;
                  case 9:
                      alert("Try again throw the 45 minutes!");
                  break;
                }
        
                //document.getElementsByClassName('ball')[0].style.visibility = 'hidden';
                //getId('loading').style.visibility = 'hidden';
                getId('come-button').disabled = false;
                getId('error-log').style.visibility = 'visible';
                //alert(xhr.responseText);
                getId('error-log').innerHTML = xhr.responseText;
            }
        }
    };

  //getId('loading').innerHTML = 'Загрузка...';
  //getId('come-button').disabled = true;

  return;
};

