/**
 *  Javascript Main
 *  @author     Mihai Cristescu <mihai.cristescu@gmail.com>
 *  @version    1.0 (last revision: Feb 2014)
 *  @copyright  (c) 2014 Mihai Cristescu
 *  @license    Comercial
 */
(function() {
  $(document).ready(function() {
    var nav = $("nav"),
        class_box = $(".box"),
        id_box_companii = $("#box_companii"),
        id_box_vanzatori = $("#box_vanzatori"),
        id_box_persoane = $("#box_persoane"),
        id_box_oferte = $("#box_oferte"),
        obj_pag = {
          oferte:               {
            id_box:  "#box_oferte",
            path:    "php/oferte.php",
            optiuni: { listare: 1}
          },
          oferta_noua:          {
            id_box:  "#box_oferte",
            path:    "php/oferte.php",
            optiuni: { formular_creare: 1 }
          },
          comenzi:              {
            id_box:  "#box_comenzi",
            path:    "php/comenzi.php",
            optiuni: { listare: 1}
          },
          comanda_noua:         {
            id_box:  "#box_comanda_noua",
            path:    "php/comanda_noua.php",
            optiuni: { formular_creare: 1 }
          },
          statistici_oferte:    {
            id_box:  "#box_statistici_ofertare",
            path:    "php/stats_ofertare.php",
            optiuni: {}
          },
          statistici_comenzi:   {
            optiuni: {}
          },
          statistici_clienti:   {
            optiuni: {}
          },
          statistici_furnizori: {
            optiuni: {}
          },
          companii:             {
            id_box:  "#box_companii",
            path:    "php/companii.php",
            optiuni: {}
          },
          vanzatori:            {
            id_box:  "#box_vanzatori",
            path:    "php/vanzatori.php",
            optiuni: {}
          },
          persoane:             {
            id_box:  "#box_persoane",
            path:    "php/persoane.php",
            optiuni: {}
          }
        },
        timp_fadein = 50,
        timp_fadeout = 80,
        isInArray = function(value, array) {
          return array.indexOf(value) > -1;
        },
        AjaxFail = function(jqXHR, textStatus) {
          var box = $(".box:visible");
          if (textStatus === "error") {
            box.append('<span class="error ajax">A intervenit o eroare!<br/>' + jqXHR.responseText + "</span>");
          }
          if (textStatus === "timeout") {
            box.append('<span class="error ajax">Rețeaua este lentă sau întreruptă.</span>');
          }
        },
        convertDate = function(value) {
          var out,
              temp = value.split("-"),
              luni = {
                Ian: 0, Feb: 1, Mar: 2, Apr: 3,
                Mai: 4, Iun: 5, Iul: 6, Aug: 7,
                Sep: 8, Oct: 9, Noi: 10, Dec: 11
              };
          out = new Date(temp[2], luni[temp[1]], temp[0]);
          return out;
        },
        initializare_Zebra = function() {
          var elem = $("input.datepicker"),
              exp = $("#data_expirare");
          $(".Zebra_DatePicker").remove();
          if (elem.length) {
            elem.Zebra_DatePicker({
              onClear:  function() {
                elem.attr("data-data", "");
                if (exp.length) {
                  $("#data_expirare").val("").attr("data-data", "");
                }
              },
              onSelect: function(user_data, date_MSQL, data_JS, element) {
                /**
                 The callback function takes 4 parameters:
                 - the date in the format specified by the “format” attribute;
                 - the date in YYYY-MM-DD format
                 - the date as a JavaScript Date object
                 - a reference to the element the date picker is attached to, as a jQuery object*/
                var v = $("#valabilitate").val(),
                    data_expirare = data_JS.addDays(v);
                element.attr("data-data", date_MSQL);
                if (exp.length) {
                  exp.val(data_expirare.toString("d-MMM-yyyy"))
                      .attr("data-data", data_expirare.toString("yyyy-MM-dd"));
                }
              }
            });
          }
        },
        load_box = function(box_curent, box_nou, path, optiuni) {
          /* se incarca box-ul unei optiuni noi din meniu
           implemantare box.load cu fadeout/fadein
           intre box-ul optiunii curente si box-ul optiunii noi care se incarca */

          optiuni.width = parseInt(box_nou.css("width")); // se trimite si latimea ferestrei noi
          $.ajax({
            async:   true,
            url:     path,
            timeout: 5000,
            data:    {
              optiuni: optiuni
            }
          })
              .done(function(data) {
                box_curent.fadeOut(timp_fadeout).promise()
                    .done(function() {
                      box_curent.empty();
                    })
                    .done(function() {
                      box_nou.html(data);
                    })
                    .done(function() {
                      box_nou.fadeIn(timp_fadein);
                      initializare_Zebra();
//                      $("input").eq(0).focus();
                    });
              })
              .fail(function(jqXHR, textStatus) {
                AjaxFail(jqXHR, textStatus);
              });
        },
        pozitionare_lista_sugestii = function(elem_sursa, elem_destinatie) {
          /* pozitioneaza fereastra de sugestii sub campul apelat
           elem_sursa este cel de la care se preia pozitia si dimensiunile
           elem_destinatie este elementul care se pozitioneaza */
          var Xleft = elem_sursa.position().left,
              Xtop = elem_sursa.position().top,
              Xwidth = parseInt(elem_sursa.css("width")),
              Xheight = parseInt(elem_sursa.css("height")),
              Rheight = parseInt(elem_destinatie.children(":not(#source)").first().outerHeight());
          elem_destinatie.css({
            "left":       Xleft,
            "top":        Xtop + Xheight + 1,
            "min-width":  Xwidth,
            "max-height": Rheight * 7
          });
        };

    $.ajaxSetup({
      cache: false,
      type:  "POST"
    });

    $(document).on({
      ajaxStart: function() {
        console.log("Trigger: ajaxstart");
        $("span.ajax").remove();
      },
      mousedown: function(event) {
        console.log("Trigger: mousedown all");
        var target = $(event.target);
        if (typeof target.closest(".ddm").attr("id") === "undefined" && !target.hasClass("deschis")) {
          $(".ddm").hide();
        } else {
          event.preventDefault();
        }
      },
      keyup:     function(event) {
        console.log("Trigger: keyup");
        var renunta = $("#renunta");
        if (event.keyCode === 27 && renunta.length) {
          renunta.trigger("mouseup");
        }
      }
    });

    nav.on({
      mouseup:   function() {
        console.log("Trigger: mouseup .menu");
        if ($(this).hasClass("selected")) {
          $(this).removeClass("selected");
          $(this).parent().next().children().hide();
          return;
        }
        $("nav .menu, nav ul").removeClass("selected");
        $("nav .option").removeClass("selected").hide();
        $(this).addClass("selected").parent().next().addClass("selected").children().show();
      },
      mousedown: function(event) {
        console.log("Trigger: mousedown .menu");
        console.clear();
        event.preventDefault();
      }
    }, ".menu");

    nav.on({
      mouseup:   function() {
        console.log("Trigger: mouseup: .option");
        $(".option").removeClass("selected"); // deselect all other option;
        $(this).addClass("selected").show();
        var box_curent = $(".box:visible"),
            id = this.id,
            box_nou = $(obj_pag[id].id_box),
            path = obj_pag[id].path,
            optiuni = obj_pag[id].optiuni;
        load_box(box_curent, box_nou, path, optiuni);
      },
      mousedown: function(event) {
        console.log("Trigger: mousedown .option");
        event.preventDefault();
      }
    }, ".option");

    class_box.on({
      mouseup: function() {
        console.log("Trigger: mouseup #word");
        var $id = $("#select_persoana").attr("data-id");
        id_box_oferte.find(".error, .mesaj, p, a").remove();
        if (!$id) {
          id_box_oferte.append('<span class="error">Alegeţi o companie şi o persoană de contact.</p>');
          return;
        }
        $.ajax({
          async:   false,
          url:     "php/word/genereaza_word.php",
          data:    { id_persoana: $id },
          timeout: 5000})
            .done(function() {
              $("#box_oferte")
                  .append('<span class="mesaj">Documentul s-a generat cu succes.&nbsp</span>')
                  .append('<a href="/php/word/Oferta.docx">Descarcă aici.</a>');
            })
            .fail(function(jqXHR, textStatus) {
              AjaxFail(jqXHR, textStatus);
            });
      }
    }, "#word");

    class_box.on({
      focus: function(event) {
        console.log("Trigger: focus input");
        $(this).removeClass("required");
        if ($(this).hasClass("deschis")) {
          event.preventDefault();
        }
      }
    }, "input");

    class_box.on({
      keydown: function(event) {
        console.log("Trigger: keydown:input#camp");
        if (event.which == 13) {
          event.preventDefault();
          $(".nou").trigger("mouseup");
        }
      },
      keyup:   function(event) {
        console.log("Trigger: keyup:input#camp");
        var camp_str = $(this).val(),
            root = $(this).closest(".box").attr("id").slice(4),
            box_curent = $("#box_" + root),
            path = "php/" + root + ".php";
        if (event.which == 13 || event.which == 16) {   // enter sau shift
          return;
        }
        if ((!camp_str.length && event.which !== 27) || camp_str.length > 2 || event.which === 53) {     // 53 == "%" toate
          $.ajax({
            async:   true,
            url:     path,
            data:    {
              camp_str: camp_str
            },
            timeout: 5000})
              .done(function(raspuns) {
                box_curent.children("span, table, p, a, div").remove().end().append(raspuns);
              })
              .fail(function(jqXHR, textStatus) {
                AjaxFail(jqXHR, textStatus);
              });
        }
      }
    }, "input#camp");

    class_box.on({
      mousedown: function(event) {
        event.preventDefault();
      },
      mouseup:   function() {
        var root = $(this).closest(".box").attr("id").slice(4),
            path = "php/" + root + ".php",
            box_curent = $("#box_" + root);

        $.ajax({
          async:   true,
          url:     path,
          data:    {
            optiuni: {formular_creare: 1}
          },
          timeout: 5000})
            .done(function(raspuns) {
              box_curent.fadeOut(timp_fadeout);
              box_curent.queue("fx", function() {
                $(this).empty().append(raspuns);
                $(this).dequeue("fx");
              });
              box_curent.fadeIn(timp_fadein);
              box_curent.queue("fx", function() {
                $(this).find('input[type="text"]').eq(0).focus();
                $(this).dequeue("fx");
              });
            })
            .fail(function(jqXHR, textStatus) {
              AjaxFail(jqXHR, textStatus);
            });
      }
    }, ".nou");

    class_box.on({
      mousedown: function(event) {
        event.preventDefault();
      },
      mouseup:   function() {
        console.log("Trigger: mouseup #renunta");
        var root = $(this).closest(".box").attr("id").slice(4),
            box = $(".box:visible"),
            path = "php/" + root + ".php",
            optiuni = obj_pag[root].optiuni;
        if (root === "oferte") {
          $("#oferte").trigger("mouseup");
          return;
        }
        load_box(box, box, path, optiuni);
      }
    }, "#renunta");

    class_box.on({
      mousedown: function(event) {
        event.preventDefault();
      },
      mouseup:   function() {
        console.log("Trigger: mouseup:#sterge");

        var r, id = $("form input").eq(0).val(),
            root = $(this).closest(".box").attr("id").slice(4),
            box_curent = $("#box_" + root),
            path = "php/" + root + ".php",
            optiuni = obj_pag[root].optiuni;
        switch (root) {
          case "oferte":
            r = confirm("Sigur se șterge oferta?");
            break;
          case "companii":
            r = confirm("Sigur se șterge clientul?");
            break;
          case "vanzatori":
            r = confirm("Sigur se șterge vânzătorul?");
            break;
          case "persoane":
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
                if (raspuns == "ok") {
                  load_box(box_curent, box_curent, path, optiuni);
                }
                else {
                  var html = '<span class="error">Nu este permisă ștergerea.</span>';

                  switch (root) {
                    case "companii":
                      html += '<span class="error">Compania este folosită la oferte sau persoane de contact.</span>';
                      break;
                    case "vanzatori":
                      html += '<span class="error">Vânzătorul este folosit la oferte.</span>';
                      break;
                    case "persoane":
                      html += '<span class="error">Persoana de contact este folosită la oferte.</span>';
                      break;
                  }
                  box_curent.append(html);
//                  box_curent.append(raspuns);
                }
              })
              .fail(function(jqXHR, textStatus) {
                AjaxFail(jqXHR, textStatus);
              });
        }
      }
    }, "#sterge");

    class_box.on({
      keydown: function(event) {
        if (isInArray(event.which, [13, 38, 40])) event.preventDefault();
      },
      keyup:   function(event) {
        var $text,
            id,
            camp = $(this),
            path = "php/companii.php",
            lista = $("#lista_companii"),
            string = camp.val().trim();
        switch (event.which) {
          case 38:
            // key up
            if (lista.find(".noresults").length) {
              return;
            }
            if (lista.find(".selected").length) {
              if (lista.find(".rec:first.selected").length) {
                lista.find(".selected").removeClass("selected");
                lista.find(".rec:last").addClass("selected");
                $text = lista.find(".rec:last").children().first().text();
                camp.val($text).focus();
                return;
              }
              lista.find(".selected").removeClass("selected").prev().addClass("selected");
              $text = lista.find(".selected").children().first().text();
              camp.val($text).focus();
              return;
            } else {
              $text = lista.find(".rec:last").addClass("selected").children().first().text();
              camp.val($text).focus();
              return;
            }

          case 40:
            // key down
            if (lista.find(".noresults").length) {
              return;
            }
            if (lista.find(".selected").length) {
              if (lista.find(".rec:last.selected").length) {
                lista.find(".selected").removeClass("selected");
                lista.find(".rec:first").addClass("selected");
                $text = lista.find(".rec:first").children().first().text();
                camp.val($text).focus();
                return;
              }
              lista.find(".selected").removeClass("selected").next().addClass("selected");
              $text = lista.find(".selected").children().first().text();
              camp.val($text).focus();
              return;
            } else { // nu e nimic selectat
              $text = lista.find(".rec:nth-child(2)").addClass("selected").children().first().text();
              camp.val($text).focus();
              return;
            }

          case 13: // key enter
            var selected = lista.find(".selected");
            if (lista.is(":not(:visible)").length) {
              return;
            }
            if (selected.length) {
              $text = selected.children().first().text();
              camp.val($text);
              id = parseInt(selected.children().first().attr("id").slice(1));
              lista.hide().promise().done(function() {
                $("input").eq(camp.val($text).index("input") + 1).focus();
                camp.attr("data-id", id);
                $("#select_persoana").val("").attr("data-id", "");
                lista.empty();
              });
              return;
            } else {
              return;
            }
          case 8:
            camp.attr("data-id", "");
            break;
          case 46:
            camp.attr("data-id", "");
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
                lista.html(raspuns).stop(true, false).fadeIn("fast").scrollTop(0);
                pozitionare_lista_sugestii(camp, lista);
                camp.attr("data-id", "");
              })
              .fail(function(jqXHR, textStatus) {
                AjaxFail(jqXHR, textStatus);
              });
        } else {
          lista.hide().promise().done(function() {
            lista.empty();
          });
        }
      }
    }, "#select_companie");

    class_box.on({
      mousedown: function(event) {
        console.log("Trigger: mousedown #select_persoana");
        event.preventDefault();
        var lista = $("#lista_persoane"),
            path = "php/persoane.php",
            camp = $(this),
            camp_companie = $("#select_companie"),
            id_companie = camp_companie.attr("data-id");
        camp.focus();
        $(".ddm").not(lista).hide();
        if (!id_companie) {
          camp_companie.focus();
          camp.val("");
        } else if (!lista.is(":visible")) {
          $.ajax({
            async:   true,
            url:     path,
            data:    {
              select_persoana: 1,
              id_companie:     id_companie
            },
            timeout: 5000})
              .done(function(raspuns) {
                lista.html(raspuns).stop(true, false).fadeIn("fast");
                pozitionare_lista_sugestii(camp, lista);
                $(this).attr("data-id", "").addClass("deschis");
              })
              .fail(function(jqXHR, textStatus) {
                AjaxFail(jqXHR, textStatus);
              });
        } else {
          lista.hide();
          $(this).blur().removeClass("deschis");
        }
      }
    }, "#select_persoana");

    class_box.on({
      mousedown: function(event) {
        console.log("Trigger: mousedown #select_vanzator");
        event.preventDefault();
        var lista = $("#lista_vanzatori"),
            path = "php/vanzatori.php",
            camp = $(this);
        camp.focus();
        $(".ddm").not(lista).hide();
        if (!lista.is(":visible")) {
          $.ajax({
            async:   true,
            url:     path,
            data:    { select_vanzator: 1 },
            timeout: 5000})
              .done(function(raspuns) {
                lista.html(raspuns).stop(true, false).fadeIn("fast").scrollTop(0);
                pozitionare_lista_sugestii(camp, lista);
                $(this).attr("data-id", "").addClass("deschis");
              })
              .fail(function(jqXHR, textStatus) {
                AjaxFail(jqXHR, textStatus);
              });
        } else {
          lista.hide().empty();
          $(this).blur().removeClass("deschis");
        }
      }
    }, "#select_vanzator");

    class_box.on({
      mousedown: function(event) {
        console.log("Trigger: mousedown #select_stadiu");
        event.preventDefault();
        var lista = $("#lista_stadii"),
            camp = $(this);
        camp.focus();
        $(".ddm").not(lista).hide();
        if (!lista.is(":visible")) {
          lista.fadeIn("fast");
          $(this).addClass("deschis");
          pozitionare_lista_sugestii(camp, lista);
        } else {
          lista.hide();
          $(this).removeClass("deschis").blur();
        }
      }
    }, "#select_stadiu");

    class_box.on({
      mousedown: function(event) {
        console.log("Trigger: mousedown #select_sex");
        event.preventDefault();
        var lista = $("#lista_sex"),
            camp = $(this);
        camp.focus();
        $(".ddm").not(lista).hide();
        if (!lista.is(":visible")) {
          lista.stop(true, false).fadeIn("fast");
          $(this).addClass("deschis");
          pozitionare_lista_sugestii(camp, lista);
        } else {
          lista.hide();
          $(this).blur().removeClass("deschis");
        }
      }
    }, "#select_sex");

    class_box.on({
      mousedown: function(event) {
        console.log("Trigger: mousedown #select_an");
        event.preventDefault();
        var lista = $("#lista_ani"),
            camp = $(this);
        camp.focus();
        $(".ddm").not(lista).hide();
        if (!lista.is(":visible")) {
          lista.stop(true, false).fadeIn("fast");
          $(this).addClass("deschis");
          pozitionare_lista_sugestii(camp, lista);
        } else {
          lista.hide();
          $(this).blur().removeClass("deschis");
        }
      }
    }, "#select_an");

    class_box.on({
      mouseenter: function() {
        $("#lista_companii").find(".rec").removeClass("selected");
        $(this).addClass("selected");
      },
      mouseleave: function() {
        $(this).removeClass("selected");
      },
      mouseup:    function(event) {
        console.log("Trigger: mouseup #lista_companii .rec");
        event.preventDefault();
        var $this = $(this).children().first(),
            id = parseInt($this.attr("id").slice(1)),
            $text = $this.text(),
            lista = $("#lista_companii"),
            camp = $("#select_companie");
        lista.hide().promise().done(function() {
          camp.attr("data-id", id).val($text);
          $("#select_persoana").val("").attr("data-id", "");
          lista.empty();
          if ($("#formular_filtre").length) {
            camp.blur().trigger("aplica");
          } else {
            $("input").eq(camp.index("input") + 1).focus();
          }
        });
      }
    }, "#lista_companii .rec");

    class_box.on({
      mouseenter: function() {
        $("#lista_persoane").find(".rec").removeClass("selected");
        $(this).addClass("selected");
      },
      mouseleave: function() {
        $(this).removeClass("selected");
      },
      mouseup:    function() {
        var $this = $(this).children().first(),
            id = parseInt($this.attr("id").slice(1)),
            $text = $this.text(),
            lista = $("#lista_persoane"),
            camp = $("#select_persoana");
        lista.hide().promise().done(function() {
          $("input").eq(camp.val($text).index("input") + 1).focus();
          camp.attr("data-id", id);
          lista.empty();
        });
      }
    }, "#lista_persoane .rec");

    class_box.on({
      mouseenter: function() {
        $("#lista_vanzatori").find(".rec").removeClass("selected");
        $(this).addClass("selected");
      },
      mouseleave: function() {
        $(this).removeClass("selected");
      },
      mouseup:    function(event) {
        console.log("Trigger: mouseup #lista_vanzatori .rec");
        event.preventDefault();
        var $this = $(this).children().first(),
            id = parseInt($this.attr("id").slice(1)),
            $text = $this.text(),
            lista = $("#lista_vanzatori"),
            camp = $("#select_vanzator");
        lista.hide().promise()
            .done(function() {
              camp.attr("data-id", id).val($text);
              lista.empty();
              if ($("#formular_filtre").length) {
                camp.blur().trigger("aplica");
              } else {
                $("input").eq(camp.index("input") + 1).focus();
              }
            });
      }
    }, "#lista_vanzatori .rec");

    class_box.on({
      mouseenter: function() {
        $("#lista_stadii").find(".rec").removeClass("selected");
        $(this).addClass("selected");
      },
      mouseleave: function() {
        $(this).removeClass("selected");
      },
      mouseup:    function(event) {
        console.log("Trigger: mouseup #lista_stadii .rec");
        event.preventDefault();
        var $this = $(this).children().first(),
            id = parseInt($this.attr("id").slice(1)),
            $text = $this.text(),
            lista = $("#lista_stadii"),
            camp = $("#select_stadiu");
        lista.hide().promise().done(function() {
          camp.attr("data-id", id).val($text);
          if ($("#formular_filtre").length) {
            camp.blur().trigger("aplica");
          } else {
            $("input").eq(camp.index("input") + 1).focus();
          }
        });
      }
    }, "#lista_stadii .rec");

    class_box.on({
      mouseenter: function() {
        $("#lista_sex").find(".rec").removeClass("selected");
        $(this).addClass("selected");
      },
      mouseleave: function() {
        $(this).removeClass("selected");
      },
      mouseup:    function() {
        var $this = $(this).children().first(),
            id = parseInt($this.attr("id").slice(1)),
            $text = $this.text(),
            lista = $("#lista_sex"),
            camp = $("#select_sex");
        lista.hide().promise().done(function() {
          $("input").eq(camp.val($text).index("input") + 1).focus();
          camp.attr("data-id", id);
        });
      }
    }, "#lista_sex .rec");

    class_box.on({
      mouseenter: function() {
        $("#lista_ani").find(".rec").removeClass("selected");
        $(this).addClass("selected");
      },
      mouseleave: function() {
        $(this).removeClass("selected");
      },
      mouseup:    function(event) {
        console.log("Trigger: mouseup #lista_ani .rec");
        event.preventDefault();
        var $this = $(this).children().first(),
            id = parseInt($this.attr("id").slice(1)),
            $text = $this.text(),
            lista = $("#lista_ani"),
            camp = $("#select_an");
        lista.hide().promise().done(function() {
          camp.attr("data-id", id).val($text);
          if ($("#formular_filtre").length) {
            camp.blur().trigger("aplica");
          } else {
            $("input").eq(camp.index("input") + 1).focus();
          }
        });
      }
    }, "#lista_ani .rec");

    class_box.on({
      mousedown: function(event) {
        event.preventDefault();
      },
      mouseup:   function(event) {
        event.preventDefault();
        var root = $(this).closest(".box").attr("id").slice(4),
            id = parseInt($(this).parent().attr("id").slice(1)),
            box_curent = $("#box_" + root),
            path = "php/" + root + ".php",
            optiuni = {};

        $.ajax({
          async:   true,
          url:     path,
          data:    {
            optiuni: {formular_editare: 1},
            id:      id
          },
          timeout: 5000})
            .done(function(data) {
              if (data === "Inexistent") {
                load_box(box_curent, box_curent, path, optiuni);
              }
              else {
                box_curent.fadeOut(timp_fadeout)
                    .promise()
                    .done(function() {
                      box_curent.empty().append(data);
                    })
                    .done(function() {
                      box_curent.fadeIn(timp_fadein);
                      initializare_Zebra();
                    });
              }
            })
            .fail(function(jqXHR, textStatus) {
              AjaxFail(jqXHR, textStatus);
            });
      }
    }, "span.actiune");

    id_box_companii.on({
      mouseup: function(event) {
        var path = "php/companii.php",
            flag = false,
            pattern,
            values = [],
            camp = $("form input"),
            optiuni = {},
            salveaza = (this.id === "creaza_companie") ? 1 : 0; // (1) creare, (0) modificare
        event.preventDefault();
        $("span.error").remove();
        camp.removeClass("required");
        // prelucare ID companie
        values[0] = parseInt(camp.eq(0).val());
        // prelucare nume companie
        pattern = /^.{5,100}$/;
        values[1] = camp.eq(1).val(camp.eq(1).val().trim()).val();
        if (!pattern.test(values[1])) {
          flag = true;
          camp.eq(1).addClass("required").parent().append('<span class="error">Minim 5 caractere.</span>');
        }
        // prelucare adresa companie
        pattern = /^.{5,150}$/;
        values[2] = camp.eq(2).val(camp.eq(2).val().trim()).val();
        if (!pattern.test(values[2])) {
          flag = true;
          camp.eq(2).addClass("required").parent().append('<span class="error">Minim 5 caractere.</span>');
        }
        // prelucare oras companie
        pattern = /^.{3,30}$/;
        values[3] = camp.eq(3).val(camp.eq(3).val().trim()).val();
        if (!pattern.test(values[3])) {
          flag = true;
          camp.eq(3).addClass("required").parent().append('<span class="error">Minim 3 caractere.</span>');
        }
        // prelucare tara companie
        pattern = /^.{5,50}$/;
        values[4] = camp.eq(4).val(camp.eq(4).val().trim()).val();
        if (!pattern.test(values[4])) {
          flag = true;
          camp.eq(4).addClass("required").parent().append('<span class="error">Minim 5 caractere.</span>');
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
                  load_box(id_box_companii, id_box_companii, path, optiuni);
                } else if (data === "exista") {
                  camp.eq(1).addClass("required")
                      .parent()
                      .append('<span class="error">Compania există deja.</span>');
                }
                else {
                  id_box_companii.append('<span class="error">Eroare:</span>' + data);
                }
              })
              .fail(function(jqXHR, textStatus) {
                AjaxFail(jqXHR, textStatus);
              });
        }
      }
    }, "#creaza_companie, #editeaza_companie ");

    id_box_vanzatori.on({
      mouseup: function(event) {
        event.preventDefault();
        var path = "php/vanzatori.php",
            flag = false,
            pattern,
            values = [],
            camp = $("form input"),
            optiuni = {},
            salveaza = (this.id === "creaza_vanzator") ? 1 : 0; // (1) creare, (0) modificare
        event.preventDefault();
        $("span.error").remove();
        camp.removeClass("required");
        // prelucare ID vanzator
        values[0] = parseInt(camp.eq(0).val());
        // prelucare nume vanzator
        pattern = /^.{3,50}$/;
        values[1] = camp.eq(1).val(camp.eq(1).val().trim()).val();
        if (!pattern.test(values[1])) {
          flag = true;
          camp.eq(1).addClass("required").parent().append('<span class="error">Minim 3 caractere.</span>');
        }
        // prelucare prenume vanzator
        pattern = /^.{3,50}$/;
        values[2] = camp.eq(2).val(camp.eq(2).val().trim()).val();
        if (!pattern.test(values[2])) {
          flag = true;
          camp.eq(2).addClass("required").parent().append('<span class="error">Minim 3 caractere.</span>');
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
                  load_box(id_box_vanzatori, id_box_vanzatori, path, optiuni);
                } else if (data === "exista") {
                  camp.eq(1).addClass("required");
                  camp.eq(2).addClass("required")
                      .parents("table")
                      .prepend('<span class="error">Combinația nume și prenume există deja.</span>')
                }
                else {
                  id_box_vanzatori.append('<span class="error">Eroare:</span>' + data);
                }
              })
              .fail(function(jqXHR, textStatus) {
                AjaxFail(jqXHR, textStatus);
              });
        }
      }
    }, "#creaza_vanzator, #editeaza_vanzator");

    id_box_persoane.on({
      mouseup: function(event) {
        var path = "php/persoane.php",
            flag = false,
            pattern,
            values = [],
            camp = $("form input"),
            optiuni = {},
            salveaza = (this.id == "creaza_persoana") ? 1 : 0;  // 1-salveaza nou, 0-modific existent
        event.preventDefault();
        $("span.error").remove();
        camp.removeClass("required");
        // prelucrare ID
        values[0] = parseInt(camp.eq(0).val()) || null;

        pattern = /^.{3,50}$/;
        // Prelucrare nume
        values[1] = camp.eq(1).val(camp.eq(1).val().trim()).val();
        if (!pattern.test(values[1])) {
          flag = true;
          camp.eq(1).addClass("required").parent().append('<span class="error">Minim 3 caractere.</span>');
        }
        // Prelucrare prenume
        values[2] = camp.eq(2).val(camp.eq(2).val().trim()).val();
        if (!pattern.test(values[2])) {
          flag = true;
          camp.eq(2).addClass("required").parent().append('<span class="error">Minim 3 caractere.</span>');
        }
        // Prelucrare telefon
        pattern = /^[\+\-\(\)\s0-9]{3,}$/;
        values[3] = camp.eq(3).val(camp.eq(3).val().slice(0, 50).trim()).val();
        if (!pattern.test(values[3])) {
          flag = true;
          camp.eq(3).addClass("required").parent().append('<span class="error">Caractere permise : numere, spatii, +(-)<br />Minim 3 caracatere.</span>');
        }
        // Prelucrare fax
        values[4] = camp.eq(4).val(camp.eq(4).val().slice(0, 50).trim()).val();
        if (!pattern.test(values[4])) {
          flag = true;
          camp.eq(4).addClass("required").parent().append('<span class="error">Caractere permise : numere, spatii, +(-)<br />Minim 3 caracatere.</span>');
        }
        // Prelucrare telefon mobil
        values[5] = camp.eq(5).val(camp.eq(5).val().slice(0, 50).trim()).val();
        if (!pattern.test(values[5])) {
          flag = true;
          camp.eq(5).addClass("required").parent().append('<span class="error">Caractere permise : numere, spatii, +(-)<br />Minim 3 caracatere.</span>');
        }
        // prelucrare email
        pattern = /^[\w-]+(\.[\w-]+)*@([a-z0-9-]+(\.[a-z0-9-]+)*?\.[a-z]{2,6}|(\d{1,3}\.){3}\d{1,3})(:\d{4})?$/;
        values[6] = camp.eq(6).val(camp.eq(6).val().slice(0, 100).trim()).val();
        if (!pattern.test(values[6])) {
          flag = true;
          camp.eq(6).addClass("required").parent().append('<span class="error">Adresa de email nu este validă.</span>');
        }
        // validare sex
        values[7] = camp.eq(7).attr("data-id");
        if (!values[7].length) {
          flag = true;
          camp.eq(7).addClass("required").parent().append('<span class="error">Alegeţi o opţiune.</span>');
        }
        // validare companie
        values[8] = camp.eq(8).attr("data-id");
        if (!values[8].length) {
          flag = true;
          camp.eq(8).addClass("required").parent().append('<span class="error">Alegeţi o companie.</span>');
        }
        // validare Departament
        pattern = /^.{3,50}$/;
        values[9] = camp.eq(9).val(camp.eq(9).val().trim()).val();
        if (!pattern.test(values[9])) {
          flag = true;
          camp.eq(9).addClass("required").parent().append('<span class="error">Minim 3 caracatere.</span>');
        }
        // validare Functie
        values[10] = camp.eq(10).val(camp.eq(10).val().trim()).val();
        if (!pattern.test(values[10])) {
          flag = true;
          camp.eq(10).addClass("required").parent().append('<span class="error">Minim 3 caracatere.</span>');
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
                  load_box(id_box_persoane, id_box_persoane, path, optiuni);
                } else if (data === "exista") {
                  camp.filter(function(i) {
                    return $.inArray(i, [1, 2, 8]) > -1;
                  }).addClass("required");
                  $("table").prepend('<span class="error">Combinația nume, prenume şi companie există deja.</span>');
                } else {
                  id_box_persoane.append('<span class="error">Eroare:</span>' + data);
                }
              })
              .fail(function(jqXHR, textStatus) {
                AjaxFail(jqXHR, textStatus);
              });
        }
      }
    }, "#creaza_persoana, #editeaza_persoana");

    id_box_oferte.on({
      mouseup: function(event) {
        var path = "php/oferte.php",
            flag = false,
            pattern,
            values = [],
            camp = $("form input, form textarea"),
            salveaza = (this.id == "creaza_oferta") ? 1 : 0;
        event.preventDefault();
        $("span.error").remove();
        camp.removeClass("required");

        // Prelucrare id oferta
        values[0] = parseInt(camp.eq(0).val()) || null;

        // Prelucrare nume
        pattern = /^.{3,200}$/;
        values[1] = camp.eq(1).val(camp.eq(1).val().trim()).val();
        if (!pattern.test(values[1])) {
          flag = true;
          camp.eq(1).addClass("required").closest("td").append('<span class="error">Minim 3 caractere.</span>');
        }

        // prelucrare data oferta
        values[2] = camp.eq(2).attr("data-data");
        if (!values[2].length) {
          flag = true;
          camp.eq(2).addClass("required").closest("td").append('<span class="error">Selectați data ofertei.</span>');
        }

        // prelucrare valabilitate
        values[3] = camp.eq(3).val();
        if (!values[3].length) {
          flag = true;
          camp.eq(3).addClass("required").closest("td").append('<span class="error">Introduceți valabilitatea ofertei.</span>');
        }

        // prelucrare vanzator
        values[4] = camp.eq(4).attr("data-id");
        if (!values[4].length) {
          flag = true;
          camp.eq(4).addClass("required").closest("td").append('<span class="error">Selectați persoana resonsabilă de vânzări.</span>');
        }

        // citire data expirare
        values[5] = camp.eq(5).attr("data-data");

        // citire descriere
        values[6] = camp.eq(6).val();

        // prelucrare stadiu
        values[7] = camp.eq(7).attr("data-id");
        if (!values[7].length) {
          flag = true;
          camp.eq(7).addClass("required").closest("td").append('<span class="error">Selectați stadiul ofertei.</span>');
        }

        // citire raportare volum
        values[8] = camp.eq(8)[0].checked ? 1 : 0;

        // prelucrare companie
        values[9] = camp.eq(9).attr("data-id");
        if (!values[9].length) {
          flag = true;
          camp.eq(9).addClass("required").closest("td").append('<span class="error">Selectați compania căreia i se adresează oferta.</span>');
        }

        // prelucrare persoana
        values[10] = camp.eq(10).attr("data-id");
        if (!values[10].length) {
          flag = true;
          camp.eq(10).addClass("required").closest("td").append('<span class="error">Selectați persoana de contact.</span>');
        }

        // prelucrare valoare oferta
        values[11] = parseFloat(camp.eq(11).val());
        if (!values[11]) {
          flag = true;
          camp.eq(11).addClass("required").closest("td").append('<span class="error">Introduceți valoarea ofertei.</span>');
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
                  $("#oferte").trigger("mouseup");
                } else if (data === "exista") {
                  camp.filter(function(i) {
                    return $.inArray(i, [1, 2, 9]) > -1;
                  }).addClass("required");
                  $("table").prepend('<span class="error">Combinația nume, dată şi companie există deja.</span>');
                } else {
                  id_box_oferte.append('<span class="error">Eroare:</span>' + data);
                }
              })
              .fail(function(jqXHR, textStatus) {
                AjaxFail(jqXHR, textStatus);
              });

        }
      }
    }, "#creaza_oferta, #editeaza_oferta");

    id_box_oferte.on({
      aplica: function() {
        console.log("Trigger: aplica");
        var root = $(this).closest(".box").attr("id").slice(4),
            box_curent = $("#box_" + root),
            path = "php/" + root + ".php",
            optiuni = {}, filtre = $("#filtre"),
            vanzator = $("#select_vanzator"),
            companie = $("#select_companie"),
            stadiu = $("#select_stadiu"),
            an = $("#select_an"),
            id_vanzator = vanzator.attr("data-id"),
            id_companie = companie.attr("data-id"),
            id_stadiu = stadiu.attr("data-id"),
            id_an = an.attr("data-id");
        optiuni.filtrare = 1;

        filtre.attr("data-idcompanie", id_companie);
        if (id_companie) {
          optiuni.companie = {
            id: id_companie
          }
        }

        filtre.attr("data-idvanzator", id_vanzator);
        if (id_vanzator) {
          optiuni.vanzator = {
            id: id_vanzator
          }
        }

        filtre.attr("data-idstadiu", id_stadiu);
        if (id_stadiu) {
          optiuni.stadiu = {
            id: id_stadiu
          }
        }

        filtre.attr("data-idan", id_an);
        if (id_an) {
          optiuni.an = {
            id: id_an
          }
        }

        $.ajax({
          async:   true,
          url:     path,
          data:    { optiuni: optiuni },
          timeout: 5000})
            .done(function(raspuns) {
              box_curent.children(".rezultate, .total").remove().end().append(raspuns);
            })
            .fail(function(jqXHR, textStatus) {
              AjaxFail(jqXHR, textStatus);
            });
      }
    });

    id_box_oferte.on({
      mouseup: function() {
        console.log("Trigger: mouseup #reset");
        $("input").attr("data-id", "").val("");
        $(this).trigger("aplica");
      }
    }, "#reset");

    id_box_oferte.on({
      keydown: function(event) {
        if (!isInArray(event.keyCode, [8, 9, 46, 37, 39, 96, 97, 98, 99, 100, 101, 102,
          103, 104, 105, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57])) {
          event.preventDefault();
        } else {
          if (this.selectionStart === this.selectionEnd && $(this).val().length === 3 && !isInArray(event.keyCode, [8, 9 , 46, 37, 39])) {
            event.preventDefault();
          }
        }
      },
      input:   function() {
        var data_ro = $("#data_oferta").val();
        if (data_ro !== "") {
          var valabilitate = $("#valabilitate").val();
          var dataJS_exp = convertDate(data_ro).addDays(valabilitate);
          var exp_ro = dataJS_exp.toString("d-MMM-yyyy");
          var exp_MSQL = dataJS_exp.toString("yyyy-MM-dd");
          $("#data_expirare").val(exp_ro).attr("data-data", exp_MSQL);
        }
      }
    }, "#valabilitate");

    id_box_oferte.on({
      keydown: function(event) {
        if (!isInArray(event.keyCode, [8, 9, 46, 37, 39, 96, 97, 98, 99, 100, 101, 102,
          103, 104, 105, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57])) {
          event.preventDefault();
        } else {
          if (this.selectionStart === this.selectionEnd && $(this).val().length === 12 && !isInArray(event.keyCode, [8, 9 , 46, 37, 39])) {
            event.preventDefault();
          }
        }
      }
    }, "#valoare_oferta");

  })
})();
