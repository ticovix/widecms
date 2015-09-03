$(function(){

	config_upload_modal = function(data){
		var $realname = data.real_name;
		var $id_input = data.id_input;
		var $id_function = data.id_function;
		var $url = data.url;

		var modal = "#Modal-"+$realname;
		var image_resize = $(modal+" .input_image_resize");
		var image_x = $(modal+" .input_image_x");
		var image_y = $(modal+" .input_image_y");
		var image_ratio = $(modal+" .input_image_ratio");
		var image_ratio_x = $(modal+" .input_image_ratio_x");
		var image_ratio_y = $(modal+" .input_image_ratio_y");
		var image_ratio_crop = $(modal+" .input_image_ratio_crop");
		var image_ratio_fill = $(modal+" .input_image_ratio_fill");
		var image_background_color = $(modal+" .input_image_background_color");
		var image_reflection_height = $(modal+" .input_reflection_height");
		var image_reflection_opacity = $(modal+" .input_reflection_opacity");
		var image_reflection_space = $(modal+" .input_reflection_space");
		var image_opacity = $(modal+" .input_image_opacity");
		var image_border_transparent = $(modal+" .input_border_transparent");
		var image_greyscale = $(modal+" .input_image_greyscale");
		var image_border = $(modal+" .input_image_border");
		var image_border_color = $(modal+" .input_image_border_color");
		var image_border_opacity = $(modal+" .input_image_border_opacity");
		var thumbnail_300px = $(modal+" .thumbnail_300px");
		var thumbnail_200px = $(modal+" .thumbnail_200px");
		var thumbnail_100px = $(modal+" .thumbnail_100px");
                var image_convert = $(modal+" .input_image_convert");
                var image_text = $(modal+" .input_image_text");
                var image_text_color = $(modal+" .input_image_text_color");
                var image_text_background = $(modal+" .input_image_text_background");
                var image_text_opacity = $(modal+" .input_image_text_opacity");
                var image_text_background_opacity = $(modal+" .input_image_text_background_opacity");
                var image_text_padding = $(modal+" .input_image_text_padding");
                var image_text_position = $(modal+" .input_image_text_position");
                var image_text_direction = $(modal+" .input_image_text_direction");
                var image_text_x = $(modal+" .input_image_text_x");
                var image_text_y = $(modal+" .input_image_text_y");

		var image = $(".image-"+$realname);
		$(modal+" input").bind("keyup", function(e) {
		  var code = e.keyCode || e.which; 
		  if (code  == 13) {      
		  	$(".btn-"+$realname).click(); 
		  	e.preventDefault();
		    return false;
		  }     
		});
		$(modal+" input").bind("keyup keypress", function(e) {
		  var code = e.keyCode || e.which; 
		  if (code  == 13) {      
		    e.preventDefault();
		    return false;
		  }
		});
		image_resize.change(function(){
			if(image_resize.val()=="true"){
				image_x.removeAttr("disabled");
				image_y.removeAttr("disabled");
				image_ratio.removeAttr("disabled");
				image_ratio_x.removeAttr("disabled");
				image_ratio_y.removeAttr("disabled");
				image_ratio_crop.removeAttr("disabled");
				image_ratio_fill.removeAttr("disabled");
			}else{
				image_x.attr("disabled","disabled");
				image_y.attr("disabled","disabled");
				image_ratio.attr("disabled","disabled");
				image_ratio_x.attr("disabled","disabled");
				image_ratio_y.attr("disabled","disabled");
				image_ratio_crop.attr("disabled","disabled");
				image_ratio_fill.attr("disabled","disabled");
			}
		});
		$(modal+" .refresh-"+$realname).click(function(){
			var x = 0;
			var arr = new Array();
			if(image_resize.val()!="false"){
				arr[x] = "image_resize="+image_resize.val();
			}
			if(image_x.val()!=""){
				x++;
				arr[x] = "image_x="+image_x.val();
			}
			if(image_y.val()!=""){
				x++;
				arr[x] = "image_y="+image_y.val();
			}
			if(image_ratio.val()!=""){
				x++;
				arr[x] = "image_ratio="+image_ratio.val();
			}
			if(image_ratio_x.val()!=""){
				x++;
				arr[x] = "image_ratio_x="+image_ratio_x.val();
			}
			if(image_ratio_y.val()!=""){
				x++;
				arr[x] = "image_ratio_y="+image_ratio_y.val();
			}
			if(image_ratio_crop.val()!=""){
				x++;
				arr[x] = "image_ratio_crop="+image_ratio_crop.val();
			}
			if(image_ratio_fill.val()!=""){
				x++;
				arr[x] = "image_ratio_fill="+image_ratio_fill.val();
			}
			if(image_background_color.val()!=""){
				x++;
				arr[x] = "image_background_color="+image_background_color.val();
			}
			if(image_reflection_height.val()!=""){
				x++;
				arr[x] = "image_reflection_height="+image_reflection_height.val();
			}
			if(image_reflection_opacity.val()!=""){
				x++;
				arr[x] = "image_reflection_opacity="+image_reflection_opacity.val();
			}
			if(image_reflection_space.val()!=""){
				x++;
				arr[x] = "image_reflection_space="+image_reflection_space.val();
			}
			if(image_opacity.val()!=""){
				x++;
				arr[x] = "image_opacity="+image_opacity.val();
			}
			if(image_border_transparent.val()!=""){
				x++;
				arr[x] = "image_border_transparent="+image_border_transparent.val();
			}
			if(image_greyscale.val()!=""){
				x++;
				arr[x] = "image_greyscale="+image_greyscale.val();
			}
			if(image_border.val()!=""){
				x++;
				arr[x] = "image_border="+image_border.val();
			}
			if(image_border_color.val()!=""){
				x++;
				arr[x] = "image_border_color="+image_border_color.val().replace('#','');;
			}
			if(image_border_opacity.val()!=""){
				x++;
				arr[x] = "image_border_opacity="+image_border_opacity.val();
			}
                        if(image_convert.val()!=""){
				x++;
				arr[x] = "image_convert="+image_convert.val();
			}
                        if(image_text.val()!=""){
				x++;
				arr[x] = "image_text="+image_text.val();
			}
                        if(image_text_color.val()!=""){
				x++;
				arr[x] = "image_text_color="+image_text_color.val().replace('#','');;
			}
                        if(image_text_background.val()!=""){
				x++;
				arr[x] = "image_text_background="+image_text_background.val().replace('#','');
			}
                        if(image_text_opacity.val()!=""){
				x++;
				arr[x] = "image_text_opacity="+image_text_opacity.val();
			}
                        if(image_text_background_opacity.val()!=""){
				x++;
				arr[x] = "image_text_background_opacity="+image_text_background_opacity.val();
			}
                        if(image_text_padding.val()!=""){
				x++;
				arr[x] = "image_text_padding="+image_text_padding.val();
			}
                        if(image_text_position.val()!=""){
				x++;
				arr[x] = "image_text_position="+image_text_position.val();
			}
                        if(image_text_direction.val()!=""){
				x++;
				arr[x] = "image_text_direction="+image_text_direction.val();
			}
                        if(image_text_x.val()!=""){
				x++;
				arr[x] = "image_text_x="+image_text_x.val();
			}
                        if(image_text_y.val()!=""){
				x++;
				arr[x] = "image_text_y="+image_text_y.val();
			}

			image.attr("src",$url+"/view-image?"+arr.join("&"));
		});
		$(".btn-"+$realname).click(function(e){

			$.ajax({
				url: $url+"/view/ajax/upload.php",
				dataType: "json",
				type: "POST",
				data: {
						image_resize:image_resize.val(),
						image_x:image_x.val(),
						image_y:image_y.val(),
						image_ratio:image_ratio.val(),
						image_ratio_x:image_ratio_x.val(), 
						image_ratio_y:image_ratio_y.val(),
						image_ratio_crop:image_ratio_crop.val(),
						image_ratio_fill:image_ratio_fill.val(),
						image_background_color:image_background_color.val(),
						image_reflection_height:image_reflection_height.val(),
						image_reflection_space:image_reflection_space.val(),
						image_reflection_opacity:image_reflection_opacity.val(),
						image_opacity:image_opacity.val(),
						image_border_transparent:image_border_transparent.val(),
						image_greyscale:image_greyscale.val(),
						image_border:image_border.val(),
						image_border_color:image_border_color.val(),
						image_border_opacity:image_border_opacity.val(),
						thumbnail_300px:thumbnail_300px.is(':checked'),
						thumbnail_200px:thumbnail_200px.is(':checked'),
						thumbnail_100px:thumbnail_100px.is(':checked'),
                                                image_convert:image_convert.val(),
                                                image_text:image_text.val(),
                                                image_text_color:image_text_color.val(),
                                                image_text_background:image_text_background.val(),
                                                image_text_opacity:image_text_opacity.val(),
                                                image_text_background_opacity:image_text_background_opacity.val(),
                                                image_text_padding:image_text_padding.val(),
                                                image_text_position:image_text_position.val(),
                                                image_text_direction:image_text_direction.val(),
                                                image_text_x:image_text_x.val(),
                                                image_text_y:image_text_y.val(),
						function: $id_function,
						id_input: $id_input
				},
				success: function(data){
					if(data.success==true){
						alert("Upload atualizado com sucesso!");
						//location.reload();
					}else if(data.message != ""){
						alert(data.message);
					}
				}
			});
			return false;
			e.preventDefault();
		});
	}
});