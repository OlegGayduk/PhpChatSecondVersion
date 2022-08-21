<?php

session_start();

ini_set('default_charset','UTF-8');

if(!isset($_SESSION['id'])) {
    exit("<span style='font: 14px/18px Arial,Helvetica,Verdana,sans-serif;position:absolute;left:0;right:0;text-align: center;top:50%;'>Фатальная ошибка,пройдите авторизацию повторно!</span>");
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <title>Диалоги</title>
    <link rel='stylesheet' type='text/css' href='../css/main.new.css'/>
    <script src='../js/jqueryLibrary.js'></script>
    <script src='../js/nanoscroll.js'></script>
    <script src='../js/controllers.js' defer></script>
    <script src='../js/avaUpload.js'></script>
    <script src='../js/fileUploads.js'></script>
</head>

<body>

<div class="back" onclick='menuActs.closeSetBarAct()'></div>
<div class='set-bar-wrap'></div>
<div class='tg-page-head'>
    <div class='tg-head-logo-wrap' onclick='menuActs.openMenuAct()'>
        <span class='tg-head-logo'>Secumes</span>
        <div class='tg-head-logo-bar'>
            <span class='bar'></span>
            <span class='bar'></span>
            <span class='bar'></span>
        </div>
    </div>
    <div class='tg-dropdown-menu-wrap'>
        <ul>
            <li onclick='menuActs.openSetBarAct()'><div class="set-icon"></div>Настройки</li>
            <li onclick='menuActs.openHelpWindowAct()'><div class="help-icon"></div>Помощь</li>
            <li onclick='menuActs.openInfWindowAct()'><div class="about-icon"></div>О Secumes</li>
        </ul>
    </div>
    <div class='tg-head-main-peer-wrap'></div>
</div>
<div class='page-main-wrap'>
    <div class='dialogs-main-wrap'>
        <div class='search-dialogs-wrap'>
            <form id='search-dialogs-form' method='post'>
                <div class="search-icon"></div>
                <input class='search-dialogs-field' placeholder='Поиск' name='search-text' type='text' oninput='searchDialogsActs.searchOninput(this)'>
                    <span class="cleanSearch" onclick="searchDialogsActs.clean(this)"></span>
                </input>
            </form>
        </div>
        <div id='dialogs-col-wrap' class='nano'>
            <div class='overthrow nano-content' onscroll='dialogsActs.getMoreDialogs(this)'></div>
        </div>
    </div>
    <div class='msgs-main-wrap'>
        <div id='msgs-history-col-wrap' class='nano'>
            <div class='overthrow nano-content' onscroll='msgsActs.getMoreMsgs(this)'></div>
        </div>
        <div class='msgs-send-form-wrap'>

            <div class="msgs-ava-interlocutor"></div>
            <div class="msgs-ava-main-seller"></div>
            
            <form id='msgs-send-form' method='post'>
                <textarea class='msgs-send-textarea' contenteditable='true' onkeydown='msgsActs.sendMsgFromKey(event)' oninput='msgsActs.writerStatus(this)' placeholder='Напишите сообщение...' name='text'></textarea>
                <span type='submit' class='msgs-history-send-btn' onclick='msgsActs.sendMsgMain(event)'>ОТПРАВИТЬ</span>
            </form>  

            <div class="bottom-send-panel">
                <div class='send-media'>
                    <form name="uploadFile" id='upload' method='post' enctype='multipart/form-data'>
                        <input name="filename" class="im_attach_input" size="28" multiple="multiple" title="Send file" type="file" onchange='messFileUploadActs.fileUpload(this,uploadFile);'>
                    </form>
                </div>
                <div class="send-photos">
                    <form name="uploadMedia" id='upload' method='post' enctype='multipart/form-data'>
                        <input name="filename" class="im_media_attach_input" size="28" multiple="multiple" accept="image/*, video/*, audio/*" title="Send media" type="file" onchange='messFileUploadActs.fileUpload(this,uploadMedia);'>
                    </form>
                </div>

            </div>   
        </div>
    </div>
</div>
</body>
</html>