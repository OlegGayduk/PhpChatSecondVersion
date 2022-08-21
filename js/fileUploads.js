//var messFileUploadActs = {
//
//    fileUpload: function(t,name) {
//
//        if(t.files[0]) {
//            this.upload(name);
//            return true;
//        } else {
//            return false;
//        }
//    },
//    
//    upload: function(name) {
//
//        var formData = new FormData(name);
//
//        if(usersInf['dialogStatus'] != false && getElems.getIdElem(getUrlParam(location.search, 'sell')) != false) {
//            if(msgSize == 'full' && msgSize != undefined) {
//                xhr({type:'POST',url:urls.sendFileUrl + window.location.search,sendContent:formData + '&date=' + encodeURIComponent(getDate()),elem:0});
//            } else {
//                xhr({type:'POST',url:urls.sendFileUrl + window.location.search,sendContent:formData + '&date=' + encodeURIComponent(getDate()) + '&myAlias=' + encodeURIComponent(myArr['alias']) + '&myAva=' + encodeURIComponent(myArr['ava']),elem:0});
//            }
//        } else {
//            xhr({type:'POST',url:urls.sendFileUrl + window.location.search,sendContent:formData + '&date=' + encodeURIComponent(getDate()) + '&myAlias=' + encodeURIComponent(myArr['alias']) + '&myAva=' + encodeURIComponent(myArr['ava']) + '&myStatus=' + encodeURIComponent(myArr['status']) +'&poluchAlias=' + encodeURIComponent(usersInf['alias']) + '&poluchAva=' + encodeURIComponent(usersInf['ava']) +'&poluchStatus=' + encodeURIComponent(usersInf['status']),elem:0});
//        }
//    },
//};