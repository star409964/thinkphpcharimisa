$(document).ready(function() {
	//themes, change CSS with JS
	//default theme(CSS) is cerulean, change it if needed
	var defaultTheme = 'cerulean';

	var currentTheme = $.cookie('currentTheme') == null ? defaultTheme : $.cookie('currentTheme');
	var msie = navigator.userAgent.match(/msie/i);
	$.browser = {};
	$.browser.msie = {};
	switchTheme(currentTheme);

	$('.navbar-toggle').click(function(e) {
		e.preventDefault();
		$('.nav-sm').html($('.navbar-collapse').html());
		$('.sidebar-nav').toggleClass('active');
		$(this).toggleClass('active');
	});

	var $sidebarNav = $('.sidebar-nav');

	// Hide responsive navbar on clicking outside
	$(document).mouseup(function(e) {
		if(!$sidebarNav.is(e.target) // if the target of the click isn't the container...
			&&
			$sidebarNav.has(e.target).length === 0 &&
			!$('.navbar-toggle').is(e.target) &&
			$('.navbar-toggle').has(e.target).length === 0 &&
			$sidebarNav.hasClass('active')
		) // ... nor a descendant of the container
		{
			e.stopPropagation();
			$('.navbar-toggle').click();
		}
	});

	$('#themes a').click(function(e) {
		e.preventDefault();
		currentTheme = $(this).attr('data-value');
		$.cookie('currentTheme', currentTheme, {
			expires: 365
		});
		switchTheme(currentTheme);
	});

	//当前项目路径
	function getRootPath() {
		var pathName = window.location.pathname.substring(1);
		var webName = pathName == '' ? '' : pathName.substring(0, pathName.indexOf('/'));
		return window.location.protocol + '//' + window.location.host + '/' + webName + '/';
	}

	function switchTheme(themeName) {
		if(themeName == 'classic') {
			$('#bs-css').attr('href', getRootPath() + 'Public/bower_components/bootstrap/dist/css/bootstrap.min.css');
		} else {
			$('#bs-css').attr('href', getRootPath() + 'Public/css/bootstrap-' + themeName + '.min.css');
		}

		$('#themes i').removeClass('glyphicon glyphicon-ok whitespace').addClass('whitespace');
		$('#themes a[data-value=' + themeName + ']').find('i').removeClass('whitespace').addClass('glyphicon glyphicon-ok');
	}

	//ajax menu checkbox
	$('#is-ajax').click(function(e) {
		$.cookie('is-ajax', $(this).prop('checked'), {
			expires: 365
		});
	});
	$('#is-ajax').prop('checked', $.cookie('is-ajax') === 'true' ? true : false);

	//disbaling some functions for Internet Explorer
	if(msie) {
		$('#is-ajax').prop('checked', false);
		$('#for-is-ajax').hide();
		$('#toggle-fullscreen').hide();
		$('.login-box').find('.input-large').removeClass('span10');

	}

	//给菜单 添加 选中状态 初始化
//	$('ul.main-menu li a').each(function() {
//		if($($(this))[0].href == String(window.location))
//			$(this).parent().addClass('active');
//	});

	//establish history variables
	var
		History = window.History, // Note: We are using a capital H instead of a lower h
		State = History.getState(),
		$log = $('#log');

	//bind to State Change
	History.Adapter.bind(window, 'statechange', function() { // Note: We are using statechange instead of popstate
		var State = History.getState(); // Note: We are using History.getState() instead of event.state
		$.ajax({
			url: State.url,
			success: function(msg) {
				$('#content').html($(msg).find('#content').html());
				$('#loading').remove();
				$('#content').fadeIn();
				var newTitle = $(msg).filter('title').text();
				$('title').text(newTitle);
				docReady();
			}
		});
	});

	//ajaxify menus
	$('a.ajax-link').click(function(e) { 
		if(msie) e.which = 1;
		if(e.which != 1 || !$('#is-ajax').prop('checked') || $(this).parent().hasClass('active')) return;
		e.preventDefault();
		$('.sidebar-nav').removeClass('active');
		$('.navbar-toggle').removeClass('active');
		$('#loading').remove();
		$('#content').fadeOut().parent().append('<div id="loading" class="center">Loading...<div class="center"></div></div>');
		var $clink = $(this);
		History.pushState(null, null, $clink.attr('href'));
		$('ul.main-menu li.active').removeClass('active');
		$clink.parent('li').addClass('active');
	});

	$('.accordion > a').click(function(e) {
		e.preventDefault();
		var $ul = $(this).siblings('ul');
		var $li = $(this).parent();
		if($ul.is(':visible')) $li.removeClass('active');
		else $li.addClass('active');
		$ul.slideToggle();
	});

	$('.accordion li.active:first').parents('ul').slideDown();

	//other things to do on document ready, separated for ajax calls
	docReady();
});

