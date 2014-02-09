(function() {
  $(document).ready(function() {
    var class_box = $('.box'),
        id_box_companii = $('#box-companii'),
        id_box_vanzatori = $('#box-vanzatori'),
        id_box_persoane = $('#box-persoane'),
        id_box_oferta_noua = $('#box-oferta-noua'),
        pagina = [  'php/oferta-noua.php',
          'php/oferte.php',
          'php/comanda_noua.php',
          'php/comenzi.php',
          'php/stats-ofertare.php',
          'php/stats-comenzi.php',
          'php/stats-clienti.php',
          'php/stats-furnizori.php',
          'php/companii.php',
          'php/vanzatori.php',
          'php/persoane.php'],
        timp_fadein = 100,
        timp_fadeout = 150;
    var load_box = function(box_curent, box_nou, path) {
      // se incarca box-ul unei optiuni noi din meniu
      // implemantare box.load cu fadeout/fadein
      // intre box-ul optiunii curente si box-ul optiunii noi care se incarca
      $.ajax({
        async:   true,
        url:     path,
        timeout: 5000
      })
          .done(function(data) {
            box_curent.fadeOut(timp_fadeout)
                .promise()
                .done(function() {
                  box_curent.empty();
                  box_nou.queue('fx', function() {
                    box_nou.html(data);
                    box_nou.dequeue('fx');
                  });
                  box_nou.fadeIn(timp_fadein, function() {
                    $('input.datepicker').Zebra_DatePicker({
                          months: ['Ianuarie', 'Februarie', 'Martie', 'Aprilie',
                            'Mai', 'Iunie', 'Iulie', 'August',
                            'Septembrie', 'Octombrie', 'Noiembrie', 'Decembrie'],
                          format: 'j-M-Y', zero_pad: true, offset: [5, 255] }
                    );
                  });
                });
          })
          .fail(function(jqXHR, textStatus) {
            $('span.ajax').remove();
            if (textStatus === "error") {
              box_curent.append('<span class="error ajax">Eroare!' + jqXHR.responseText + '</span>');
            }
            if (textStatus === "timeout") {
              box_curent.append('<span class="error ajax">Rețeaua este lentă sau întreruptă.</span>');
            }
          });
    };
    var load_interior_box = function(box, path) {
      // se incarca in box-ul curent noua pagina
      // implemantare box.load cu fadeout/fadein
      // intre cele doua pagini ale aceluiasi box care se incarca
      $.ajax({
        async: true,
        url:   path, timeout: 5000})
          .done(function(data) {
            box.fadeOut(timp_fadeout);
            box.queue('fx', function() {
              box.html(data);
              box.dequeue('fx');
            });
            box.fadeIn(timp_fadein);
          })
          .fail(function(jqXHR, textStatus) {
            $('span.ajax').remove();
            if (textStatus === "error") {
              box.append('<span class="error ajax">Eroare!' + jqXHR.responseText + '</span>');
            }
            if (textStatus === "timeout") {
              box.append('<span class="error ajax">Rețeaua este lentă sau întreruptă.</span>');
            }
          });
    };
    var pozitionare_lista_sugestii = function(elem_sursa, elem_destinatie) {
      // pozitioneaza fereastra de sugestii sub campul apelat
      // elem_sursa este cel de la care se preia pozitia si dimensiunile
      // elem_destinatie este elementul care se pozitioneaza
      var Xleft = elem_sursa.position().left,
          Xtop = elem_sursa.position().top,
          Xwidth = parseInt(elem_sursa.css('width')),
          Xheight = parseInt(elem_sursa.css('height'));
      elem_destinatie.css({
        'left':      Xleft,
        'top':       Xtop + Xheight + 1,
        'min-width': Xwidth
      });
    };
    var toggleEvents = function(event, action) {
      if (event === 'submit_formular_persoana') {
        if (action) {
          id_box_persoane.on('click.persoana', '#creaza_persoana, #editeaza_persoana', function(event) {
            var path = pagina[10],
                flag = false,
                pattern,
                values = [],
                camp = $('form input, form select'),
                salveaza = (this.id == "creaza_persoana") ? 1 : 0;  // 1-salveaza nou, 0-modific existent
            event.preventDefault();
            $('span.error').remove();
            camp.removeClass('required');
            // prelucrare ID
            values[0] = parseInt(camp.eq(0).val()) || null;
            pattern = /^.{3,50}$/;
            // Prelucrare nume
            values[1] = camp.eq(1).val(camp.eq(1).val().trim()).val();
            if (!pattern.test(values[1])) {
              flag = true;
              camp.eq(1).addClass('required').parent().append('<span class="error">Minim 3 caractere.</span>');
            }
            // Prelucrare prenume
            values[2] = camp.eq(2).val(camp.eq(2).val().trim()).val();
            if (!pattern.test(values[2])) {
              flag = true;
              camp.eq(2).addClass('required').parent().append('<span class="error">Minim 3 caractere.</span>');
            }
            // Prelucrare telefon
            pattern = /^[\+\-\(\)\s0-9]{3,}$/;
            values[3] = camp.eq(3).val(camp.eq(3).val().slice(0, 50).trim()).val();
            if (!pattern.test(values[3])) {
              flag = true;
              camp.eq(3).addClass('required').parent().append('<span class="error">Caractere permise : numere, spatii, +(-)<br />Minim 3 caracatere.</span>');
            }
            // Prelucrare fax
            values[4] = camp.eq(4).val(camp.eq(4).val().slice(0, 50).trim()).val();
            if (!pattern.test(values[4])) {
              flag = true;
              camp.eq(4).addClass('required').parent().append('<span class="error">Caractere permise : numere, spatii, +(-)<br />Minim 3 caracatere.</span>');
            }
            // Prelucrare telefon mobil
            values[5] = camp.eq(5).val(camp.eq(5).val().slice(0, 50).trim()).val();
            if (!pattern.test(values[5])) {
              flag = true;
              camp.eq(5).addClass('required').parent().append('<span class="error">Caractere permise : numere, spatii, +(-)<br />Minim 3 caracatere.</span>');
            }
            // prelucrare email
            pattern = /^[\w-]+(\.[\w-]+)*@([a-z0-9-]+(\.[a-z0-9-]+)*?\.[a-z]{2,6}|(\d{1,3}\.){3}\d{1,3})(:\d{4})?$/;
            values[6] = camp.eq(6).val(camp.eq(6).val().slice(0, 100).trim()).val();
            if (!pattern.test(values[6])) {
              flag = true;
              camp.eq(6).addClass('required').parent().append('<span class="error">Adresa de email nu este validă.</span>');
            }
            // validare sex
            values[7] = camp.eq(7).val();
            if (!values[7].length) {
              flag = true;
              camp.eq(7).addClass('required').parent().append('<span class="error">Alegeţi o opţiune.</span>');
            }
            // validare companie
            values[8] = camp.eq(11).val();
            if (!values[8].length) {
              flag = true;
              camp.eq(8).addClass('required').parent().append('<span class="error">Alegeţi o companie.</span>');
            }
            // validare Departament
            pattern = /^.{3,50}$/;
            values[9] = camp.eq(9).val(camp.eq(9).val().trim()).val();
            if (!pattern.test(values[9])) {
              flag = true;
              camp.eq(9).addClass('required').parent().append('<span class="error">Minim 3 caracatere.</span>');
            }
            // validare Functie
            values[10] = camp.eq(10).val(camp.eq(10).val().trim()).val();
            if (!pattern.test(values[10])) {
              flag = true;
              camp.eq(10).addClass('required').parent().append('<span class="error">Minim 3 caracatere.</span>');
            }
            if (!flag) {
              $.ajax({
                async:   true,
                url:     path,
                data:    {
                  salveaza: salveaza, // se creaza / se modifica persoana
                  formdata: values
                },
                timeout: 5000})
                  .done(function(data) {
                    if (data === "ok") {
                      load_interior_box(id_box_persoane, path);
                      toggleEvents('submit_formular_persoana', false);
                    } else {
                      id_box_persoane.append(data);
                    }
                  })
                  .fail(function(jqXHR, textStatus) {
                    $('span.ajax').remove();
                    if (textStatus === "error") {
                      id_box_persoane.append('<span class="error ajax">Eroare!' + jqXHR.responseText + '</span>');
                    }
                    if (textStatus === "timeout") {
                      id_box_persoane.append('<span class="error ajax">Rețeaua este lentă sau întreruptă.</span>');
                    }
                  });
            }
          });
        } else {
          id_box_persoane.off('click.persoana');
        }
      }
    };
    $.ajaxSetup({
      cache: false,
      type:  'POST'
    });
    $('nav .menu').click(function() {
      if ($(this).hasClass('selected')) {
        $(this).removeClass('selected');
        $(this).parent().next().children().hide();
        return;
      }
      $('nav .menu, nav ul').removeClass('selected');
      $('nav .option').removeClass('selected').hide();
      $(this).addClass('selected').parent().next().addClass('selected').children().show();
    });
    $('nav .option').click(function() {
      if ($(this).hasClass('selected')) {
        // daca este deja selectat nu se face nimic
        return;
      }
      $('.option').not($(this)).removeClass('selected'); // deselect all other option;
      $(this).addClass('selected').show();
      var ind = $(this).index('.option'),
          box_curent = $('.box:visible'),
          box_nou = class_box.eq(ind),
          path = pagina[ind];
      load_box(box_curent, box_nou, path);
    });
    $(document).on('ajaxStart', function() {
      $('span.ajax').remove();
    });
    $(document).keyup(function(event) {
      if (event.keyCode == 27) {
        $('#renunta').click();
      }
    });
    class_box.on('click', 'select', function() {
      $('form select').removeClass('required');
    });
    class_box.on('focus', 'input', function() {
      $(this).next('img').hide();
      $(this).addClass('normal').removeClass('required');
    });
    class_box.on('keydown', 'input#camp', function(event) {
      if (event.which == 13) {
        event.preventDefault();
        $('.nou').click();
      }
    });
    class_box.on('keyup', 'input#camp', function(event) {
      var camp_str = $(this).val(),
          $this = $(this).closest('.box').attr('id').slice(4),
          path = 'php/' + $this + '.php',
          box_current = $('#box-' + $this);
      if (event.which == 13 || event.which == 16) {   // enter sau shift
        return;
      }
      if (!camp_str.length || camp_str.length > 2 || event.which === 53) {     // 53 == '%' all records
        $.ajax({
          async:   true,
          url:     path,
          data:    {
            camp_str: camp_str
          },
          timeout: 5000})
            .done(function(raspuns) {
              box_current.children('table, p, a, div').remove().end().append(raspuns);
            })
            .fail(function(jqXHR, textStatus) {
              if (textStatus === "error") {
                box_current.append('<span class="error ajax">Eroare!' + jqXHR.responseText + '</span>');
              }
              if (textStatus === "timeout") {
                box_current.append('<span class="error ajax">Rețeaua este lentă sau întreruptă.</span>');
              }
            });
      }
    });
    class_box.on('click', '.nou', function(event) {
      event.preventDefault();
      var root = $(this).closest('.box').attr('id').slice(4),
          path = 'php/' + root + '.php',
          nume = $('#camp').val(),
          box_current = $('#box-' + root);
      $.ajax({
        async:   true,
        url:     path,
        data:    {
          formular_creare: 1,
          nume:            nume
        },
        timeout: 5000})
          .done(function(raspuns) {
            box_current.fadeOut(timp_fadeout);
            box_current.queue('fx', function() {
              $(this).empty().append(raspuns);
              $(this).dequeue('fx');
            });
            box_current.fadeIn(timp_fadein);
            box_current.queue('fx', function() {
              $(this).find('input[type="text"]').eq(0).focus();
              $(this).dequeue('fx');
            });
            toggleEvents('submit_formular_persoana', true);
          })
          .fail(function(jqXHR, textStatus) {
            if (textStatus === "error") {
              box_current.append('<span class="error ajax">Eroare!' + jqXHR.responseText + '</span>');
            }
            if (textStatus === "timeout") {
              box_current.append('<span class="error ajax">Rețeaua este lentă sau întreruptă.</span>');
            }
          });
    });
    class_box.on('click', '#renunta', function() {
      var root = $(this).closest('.box').attr('id').slice(4),
          box = $('#box-' + root),
          path = 'php/' + root + '.php';
      load_interior_box(box, path);
    });
    class_box.on('click', '#sterge', function(event) {
      event.preventDefault();
      var r, id = $('form input').eq(0).val(),
          root = $(this).closest('.box').attr('id').slice(4),
          box_current = $('#box-' + root),
          path = 'php/' + root + '.php';
      switch (root) {
        case 'companii':
          r = confirm("Sigur se șterge clientul?");
          break;
        case 'vanzatori':
          r = confirm("Sigur se șterge vânzătorul?");
          break;
        case 'persoane':
          r = confirm("Sigur se șterge persoana de contact?");
          break;
      }
      if (r == true) {
        $.ajax({
          async:   true,
          url:     path,
          data:    {
            sterge: 1,
            id:     id
          },
          timeout: 5000
        })
            .done(function(raspuns) {
              if (raspuns == 'ok') {
                load_interior_box(box_current, path);
              }
              else {
                box_current.append(raspuns);
              }
            })
            .fail(function(jqXHR, textStatus) {
              if (textStatus === "error") {
                box_current.append('<span class="error ajax">Eroare!' + jqXHR.responseText + '</span>');
              }
              if (textStatus === "timeout") {
                box_current.append('<span class="error ajax">Rețeaua este lentă sau întreruptă.</span>');
              }
            });
      }
    });
    class_box.on('keydown', 'input#camp_cauta_companie', function(event) {
      switch (event.which) {
        case 38: // key up
          event.preventDefault();
          break;
        case 40: // key up
          event.preventDefault();
          break;
        case 13: // key enter
          event.preventDefault();
          break;
        default:
          break;
      }
    });
    class_box.on('keyup', 'input#camp_cauta_companie', function(event) {
      var $text,
          tabel = $('.tabel'),
          camp = $(this),
          string = camp.val(),
          path = 'php/companii.php',
          root = $(this).closest('.box').attr('id').slice(4),
          box_current = $('#box-' + root);
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
            if ($('div.box:visible').attr('id') === "box-persoane") {
              $('input#id_companie_persoana').val(id_companie);
            }
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
              if (textStatus === "error") {
                box_current.append('<span class="error ajax">Eroare!' + jqXHR.responseText + '</span>');
              }
              if (textStatus === "timeout") {
                box_current.append('<span class="error ajax">Rețeaua este lentă sau întreruptă.</span>');
              }
            });
      } else {
        $('.tabel').fadeOut(timp_fadeout, function() {
          $('.tabel').empty();
          toggleEvents('submit_formular_persoana', true);
        });
      }
    });
    class_box.on({
      mouseup:    function() {
        var $this = $(this).children().first(), id_companie = parseInt($this.attr('id').slice(1)), $text = $this.text(); // salvez numele firmei
        if (!$(this).children('a').length) {
          $('#camp_cauta_companie').val($text).closest('tr').next().find('input:first').focus();
          if ($('div.box:visible').attr('id') === "box-persoane") {
            $('input#id_companie_persoana').val(id_companie);
          }
        }
        $('.tabel').fadeOut(timp_fadeout, function() {
          $('.tabel').empty();
          toggleEvents('submit_formular_persoana', true);
        }); // end function()
      },
      mouseenter: function() {
        $('.tabel .rec').removeClass('selected').css('background-color', '#FFFFFF');
        $(this).addClass('selected').css('background-color', '#CED5F5');
      },
      mouseleave: function() {
        $(this).removeClass('selected').css('background-color', '#FFFFFF');
      }
    }, '.tabel div');
    class_box.on('click', 'span.actiune', function(event) {
      event.preventDefault();
      var id = parseInt($(this).parent().attr('id').slice(1)),
          root = $(this).closest('.box').attr('id').slice(4),
          box_current = $('#box-' + root),
          path = 'php/' + root + '.php';
//			rezervat = encodeURIComponent($(this).parent().next().text()); // se salveaza valoare din campul de dupa ID
      $.ajax({
        async:   true,
        url:     path,
        data:    {
          formular_editare: 1,
          id:               id
        },
        timeout: 5000})
          .done(function(data) {
            if (data === 'Inexistent') {
              load_interior_box(box_current, path);
            }
            else {
              box_current.fadeOut(timp_fadeout);
              box_current.queue('fx', function() {
                $(this).empty().append(data);
                $(this).dequeue('fx');
              });
              box_current.fadeIn(timp_fadein);
              box_current.queue('fx', function() {
//							$('form input').eq(1).data("rezervat", rezervat);
                if (root == 'persoane') {
                  toggleEvents('submit_formular_persoana', true);
                }
                $(this).dequeue('fx');
              });
            }
          })
          .fail(function(jqXHR, textStatus) {
            if (textStatus === "error") {
              box_current.append('<span class="error ajax">Eroare!' + jqXHR.responseText + '</span>');
            }
            if (textStatus === "timeout") {
              box_current.append('<span class="error ajax">Rețeaua este lentă sau întreruptă.</span>');
            }
          });
    });
    id_box_companii.on('click', '#creaza_companie, #editeaza_companie ', function(event) {
      var path = pagina[8],
          flag = false,
          pattern,
          values = [],
          camp = $('form input'),
          salveaza = (this.id === 'creaza_companie') ? 1 : 0; // (1) creare, (0) modificare
      event.preventDefault();
      $('span.error').remove();
      camp.removeClass('required');
      // prelucare ID companie
      values[0] = parseInt(camp.eq(0).val());
      // prelucare nume companie
      pattern = /^.{5,100}$/;
      values[1] = camp.eq(1).val(camp.eq(1).val().trim()).val();
      if (!pattern.test(values[1])) {
        flag = true;
        camp.eq(1).addClass('required').parent().append('<span class="error">Minim 5 caractere.</span>');
      }
      // prelucare adresa companie
      pattern = /^.{5,150}$/;
      values[2] = camp.eq(2).val(camp.eq(2).val().trim()).val();
      if (!pattern.test(values[2])) {
        flag = true;
        camp.eq(2).addClass('required').parent().append('<span class="error">Minim 5 caractere.</span>');
      }
      // prelucare oras companie
      pattern = /^.{3,30}$/;
      values[3] = camp.eq(3).val(camp.eq(3).val().trim()).val();
      if (!pattern.test(values[3])) {
        flag = true;
        camp.eq(3).addClass('required').parent().append('<span class="error">Minim 3 caractere.</span>');
      }
      // prelucare tara companie
      pattern = /^.{5,50}$/;
      values[4] = camp.eq(4).val(camp.eq(4).val().trim()).val();
      if (!pattern.test(values[4])) {
        flag = true;
        camp.eq(4).addClass('required').parent().append('<span class="error">Minim 5 caractere.</span>');
      }
      if (!flag) {
        $.ajax({
          async:   true,
          url:     path,
          data:    {
            salveaza: salveaza, // se creaza | se modifica compania
            formdata: values
          },
          timeout: 5000})
            .done(function(data) {
              if (data === "ok") {
                load_interior_box(id_box_companii, path);
              } else if (data === "exista") {
                camp.eq(1).addClass('required').parent().append('<span class="error">Compania există deja în sistem</span>');
              }
              else {
                id_box_companii.append('<span class="error">Eroare:</span>' + data);
              }
            })
            .fail(function(jqXHR, textStatus) {
              $('span.ajax').remove();
              if (textStatus === "error") {
                id_box_companii.append('<span class="error ajax">Eroare!' + jqXHR.responseText + '</span>');
              }
              if (textStatus === "timeout") {
                id_box_companii.append('<span class="error ajax">Rețeaua este lentă sau întreruptă.</span>');
              }
            });
      }
    });
    id_box_vanzatori.on('click', '#creaza_vanzator, #editeaza_vanzator', function(event) {
      event.preventDefault();
      var path = pagina[9],
          flag = false,
          pattern,
          values = [],
          camp = $('form input'),
          salveaza = (this.id === 'creaza_vanzator') ? 1 : 0; // (1) creare, (0) modificare
      event.preventDefault();
      $('span.error').remove();
      camp.removeClass('required');
      // prelucare ID vanzator
      values[0] = parseInt(camp.eq(0).val());
      // prelucare nume vanzator
      pattern = /^.{3,50}$/;
      values[1] = camp.eq(1).val(camp.eq(1).val().trim()).val();
      if (!pattern.test(values[1])) {
        flag = true;
        camp.eq(1).addClass('required').parent().append('<span class="error">Minim 3 caractere.</span>');
      }
      // prelucare prenume vanzator
      pattern = /^.{3,50}$/;
      values[2] = camp.eq(2).val(camp.eq(2).val().trim()).val();
      if (!pattern.test(values[2])) {
        flag = true;
        camp.eq(2).addClass('required').parent().append('<span class="error">Minim 3 caractere.</span>');
      }
      if (!flag) {
        $.ajax({
          async:   true,
          url:     path,
          data:    {
            salveaza: salveaza, // se creaza | se modifica compania
            formdata: values
          },
          timeout: 5000})
            .done(function(data) {
              if (data === "ok") {
                load_interior_box(id_box_vanzatori, path);
              } else if (data === "exista") {
                camp.eq(1).addClass('required');
                camp.eq(2).addClass('required')
                    .parent()
                    .append('<span class="error">Combinația nume și prenume deja există.</span>')
              }
              else {
                id_box_vanzatori.append('<span class="error">Eroare:</span>' + data);
              }
            })
            .fail(function(jqXHR, textStatus) {
              $('span.ajax').remove();
              if (textStatus === "error") {
                id_box_vanzatori.append('<span class="error ajax">Eroare!' + jqXHR.responseText + '</span>');
              }
              if (textStatus === "timeout") {
                id_box_vanzatori.append('<span class="error ajax">Rețeaua este lentă sau întreruptă.</span>');
              }
            });
      }
    });
    id_box_oferta_noua.on('click', '#word', function(event) {
      // TESTARE WORD
      var _self = $(this).parent();
      var $id = $('form').find(":selected").val();
      if (!$id) {
        event.preventDefault();
        $('form select').addClass('required');
        return;
      }
      $.ajax({
        async:   false,
        url:     'php/word/genereaza_word.php', data: {
          id_persoana: $id
        },
        timeout: 5000,
        error:   function(jqXHR, textStatus) {
          if (textStatus === "timeout") {
            alert('Probleme de rețea');
            event.preventDefault();
          }
        },
        success: function() {
          _self.append('<p>Documentul s-a generat cu succes.</p>')
        }
      }); // end $.ajax
    });  // end .on
  })
})(); // ready
