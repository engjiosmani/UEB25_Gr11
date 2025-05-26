$(document).ready(function () {
    // Votimi për artistin e preferuar
    $(".vote-btn").click(function () {
        const artist = $(this).data("artist");

        $.post("ajax_handler.php", { vote_artist: artist }, function (response) {
            if (response.trim() === "success") {
                alert("Votimi u regjistrua me sukses!");
                loadVotes();
            } else if (response.trim() === "already_voted") {
                alert("Ju tashmë keni votuar.");
            } else {
                alert("Gabim gjatë votimit.");
            }
        });
    });

    // Ngarkimi i statistikave të votave
    function loadVotes() {
        $.getJSON("ajax_handler.php?get_votes=true", function (data) {
            let html = "";
            for (const artist in data) {
                html += `<li>${artist}: ${data[artist]} vota</li>`;
            }
$("#voteList").html(html);
        });
    }

    // Thirrja fillestare kur ngarkohet faqja
    loadVotes();
});
