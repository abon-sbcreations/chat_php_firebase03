<?php
ob_start();
session_start();
if (empty($_SESSION)) {
    header("Location: index.php");
}
require_once 'ConnectDb.php';
$sql = "SELECT * from user";
$db = ConnectDb::getInstance();
$mysqli = $db->getConnection();
$result = $mysqli->query($sql);
$users = [];
$user_name = '';
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        if ($_SESSION['logged_user'] != $row['id']) {
            $users[$row['id']] = ['username' => $row['username']];
        } else {
            $user_name = $row['username'];
        }
    }
} else {
    echo "0 results";
}
?>


<link href="library/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<!--link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css"-->

<script src="library/js/jquery.min.js" type="text/javascript"></script>
<script src="library/js/bootstrap.min.js" type="text/javascript"></script>


<!------ Include the above in your HEAD tag ---------->


<!DOCTYPE html>
<html class=''>
    <head>

        <meta charset='UTF-8'>
        <meta name="robots" content="noindex">
        <link rel="shortcut icon" type="image/x-icon" href="//production-assets.codepen.io/assets/favicon/favicon-8ea04875e70c4b0bb41da869e81236e54394d63638a1ef12fa558a4a835f1164.ico" />
        <link rel="mask-icon" type="" href="//production-assets.codepen.io/assets/favicon/logo-pin-f2d2b6d2c61838f7e76325261b7195c27224080bc099486ddd6dccb469b8e8e6.svg" color="#111" />
        <link rel="canonical" href="https://codepen.io/emilcarlsson/pen/ZOQZaV?limit=all&page=74&q=contact+" />
        <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700,300' rel='stylesheet' type='text/css'>
        <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.2/css/font-awesome.min.css'>
        <link href="library/css/reset.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/css/page01.css" rel="stylesheet" type="text/css"/>

        <!--prevented by designer to send this stylesheet to a separate file-->
    </head>
    <body>
