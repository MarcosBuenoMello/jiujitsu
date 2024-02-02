$(".select2").select2({
    language: "pt-BR",
    width: "100%",
    theme: "bootstrap4"
});
$(".btn-delete").on("click", function(e) {
    e.preventDefault();
    var form = $(this)
    .parents("form")
    .attr("id");
    swal({
        title: "Você está certo?",
        text:
        "Uma vez deletado, você não poderá recuperar esse item novamente!",
        icon: "warning",
        buttons: true,
        buttons: ["Cancelar", "Excluir"],
        dangerMode: true
    }).then(isConfirm => {
        if (isConfirm) {
            document.getElementById(form).submit();
        } else {
            swal("", "Este item está salvo!", "info");
        }
    });
});

// $().ready(function(){ 
//     setTimeout(() => {
//         // detectMob()
//     }, 300)
// })

function detectMob() {
    if(window.innerWidth >= 800){
        // $('#sidebarToggleTop').trigger('click')
        $('#accordionSidebar').removeClass('toggled')
    }
}

$(document).on("click", ".browse", function() {
    var file = $(this).parents().find(".file");
    file.trigger("click");
});

$(document).on("click", ".browse-video", function() {
    var file = $(this).parents().find(".file_video");
    file.trigger("click");
});

$('input[type="file"].file').change(function(e) {
    var fileName = e.target.files[0].name;
    $("#file").val(fileName);

    var reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById("preview").src = e.target.result;
    };
    reader.readAsDataURL(this.files[0]);
    $('#preview').css('display', 'block')
});

$('input[type="file"].file_video').change(function(e) {
    var fileName = e.target.files[0].name;
    $("#file_video").val(fileName);

    var reader = new FileReader();
    swal({
        title: "Um momento",
        text: "Carregando dados do video ...",
        icon: "/images/loading.gif",
        button: false,
        closeOnClickOutside: false,
        closeOnEsc: false
    });
    reader.onload = function(e) {
        swal.close()
        let mbVideo = (e.total/1000000).toFixed(2)
        if(mbVideo > 300){
            swal("Erro de upload", "Máximo suportado arquivo de 300 Mb", "error")
            .then(() => {
                var $source = $('#preview-video');
                $source[0].src = null
                $source.parent()[0].load();
                $(".file_video").val(null);
                $('.video-view').css('display', 'none')
                $('.size-video').css('display', 'none')

            })
        }
        $('.size-video').html('Tamanho: ' + mbVideo + ' MB')
        $('.size-video').css('display', 'block')
    };
    reader.readAsDataURL(this.files[0]);

    var $source = $('#preview-video');
    $source[0].src = URL.createObjectURL(this.files[0]);
    $source.parent()[0].load();
    $('.video-view').css('display', 'block')

});


$(".qtd").mask("00000", {
    reverse: true
});
$(".celular").mask("00 00000-0000", {
    reverse: true
});
$(".cardNumber").mask("0000 0000 0000 0000", {
    reverse: true
});
$(".money").mask("00000,00", {
    reverse: true
});
$(".horario").mask("00:00", {
    reverse: true
});

$(".cardExpirationMonth").mask("00", {
    reverse: true
});
$(".cardExpirationMonth").mask("00", {
    reverse: true
});
$(".securityCode").mask("0000", {
    reverse: true
});

function removeNotificacao(n){
    location.href = '/notify/delete/'+n
}