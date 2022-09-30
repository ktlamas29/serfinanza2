$(document).ready(function () {
  /*
   * Config datepicker
   */

  $('#datepicker').datepicker({
    dateFormat: 'yy-mm-dd',
    changeMonth: true,
    changeYear: true,
    yearRange: '1940:2006',
    closeText: 'Cerrar',
    prevText: '<Ant',
    nextText: 'Sig>',
    name: 'date_s',
    currentText: 'Hoy',
    monthNames: [
      'Enero',
      'Febrero',
      'Marzo',
      'Abril',
      'Mayo',
      'Junio',
      'Julio',
      'Agosto',
      'Septiembre',
      'Octubre',
      'Noviembre',
      'Diciembre',
    ],
    monthNamesShort: [
      'Ene',
      'Feb',
      'Mar',
      'Abr',
      'May',
      'Jun',
      'Jul',
      'Ago',
      'Sep',
      'Oct',
      'Nov',
      'Dic',
    ],
    dayNames: [
      'Domingo',
      'Lunes',
      'Martes',
      'Miércoles',
      'Jueves',
      'Viernes',
      'Sábado',
    ],
    dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
    dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
    weekHeader: 'Sm',
    firstDay: 1,
    isRTL: false,
    yearSuffix: '',
    beforeShow: function (input, inst) {
      setTimeout(function () {
        inst.dpDiv.css({
          top: $('#datepicker').offset().top + 40,
          left: $('#datepicker').offset().left,
        })
      }, 0)
    },
  })

  //Variables
  const inputUser = $('input[name="user_s"]')
  const birdthUser = $('input[name="date_s"]')
  const tycCheck = $('#tyc')

  //Click en ingreso
  $('.send-form-login').click((event) => {
    event.preventDefault()
    $('.invalid-feedback').html('')
    $('.invalid-feedback').removeClass('active')
    if (inputUser.val().length < 8) {
      validateAlerts(
        inputUser,
        'El código es incorrecto, intenta de nuevo.',
      )
      return false
    }
    // if (birdthUser.val() == '') {
    //   validateAlerts(birdthUser, "Debes ingresar tu fecha de nacimiento");
    //   return false;
    // }
    if (!tycCheck.prop('checked')) {
      validateAlerts(tycCheck, 'Debes aceptar los términos y condiciones.')
      return false
    }

    function validateAlerts(selector, message) {
      $('.invalid-feedback').html('')
      selector
        .parents('.form-group')
        .find('.invalid-feedback')
        .addClass('active')
        .html(message)
    }

    inputUser.val(sha256(inputUser.val()))
    // birdthUser.val(sha256(birdthUser.val()));
    // $('input[name="user_password"]').val(birdthUser.val());
    $('input[name="user_name"]').val(inputUser.val())
    inputUser.val('')
    birdthUser.val('')
    $('#form_login').submit()
  })

  // Modal end campaign

  setTimeout(function () {
    $('.modal-end-campaign').css('display', 'flex')
  }, 500)

  $('.btn-close-modal').click(function () {
    $('.modal-end-campaign').hide()
  })
  $('.backdrop').click(function(e){
    setTimeout(function () {
      $('.modal-end-campaign').css('display', 'none')
    }, 500)
  })
})



// Info code login 
const spn_bl = document.querySelector("#spn_bl");
const btn_cerrar =document.querySelector("#btn_cerrar");
const img_inf =document.querySelector("#img_inf");



img_inf.addEventListener('click', function (e) {
  spn_bl.classList.toggle("show");
})

btn_cerrar.addEventListener('click', () => {
  spn_bl.classList.toggle("show");
})
