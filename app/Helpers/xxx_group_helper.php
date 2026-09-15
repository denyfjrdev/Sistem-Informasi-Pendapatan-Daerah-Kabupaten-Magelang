<?php  	
	if ( ! function_exists('create_checkbox_action'))
	{
		function create_checkbox_action($data, $post = NULL) {
			$str = '';
			if(isset($data)){
				foreach($data as $value){
					$checked = '';
					if(!is_null($post)){
						if(in_array( $value['MenuActionId'] , $post)){
							$checked = 'checked';
						}
					}
					$str .=  '<li class="action"><input type="checkbox" name="menu[]" value="'. $value['MenuActionId'] .'"  '.$checked.'>&nbsp;&nbsp;'. $value['MenuActionName'] .'</li>';
				}
			}
			return $str;
		}
	}

	if ( ! function_exists('create_checkbox_menu'))
	{
		function create_checkbox_menu( $data, $parrent, $post  = NULL)
		{
			$str = '';
			
			if(isset($data[$parrent])){ 
				foreach($data[$parrent] as $value){
					$checked = '';
					if(!is_null($post)){
						if(in_array( $value['MenuActionId'] , $post)){
							$checked = 'checked';
						}
					}
					
					$child = create_checkbox_menu($data, $value['MenuId'] , $post);
					if( $child ){
						$str .= '<li><input type="checkbox" name="menu[]" value="'. $value['MenuActionId'] .'" '.$checked.'>&nbsp;&nbsp;'. $value['MenuName'] .' <ul>'. $child .'</ul></li>';
					} else {
						if(isset($value['Action'])){
							$str .= '<li><input type="checkbox" name="menu[]" value="'. $value['MenuActionId'] .'" '.$checked.'>&nbsp;&nbsp;'. $value['MenuName'] .' <ul>'. create_checkbox_action($value['Action'], $post) .'</ul></li>';
						} else {
							$str .= '<li><input type="checkbox" name="menu[]" value="'. $value['MenuActionId'] .'" '.$checked.'>&nbsp;&nbsp;'. $value['MenuName'] .'</li>';
						}
					}
				}
			}
			return $str;
		} 
	}