function docReady() {
	//prevent # links from moving to top
	$('a[href="#"][data-top!=true]').click(function(e) {
		e.preventDefault();
	});

	//notifications
	$('.noty').click(function(e) {
		e.preventDefault();
		var options = $.parseJSON($(this).attr('data-noty-options'));
		noty(options);
	});

	//chosen - improves select
	$('[data-rel="chosen"],[rel="chosen"]').chosen();

	//tabs
	$(document).on('click','#myTab a',function(e){
		e.preventDefault();
		$(this).tab('show');
	})
	//$('#myTab a:first').tab('show');
//	$('#myTab a').click(function(e) {
//		e.preventDefault();
//		$(this).tab('show');
//	});

	//tooltip
	$('[data-toggle="tooltip"]').tooltip();

	//auto grow textarea
	$('textarea.autogrow').autogrow();

	//popover
	$('[data-toggle="popover"]').popover();

	//iOS / iPhone style toggle switch
	$('.iphone-toggle').iphoneStyle();

	//star rating
	$('.raty').raty({
		score: 4 //default stars
	});

	//uploadify - multiple uploads
	$('#file_upload').uploadify({
		'swf': 'misc/uploadify.swf',
		'uploader': 'misc/uploadify.php'
			// Put your options here
	});

	//gallery controls container animation
	$('ul.gallery li').hover(function() {
		$('img', this).fadeToggle(1000);
		$(this).find('.gallery-controls').remove();
		$(this).append('<div class="well gallery-controls">' +
			'<p><a href="#" class="gallery-edit btn"><i class="glyphicon glyphicon-edit"></i></a> <a href="#" class="gallery-delete btn"><i class="glyphicon glyphicon-remove"></i></a></p>' +
			'</div>');
		$(this).find('.gallery-controls').stop().animate({
			'margin-top': '-1'
		}, 400);
	}, function() {
		$('img', this).fadeToggle(1000);
		$(this).find('.gallery-controls').stop().animate({
			'margin-top': '-30'
		}, 200, function() {
			$(this).remove();
		});
	});

	//gallery image controls example
	//gallery delete
	$('.thumbnails').on('click', '.gallery-delete', function(e) {
		e.preventDefault();
		//get image id
		//alert($(this).parents('.thumbnail').attr('id'));
		$(this).parents('.thumbnail').fadeOut();
	});
	//gallery edit
	$('.thumbnails').on('click', '.gallery-edit', function(e) {
		e.preventDefault();
		//get image id
		//alert($(this).parents('.thumbnail').attr('id'));
	});

	//gallery colorbox
	$('.thumbnail a').colorbox({
		rel: 'thumbnail a',
		transition: "elastic",
		maxWidth: "95%",
		maxHeight: "95%",
		slideshow: true
	});

	//gallery fullscreen
	$('#toggle-fullscreen').button().click(function() {
		var button = $(this),
			root = document.documentElement;
		if(!button.hasClass('active')) {
			$('#thumbnails').addClass('modal-fullscreen');
			if(root.webkitRequestFullScreen) {
				root.webkitRequestFullScreen(
					window.Element.ALLOW_KEYBOARD_INPUT
				);
			} else if(root.mozRequestFullScreen) {
				root.mozRequestFullScreen();
			}
		} else {
			$('#thumbnails').removeClass('modal-fullscreen');
			(document.webkitCancelFullScreen ||
				document.mozCancelFullScreen ||
				$.noop).apply(document);
		}
	});

	//tour
	if($('.tour').length && typeof(tour) == 'undefined') {
		var tour = new Tour();
		tour.addStep({
			element: "#content",
			/* html element next to which the step popover should be shown */
			placement: "top",
			title: "Custom Tour",
			/* title of the popover */
			content: "You can create tour like this. Click Next." /* content of the popover */
		});
		tour.addStep({
			element: ".theme-container",
			placement: "left",
			title: "Themes",
			content: "You change your theme from here."
		});
		tour.addStep({
			element: "ul.main-menu a:first",
			title: "Dashboard",
			content: "This is your dashboard from here you will find highlights."
		});
		tour.addStep({
			element: "#for-is-ajax",
			title: "Ajax",
			content: "You can change if pages load with Ajax or not."
		});
		tour.addStep({
			element: ".top-nav a:first",
			placement: "bottom",
			title: "Visit Site",
			content: "Visit your front end from here."
		});

		tour.restart();
	}

	//datatable
	//	$('.datatable').dataTable({
	//		"sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-12'i><'col-md-12 center-block'p>>",
	//		"sPaginationType": "bootstrap",
	//		"oLanguage": {
	//			"sLengthMenu": "_MENU_ records per page"
	//		}
	//	});
	//删除当前的row 模块
	$('.btn-close').click(function(e) {
		e.preventDefault();
		$(this).parent().parent().parent().fadeOut();
	});
	$('.btn-minimize').click(function(e) {
		e.preventDefault();
		var $target = $(this).parent().parent().next('.box-content');
		if($target.is(':visible')) $('i', $(this)).removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
		else $('i', $(this)).removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
		$target.slideToggle();
	});
	//弹出模态框
	$('.btn-setting').click(function(e) {
		e.preventDefault();
		$('#myModal').modal('show');
	});

	$('#calendar').fullCalendar({
		header: {
			left: 'prev,next today',
			center: 'title',
			right: 'month,agendaWeek,agendaDay'
		},
		defaultDate: '2014-06-12',
		events: [{
			title: 'All Day Event',
			start: '2014-06-01'
		}, {
			title: 'Long Event',
			start: '2014-06-07',
			end: '2014-06-10'
		}, {
			id: 999,
			title: 'Repeating Event',
			start: '2014-06-09T16:00:00'
		}, {
			id: 999,
			title: 'Repeating Event',
			start: '2014-06-16T16:00:00'
		}, {
			title: 'Meeting',
			start: '2014-06-12T10:30:00',
			end: '2014-06-12T12:30:00'
		}, {
			title: 'Lunch',
			start: '2014-06-12T12:00:00'
		}, {
			title: 'Birthday Party',
			start: '2014-06-13T07:00:00'
		}, {
			title: 'Click for Google',
			url: 'http://google.com/',
			start: '2014-06-28'
		}]
	});

	/*
	 * 复选框全部选择
	 * 编号：2016-11-21
	 * */
	if($('.checkall').length > 0) {
		$(document).on("click",'.checkall',function() {
			var parentTable = $(this).parents('table');
			var ch = parentTable.find('tbody input[type=checkbox]');
			if($(this).is(':checked')) {
				//check all rows in table
				ch.each(function() {
					$(this).prop('checked', true);
					$(this).parent().addClass('checked'); //used for the custom checkbox style
					$(this).parents('tr').addClass('selected'); // to highlight row as selected
				});
			} else {
				//uncheck all rows in table
				ch.each(function() {
					$(this).prop('checked', false);
					$(this).parent().removeClass('checked'); //used for the custom checkbox style
					$(this).parents('tr').removeClass('selected');
				});

			}
		});
	}
	/*
	 * 日期模块
	 * 编号：2016-11-21
	 * */
	$(document).on(
		'focus.datepicker.data-api click.datepicker.data-api',
		'[data-provide="mydatepicker"]',
		function(e){
			$(this).datepicker({
					format: "yyyy/mm/dd",
					language: "zh-CN"
				});
		}
	);
	
	
	
	
	/*
	 * 所有列表页 状态点击函数
	 * 编号：2016-11-21
	 * */
	$(document).on("click", ".lyx_status", (function() {
		var url = $(this).attr('href');
		var th = $(this);
		$.ajax({
			url: url,
			type: 'get',
			dataType: 'json',
			success: function(data) {
				if(data.status == 1) {
					//alert(data.content);
					noty({
						text: data.content,
						layout: "top",
						timeout: 1000
					});
					th.parent('td').html(data.other);
				} else {
					noty({
						text: data.content,
						layout: "top",
						type: "error",
						timeout: 1000
					});
				}
			}
		});
		return false;
	}));
	/*
	 * 内容显示在模态框中
	 * 编号：2016-11-21
	 * */
	$(document).on("click", ".modal_add",function() {
		$('#myModal').modal();
		var that = this;
		var title = $(that).data('title');
		var url = $(that).attr('href');
		$("#myModal").find('.modal-body').load(url,
			function() {
				$('#myModal').modal({
					keyboard: false
				});
				$('#myModal').find('h3').html(title?title:'新增');
				$('#myModal').modal('show');
			}
		);
	});
	
	/*
	 * 编辑内容显示在模态框中
	 * 编号：2016-11-21
	 * */
	$(document).on("click", ".modal_edit",function() {
		$('#myModal').modal();
		var that = this;
		var title = $(that).data('title');
		var url = $(that).attr('href');
		$("#myModal").find('.modal-body').load(url,
			function() {
				$('#myModal').modal({
					keyboard: false
				});
				$('#myModal').find('h3').html(title?title:'编辑');
				$('#myModal').modal('show');
			}
		);
		return false;
	});
	
	/*
	 * 模态框下提交表单
	 * 编号：2016-11-21
	 * */
	$('#modal_submit').on('click',function(){
		$('.model_insert').submit();
	});	
	/*
	 * 批量删除 提交
	 * 编号：2016-11-21
	 * */
	$(document).on("click",'#table_del',function(){
		/*判断是否有选择   有就执行删除 并且消除这几行  没有 就提示*/
			var parentTable = $('.table');
			var ch = parentTable.find('tbody input[type=checkbox]');
			var pd = false;
			ch.each(function() {
				if($(this).is(':checked')) {
					pd = true;
				}
			});
			if(pd == false) {
				alert('没有选择');
			} else {
				var conf = confirm('确定要删除所选项吗?');
				if(conf) {
					var url = $('.del-form').attr('action');
					$.ajax({
						url: url,
						type: 'post',
						data:$('.del-form').serialize(),
						success: function(res) {
							if(res.status==1){
								$('.select-btn').click();
							}
						},
						error:function(e){
							noty({
									text: '服务器请求错误',
									layout: "top",
									timeout: 1000
								});
						}
					});
				}
			}
			return false;
		});
}
	/*
	 * 删除 单个选项
	 * 编号：2017-1-1
	 * */
	$(document).off('click','.btn_del').on("click",'.btn_del',function(){
				var that = this;
				var conf = confirm('确定要删除所选项吗?');
				if(conf) {
					var url = $(this).attr('href');
					$.ajax({
						url: url,
						type: 'get',
						dataType:'json',
						success: function(res) {
							if(res.status==1){
								$(that).parents('.water').remove();
							}
						},
						error:function(e){
							noty({
									text: '服务器请求错误',
									layout: "top",
									timeout: 1000
								});
						}
					});
				}
			return false;
		});




	/*
	 * 菜单的ajax list
	 * 编号：2016-11-21
	 * */
	$(document).on("click", ".ajax-list", (function() {
		var url = $(this).attr('href');
		$('.main-menu .active').removeClass('active');
//		$(this).parents('li').addClass('active');
		$(this).parent().addClass('active');
		var th = $(this);
		contentHtml(url);
		return false;
	}));
	
	/*
	 * 加载内容到 content 模块中
	 */
	function contentHtml(url){
		$.ajax({
			url: url,
			type: 'get',
			success: function(data) {
				$('#content').html(data);
			},
			error:function(e){
				noty({
						text: '服务器请求错误',
						layout: "top",
						timeout: 1000
					});
			}
		});
	}
	
	/*
	 * 列表-查询数据请求
	 * 2016-11-27
	 */
	$(document).on("click", ".select-btn", (function() {
		var url = $(this).parent("form").attr('action');
		$.ajax({
			url: url,
			type: 'post',
			data:$(this).parent("form").serialize(),
			success: function(data) {
				$('#content').html(data);
			},
			error:function(e){
				noty({
						text: '服务器请求错误',
						layout: "top",
						timeout: 1000
					});
			}
		});
		return false;
	}));
	/*
	 * ajax-分页请求
	 * 2016-11-27
	 */
	$(document).on("click", ".ajax_page a", (function() {
		var url = $(this).attr('href');
		$.ajax({
			url: url,
			type: 'post',
			data:$(".search-form").serialize(),
			success: function(data) {
				$('#content').html(data);
			},
			error:function(e){
				noty({
						text: '服务器请求错误',
						layout: "top",
						timeout: 1000
					});
			}
		});
		return false;
	}));
	
	/*
	 * 模态框 加载特效
	 */
	
	function waiting(type,offon){
		switch (type){
			case 1: var html = '<div class="load1"><div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div><div class="rect5"></div></div>';
				break;
			case 2: var html = '<div class="load2"></div>';
				break;
			case 3: var html = '<div class="load3"><div class="double-bounce1"></div><div class="double-bounce2"></div></div>';
				break;
			case 4: var html = '<div class="load4"><div class="cube1"></div><div class="cube2"></div></div>';
				break;
			case 5: var html = '<div class="load5"><div class="dot1"></div><div class="dot2"></div></div>';
				break;
			case 6: var html = '<div class="load6"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div>';
				break;
			case 7: var html = '<div class="load7"></div>';
				break;	
			default: var html = '<div class="load8"><div class="load8-container container1"><div class="circle1"></div><div class="circle2"></div><div class="circle3"></div><div class="circle4"></div></div><div class="load8-container container2"><div class="circle1"></div><div class="circle2"></div><div class="circle3"></div><div class="circle4"></div></div><div class="load8-container container3"><div class="circle1"></div><div class="circle2"></div><div class="circle3"></div><div class="circle4"></div></div></div>';
				break;
		}
		if(offon!=true){
			$('#modal_waiting').modal('hide');
		}else{
			$('#modal_waiting').find('.modal-content').html(html);
			$('#modal_waiting').modal('show');
		}
	}
	/*
	 * 模态框整个 填充内容
	 */
	function model_all_html(url){
		$('#modal_other').modal();
		$("#modal_other").load(url,
			function() {
				$('#modal_other').modal({
					keyboard: false
				});
				$('#modal_other').modal('show');
			}
		);
	}
	
