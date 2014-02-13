/**
 * Created by ro1h00a0 on 13.02.2014.
 */
class_box.on('keyup', 'input.livesearch', function(event) {
  var $text,
      tabel = $('.tabel'),
      camp = $(this),
      id = camp.attr('id');
      string = camp.val(),
      path = 'php/companii.php',
      root = $(this).closest('.box').attr('id').slice(4),
      box_curent = $('#box-' + root);



  switch (event.which) {
    case 38:
      // key up
      if ($('.tabel .rec p').length == 0) {
        // daca a aparut mesajul nu exista nu facem nimic
        return;
      }
      if ($('.tabel .rec').hasClass('selected')) { // e deja selectat
        if ($('.tabel .rec:first').hasClass('selected')) {
          $('.tabel .selected').css('background-color', '#ffffff').removeClass('selected');
          $('.tabel .rec:last').css('background-color', '#CED5F5').addClass('selected');
          $text = $('.tabel .rec:last').children().first().text();
          $('#camp_cauta_companie').val($text).focus();
          return;
        }
        $('.tabel .selected').css('background-color', '#ffffff')
            .removeClass('selected')
            .prev()
            .css('background-color', '#CED5F5')
            .addClass('selected');
        $text = $('.tabel .selected').children().first().text();
        $(this).val($text).focus();
        return;
      } else { // nu e nimic selectat
        $('.tabel .rec:last').css('background-color', '#CED5F5').addClass('selected');
        $text = $('.rec:last').children().first().text();
        $(this).val($text).focus();
        return;
      }
    case 40:
      // keu down
      if ($('.tabel .rec p').length == 0) {
        // daca a aparut mesaul nu exista nu facem nimic
        return;
      }
      if ($('.tabel .rec').hasClass('selected')) {
        // e deja selectat
        if ($('.tabel .rec:last').hasClass('selected')) {
          $('.tabel .selected').css('background-color', '#ffffff').removeClass('selected');
          $('.tabel .rec:first').addClass('selected');
          $text = $('.tabel .rec:first').children().first().text();
          $(this).val($text).focus();
          return;
        }
        $('.tabel .selected').css('background-color', '#ffffff').removeClass('selected').next().css('background-color', '#CED5F5').addClass('selected');
        $text = $('.tabel .selected').children().first().text();
        $(this).val($text).focus();
        return;
      } else {
        // nu e nimic selectat
        $('.tabel .rec:nth-child(2)').css('background-color', '#CED5F5').addClass('selected');
        $text = $('.rec:nth-child(2)').children().first().text();
        $(this).val($text).focus();
        return;
      }
    case 13: // key enter
      var id_companie, selected = $('.tabel .selected');
      if (!$('.tabel:visible').length) {
        return;
      }
      if (selected.length) {
        $text = selected.children().first().text();
        id_companie = parseInt(selected.children().first().attr('id').slice(1));
        $('.tabel').fadeOut(timp_fadeout, function() {
          $('.tabel').empty();
          toggleEvents('submit_formular_persoana', true);
        });
        $(this).val($text).closest('tr').next().find('input:first').focus();
        $('input#id_companie').val(id_companie);
        return;
      } else {
        return;
      }
    default:
      break;
  }
  if (string.length > 2) {
    $.ajax({
      async:   true,
      url:     path,
      data:    { companie: string },
      timeout: 5000})
        .done(function(raspuns) {
          pozitionare_lista_sugestii(camp, tabel);
          tabel.html(raspuns).fadeIn(100);
          toggleEvents('submit_formular_persoana', false);
        })
        .fail(function(jqXHR, textStatus) {
          AjaxFail(jqXHR, textStatus, box_curent);
        });
  } else {
    $('.tabel').fadeOut(timp_fadeout, function() {
      $('.tabel').empty();
      toggleEvents('submit_formular_persoana', true);
    });
  }
});