<div id="loading"></div>
        <div id="frame">
            <div id="sidepanel">
                <div id="profile">
                    <div class="wrap">
                        <img id="profile-img" src="library/img/male.png" class="online" alt="" />
                        <p><?= $user_name ?></p>
                        <i class="fa fa-chevron-down expand-button" aria-hidden="true"></i>

                    </div>
                </div>
                <div id="search">
                    <label for=""><i class="fa fa-search" aria-hidden="true"></i></label>
                    <input type="text" placeholder="Search contacts..." />
                </div>
                <div id="contacts">
                    <ul id="contact_list"></ul>
                </div>
                <div id="bottom-bar">
                    <button id="addcontact"><i class="fa fa-user-plus fa-fw" aria-hidden="true"></i> <span>Add contact</span></button>
                    <button id="settings"><i class="fa fa-cog fa-fw" aria-hidden="true"></i> <span>Settings</span></button>
                </div>
            </div>
            <div class="content">
                <div class="contact-profile">
                    <img id="currentUserId" src="library/img/male1.png" alt="" />
                    <p id="contactProfileName"></p>

                </div>
                <div class="messages">
                    <ul></ul>
                </div>
                <div class="message-input">
                    <div class="wrap">
                        <input type="text" placeholder="Write your message..." />
                        <i class="fa fa-paperclip attachment" data-toggle="modal" data-target="#myModal" aria-hidden="true"></i>
                        <button class="submit"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog">

                <!---file attach-- Modal content-start-->
                <div id="fileUploadModal01" class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Upload File</h4>
                    </div>
                    <div class="modal-body">
                        <form method="post" id="imageUpload" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="exampleFormControlFile1">Example file input</label>
                                <input type="file" class="form-control-file" id="uploaded_image" name="uploaded_image">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <!---file attach-- Modal content-end-->
            </div>
        </div>

        <script src="library/js/5.0.1/firebase-app.js" type="text/javascript"></script>
        <script src="library/js/5.0.1/firebase-database.js" type="text/javascript"></script>
        <script src="assets/js/myfirebase_confs.js" type="text/javascript"></script>
        <script >
            firebase.initializeApp(firebase_config);
            var totChat = [];            var logged_id;
            var current_id;            var current_id;
            var logRef;            var curRef;
            var logToRef;
            $(document).ready(function () {
                $(".message-input input").attr('disabled', 'disabled');
                $("#currentUserId").hide();
                logToRef = firebase.database().ref().child('chats').orderByChild("to_id").equalTo(parseInt('1'));
                logToRef.on("child_added", function (snap) {
                    snap = snap.val();
                    console.log(snap);
                    if ($("#user_" + snap['from_id'] + " .preview").length > 0) {
                        $("#user_" + snap['from_id'] + " .preview").html(snap['message'])
                    }
                });
                //frame
                $('.content').hide();
                $("#frame").append("<div class='pageintro' ><h1>Please select contact to start chatting.</h1></div>");
            });

            $(".messages").animate({scrollTop: $(document).height()}, "fast");

            $("#profile-img").click(function () {
                $("#status-options").toggleClass("active");
            });

            $(".expand-button").click(function () {
                $("#profile").toggleClass("expanded");
                $("#contacts").toggleClass("expanded");
            });

            $("#status-options ul li").click(function () {
                $("#profile-img").removeClass();
                $("#status-online").removeClass("active");
                $("#status-away").removeClass("active");
                $("#status-busy").removeClass("active");
                $("#status-offline").removeClass("active");
                $(this).addClass("active");
                if ($("#status-online").hasClass("active")) {
                    $("#profile-img").addClass("online");
                } else if ($("#status-away").hasClass("active")) {
                    $("#profile-img").addClass("away");
                } else if ($("#status-busy").hasClass("active")) {
                    $("#profile-img").addClass("busy");
                } else if ($("#status-offline").hasClass("active")) {
                    $("#profile-img").addClass("offline");
                } else {
                    $("#profile-img").removeClass();
                }
                ;

                $("#status-options").removeClass("active");
            });
            $.each(<?= json_encode($users) ?>, function (id, row) {
                $("#contact_list").append("<li class='contact' id='user_" + id + "'><div class='wrap'>"
                        + "<img src='library/img/male1.png' alt='' /><div class='meta'><p class='name'>" + row['username'] + "</p><p class='preview'>"
                        + "No unseen message till now</p></div></div></li>");

                $(document).on('click', '#user_' + id, function () {
                    $('.pageintro').hide();
                    $('.content').show();
                    console.log("current = " + id);
                    current_id = id;
                    totChat = [];
                    logged_id = <?= $_SESSION['logged_user'] ?>;
                    $.ajax({type: "POST", async: "false",
                        url: "chat02ajax.php",
                        data: {current_id: current_id, logged_id: logged_id},
                        success: function (html) {
                            console.log("chat 2 ajax ");
                            var arr = $.parseJSON(html);
                            $.each(arr, function (k, row) {
                                totChat.push({
                                    'message' : row['txt_msg'],
                                    'ip': row['ip'],
                                    'link' : row['is_link'],
                                    'from_id' : row['from_id'],
                                    'to_id' : row['to_id'],
                                    'current_time' : row['time1']
                                });
                            });
                            console.log(totChat);
                            showChat();
                        }
                    });

                    $(".message-input input").removeAttr('disabled');
                    $("#contactProfileName").html(row['username']);
                    $("#currentUserId").show();

                    logRef = firebase.database().ref().child('chats').orderByChild("from_id").equalTo(parseInt(logged_id));
                    curRef = firebase.database().ref().child('chats').orderByChild("from_id").equalTo(parseInt(current_id));
                    logRef.on("child_added", function (snap) {
                        console.log(snap);
                        snap = snap.val();
                        if (snap['to_id'] == current_id) {
                            console.log("chat push logRef");
                            totChat.push(snap);
                            showChat();
                        }
                    });
                    curRef.on("child_added", function (snap1) {
                        console.log("toRef child_added");
                        var snap = snap1.val();
                        if (snap['to_id'] == logged_id) {
                            console.log("chat push toRef");
                            console.log("snap 11")
                            totChat.push(snap);
                            console.log("snap 12")
                            showChat();
                            $.ajax({type: "POST", async: "false",
                                url: "chat01ajax.php", data: {row: snap},
                                success: function (html) {
                                    console.log("chat 11 ajax");
                                    console.log(html);
                                    console.log("chat 12 ajax");
                                    var arr = $.parseJSON(html);
                                    console.log(arr);
                                    console.log("chat 13 ajax");
                                    if (arr.status == 1) {
                                        console.log("fire key " + snap1.key + " delete");
                                        firebase.database().ref().child('chats').child(snap1.key).remove();
                                        console.log(totChat);
                                    }
                                }
                            });
                        }
                    });
                });
            });


            function showChat() {
                $('.messages ul').html("");
                var result = [];
                $.each(totChat, function (i, e) {
                    var matchingItems = $.grep(result, function (item) {
                        return item.current_time === e.current_time;
                    });
                    if (matchingItems.length === 0) {
                        result.push(e);
                    }
                });
                result.sort(function (a, b) {
                    if (a.current_time > b.current_time) {
                        return 1
                    }
                    if (a.current_time < b.current_time) {
                        return -1
                    }
                    return 0;
                });
                $.each(result, function (key, snap) {
                    var msg = "";
                    if(snap['link']==1){
                        console.log("url making");
                        var url = decodeURI(snap['message']);
                        var file = url.substring(url.lastIndexOf('/')+1);
                        msg = "<a target='_blank' href='" + url + "'>click to open "+file+"</a>";
                    }else{
                        console.log("string making");
                        msg = decodeURI(snap['message']);
                    }
                    if (snap['from_id'] == logged_id) {
                        $("<li class='sent'><img src='library/img/male.png' alt=''><p>" + msg + "</p></li>")
                                .appendTo($('.messages ul'));
                    } else {
                        $("<li class='replies'><img src='library/img/male1.png' alt=''><p>" + msg + "</p></li>")
                                .appendTo($('.messages ul'));
                    }
                });
                console.log("showChat");
                console.log(result);
                console.log("showChat");
                totChat = result;
                scrollBottom(".messages", 0);
            }
            function scrollBottom(identifier, sec) {
                $(identifier).animate({
                    scrollTop: $(identifier)[0].scrollHeight - $(identifier)[0].clientHeight
                }, sec);
            }
            function newMessage() {
                console.log("newMessage");
                message = $(".message-input input").val();
                if ($.trim(message) == '') {
                    return false;
                }
                var ip = '<?= $_SERVER['REMOTE_ADDR'] ?>';
                $('.message-input input').val(null);
                $('.contact.active .preview').html('<span>You: </span>' + message);
                $(".messages").animate({scrollTop: $(document).height()}, "fast");
                var newPostKey = firebase.database().ref().child('chats').push().key;
                var x1 = firebase.database().ref('chats/' + newPostKey).set({
                    'message': encodeURI(message),
                    'link':0,
                    'ip': ip,
                    'from_id': parseInt(logged_id),
                    'to_id': parseInt(current_id),
                    'current_time': $.now()
                });
                console.log(totChat);
                console.log("newMessage2");
            }
        console.log("newMessage3");
            $('.submit').click(function () {
                $(this).submit(function () {
                    return false;
                });
                newMessage();
            });

            $(window).on('keydown', function (e) {
                if (e.which == 13) {
                    $(this).submit(function () {
                        return false;
                    });
                    newMessage();
                    return false;
                }
            });
            function removeAll() {
                var chatRef = firebase.database().ref().child('chats');
                chatRef.remove();
            }
            function displayAll() {
                var chatRef = firebase.database().ref().child('chats');
                chatRef.on("value", function (snap) {
                    snap = snap.val();
                    console.log(snap);
                });
            }
            $(document).on('submit', '#imageUpload', function (e) {
                e.preventDefault();
                $.ajax({
                    url: "ajax_file_upload.php",
                    type: "POST",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        var stat = $.parseJSON(data);
                        console.log(stat);
                        if (stat['status'] == 1) {
                            $('#imageUpload')[0].reset();
                            $('#myModal').modal('hide');
                            var msg = stat['full_path'];
                            var newPostKey = firebase.database().ref().child('chats').push().key;
                            var x1 = firebase.database().ref('chats/' + newPostKey).set({
                                'message': encodeURI(msg),
                                'link':1,
                                'ip': '<?= $_SERVER['REMOTE_ADDR'] ?>',
                                'from_id': parseInt(logged_id),
                                'to_id': parseInt(current_id),
                                'current_time': $.now()
                            });
                            $("<li class='sent'><img src='library/img/male.png' alt=''><p>" + msg + "</p></li>")
                                    .appendTo($('.messages ul'));
                        }
                    }
                });
            });
            $(document).on('change', "#uploaded_image", function (e) {
                e.preventDefault();
                $('#imageUpload').submit();
            });
            function newWindow(link1) {
                myWindow = window.open(link1, "abcd", "width=450, height=500");
            }
            //# sourceURL=pen.js
        </script>
    </body></html>


<!--please select contact to start chatting
on load, chatpane is invisible. on click show chat pane and start chatting
-->
