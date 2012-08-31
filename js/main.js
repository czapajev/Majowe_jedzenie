$(document).ready(function() {
	$('.add_s').click(add_s);
	check_partial();
	$.ajax({
		url: "php/cat_dropdown.php",
		success: function(data) {
			$('#cat_dropdown').html(data);
			$('#save_mat').click(function() {
				par = $('#add_material');
				brand = $('#m_marka').val();
				name = $('#m_nazwa').val();
				cat = $('#cat').val();
				i=1;
				skladniki ='';
				now = '';
				$('.skladnik').each(function() {
					skladniki += 'sk['+i+']='+$(this).val() + '&';
					if($(this).nextAll('input').attr('checked') == 'checked') {
						now += 'now[' + i + ']=true&';
					}
					i++;
				});
				saveData = 'cat=' + cat +'&brand=' + brand +'&name=' + name + '&' + skladniki + now;
				
				$.ajax({
					url: "php/save_mat.php",
					data: saveData,
					type: "POST",
					success: function(data) {
						if(data == 1) {
							$('#status').text('Materiał dodany').fadeIn();
							setTimeout(hide, 1500, new Array(true, par));
							$('#m_marka').val('');
							$('#m_nazwa').val('');	
							$('.skladnik').each(function() {
								$(this).val('');
							});		
						} else {
							$('#status').text('Wystąpił błąd').fadeIn();
							setTimeout(hide, 1500, new Array(false, par));
						}
					}
				})
			});

		}
	})
	
		
	$('#add_material_show,#add_material_show2').click(function () {
		$('#add_material').siblings().fadeOut(function() {
			$('#add_material').fadeIn();
		});
		
	});
	$('#settings_show').click(function() {
		$('#settings').siblings().fadeOut(function() {
			show_cat();
		});
		
	});
	$('.close').click(function() {
		$(this).parent().fadeOut();
	});
	$('#add_shopping_show, #add_shopping_show2').click(function() {
		$('#add_shopping').siblings().fadeOut(function() {
			show_list(true,'#add_shopping','101', $('#save_shopping'));
		});
		
	});
	$('#add_meal_show').click(function() {
		$('#add_meal').siblings().fadeOut(function() {
			show_list(true,'#add_meal','601',$('#save_meal'));
		});
		
	});
	
	$('#stock_report_show').click(function() {
		$('#stock_report').siblings().fadeOut(function() {
			$.ajax({
				url: "php/stock_report.php",
				success: function(data) {
					$('#stock').html(data);
					$('#stock_report').fadeIn();
				}
			});
		})
		
	});
	$('#add_cat').click(function() {
		sendData = "cat=" + $(this).prev().prev().val() + "&color=" + $(this).prev().val();
		a = $(this).prev();
		$.ajax({
			url: "php/cat_list.php",
			type: "POST",
			data: sendData,
			success: function() {
				show_cat();
				a.val('');
				a.prev().val('');
			}
		})
	});

});

function show_list(fade, referer, move, button) {
	sendData = 'ref=' + referer;
	$.ajax({
			type: "POST",
			data: sendData,
			url: "php/mat_list_show.php",
			success: function(data) {
				$('.mat_list').html(data);
				if(fade) {
					$(referer).fadeIn();
				}
				$('.inc').click(function() {
					val = $(this).prev().val()*1;
					val++;
					$(this).prev().val(val);
				});
				$('.dec').click(function() {
					val = $(this).next().val()*1;
					val--;
					if(val<0) {
						$('#status').text('Nie może byćmniej niż 0').fadeIn();
						setTimeout(hide, 1500, false);
						val = 0;
					}
					$(this).next().val(val);
				});
			}
		});
	button.unbind('click').click(function() {
		saveData = '';
		i=1;
		$(referer + ' .mat_list_line').each(function() {
			if($(this).val() >0) {
				//alert($(this).parent().parent().attr('id'));
				saveData += "mat[" + i +"]=" + $(this).parent().parent().attr('id') + "&qty[" + i + "]=" + $(this).val() +"&";
				if(referer == '#add_meal') {
					if($(this).parent().parent().next().children().eq(2).children().eq(0).val() != '') {
						saveData += "smak[" + i +"]=" + $(this).parent().parent().next().children().eq(2).children().eq(0).val() + "&";
					}
					if($(this).parent().parent().next().children().eq(3).children().eq(1).val() != '') {
						saveData += "comment[" + i +"]=" + $(this).parent().parent().next().children().eq(1).children().eq(0).val() + "&";
					}
				}
				i++;
			}
		});
		//alert(saveData);
		if(saveData.length > 0) {
			saveData += "mov=" + move;
			$.ajax({
				url: "php/save_movement.php",
				type: "POST",
				data: saveData,
				success: function(data) {
					if(data == 1) {
						//alert(referer);
						show_status("Udalo się", 1500, new Array(true,referer));
					} else {
						
						show_status(data, 1500, new Array(false,referer));
					}
				}
			});
		} else {
			alert(saveData);
			show_status('Brak produktów',1500,new Array(false,referer));
		}
	});
}

