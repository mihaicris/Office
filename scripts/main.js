(function() {
	$(document).ready(function() {
		var class_box = $('.box'),
			id_box_companii = $('#box-companii'),
			id_box_vanzatori = $('#box-vanzatori'),
			id_box_oferta_noua = $('#box-oferta-noua'),
			id_box_persoane = $('#box-persoane'),
			timp_tranzitie_afisari = 100;

		$.ajaxSetup({
			async: true,
			cache: false,
			type : 'POST'
		});
		$('.menu').click(function() {
			// tratez click pe optiunile din menu
			if($(this).hasClass('selected')) {
				$(this).removeClass('selected');
				$(this).next().children().hide();
				$('.box').hide();
				return;
			}
			$('.menu').removeClass('selected');
			$('.option').removeClass('selected').hide();
			$(this).addClass('selected').next().children().show()
		}); // .menu click
		$('.option').click(function() {
			// tratez click pe una dintre suboptiunile din submenu
			if($(this).hasClass('selected')) { // daca este deja selectat nu se face nimic
				return;
			}
			$('.option').not($(this)).removeClass('selected'); // deselect all other option;
			$(this).addClass('selected').show();
			var ind = $(this).index('.option');
			var pagina = [
				'php/oferta_noua.php',
				'php/oferte.php',
				'php/comanda_noua.php',
				'php/comenzi.php',
				'php/stats-ofertare.php',
				'php/stats-comenzi.php',
				'php/stats-clienti.php',
				'php/stats-furnizori.php',
				'php/companii.php',
				'php/vanzatori.php',
				'php/persoane.php'
			];

			class_box.hide().empty(); // se ascund si apoi se golesc de continut toate divurile .box
			var box = class_box.eq(ind); // se salveaza in varabila box divul care se va activa
			box.load(pagina[ind], function(response, status, xhr) {
				if(status == "error") {
					var msg = "Sorry but there was an error: ";
					box.html(msg + xhr.status + " " + xhr.statusText);
				}
				box.fadeIn(100)
			})
		}); // .option click

		var toggleEvents = function(event, action) {
			switch(event) {
				case 'submit_creaza_persoana':
					if(action == 'on') {
						id_box_persoane.on('click.creaza_persoana', '#creaza_persoana, #modifica_persoana', function(event) {
							var flag = false,
								pattern,
								values = [],
								camp = $('form input, form select'),
								salveaza = (this.id == "creaza_persoana") ? 1 : 0;  // 1-salveaza nou, 0-modific existent
							event.preventDefault();
							$('form span.error').remove();
							camp.addClass('normal').removeClass('required');

							pattern = /.{3,50}/;
							// Prelucrare nume
							values.nume = camp.eq(0).val(camp.eq(0).val().trim().slice(0, 50)).val();
							if(!pattern.test(values.nume)) {
								flag = true;
								camp.eq(0).toggleClass('normal required').parent().append('<span class="error">Minim 3 caractere.</span>');
							}

							// Prelucrare prenume
							values.prenume = camp.eq(1).val(camp.eq(1).val().trim().slice(0, 50)).val();
							if(!pattern.test(values.prenume)) {
								flag = true;
								camp.eq(1).toggleClass('normal required').parent().append('<span class="error">Minim 3 caractere.</span>');
							}

							// Prelucrare telefon
							pattern = /^[+()\s0-9]{3,}$/;
							values.telefon = camp.eq(2).val(camp.eq(2).val().trim().slice(0, 50)).val();
							if(!pattern.test(values.telefon)) {
								flag = true;
								camp.eq(2).toggleClass('normal required').parent().append('<span class="error">Caractere permise : numere, spatii, + ( )<br />Minim 3 caracatere.</span>');
							}

							// Prelucrare fax
							pattern = /^[+()\s0-9]{3,}$/;
							values.fax = camp.eq(3).val(camp.eq(3).val().trim().slice(0, 50)).val();
							if(!pattern.test(values.fax)) {
								flag = true;
								camp.eq(3).toggleClass('normal required').parent().append('<span class="error">Caractere permise : numere, spatii, + ( )<br />Minim 3 caracatere.</span>');
							}

							// Prelucrare telefon mobil
							pattern = /^[+()\s0-9]{3,}$/;
							values.mobil = camp.eq(4).val(camp.eq(4).val().trim().slice(0, 50)).val();
							if(!pattern.test(values.mobil)) {
								flag = true;
								camp.eq(4).toggleClass('normal required').parent().append('<span class="error">Caractere permise : numere, spatii, + ( )<br />Minim 3 caracatere.</span>');
							}

							// prelucrare email
							pattern = /^[\w-]+(\.[\w-]+)*@([a-z0-9-]+(\.[a-z0-9-]+)*?\.[a-z]{2,6}|(\d{1,3}\.){3}\d{1,3})(:\d{4})?$/;
							values.email = camp.eq(5).val(camp.eq(5).val().trim().slice(0, 100)).val();
							if(!pattern.test(values.email)) {
								flag = true;
								camp.eq(5).toggleClass('normal required').parent().append('<span class="error">Adresa de email nu este validă.</span>');
							}

							// validare sex
							values.sex = camp.eq(6).val();
							if(!values.sex.length) {
								flag = true;
								camp.eq(6).toggleClass('normal required').parent().append('<span class="error">Alegeţi o opţiune.</span>');
							}

							// validare companie
							values.id_companie = camp.eq(7).data('id_companie');
							if(!values.id_companie) {
								flag = true;
								camp.eq(7).toggleClass('normal required').parent().append('<span class="error">Alegeţi o companie.</span>');
							}

							// validare Departament
							values.departament = camp.eq(8).val(camp.eq(8).val().trim().slice(0, 50)).val();
							if(!values.departament.length) {
								flag = true;
								camp.eq(8).toggleClass('normal required').parent().append('<span class="error">Minim 3 caracatere.</span>');
							}

							// validare Functie
							values.functie = camp.eq(9).val(camp.eq(9).val().trim().slice(0, 50)).val();
							if(!values.functie.length) {
								flag = true;
								camp.eq(9).toggleClass('normal required').parent().append('<span class="error">Minim 3 caracatere.</span>');
							}

							values.id_persoana = camp.eq(10).attr('id');

							console.clear();
							console.dir(values);
							//iesire fortata pentru ca nu este gata
							return;

							if(!flag) {
								$.ajax({
									async  : false,
									url    : 'php/persoane.php',
									type   : 'POST',
									data   : {
										salveaza: $salveaza, // se creaza / se modifica persoana
										formdata: $values
									},
									timeout: 3000,
									error  : function(response, status, xhr) {
										var msg;
										if(status == "error") {
											msg = "Eroare: ";
											alert(msg + xhr.status + " " + xhr.statusText)
										}
										if(status === "timeout") {
											msg = "Probleme de retea: ";
											alert(msg + xhr.status + " " + xhr.statusText)
										}
									},
									success: function(raspuns) {
										if(raspuns === "ok") {
											$('#box-persoane').load('php/persoane.php');
										} else {
											$('#box-persoane').append(raspuns);
										}
									} // end ajax succes
								}); // end $.ajax
							} // end if flag
						});  // end .on
					}
					else {
						id_box_persoane.off('click.creaza_persoana');
					}
					break;
				default:
					break;
			}
		};
		toggleEvents('submit_creaza_persoana', 'on');

		class_box.on('change', 'select', function() {
			$('form select').removeClass('required');
			$('form #default').remove();
		});

		class_box.on('focus', 'input', function() {
			$(this).next("img").hide();
			$(this).addClass('normal').removeClass('required');
		});

		class_box.on('keyup', 'input#camp', function(event) {
			var formdata = 'camp_str=' + encodeURIComponent($(this).val()), $this;
			if((formdata.length > 11 || formdata.length == 9) && event.which != 13) {
				$this = $(this).closest('.box').attr('id').slice(4);
				$.post('php/' + $this + '.php', formdata, function(date_primite) {
					$('#box-' + $this).children('table, p, a, div').remove().end().append(date_primite);
				}); // end function
			} // end if
		}); // end on

		class_box.on('click', '.nou', function(event) {
			event.preventDefault();
			var formdata = "creare=1&nume=" + encodeURIComponent($('#camp').val()),
				$this = $(this).closest('.box').attr('id').slice(4),
				a = $('#box-' + $this);
			$.post('php/' + $this + '.php', formdata, function(date_primite) {
				a.fadeOut(timp_tranzitie_afisari);
				a.queue('fx', function() {
					$(this).empty().append(date_primite);
					$(this).dequeue('fx');
				});
				a.fadeIn(timp_tranzitie_afisari).find('input').eq(0).focus();
				$('#camp_cauta_companie').removeData('id_companie');
			});
		});

		class_box.on('click', '#renunta', function() {
			// click pe renunta
			var $this = $(this).closest('.box').attr('id').slice(4);
			$('#box-' + $this).fadeOut(timp_tranzitie_afisari, function() {
				$('#box-' + $this).load('php/' + $this + '.php', function() {
					$('#box-' + $this).fadeIn(timp_tranzitie_afisari);
				});
			});
		});

		class_box.on('click', '#sterge', function(event) {
			// click pe sterge
			event.preventDefault();
			var $this = $(this).closest('.box').attr('id').slice(4);
			var r, formdata;
			switch($this) {
				case 'companii':
					r = confirm("Sigur se șterge clientul?");
					break;
				case 'vanzatori':
					r = confirm("Sigur se șterge vânzătorul?");
					break;
			}
			if(r == true) {
				var id = $('form input').eq(0).val();
				formdata = "sterge=1&id=" + id;
				$.post('php/' + $this + '.php', formdata, function(date_primite) {
					if(date_primite == 'ok') {
						$('#box-' + $this).load('php/' + $this + '.php');
					} else {
						alert('Eroare!')
					} // end else
				}); // end .post
			}
		}); // end .on

		class_box.on('keydown', 'input#camp_cauta_companie', function(event) {
			switch(event.which) {
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
			var $text;

			function aranjeaza_elemente() { // pozitioneaza fereastra de sugenstii sub input #camp
				var a = $('#camp_cauta_companie');
				$('.tabel').css({
					'left' : a.position().left,
					'top'  : a.position().top + a.height() + 10,
					'width': a.css('width')
				}); // end .css
			} // end function

			switch(event.which) {
				case 38: // key up
					if($('.tabel .rec p').length == 0) { // verficam daca exista cel putin un rezultat
						return;
					}
					if($('.tabel .rec').hasClass('selected')) { // e deja selectat
						if($('.tabel .rec:first').hasClass('selected')) {
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
				case 40: // key down
					// code pentru selectare in jos
					if($('.tabel .rec p').length == 0) { // verficam daca exista cel putin un rezultat
						return;
					}
					if($('.tabel .rec').hasClass('selected')) {
						// e deja selectat
						if($('.tabel .rec:last').hasClass('selected')) {
							$('.tabel .selected').css('background-color', '#ffffff').removeClass('selected');
							$('.tabel .rec:first').addClass('selected');
							$text = $('.tabel .rec:first').children().first().text();
							$(this).val($text).focus();
							return;
						}
						$('.tabel .selected').css('background-color', '#ffffff')
							.removeClass('selected').next().css('background-color', '#CED5F5').addClass('selected');
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
					var id_companie,
						selected = $('.tabel .selected');
					if(!$('.tabel:visible').length) {
						return;
					}
					if(selected.length) {
						$text = selected.children().first().text();
						id_companie = selected.children().first().attr('id').slice(1);
						$(this).data('id_companie', id_companie).val($text).closest('tr').next().find('input:first').focus();
						$('.tabel').fadeOut(100, function() {
							$('.tabel').empty();
							toggleEvents('submit_creaza_persoana', 'on');
						});
						return;
					} else {
						return;
					}
				default:
					break;
			} // end switch
			var $string = $(this).val();
			if($string.length > 2) {

				$.ajax({
					async  : false,
					url    : 'php/companii.php',
					type   : 'POST',
					data   : {
						companie: $string
					},
					timeout: 3000,
					error  : function(response, status, xhr) {
						var msg;
						if(status == "error") {
							msg = "Eroare: ";
							alert(msg + xhr.status + " " + xhr.statusText)
						}
						if(status === "timeout") {
							msg = "Probleme de retea: ";
							alert(msg + xhr.status + " " + xhr.statusText)
						}
					},
					success: function(raspuns) {
						aranjeaza_elemente();
						$('.tabel').html(raspuns).fadeIn(100);
						toggleEvents('submit_creaza_persoana', 'off');
					} // end ajax succes
				}); // end $.ajax
			} else {
				$('.tabel').fadeOut(100, function() {
					$('.tabel').empty();
					toggleEvents('submit_creaza_persoana', 'on');
				});
			}
		});

		class_box.on({
			mouseup   : function() {
				var $this = $(this).children().first(),
					id_companie = parseInt($this.attr('id').slice(1)),
					$text = $this.text(); // salvez numele firmei
				if(!$(this).children('a').length) {
					$('#camp_cauta_companie').data('id_companie', id_companie)
						.val($text).closest('tr').next().find('input:first').focus();
				}
				$('.tabel').fadeOut(100, function() {
					$('.tabel').empty();
					toggleEvents('submit_creaza_persoana', 'on');
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
			var id, formdata, rezervat, $box;
			$box = $(this).closest('.box').attr('id').slice(4);
			id = $(this).parent().attr('id').slice(1);
			formdata = "editeaza=" + id;
			rezervat = encodeURIComponent($(this).parent().next().text()); // se salveaza valoare din campul de dupa ID
			$.post('php/' + $box + '.php', formdata, function(date_primite, textStatus, xhr) {
				if(date_primite == "Inexistent") {
					$('#box-' + $box).load('php/' + $box + '.php');
				} else {
					$('#box-' + $box).empty().append(date_primite);
					$("form input").eq(1).data("rezervat", rezervat);
				}
			}); // end .post
		}); // end .on

		id_box_companii.on('click', '#modifica', function(event) {
			// administrare companii, formular editare client, click pe modifica
			event.preventDefault();
			var values = [], flag,
				$this = $("form").attr("id").slice(8),
				$url = "php/companii.php",
				$string = 'Compania',
				camp = $('form input');
			$('form span.error').remove();

			camp.each(function(i) {
				$(this).val($(this).val().trim());
				values[i] = encodeURIComponent($(this).val());
			});
			var formdata = "nume_test=" + values[1]; //nume client sau furnizor pentru verificare
			var rezervat = $("form input").eq(1).data("rezervat");
			//		console.debug('Rezervat: ', rezervat);
			//		console.debug('Modificat: ', values[1]);
			if(formdata.length > 14) {
				if(rezervat != values[1]) {
					// nu este cel initial din camp
					$.ajax({
						url    : $url,
						async  : false,
						data   : formdata,
						timeout: 3000,
						error  : function() {
							alert('Probleme de rețea');
						},
						success: function(date_primite) {
							if(date_primite == "este") {
								camp.eq(1).after('<span class="error">' + $string + ' se află deja în baza de date</span>');
							} else {
								$('#c_nume').attr('src', '/Projects/Start/images/yes_small.png').show();
							} // end else
						} // end succes
					}); // end ajax
				} // end if rezervat
				else {
					$('#c_nume').attr('src', '/Projects/Start/images/yes_small.png').show();
					console.debug('Numele companiei nu s-a modificat.')
				}
			} else {
				camp.eq(1).after("<span class='error'>Minim 5 caractere.</span>");
			} // end if
			if(values[2].length < 5) { // adresa
				camp.eq(2).after('<span class="error">Minim 5 caractere.</span>');
			} else {
				$('#c_adresa').attr('src', '/Projects/Start/images/yes_small.png').show();
			}
			if(values[3].length <= 2) { // oras
				camp.eq(3).after('<span class="error">Minim 3 caractere.</span>');
			} else {
				$('#c_oras').attr('src', '/Projects/Start/images/yes_small.png').show();
			}
			if(values[4].length <= 4) {
				camp.eq(4).after('<span class="error">Minim 5 caractere.</span>');
			} else {
				$('#c_tara').attr('src', '/Projects/Start/images/yes_small.png').show();
			}
			flag = $('form span.error').length;
			if(!flag) {
				$('form img').hide();
				$('form span.error').remove();
				formdata = "salveaza=2&id=" + values[0] + "&nume=" + values[1] + "&adresa=" + values[2] + "&oras=" + values[3] + "&tara=" + values[4];
				$.post('php/' + $this + '.php', formdata, function(date_primite) {
					if(date_primite == 'ok') {
						$('#box-' + $this).load('php/' + $this + '.php');
					} else {
						$('form span.error').remove();
						camp.eq(1).after('<span class="error">Acest nume este deja in baza de date.</span>');
					} // end else
				}); // end .post
			}
		}); // end .on

		id_box_companii.on('click', '#creaza_companie', function(event) {
			event.preventDefault();
			// administrare clienti, formular creare client nou, click pe "Salveaza"
			// console.debug($(this).attr('id'));
			var form,
				flag,
				box = $('#box-companii'),
				$url = "php/companii.php",
				values = [],
				$string = 'Compania',
				camp = $('form input');
			$('form span.error').remove();
			camp.each(function(i) {
				$(this).val($(this).val().trim());
				values[i] = encodeURIComponent($(this).val());
			});
			formdata = "nume_test=" + values[0]; //nume client sau furnizor
			if(formdata.length > 14) {
				$.ajax({
					async  : false,
					url    : $url,
					data   : formdata,
					success: function(date_primite) {
						if(date_primite != "este") {
							$('#c_nume').attr('src', 'images/yes_small.png').show();
						} else {
							camp.eq(0).after('<span class="error">' + $string + ' se află deja în baza de date</span>');
						}
					}
				});
			} else {
				camp.eq(0).after("<span class='error'>Minim 5 caractere.</span>");
			} // end if
			if(values[1].length < 5) { // adresa
				camp.eq(1).after('<span class="error">Minim 5 caractere.</span>');
			} else {
				$('#c_adresa').attr('src', 'images/yes_small.png').show();
			}
			if(values[2].length <= 2) { // oras
				camp.eq(2).after('<span class="error">Minim 3 caractere.</span>');
			} else {
				$('#c_oras').attr('src', 'images/yes_small.png').show();
			}
			if(values[3].length <= 4) {
				camp.eq(3).after('<span class="error">Minim 5 caractere.</span>');
			} else {
				$('#c_tara').attr('src', 'images/yes_small.png').show();
			}
			flag = $('form span.error').length;
			if(!flag) {
				$('form img').hide();
				var formdata = "salveaza=1&nume=" + values[0] + "&adresa=" + values[1] + "&oras=" + values[2] + "&tara=" + values[3];
				$.ajax({
					url    : $url,
					data   : formdata,
					timeout: 3000,
					error  : function() {
						alert('Probleme de rețea');
					},
					success: function(date_primite) {
						if(date_primite == 'ok') {
							box.load($url);
						} else {
							alert(date_primite);
						} // end else
					} // end function
				}); // end ajax
			} // end if flag
		}); // end .on()

		id_box_vanzatori.on('click', '#creaza_vanzator, #modifica', function(event) {
			// administrare vanzatori, formular creare vanzator nou, click pe Creaza vanzatorul
			event.preventDefault();
			var camp, form, formdata, values = [],
				rezervat, modificat, id_vanzator;
			camp = $('form input');
			if(this.id == 'modifica') {
				id_vanzator = camp.eq(0).val();
				camp = camp.slice(1);
			}
			$('form span'.error).remove();
			camp.each(function(i) {
				$(this).val($(this).val().trim()); // rescriu valoarea campului fara spatii
				values[i] = encodeURIComponent($(this).val()); // creez array cu valorile din camp
				if(values[i].length < 3) {
					$(this).after('<span class="error">Minim 3 caractere.</span>')
				} // end if
			}); // end each
			if($('form span.error').length) {
				return
			}
			rezervat = camp.eq(0).data('rezervat'); // daca rezervat exista se doreste modificare
			modificat = values[0] + '%20' + values[1];
			console.debug('Modificat:', modificat);
			if(rezervat == modificat) { // rezervat exista, la modificare daca sunt la fel campurile se iese
				console.debug('Modificare: Datele vânzătorului nu s-au modificat.');
				id_box_vanzatori.load('php/vanzatori.php');
				return
			}
			formdata = 'nume_test=1&nume=' + values[0] + '&prenume=' + values[1];
			$.post('php/vanzatori.php', formdata, function(data, textStatus, xhr) {
				if(data == "este") {
					camp.eq(0).after('<span class="error">Este deja în baza de date.</span>');

				} else {
					if(!rezervat) {
						formdata = 'salveaza=1&nume=' + values[0] + '&prenume=' + values[1]; // save new
					} else {
						formdata = 'salveaza=2&nume=' + values[0] + '&prenume=' + values[1] + '&id=' + id_vanzator; // update existing
					}
					$.post('php/vanzatori.php', formdata, function() {
						// console.debug('Am salvat / modificat vanzatorule existent');
						id_box_vanzatori.load('php/vanzatori.php');
					});
				} // end if
			}); // end post
		}); // end on.click

		id_box_oferta_noua.on('click', '#word', function(event) {
			// TESTARE WORD
			var _self = $(this).parent();
			var $id = $('form').find(":selected").val();
			if(!$id) {
				event.preventDefault();
				$('form select').addClass('required').children().text('Alegeți...');
				return;
			}
			$.ajax({
				async  : false,
				url    : 'php/word/genereaza_word.php',
				type   : 'POST',
				data   : {
					id_persoana: $id
				},
				timeout: 3000,
				error  : function(jqXHR, textStatus, errorThrown) {
					if(textStatus === "timeout") {
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
