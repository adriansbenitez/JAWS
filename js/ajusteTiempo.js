$(document).ready(function() {
    var tiempo = $('input[name="restante"]').val();
    if (tiempo !== "0" && tiempo !== "") {
        $('#timer').chrony({text: tiempo,
            alert: {hour: 0, minute: 1, second: 0},
            finish: function() {
                $("#frmexamen").submit();
            }
        });
    }

    $("#guardarycontinuar").click(function() {
        $('#esTemporal').val(0);
        if ($("#timer").length) {
            var tiempo = parseInt($("#hour").html()) * 60 + parseInt($("#minute").html());
            $("#tiempo").attr("value", tiempo)
        }
        $("#frmexamen").submit();
    });
});