function show_cat() {
	$.ajax({
		url: "php/cat_list.php",
		success: function(data) {
			$('#cats').html(data);
			$('#settings').fadeIn();
			$('.color').blur(function() {
				$(this).css('background-color', $(this).val());
			});
			$('.change_ss').unbind('click').click(function() {
				color = $(this).parent().prev().prev().children().val();
				ss = $(this).parent().prev().children().val();
				cid = $(this).parent().parent().attr('id');
				sendData = 'color=' + color + '&ss=' + ss + '&cid=' + cid;
				$.ajax({
					url: "php/cat_list.php",
					data: sendData,
					type: "POST",
					success: function(data) {
						if(data ==  1) {
							show_status('Udało się', 1500,new Array(false, '#add_meal'));
							
						} else {
							show_status('Wystąpił błąd', 1500,new Array(false, '#add_meal'));
						}
					}
				})
				
			});
			$('.del_cat').unbind('click').click(function() {
				cid = $(this).parent().parent().attr('id');
				cthis = $(this).parent().parent();
				sendData = "delete=true&cid=" + cid;
				$.ajax({
					url: "php/cat_list.php",
					data: sendData,
					type: "POST",
					success: function(data) {
						if(data == '00') {
							//show_status('Udało się', 1500,new Array(false, '#add_meal'));
							cthis.slideUp();
						} else {
							show_status('Wystąpił błąd', 1500,new Array(false, '#add_meal'));
						}
					}
				})
			});
			$('#cat_color, .color').ColorPicker({
				onSubmit: function(hsb, hex, rgb, el) {
					hex2 = "#" + hex;
					$(el).val(hex2);
					$(el).css('background-color', $(el).val());
					$(el).ColorPickerHide();
				},
				onBeforeShow: function () {
					$(this).ColorPickerSetColor(this.value);
				}
			})
			.bind('keyup', function(){
				$(this).ColorPickerSetColor(this.value);
			})
			.bind('focus', function(){
				$(this).ColorPickerSetColor(this.value);
			})
		}
	})
}

add_s = function() {
		id = $(this).attr('id').slice(-1);
		$(this).attr('id','del_'+id).text('Usun').removeClass('add_s').addClass('del_s').unbind('click').click(del_s);
		
		nid = id+1;
		
		
		$(this).parent().after($('<div>')
		.append($('<input>').attr('type','text').attr('id','s_nazwa_'+nid).addClass('skladnik'))
		.append(' nowość ')
		.append($('<input>').attr('type','checkbox').attr('id','s_'+nid+'_now'))
		.append($('<a>').attr('href','#').attr('id','s_'+nid).addClass('add_s').text('Dodaj kolejny').click(add_s))
		
		);
}
	
del_s = function() {
	$(this).parent().detach();
	
}
hide = function(par) {
	if((par[0])) {
		$(par[1]).fadeOut();
	}
	$('#status').fadeOut('slow');
	show_list(false,par[1],'101',$('#save_shopping'));
}
function show_status(info, dur, par) {
	$('#status').html(info).fadeIn();
	setTimeout(hide, dur, par);
	$('#status').click(function() {
		$(this).fadeOut();
	})
}
function check_partial() {
	$.ajax({
		url: "php/check_partial.php",
		success: function(data) {
			show_status(data, 5000, new Array(false, '#add_meal'));
		}
	})
}