//additional functions for data table
//
//$.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings) {
//	return {
//		"iStart": oSettings._iDisplayStart,
//		"iEnd": oSettings.fnDisplayEnd(),
//		"iLength": oSettings._iDisplayLength,
//		"iTotal": oSettings.fnRecordsTotal(),
//		"iFilteredTotal": oSettings.fnRecordsDisplay(),
//		"iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
//		"iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
//	};
//}
//$.extend($.fn.dataTableExt.oPagination, {
//	"bootstrap": {
//		"fnInit": function(oSettings, nPaging, fnDraw) {
//			var oLang = oSettings.oLanguage.oPaginate;
//			var fnClickHandler = function(e) {
//				e.preventDefault();
//				if(oSettings.oApi._fnPageChange(oSettings, e.data.action)) {
//					fnDraw(oSettings);
//				}
//			};
//
//			$(nPaging).addClass('pagination').append(
//				'<ul class="pagination">' +
//				'<li class="prev disabled"><a href="#">&larr; ' + oLang.sPrevious + '</a></li>' +
//				'<li class="next disabled"><a href="#">' + oLang.sNext + ' &rarr; </a></li>' +
//				'</ul>'
//			);
//			var els = $('a', nPaging);
//			$(els[0]).bind('click.DT', {
//				action: "previous"
//			}, fnClickHandler);
//			$(els[1]).bind('click.DT', {
//				action: "next"
//			}, fnClickHandler);
//		},
//
//		"fnUpdate": function(oSettings, fnDraw) {
//			var iListLength = 5;
//			var oPaging = oSettings.oInstance.fnPagingInfo();
//			var an = oSettings.aanFeatures.p;
//			var i, j, sClass, iStart, iEnd, iHalf = Math.floor(iListLength / 2);
//
//			if(oPaging.iTotalPages < iListLength) {
//				iStart = 1;
//				iEnd = oPaging.iTotalPages;
//			} else if(oPaging.iPage <= iHalf) {
//				iStart = 1;
//				iEnd = iListLength;
//			} else if(oPaging.iPage >= (oPaging.iTotalPages - iHalf)) {
//				iStart = oPaging.iTotalPages - iListLength + 1;
//				iEnd = oPaging.iTotalPages;
//			} else {
//				iStart = oPaging.iPage - iHalf + 1;
//				iEnd = iStart + iListLength - 1;
//			}
//
//			for(i = 0, iLen = an.length; i < iLen; i++) {
//				// remove the middle elements
//				$('li:gt(0)', an[i]).filter(':not(:last)').remove();
//
//				// add the new list items and their event handlers
//				for(j = iStart; j <= iEnd; j++) {
//					sClass = (j == oPaging.iPage + 1) ? 'class="active"' : '';
//					$('<li ' + sClass + '><a href="#">' + j + '</a></li>')
//						.insertBefore($('li:last', an[i])[0])
//						.bind('click', function(e) {
//							e.preventDefault();
//							oSettings._iDisplayStart = (parseInt($('a', this).text(), 10) - 1) * oPaging.iLength;
//							fnDraw(oSettings);
//						});
//				}
//
//				// add / remove disabled classes from the static elements
//				if(oPaging.iPage === 0) {
//					$('li:first', an[i]).addClass('disabled');
//				} else {
//					$('li:first', an[i]).removeClass('disabled');
//				}
//
//				if(oPaging.iPage === oPaging.iTotalPages - 1 || oPaging.iTotalPages === 0) {
//					$('li:last', an[i]).addClass('disabled');
//				} else {
//					$('li:last', an[i]).removeClass('disabled');
//				}
//			}
//		}
//	}
//});