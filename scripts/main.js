/**
 *  Javascript Main
 *  @author     Mihai Cristescu <mihai.cristescu@gmail.com>
 *  @version    1.0 (last revision: Feb 2014)
 *  @copyright  (c) 2014 Mihai Cristescu
 *  @license    Comercial
 */
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
        timp_fadein = 150,
        timp_fadeout = 200;

    var isInArray = function(value, array) {
      return array.indexOf(value) > -1;
    };
    var AjaxFail = function(jqXHR, textStatus, box) {
      if (textStatus === "error") {
        box.append('<span class="error ajax">Eroare!' + jqXHR.responseText + '</span>');
      }
      if (textStatus === "timeout") {
        box.append('<span class="error ajax">Rețeaua este lentă sau întreruptă.</span>');
      }
    };
    var convertDate = function(value) {
      var out,
          temp = value.split('-'),
          luni = {
            Ian: 0, Feb: 1, Mar: 2, Apr: 3,
            Mai: 4, Iun: 5, Iul: 6, Aug: 7,
            Sep: 8, Oct: 9, Noi: 10, Dec: 11
          };
      out = new Date(temp[2], luni[temp[1]], temp[0]);
      return out;
    };
    var initializare_Zebra = function() {
      $('.Zebra_DatePicker').remove();
      $('input.datepicker').Zebra_DatePicker({
        onClear:  function() {
          $('#data_expirare').val('');
        },
        onSelect: function(user_data, b, data_JS, d) {
          /**
           The callback function takes 4 parameters:
           - the date in the format specified by the “format” attribute;
           - the date in YYYY-MM-DD format
           - the date as a JavaScript Date object
           - a reference to the element the date picker is attached to, as a jQuery object*/
          var v = $('#valabilitate').val();
          var data = data_JS.addDays(v).toString('d-MMM-yyyy');
          $('#data_expirare').val(data);
        }
      });
    };
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
                  box_nou.fadeIn(timp_fadein);
                  initializare_Zebra();
                });
          })
          .fail(function(jqXHR, textStatus) {
            AjaxFail(jqXHR, textStatus, box_curent);
          });
    };

    var pozitionare_lista_sugestii = function(elem_sursa, elem_destinatie) {
      // pozitioneaza fereastra de sugestii sub campul apelat
      // elem_sursa este cel de la care se preia pozitia si dimensiunile
      // elem_destinatie este elementul care se pozitioneaza
      var Xleft = elem_sursa.position().left,
          Xtop = elem_sursa.position().top,
          Xwidth = parseInt(elem_sursa.css('width')),
          Xheight = parseInt(elem_sursa.css('height')),
          Rheight = parseInt(elem_destinatie.children(':not(#source)').first().outerHeight());
      elem_destinatie.css({
        'left':       Xleft,
        'top':        Xtop + Xheight + 1,
        'min-width':  Xwidth,
        'max-height': Rheight * 7
      });
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

    id_box_oferta_noua.on('keydown', '#valabilitate', function(event) {
      if (!isInArray(event.keyCode, [8, 9, 46, 37, 39, 96, 97, 98, 99, 100, 101, 102,
        103, 104, 105, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57])) {
        event.preventDefault();
      } else {
        if (this.selectionStart === this.selectionEnd && $(this).val().length === 3 && !isInArray(event.keyCode, [8, 9 , 46, 37, 39])) {
          event.preventDefault();
        }
      }
    });

    id_box_oferta_noua.on('input', '#valabilitate', function() {
      var data = convertDate($('input.datepicker').val());
      var valabilitate = $('#valabilitate').val();
      var b = data.addDays(valabilitate).toString('d-MMM-yyyy');
      $('#data_expirare').val(b);
    });

    id_box_oferta_noua.on('keydown', '#valoare_oferta', function(event) {
      if (!isInArray(event.keyCode, [8, 9, 46, 37, 39, 96, 97, 98, 99, 100, 101, 102,
        103, 104, 105, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57])) {
        event.preventDefault();
      } else {
        if (this.selectionStart === this.selectionEnd && $(this).val().length === 12 && !isInArray(event.keyCode, [8, 9 , 46, 37, 39])) {
          event.preventDefault();
        }
      }
    });

    id_box_oferta_noua.on('input', '#valoare_oferta', function() {

    });

    class_box.on('click', 'select', function() {
      $('form select').removeClass('required');
    });

    class_box.on('focus', 'input', function() {
      $(this).next('img').hide();
      $(this).removeClass('required');
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
          box_curent = $('#box-' + $this);
      if (event.which == 13 || event.which == 16) {   // enter sau shift
        return;
      }
      if (!camp_str.length || camp_str.length > 2 || event.which === 53) {     // 53 == '%' toate
        $.ajax({
          async:   true,
          url:     path,
          data:    {
            camp_str: camp_str
          },
          timeout: 5000})
            .done(function(raspuns) {
              box_curent.children('table, p, a, div').remove().end().append(raspuns);
            })
            .fail(function(jqXHR, textStatus) {
              AjaxFail(jqXHR, textStatus, box_curent);
            });
      }
    });

    class_box.on('click', '.nou', function(event) {
      event.preventDefault();
      var root = $(this).closest('.box').attr('id').slice(4),
          path = 'php/' + root + '.php',
          nume = $('#camp').val(),
          box_curent = $('#box-' + root);
      $.ajax({
        async:   true,
        url:     path,
        data:    {
          formular_creare: 1,
          nume:            nume
        },
        timeout: 5000})
          .done(function(raspuns) {
            box_curent.fadeOut(timp_fadeout);
            box_curent.queue('fx', function() {
              $(this).empty().append(raspuns);
              $(this).dequeue('fx');
            });
            box_curent.fadeIn(timp_fadein);
            box_curent.queue('fx', function() {
              $(this).find('input[type="text"]').eq(0).focus();
              $(this).dequeue('fx');
            });
          })
          .fail(function(jqXHR, textStatus) {
            AjaxFail(jqXHR, textStatus, box_curent);
          });
    });

    class_box.on('click', '#renunta', function() {
      var root = $(this).closest('.box').attr('id').slice(4),
          box = $('#box-' + root),
          path = 'php/' + root + '.php';
      load_box(box, box, path);
    });

    class_box.on('click', '#sterge', function(event) {
      event.preventDefault();
      var r, id = $('form input').eq(0).val(),
          root = $(this).closest('.box').attr('id').slice(4),
          box_curent = $('#box-' + root),
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
                load_box(box_curent, box_curent, path);
              }
              else {
                box_curent.append(raspuns);
              }
            })
            .fail(function(jqXHR, textStatus) {
              AjaxFail(jqXHR, textStatus, box_curent);
            });
      }
    });

    class_box.on({
      keydown: function(event) {
        if (isInArray(event.which, [13, 38, 40])) event.preventDefault();
      },
      keyup:   function(event) {
        var $text,
            id,
            camp = $(this),
            path = 'php/companii.php',
            lista = $('#lista_companii'),
            string = camp.val().trim(),
            root = camp.closest('.box').attr('id').slice(4),
            box_curent = $('#box-' + root);

        switch (event.which) {
          case 38:
            // key up
            if (lista.find('.noresults').length) {
              return;
            }
            if (lista.find('.selected').length) {
              if (lista.find('.rec:first.selected').length) {
                lista.find('.selected').removeClass('selected');
                lista.find('.rec:last').addClass('selected');
                $text = lista.find('.rec:last').children().first().text();
                camp.val($text).focus();
                return;
              }
              lista.find('.selected').removeClass('selected').prev().addClass('selected');
              $text = lista.find('.selected').children().first().text();
              camp.val($text).focus();
              return;
            } else {
              $text = lista.find('.rec:last').addClass('selected').children().first().text();
              camp.val($text).focus();
              return;
            }

          case 40:
            // key down
            if (lista.find('.noresults').length) {
              return;
            }
            if (lista.find('.selected').length) {
              if (lista.find('.rec:last.selected').length) {
                lista.find('.selected').removeClass('selected');
                lista.find('.rec:first').addClass('selected');
                $text = lista.find('.rec:first').children().first().text();
                camp.val($text).focus();
                return;
              }
              lista.find('.selected').removeClass('selected').next().addClass('selected');
              $text = lista.find('.selected').children().first().text();
              camp.val($text).focus();
              return;
            } else { // nu e nimic selectat
              $text = lista.find('.rec:nth-child(2)').addClass('selected').children().first().text();
              camp.val($text).focus();
              return;
            }

          case 13: // key enter
            var selected = lista.find('.selected');
            if (lista.is(':not(:visible)').length) {
              return;
            }
            if (selected.length) {
              $text = selected.children().first().text();
              camp.val($text);
              id = parseInt(selected.children().first().attr('id').slice(1));
              lista.hide().promise().done(function() {
                $('input').eq(camp.val($text).index('input') + 1).focus();
                camp.attr('data-id', id);
                $('#select_persoana').val('').attr('data-id', '');
                lista.empty();
              });
              return;
            } else {
              return;
            }
          case 8:
            camp.attr('data-id', '');
            break;
          case 46:
            camp.attr('data-id', '');
            break;
          default:
            break;
        }
        if (string.length > 2) {
          $.ajax({
            async:   true,
            url:     path,
            data:    { select_companie: string  },
            timeout: 5000})
              .done(function(raspuns) {
                lista.html(raspuns).show();
                pozitionare_lista_sugestii(camp, lista);
                camp.attr('data-id', '');
              })
              .fail(function(jqXHR, textStatus) {
                AjaxFail(jqXHR, textStatus, box_curent);
              });
        } else {
          lista.hide().promise().done(function() {
            lista.empty();
          });
        }
      },
      mouseup: function() {
        $('#lista_companii, #lista_persoane, #lista_stadii').hide();
      }
    }, 'input#select_companie');

    class_box.on({
      mouseenter: function() {
        $('#lista_companii').find('.rec').removeClass('selected');
        $(this).addClass('selected');
      },
      mouseleave: function() {
        $(this).removeClass('selected');
      },
      mouseup:    function() {
        var $this = $(this).children().first(),
            id = parseInt($this.attr('id').slice(1)),
            $text = $this.text(),
            lista = $('#lista_companii'),
            camp = $('#select_companie');
        lista.hide().promise().done(function() {
          $('input').eq(camp.val($text).index('input') + 1).focus();
          camp.attr('data-id', id);
          $('#select_persoana').val('').attr('data-id', '');
          lista.empty();
        });
      }
    }, '#lista_companii .rec');

    class_box.on({
      mouseup: function() {
        var lista = $('#lista_persoane'),
            path = 'php/persoane.php',
            camp = $(this),
            id_companie = $('#select_companie').attr('data-id'),
            root = $(this).closest('.box').attr('id').slice(4),
            box_curent = $('#box-' + root);
        $('#lista_companii, #lista_persoane, #lista_stadii').hide();
        if (!id_companie) {
          camp.val('');
          $('#select_companie').focus();
          return;
        } else if (!lista.is(':visible')) {
          $.ajax({
            async:   true,
            url:     path,
            data:    {
              select_persoana: 1,
              id_companie:     id_companie
            },
            timeout: 5000})
              .done(function(raspuns) {
                lista.html(raspuns).show();
                pozitionare_lista_sugestii(camp, lista);
                $(this).attr('data-id', '');
              })
              .fail(function(jqXHR, textStatus) {
                AjaxFail(jqXHR, textStatus, box_curent);
              });
        } else {
          lista.hide();
        }
      }
    }, 'input#select_persoana');

    class_box.on({
      mouseenter: function() {
        $('#lista_persoane').find('.rec').removeClass('selected');
        $(this).addClass('selected');
      },
      mouseleave: function() {
        $(this).removeClass('selected');
      },
      mouseup:    function() {
        var $this = $(this).children().first(),
            id = parseInt($this.attr('id').slice(1)),
            $text = $this.text(),
            lista = $('#lista_persoane'),
            camp = $('#select_persoana');
        lista.hide().promise().done(function() {
          $('input').eq(camp.val($text).index('input') + 1).focus();
          camp.attr('data-id', id);
          lista.empty();
        });
      }
    }, '#lista_persoane .rec');

    class_box.on({
      mouseup: function() {
        var lista = $('#lista_vanzatori'),
            path = 'php/vanzatori.php',
            camp = $(this),
            root = $(this).closest('.box').attr('id').slice(4),
            box_curent = $('#box-' + root);
        $('#lista_companii, #lista_persoane, #lista_stadii').hide();
        if (!lista.is(':visible')) {
          $.ajax({
            async:   true,
            url:     path,
            data:    { select_vanzator: 1 },
            timeout: 5000})
              .done(function(raspuns) {
                lista.html(raspuns).show();
                pozitionare_lista_sugestii(camp, lista);
                $(this).attr('data-id', '');
              })
              .fail(function(jqXHR, textStatus) {
                AjaxFail(jqXHR, textStatus, box_curent);
              });
        } else {
          lista.hide();
        }
      }
    }, 'input#select_vanzator');

    class_box.on({
      mouseenter: function() {
        $('#lista_vanzatori').find('.rec').removeClass('selected');
        $(this).addClass('selected');
      },
      mouseleave: function() {
        $(this).removeClass('selected');
      },
      mouseup:    function() {
        var $this = $(this).children().first(),
            id = parseInt($this.attr('id').slice(1)),
            $text = $this.text(),
            lista = $('#lista_vanzatori'),
            camp = $('#select_vanzator');
        lista.hide().promise().done(function() {
          $('input').eq(camp.val($text).index('input') + 1).focus();
          camp.attr('data-id', id);
          lista.empty();
        });
      }
    }, '#lista_vanzatori .rec');

    class_box.on({
      mouseup: function() {
        var lista = $('#lista_stadii'),
            camp = $(this);
        $('#lista_companii, #lista_persoane, #lista_vanzatori').hide();
        if (!lista.is(':visible')) {
          lista.show();
          pozitionare_lista_sugestii(camp, lista);
        } else {
          lista.hide();
        }
      }
    }, 'input#select_stadiu');

    class_box.on({
      mouseenter: function() {
        $('#lista_stadii').find('.rec').removeClass('selected');
        $(this).addClass('selected');
      },
      mouseleave: function() {
        $(this).removeClass('selected');
      },
      mouseup:    function() {
        var $this = $(this).children().first(),
            id = parseInt($this.attr('id').slice(1)),
            $text = $this.text(),
            lista = $('#lista_stadii'),
            camp = $('#select_stadiu');
        lista.hide().promise().done(function() {
          $('input').eq(camp.val($text).index('input') + 1).focus();
          camp.attr('data-id', id);
        });
      }
    }, '#lista_stadii .rec');

    class_box.on('click', 'span.actiune', function(event) {
      event.preventDefault();
      var id = parseInt($(this).parent().attr('id').slice(1)),
          root = $(this).closest('.box').attr('id').slice(4),
          box_curent = $('#box-' + root),
          path = 'php/' + root + '.php';
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
              load_box(box_curent, box_curent, path);
            }
            else {
              box_curent.fadeOut(timp_fadeout);
              box_curent.queue('fx', function() {
                $(this).empty().append(data);
                $(this).dequeue('fx');
              });
              box_curent.fadeIn(timp_fadein);
              box_curent.queue('fx', function() {
                $(this).dequeue('fx');
              });
            }
          })
          .fail(function(jqXHR, textStatus) {
            AjaxFail(jqXHR, textStatus, box_curent);
          });
    });

    id_box_oferta_noua.on('click', '#creaza_oferta', function(event) {
      var path = pagina[1],
          flag = false,
          pattern,
          values = [],
          camp = $('form input'),
          salveaza = (this.id == "creaza_oferta") ? 1 : 0;
      event.preventDefault();
      $('span.error').remove();
      camp.removeClass('required');
      values[0] = parseInt(camp.eq(0).val()) || null;

      // Prelucrare nume
      pattern = /^.{3,200}$/;
      values[1] = camp.eq(1).val(camp.eq(1).val().trim()).val();
      if (!pattern.test(values[1])) {
        flag = true;
        camp.eq(1).addClass('required').closest('td').append('<span class="error">Minim 3 caractere.</span>');
      }

      // prelucrare data
      values[2] = camp.eq(2).val();
      if (!values[2].length) {
        flag = true;
        camp.eq(2).addClass('required').closest('td').append('<span class="error">Selectați data ofertei.</span>');
      }

      // prelucrare valabilitate
      values[3] = camp.eq(3).val();
      if (!values[3].length) {
        flag = true;
        camp.eq(3).addClass('required').closest('td').append('<span class="error">Introduceți valabilitatea ofertei.</span>');
      }

      // prelucrare vanzator
      values[4] = camp.eq(4).attr('data-id');
      if (!values[4].length) {
        flag = true;
        camp.eq(4).addClass('required').closest('td').append('<span class="error">Selectați persoana resonsabilă de vânzări.</span>');
      }

      // citire data expirare
      values[5] = camp.eq(5).val();

      // prelucrare stadiu
      values[6] = camp.eq(6).attr('data-id');
      if (!values[6].length) {
        flag = true;
        camp.eq(6).addClass('required').closest('td').append('<span class="error">Selectați stadiul ofertei.</span>');
      }

      // citire raportare volum
      values[7] = camp.eq(7).val();

      // prelucrare companie
      values[8] = camp.eq(8).attr('data-id');
      if (!values[8].length) {
        flag = true;
        camp.eq(8).addClass('required').closest('td').append('<span class="error">Selectați compania căreia i se adresează oferta.</span>');
      }

      // prelucrare persoana
      values[9] = camp.eq(9).attr('data-id');
      if (!values[9].length) {
        flag = true;
        camp.eq(9).addClass('required').closest('td').append('<span class="error">Selectați persoana de contact.</span>');
      }

      // prelucrare valoare oferta
      values[10] = parseFloat(camp.eq(10).val());
      if (!values[10]) {
        flag = true;
        camp.eq(10).addClass('required').closest('td').append('<span class="error">Introduceți valoarea ofertei.</span>');
      }

      if (!flag) {
        console.dir(values);
      }
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
                load_box(id_box_companii, id_box_companii, path);
              } else if (data === "exista") {
                camp.eq(1).addClass('required')
                    .parent()
                    .append('<span class="error">Compania există deja.</span>');
              }
              else {
                id_box_companii.append('<span class="error">Eroare:</span>' + data);
              }
            })
            .fail(function(jqXHR, textStatus) {
              AjaxFail(jqXHR, textStatus, box_curent);
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
                load_box(id_box_vanzatori, id_box_vanzatori, path);
              } else if (data === "exista") {
                camp.eq(1).addClass('required');
                camp.eq(2).addClass('required')
                    .parents('table')
                    .prepend('<span class="error">Combinația nume și prenume există deja.</span>')
              }
              else {
                id_box_vanzatori.append('<span class="error">Eroare:</span>' + data);
              }
            })
            .fail(function(jqXHR, textStatus) {
              console.log('Box_curent')
              AjaxFail(jqXHR, textStatus, id_box_vanzatori);
            });
      }
    });

    id_box_persoane.on('click', '#creaza_persoana, #editeaza_persoana', function(event) {
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
      values[8] = camp.eq(8).attr('data-id');
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
                load_box(id_box_persoane, id_box_persoane, path);
              } else if (data === "exista") {
                camp.filter(function(i) {
                  return $.inArray(i, [1, 2, 8]) > -1;
                }).addClass('required');
                $('table').prepend('<span class="error">Combinația nume, prenume şi companie există deja.</span>');
              } else {
                id_box_persoane.append('<span class="error">Eroare:</span>' + data);
              }
            })
            .fail(function(jqXHR, textStatus) {
              AjaxFail(jqXHR, textStatus, id_box_persoane);
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
})();
