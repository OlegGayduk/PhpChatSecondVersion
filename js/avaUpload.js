var avaUploadActs = {
    beginFileUpload:function(t) {
        var input = document.getElementById('upload').getElementsByTagName('input')[0];
        input.click();
    },
    
    fileUpload:function(t) {
    
        var file = t.files[0];
    
        if(file) {
            this.upload(file);
            return true;
        } else {
            return false;
        }
    },
    
    upload:function(file) {
    
        var xhr = new XMLHttpRequest();
    
        var formData = new FormData(document.forms.avaSet);
    
        xhr.onload = xhr.onerror = function() {
          if (this.status != 200) {
            alert(xhr.status + ': ' + xhr.statusText);
          } else {
            if(xhr.responseText != 'error') {
                var pc = document.getElementsByClassName("ava")[0];
                pc.innerHTML = "<img src='" + xhr.responseText + "' class='ava-included' width='72' height='72'>";
            } else {
              alert(xht.responseText);
            }
          }
        };
    
        xhr.upload.onprogress = function(event) {
            log(parseInt(event.loaded / event.total * 100, 10) + '%');
        }
    
        xhr.open("POST", "../php/avaUpload.php", true);
    
        xhr.send(formData);
    },
};