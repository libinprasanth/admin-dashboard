(function($){
	$(document).ready(function(){
    $('body').on('click', '.removeDashboard', function(){
      if(confirm("Are you sure want to delete?")){
			  $(this).closest('.col').remove();
			}
    }); 
   
		$('body').on('click', '.editDashboard', function(){ 
      $(this).closest('.dashboard-boxes').find('.name-box').toggle();
      $(this).closest('.dashboard-boxes').find('.name-placeholder').toggle();
			$(this).closest('.dashboard-boxes').find('.path-placeholder').toggle();
			$(this).closest('.dashboard-boxes').find('.path-box').toggle();
    });
		var icon_target = '';
		$('body').on('click', '.icon-placeholder', function(){  
		  $('.ui-dashboard-modal').addClass('active');
			$('.ui-dashboard-modal-overlay').addClass('active');
			$('input[name="icon-chooser"]').prop('checked', false);
			icon_target = $(this).closest('.dashboard-boxes').attr('data-id'); 
		});
		$('body').on('click', '.ui-dashboard-modal-overlay', function(){ 
		  $('.ui-dashboard-modal').removeClass('active');
			$('.ui-dashboard-modal-overlay').removeClass('active');
			icon_target = '';
		});
		$('input[name="icon-chooser"]').change(function(){
			var value = $('input[name="icon-chooser"]:checked').val();
			$(icon_target).find('.icon-box').val(value);
			$(icon_target).find('.icon-placeholder').attr('class',value).addClass('icon-placeholder'); 
			$('.ui-dashboard-modal').removeClass('active');
			$('.ui-dashboard-modal-overlay').removeClass('active');
		});
		
    //$('.ui-dashboard-modal').addClass('active');
    //$('.ui-dashboard-modal-overlay').addClass('active');

		 
		$('body').on('click', '.add-new-item', function(){
			var now = Math.floor(Date.now() / 1000);
			var options = '';
			for(i=0;i<window.menus.length;i++){
				var m = window.menus[i];
				options += "<option value='"+m['path']+"'>"+m['name']+"</option>";
			}
			var con = '<div class="col portlet portlet-new ui-sortable-handle" id="icon-holder--'+now+'"> <div class="dashboard-boxes" data-icon="la la-gear" data-name="title" data-path="" data-id="#icon-holder--'+now+'"> <a href="javascript:;" class="removeDashboard"><i class="la la-trash"></i></a> <a href="javascript:;" class="editDashboard"><i class="la la-pencil"></i></a> <div class="dashboard-boxes-inner"><i class="icon-placeholder la la-gear"></i><input type="text" class="icon-box" name="dashboard['+now+'][icon]" value="la la-gear" style="display: none;"><h5><span class="name-placeholder">title</span><input type="text" class="name-box" name="dashboard['+now+'][name]" value="title" style="display: none;"></h5><h6 class=""> <span class="path-placeholder">enter url</span><select class="path-box" name="dashboard['+now+'][path]" style="display: none;">'+options+'</select></h6></div></div></div>';
			$(con).insertAfter($(this).closest('.col'));
		}); 
		
		$('.searhIcons input').on('input',function(){
			var val = $(this).val();
			$('.dsj .icon-box-in i').each(function(){
				var v = $(this).attr('class');
				if(v.indexOf(val) != -1){
					$(this).closest('.icon-box-in').show();
				} else {
					$(this).closest('.icon-box-in').hide();
				}
			});
		});
		
		$('.name-box').on('change',function(){
			var val = $(this).val();
			$(this).closest('h5').find('.name-placeholder').html(val);
		});
		
		$('.path-box').on('change',function(){
			var val = $(this).val();
			$(this).closest('h6').find('.path-placeholder').html(val);
		});
		 
		$('.sortableCol').sortable({items: '.portlet'}); 
	});
})(jQuery);