<?php if (count($form_field_names) > 0) { ?>
<div class="report-custom-forms-text">

<?php


	foreach ($form_field_names as $field_id => $field_property)
	{
		if ($field_property['field_type'] == 8)
		{
			echo "";

			if (isset($field_propeerty['field_default']))
			{
				echo "<div class=\"" . $field_property['field_name'] . "\">";
			}
			else
			{
				echo "<div class=\"custom_div\">";
			}
			$title = html::specialchars($field_property['field_name']);
			
			switch ($title) {
				case "titulo-categorias":
						break;
				case "titulo-avanzado":
						break;
				default: 
						echo "<h2>" . Kohana::lang("ui_main.$title") . "</h2>";
						break;
			}
			
			
			echo "";

			continue;
		}
		elseif ($field_property['field_type'] == 9)
		{
			echo "</div>";
			continue;
		}

	

		// Get the value for the form field
		$value = $field_property['field_response'];

		// Check if a value was fetched
		if ($value == "" AND empty($show_empty))
			continue;

		if ($field_property['field_type'] == 1 OR $field_property['field_type'] > 3)
		{
			// Text Field Kohana::lang('ui_main'.html::specialchars($field_property['field_name']))
			// Is this a date field?
			$title = html::specialchars($field_property['field_name']);
			echo "<strong>" . Kohana::lang("ui_main.$title") . ": </strong>";
			
			
			switch ($title) {
				case "telefono":
						echo "" . $value ."<br/>";
						break;
			case "fecha-inicio":
						echo "" . $value ."<br/>";
						break;
			case "email":
						$p = str_split(trim($value));
								$new_mail = '';
								foreach ($p as $val) {
										$new_mail .= '&#'.ord($val).';';
								}
								echo "" . $new_mail."<br/>";
						break;
				case "sitio_web":
						$findme = 'http://';
						$pos = strpos($value, $findme);

						if ($pos === false) {
								echo "<a target=\"_blank\" href=\"http://".$value."\">http://" . $value ."</a><br/>";
						} else {
								echo "<a target=\"_blank\" href=\"".$value."\">" . $value ."</a><br/>";
						}
						

						break;
				default: 
						if (strpos($value,',') !== false) {
								$array = explode(',', $value);
								foreach ($array as &$x) {
										echo "" . Kohana::lang("ui_main.$x")." | ";
								}
								echo "<br>";
								
						} else {
								echo "" . Kohana::lang("ui_main.$value")."<br/>";
						}
						
						
						break;
			}
			
			
		}
		elseif ($field_property['field_type'] == 2)
		{
			// TextArea Field
			$title = html::specialchars($field_property['field_name']);
			echo "<h3>" . Kohana::lang("ui_main.$title") . "</h3>";
			echo "<class=\"answer\">" . $value ."";
		}
		elseif ($field_property['field_type'] == 3)
		{
			$title = html::specialchars($field_property['field_name']);
			echo "<strong>" . Kohana::lang("ui_main.$title") . ": </strong>";
			echo "<class=\"answer\">" . date('M d Y', strtotime($value)) . "";
		}
		//echo "</div>";
		echo "";
	}
?>

</div>
<?php } ?>