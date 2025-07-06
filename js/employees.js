$(document).ready(function () {
    const sound = document.getElementById("dingSound");
    let paused = false;

    function flashOnly() {
        $("#ticketDisplay").addClass("flash-effect");
        setTimeout(() => {
            $("#ticketDisplay").removeClass("flash-effect");
        }, 700);
    }

    function flashWithSound() {
        flashOnly();
        try { sound.play(); } catch (e) {}
    }

    function showTicket(response, withSound = false) {
        $("#ticketDisplay").val(response);
        $("#ticketDisplay").css("background-color", "blueviolet");
        withSound ? flashWithSound() : flashOnly();
    }

    $("#Next").click(function () {
        if (paused) return;
        $.ajax({
            url: "next_ticket.php",
            method: "GET",
            success: function (response) {
                showTicket(response, false); // بدون صوت
            }
        });
    });

    $("#Re-Call").click(function () {
        if (paused) return;
        $.ajax({
            url: "recall_ticket.php",
            method: "GET",
            success: function (response) {
                showTicket(response, true); // مع صوت
            }
        });
    });

    $("#Not_found").click(function () {
        if (paused) return;
        $.ajax({
            url: "skip_ticket.php",
            method: "GET",
            success: function () {
                $("#Next").click();
            }
        });
    });

    $("#Stop").click(function () {
        if (!paused) {
            $("#ticketDisplay").val("متوقف مؤقتًا");
            $("#ticketDisplay").css("background-color", "transparent");
            $("#Next, #Re-Call, #Not_found").prop("disabled", true);
            $(this).text("استمرار");
            paused = true;
        } else {
            $("#ticketDisplay").val("");
            $("#ticketDisplay").css("background-color", "transparent");
            $("#Next, #Re-Call, #Not_found").prop("disabled", false);
            $(this).text("إيقاف مؤقت");
            paused = false;
        }
    });

    $("#logoutButton").click(function () {
        window.location.href = "logout.php";
    });
});
