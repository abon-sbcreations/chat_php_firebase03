firebase.initializeApp(firebase_config);
        var totChat = [];       var logged_id;
        var current_id;         var current_id;
        var logRef;             var curRef;
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
                        'message': row['txt_msg'],
                                'ip': row['ip'],
                                'from_id': row['from_id'],
                                'to_id': row['to_id'],
                                'current_time': row['time1']
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
                                totChat.push(snap);
                                showChat();
                                snap['message'] = $.parseHTML(snap['message']);
                                $.ajax({type: "POST", async: "false",
                                        url: "chat01ajax.php", data: {row: snap},
success: function (html) {
                                                console.log("chat 1 ajax");
                                                var arr = $.parseJSON(html);
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
                                                        return - 1
}
return 0;
});
$.each(result, function (key, snap) {
                                                        if (snap['from_id'] == logged_id) {
                                                $("<li class='sent'><img src='library/img/male.png' alt=''><p>" + snap['message'] + "</p></li>")
                                                        .appendTo($('.messages ul'));
} else {
                                                        $("<li class='replies'><img src='library/img/male1.png' alt=''><p>" + snap['message'] + "</p></li>")
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
                                                        'message': message,
                                                        'ip': ip,
                                                        'from_id': parseInt(logged_id),
                                                        'to_id': parseInt(current_id),
                                                        'current_time': $.now()
});
console.log(totChat);
}

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
                                                                                var msg = "<a target='_blank' href='" + stat['full_path'] + "'>Click To Download:" + stat['file_name'] + "</a>";
                                                                                var newPostKey = firebase.database().ref().child('chats').push().key;
                                                                                var x1 = firebase.database().ref('chats/' + newPostKey).set({
                                                                        'message': msg,
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