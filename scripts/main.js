(function () {
	$(document).ready(function () {
		var class_box = $('.box'),
			id_box_companii = $('#box-companii'),
			id_box_vanzatori = $('#box-vanzatori'),
			id_box_oferta_noua = $('#box-oferta-noua'),
			id_box_persoane = $('#box-persoane'),
			pagina = [  'php/oferta_noua.php',
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
			timp_fadein = 80,
			timp_fadeout = 250;

		var tranzitie_interior_box = function (box, path) {
			$.ajax({
				async: true,
				url: path,
				type: 'POST',
				timeout: 5000})
				.done(function (data) {
					box.fadeOut(timp_fadeout);
					box.queue('fx', function () {
						box.html(data);
						box.dequeue('fx');
					});
					box.fadeIn(timp_fadein);
				})
				.fail(function (jqXHR, textStatus) {
					$('span.ajax').remove();
					if (textStatus === "error") {
						box.append('<span class="error ajax">Eroare!<br/>' + jqXHR.responseText + '</span>');
					}
					if (textStatus === "timeout") {
						box.append('<span class="error ajax">Rețeaua este lentă sau întreruptă.</span>');
					}
				});
		};

		var tranzitie_box = function (box_curent, box_nou, path) {
			$.ajax({
				async: true,
				url: path,
				type: 'POST',
				timeout: 5000})
				.done(function (data) {
					box_curent.fadeOut(timp_fadeout)
						.promise()
						.done(function () {
							box_curent.empty();
							box_nou.queue('fx', function () {
								box_nou.html(data);
								box_nou.dequeue('fx');
							});
							box_nou.fadeIn(timp_fadein);
						});
				})
				.fail(function (jqXHR, textStatus) {
					$('span.ajax').remove();
					if (textStatus === "error") {
						box_curent.append('<span class="error ajax">Eroare!<br/>' + jqXHR.responseText + '</span>');
					}
					if (textStatus === "timeout") {
						box_curent.append('<span class="error ajax">Rețeaua este lentă sau întreruptă.</span>');
					}
				});
		};

		var toggleEvents = function (event, action) {
			if (event === 'submit_formular_persoana') {
				if (action) {
					id_box_persoane.on('click.persoana', '#creaza_persoana, #editeaza_persoana', function (event) {
						var path = 'php/persoane.php',
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

						pattern = /.{3,50}/;
						// Prelucrare nume
						values[1] = camp.eq(1).val(camp.eq(1).val().slice(0, 50).trim()).val();
						if (!pattern.test(values[1])) {
							flag = true;
							camp.eq(1).addClass('required').parent().append('<span class="error">Minim 3 caractere.</span>');
						}

						// Prelucrare prenume
						values[2] = camp.eq(2).val(camp.eq(2).val().slice(0, 50).trim()).val();
						if (!pattern.test(values[2])) {
							flag = true;
							camp.eq(2).addClass('required').parent().append('<span class="error">Minim 3 caractere.</span>');
						}

						// Prelucrare telefon
						pattern = /^[+()\s0-9]{3,}$/;
						values[3] = camp.eq(3).val(camp.eq(3).val().slice(0, 50).trim()).val();
						if (!pattern.test(values[3])) {
							flag = true;
							camp.eq(3).addClass('required').parent().append('<span class="error">Caractere permise : numere, spatii, + ( )<br />Minim 3 caracatere.</span>');
						}

						// Prelucrare fax
						pattern = /^[+()\s0-9]{3,}$/;
						values[4] = camp.eq(4).val(camp.eq(4).val().slice(0, 50).trim()).val();
						if (!pattern.test(values[4])) {
							flag = true;
							camp.eq(4).addClass('required').parent().append('<span class="error">Caractere permise : numere, spatii, + ( )<br />Minim 3 caracatere.</span>');
						}

						// Prelucrare telefon mobil
						pattern = /^[+()\s0-9]{3,}$/;
						values[5] = camp.eq(5).val(camp.eq(5).val().slice(0, 50).trim()).val();
						if (!pattern.test(values[5])) {
							flag = true;
							camp.eq(5).addClass('required').parent().append('<span class="error">Caractere permise : numere, spatii, + ( )<br />Minim 3 caracatere.</span>');
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
						values[9] = camp.eq(9).val(camp.eq(9).val().slice(0, 50).trim()).val();
						if (!values[9].length) {
							flag = true;
							camp.eq(9).addClass('required').parent().append('<span class="error">Minim 3 caracatere.</span>');
						}

						// validare Functie
						values[10] = camp.eq(10).val(camp.eq(10).val().slice(0, 50).trim()).val();
						if (!values[10].length) {
							flag = true;
							camp.eq(10).addClass('required').parent().append('<span class="error">Minim 3 caracatere.</span>');
						}

						if (!flag) {
							$.ajax({
								async: true,
								url: path,
								type: 'POST',
								data: {
									salveaza: salveaza, // se creaza / se modifica persoana
									formdata: values
								},
								timeout: 5000})
								.done(function (raspuns) {
									if (raspuns === "ok") {
										tranzitie_interior_box(id_box_persoane, path);
										toggleEvents('submit_formular_persoana', false);
									} else {
										id_box_persoane.append(raspuns);
									}
								})
								.fail(function (jqXHR, textStatus) {
									$('span.ajax').remove();
									if (textStatus === "error") {
										id_box_persoane.append('<span class="error ajax">Eroare!<br/>' + jqXHR.responseText + '</span>');
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
			type: 'POST'
		});

		$('.menu').click(function () {
			// tratez click pe optiunile din menu
			if ($(this).hasClass('selected')) {
				$(this).removeClass('selected');
				$(this).next().children().hide();
				$('.box').hide();
				return;
			}
			$('.menu').removeClass('selected');
			$('.option').removeClass('selected').hide();
			$(this).addClass('selected').next().children().show()
		});
		$('.option').click(function () {
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
			tranzitie_box(box_curent, box_nou, path);
		});


		$(document).keyup(function (event) {
			if (event.keyCode == 27) {
				$('#renunta').click();
			}
		});

		class_box.on('change', 'select', function () {
			$('form select').removeClass('required');
			$('form #default').remove();
		});

		class_box.on('focus', 'input', function () {
			$(this).next('img').hide();
			$(this).addClass('normal').removeClass('required');
		});

		class_box.on('keydown', 'input#camp', function (event) {
			if (event.which == 13) {
				event.preventDefault();
				$('.nou').click();
			}
		});

		class_box.on('keyup', 'input#camp', function (event) {

			var camp_str = $(this).val(),
				$this = $(this).closest('.box').attr('id').slice(4),
				path = 'php/' + $this + '.php',
				box_current = $('#box-' + $this);

			console.log(camp_str);

			if (camp_str.length > 2 && event.which != 13) {
				$.ajax({
					async: false,
					url: path,
					type: 'POST',
					data: {
						camp_str: camp_str
					},
					timeout: 5000})
					.done(function (raspuns) {
						box_current.children('table, p, a, div').remove().end().append(raspuns);
					})
					.fail(function (jqXHR, textStatus) {
						$('span.ajax').remove();
						if (textStatus === "error") {
							box_current.append('<span class="error ajax">Eroare!<br/>' + jqXHR.responseText + '</span>');
						}
						if (textStatus === "timeout") {
							box_current.append('<span class="error ajax">Rețeaua este lentă sau întreruptă.</span>');
						}
					});
			}
		});

		class_box.on('click', '.nou', function (event) {
			event.preventDefault();
			var formdata = "formular-creare=1&nume=" + encodeURIComponent($('#camp').val()), $this = $(this).closest('.box').attr('id').slice(4), a = $('#box-' + $this), page = 'php/' + $this + '.php';
			$.ajax({
				type: 'POST',
				cache: false,
				async: true,
				data: formdata,
				url: page,
				timeout: function () {
					alert('Probleme de rețea!');
				},
				success: function (raspuns) {
					a.fadeOut(timp_fadeout);
					a.queue('fx', function () {
						$(this).empty().append(raspuns);
						$(this).dequeue('fx');
					});
					a.fadeIn(timp_fadein);
					a.queue('fx', function () {
						$(this).find('input').eq(0).focus();
						$(this).dequeue('fx');
					});
					toggleEvents('submit_formular_persoana', true);
				}
			});
		});

		class_box.on('click', '#renunta', function () {
			var $this = $(this).closest('.box').attr('id').slice(4),
				box = $('#box-' + $this),
				path = 'php/' + $this + '.php';
			tranzitie_interior_box(box, path);
		});

		class_box.on('click', '#sterge', function (event) {
			// click pe sterge
			event.preventDefault();

			var $this = $(this).closest('.box').attr('id').slice(4),
				r, id, formdata;

			switch ($this) {
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
				id = $('form input').eq(0).val();
				formdata = "sterge=1&id=" + id;
				$.post('php/' + $this + '.php', formdata, function (date_primite) {
					if (date_primite == 'ok') {
						var box = $('#box-' + $this),
							path = 'php/' + $this + '.php';
						tranzitie_interior_box(box, path);
					} else {
						alert('Eroare!')
					} // end else
				}); // end .post
			}
		}); // end .on

		class_box.on('keydown', 'input#camp_cauta_companie', function (event) {
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

		class_box.on('keyup', 'input#camp_cauta_companie', function (event) {
			var $text;

			function aranjeaza_elemente() { // pozitioneaza fereastra de sugenstii sub input #camp
				var a = $('#camp_cauta_companie'),
					Xleft = a.position().left,
					Xtop = a.position().top,
					Xwidth = parseInt(a.css('width')),
					Xheight = parseInt(a.css('height'));

				$('.tabel').css({
					'left': Xleft,
					'top': Xtop + Xheight + 1,
					'min-width': Xwidth
				});
			}
			switch (event.which) {
				case 38:
					// key up
					if ($('.tabel .rec p').length == 0) {
						// daca a aparut mesaul nu exista nu facem nimic
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
						$('.tabel .selected').css('background-color', '#ffffff').removeClass('selected').prev().css('background-color', '#CED5F5').addClass('selected');
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
						$('.tabel').fadeOut(timp_fadeout, function () {
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
			} // end switch
			var $string = $(this).val();
			if ($string.length > 2) {

				$.ajax({
					async: false,
					url: 'php/companii.php',
					type: 'POST',
					data: {
						companie: $string
					},
					timeout: 3000,
					error: function (response, status, xhr) {
						var msg;
						if (status == "error") {
							msg = "Eroare: ";
							alert(msg + xhr.status + " " + xhr.statusText)
						}
						if (status === "timeout") {
							msg = "Probleme de retea: ";
							alert(msg + xhr.status + " " + xhr.statusText)
						}
					},
					success: function (raspuns) {
						aranjeaza_elemente();
						$('.tabel').html(raspuns).fadeIn(100);
						toggleEvents('submit_formular_persoana', false);
					} // end ajax succes
				}); // end $.ajax
			} else {
				$('.tabel').fadeOut(timp_fadeout, function () {
					$('.tabel').empty();
					toggleEvents('submit_formular_persoana', true);
				});
				$('.tabel').fadeOut(timp_fadeout, function () {
					$('.tabel').empty();
					toggleEvents('submit_formular_persoana', true);
				});
			}
		});

		class_box.on({
			mouseup: function () {
				var $this = $(this).children().first(), id_companie = parseInt($this.attr('id').slice(1)), $text = $this.text(); // salvez numele firmei
				if (!$(this).children('a').length) {
					$('#camp_cauta_companie').val($text).closest('tr').next().find('input:first').focus();
					if ($('div.box:visible').attr('id') === "box-persoane") {
						$('input#id_companie_persoana').val(id_companie);
					}
				}
				$('.tabel').fadeOut(timp_fadeout, function () {
					$('.tabel').empty();
					toggleEvents('submit_formular_persoana', true);
				}); // end function()
			},
			mouseenter: function () {
				$('.tabel .rec').removeClass('selected').css('background-color', '#FFFFFF');
				$(this).addClass('selected').css('background-color', '#CED5F5');
			},
			mouseleave: function () {
				$(this).removeClass('selected').css('background-color', '#FFFFFF');
			}
		}, '.tabel div');

		class_box.on('click', 'span.actiune', function (event) {
			event.preventDefault();
			var id,
				formdata,
				rezervat,
				box;

			box = $(this).closest('.box').attr('id').slice(4);

			id = parseInt($(this).parent().attr('id').slice(1));

			formdata = "formular-editare=" + id;

			rezervat = encodeURIComponent($(this).parent().next().text()); // se salveaza valoare din campul de dupa ID

			$.post('php/' + box + '.php', formdata, function (raspuns) {
				var a = $('#box-' + box),
					cale = 'php/' + box + '.php';
				if (raspuns === "Inexistent") {
					tranzitie_interior_box(a, cale);
				} else {
					a.fadeOut(timp_fadeout);
					a.queue('fx', function () {
						$(this).empty().append(raspuns);
						$(this).dequeue('fx');
					});
					a.fadeIn(timp_fadein);
					a.queue('fx', function () {
						$('form input').eq(1).data("rezervat", rezervat);
						toggleEvents('submit_formular_persoana', true);
						$(this).dequeue('fx');
					});
				}
			}); // end .post
		});

		//TODO de unit creaza companie si modifica companie in acelasi .on()
		id_box_companii.on('click', '#creaza_companie', function (event) {
			event.preventDefault();
			// administrare clienti, formular creare client nou, click pe "Salveaza"
			// console.debug($(this).attr('id'));
			var form, flag, box = $('#box-companii'), $url = "php/companii.php", values = [], $string = 'Compania', camp = $('form input');
			$('span.error').remove();
			camp.each(function (i) {
				$(this).val($(this).val().trim());
				values[i] = encodeURIComponent($(this).val());
			});
			formdata = "nume_test=" + values[0]; //nume client sau furnizor
			if (formdata.length > 14) {
				$.ajax({
					async: false,
					url: $url,
					data: formdata,
					success: function (date_primite) {
						if (date_primite != "este") {
							$('#c_nume').attr('src', 'images/yes_small.png').show();
						} else {
							camp.eq(0).after('<span class="error">' + $string + ' se află deja în baza de date</span>');
						}
					}
				});
			} else {
				camp.eq(0).after("<span class='error'>Minim 5 caractere.</span>");
			} // end if
			if (values[1].length < 5) { // adresa
				camp.eq(1).after('<span class="error">Minim 5 caractere.</span>');
			} else {
				$('#c_adresa').attr('src', 'images/yes_small.png').show();
			}
			if (values[2].length <= 2) { // oras
				camp.eq(2).after('<span class="error">Minim 3 caractere.</span>');
			} else {
				$('#c_oras').attr('src', 'images/yes_small.png').show();
			}
			if (values[3].length <= 4) {
				camp.eq(3).after('<span class="error">Minim 5 caractere.</span>');
			} else {
				$('#c_tara').attr('src', 'images/yes_small.png').show();
			}
			flag = $('form span.error').length;
			if (!flag) {
				$('form img').hide();
				var formdata = "salveaza=1&nume=" + values[0] + "&adresa=" + values[1] + "&oras=" + values[2] + "&tara=" + values[3];
				$.ajax({
					url: $url,
					data: formdata,
					timeout: 3000,
					error: function () {
						alert('Probleme de rețea');
					},
					success: function (raspuns) {
						if (raspuns == 'ok') {
							tranzitie_interior_box(box, $url)
						} else {
							box.append(raspuns);
						}
					}
				});
			}
		});
		id_box_companii.on('click', '#modifica_companie', function (event) {
			// administrare companii, formular editare client, click pe modifica
			event.preventDefault();
			var values = [], flag, $this = $("form").attr("id").slice(8), $url = "php/companii.php", $string = 'Compania', camp = $('form input');
			$('span.error').remove();

			camp.each(function (i) {
				$(this).val($(this).val().trim());
				values[i] = encodeURIComponent($(this).val());
			});
			var formdata = "nume_test=" + values[1]; //nume client sau furnizor pentru verificare
			var rezervat = $("form input").eq(1).data("rezervat");
			//		console.debug('Rezervat: ', rezervat);
			//		console.debug('Modificat: ', values[1]);
			if (formdata.length > 14) {
				if (rezervat != values[1]) {
					// nu este cel initial din camp
					$.ajax({
						url: $url,
						async: false,
						data: formdata,
						timeout: 3000,
						error: function () {
							alert('Probleme de rețea');
						},
						success: function (date_primite) {
							if (date_primite == "este") {
								camp.eq(1).after('<span class="error">' + $string + ' se află deja în baza de date</span>');
							} else {
								$('#c_nume').attr('src', '../images/yes_small.png').show();
							} // end else
						} // end succes
					}); // end ajax
				} // end if rezervat
				else {
					$('#c_nume').attr('src', '../images/yes_small.png').show();
					console.debug('Numele companiei nu s-a modificat.')
				}
			} else {
				camp.eq(1).after("<span class='error'>Minim 5 caractere.</span>");
			} // end if
			if (values[2].length < 5) { // adresa
				camp.eq(2).after('<span class="error">Minim 5 caractere.</span>');
			} else {
				$('#c_adresa').attr('src', '../images/yes_small.png').show();
			}
			if (values[3].length <= 2) { // oras
				camp.eq(3).after('<span class="error">Minim 3 caractere.</span>');
			} else {
				$('#c_oras').attr('src', '../images/yes_small.png').show();
			}
			if (values[4].length <= 4) {
				camp.eq(4).after('<span class="error">Minim 5 caractere.</span>');
			} else {
				$('#c_tara').attr('src', '../images/yes_small.png').show();
			}
			flag = $('form span.error').length;
			if (!flag) {
				$('form img').hide();
				$('form span.error').remove();
				formdata = "salveaza=2&id=" + values[0] + "&nume=" + values[1] + "&adresa=" + values[2] + "&oras=" + values[3] + "&tara=" + values[4];
				$.post('php/' + $this + '.php', formdata, function (date_primite) {
					if (date_primite == 'ok') {
						$('#box-' + $this).load('php/' + $this + '.php');
					} else {
						$('form span.error').remove();
						camp.eq(1).after('<span class="error">Acest nume este deja in baza de date.</span>');
					} // end else
				}); // end .post
			}
		});

		id_box_vanzatori.on('click', '#creaza_vanzator, #modifica_vanzator', function (event) {
			// administrare vanzatori, formular creare vanzator nou, click pe Creaza vanzatorul
			event.preventDefault();
			var camp, form, formdata, values = [], rezervat, modificat, id_vanzator;
			camp = $('form input');
			if (this.id == 'modifica_vanzator') {
				id_vanzator = camp.eq(0).val();
				camp = camp.slice(1);
			}
			$('form span.error').remove();
			camp.each(function (i) {
				$(this).val($(this).val().trim()); // rescriu valoarea campului fara spatii
				values[i] = encodeURIComponent($(this).val()); // creez array cu valorile din camp
				if (values[i].length < 3) {
					$(this).after('<span class="error">Minim 3 caractere.</span>')
				} // end if
			}); // end each
			if ($('form span.error').length) {
				return
			}
			rezervat = camp.eq(0).data('rezervat'); // daca rezervat exista se doreste modificare
			modificat = values[0] + '%20' + values[1];
			console.log('Modificat:', modificat);
			if (rezervat == modificat) { // rezervat exista, la modificare daca sunt la fel campurile se iese
				console.log('Modificare: Datele vânzătorului nu s-au modificat.');
				id_box_vanzatori.load('php/vanzatori.php');
				return
			}
			formdata = 'nume_test=1&nume=' + values[0] + '&prenume=' + values[1];
			$.post('php/vanzatori.php', formdata, function (data) {
				if (data == "este") {
					camp.eq(0).after('<span class="error">Este deja în baza de date.</span>');

				} else {
					if (!rezervat) {
						formdata = 'salveaza=1&nume=' + values[0] + '&prenume=' + values[1]; // save new
					} else {
						formdata = 'salveaza=2&nume=' + values[0] + '&prenume=' + values[1] + '&id=' + id_vanzator; // update existing
					}
					$.post('php/vanzatori.php', formdata, function () {
						// console.debug('Am salvat / modificat vanzatorule existent');
						id_box_vanzatori.load('php/vanzatori.php');
					});
				}
			});
		});

		id_box_oferta_noua.on('click', '#word', function (event) {
			// TESTARE WORD
			var _self = $(this).parent();
			var $id = $('form').find(":selected").val();
			if (!$id) {
				event.preventDefault();
				$('form select').addClass('required').children().text('Alegeți...');
				return;
			}
			$.ajax({
				async: false,
				url: 'php/word/genereaza_word.php',
				type: 'POST',
				data: {
					id_persoana: $id
				},
				timeout: 3000,
				error: function (jqXHR, textStatus) {
					if (textStatus === "timeout") {
						alert('Probleme de rețea');
						event.preventDefault();
					}
				},
				success: function () {
					_self.append('<p>Documentul s-a generat cu succes.</p>')
				}
			}); // end $.ajax
		});  // end .on

	})
})(); // ready
