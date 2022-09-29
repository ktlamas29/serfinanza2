$(document).ready(function () {
  const openModal = $('.open-modal-confirm');

  openModal.on('click', function () {
    const name = $(this).data('name');
    const logo = $(this).data('logo');
    const valor = $(this).data('valor');
    const image = $(this).data('image');
    const description = $(this).data('description');
    const id = $(this).data('id');

    $('#img-modal').attr("src", image);
    $('#img-modal-logo').attr("src", logo);
    $('#title-modal').text(valor)
    $('#subtitle-modal').text(name);
    $('#description-modal').text(description);
    $('.btn-modal-redimir').attr('href', `/redimir.php?premio=${id}`);
    window.dataLayer.push({
      event: 'intencion_redención',
      campaign: 'Serfinanza',
      brand: name,
      price: valor
    });
  })


  $('a.no-action').on('click', function (e) {
    e.preventDefault();
  })
  $('a.no-action').click(function () { return false; });

  $('.btn-modal-redimir').on('click', function (e) {
    window.dataLayer.push({
      event: 'confirmación_redención',
      campaign: 'Serfinanza',
      brand: $('#subtitle-modal').text(),
      price: $('#title-modal').text()
    });
  });


});