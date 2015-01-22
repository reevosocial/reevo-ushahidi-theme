<!-- File modified to be able to display translated version of each custom field. The translations are stored in the i18n folder of this theme. -->

<div id="custom_forms">

<?php
	// If the user has insufficient permissions to edit report fields, we flag this for a warning message
	$show_permission_message = FALSE;

	foreach ($disp_custom_fields as $field_id => $field_property)
	{
		// Is the field required
		$isrequired = ($field_property['field_required'])
			? "<span class='required'> *</span>"
			: "";

		// Private field
		$isprivate = ($field_property['field_ispublic_visible'])
			? '<span class="private">(' . Kohana::lang('ui_main.private') . ')</span>'
			: '';

		// Workaround for situations where admin can view, but doesn't have sufficient perms to edit.
		if (isset($custom_field_mismatch))
		{
			if(isset($custom_field_mismatch[$field_id]))
			{
				if($show_permission_message == FALSE)
				{
					echo '<small>'.Kohana::lang('ui_admin.custom_forms_insufficient_permissions').'</small><br/>';
					$show_permission_message = TRUE;
				}

				echo '<strong>'.$field_property['field_name'].'</strong><br/>';
				if (isset($form['custom_field'][$field_id]))
				{
					echo $form['custom_field'][$field_id];
				}
				else
				{
					echo Kohana::lang('ui_main.no_data');;
				}
				echo '<br/><br/>';
				//echo "</div>";
				continue;
			}
		}

		// Give all the elements an id so they can be accessed easily via javascript
		$id_name = 'id="custom_field_'.$field_id.'"';

		// Get the field value
		$field_value = ( ! empty($form['custom_field'][$field_id]))
			? $form['custom_field'][$field_id]
			: $field_property['field_default'];

		if ($field_property['field_type'] == 1)
		{
			// Text Field
			$tt = Kohana::lang("ui_main.tt-".$field_property['field_name']);
			echo '<div title="'.$tt.'" class="report_row report_row_text" id="custom_field_row_' . $field_id .'">';

			$field_options = customforms::get_custom_field_options($field_id);

			if (isset($field_options['field_hidden']) AND !isset($editor))
			{
				if($field_options['field_hidden'] == 1)
				{
					echo form::hidden($field_property['field_name'], $field_value);
				}
				else
				{
// 				  We take the field name from the custom field admin page and use it as parameter of the ui_main translation file. The exact field name is defined in the ui_main.php of each language in this specific theme
				
				  $name = Kohana::lang('ui_main.'.$field_property['field_name']);
					echo "<h4>" . $name . $isrequired . " " . $isprivate . "</h4>";
					echo form::input('custom_field['.$field_id.']', $field_value, $id_name .' class="text custom_text"');
				}
			}
			else
			{
				$name = Kohana::lang('ui_main.'.$field_property['field_name']);
				echo "<h4>" . $name . $isrequired . " " . $isprivate . "</h4>";
				echo form::input('custom_field['.$field_id.']', $field_value, $id_name .' class="text custom_text"');
			}
			echo "</div>";
		}
		elseif ($field_property['field_type'] == 2)
		{
			// TextArea Field
			$field_options = customforms::get_custom_field_options($field_id);
			if (isset($field_options['field_datatype']))
			{
				$extra_fields = $id_name . ' class="textarea custom_text" rows="3"';

				if ($field_options['field_datatype'] == 'text')
				{
					$tt = Kohana::lang("ui_main.tt-".$field_property["field_name"]);
					echo "<div title=\"$tt\" class=\"report_row\" id=\"custom_field_row_" . $field_id ."\">";
				  $name = Kohana::lang('ui_main.'.$field_property['field_name']);
					echo "<h4>" . $name . $isrequired . " " . $isprivate . "</h4>";
					echo form::textarea('custom_field['.$field_id.']', $field_value, $extra_fields);
					echo "</div>";
				}

				if ($field_options['field_datatype'] == 'markup')
				{
					$tt = Kohana::lang("ui_main.tt-".$field_property["field_name"]);
					echo "<div title=\"$tt\" class=\"report_row\" id=\"custom_field_row_" . $field_id ."\">";
				  $name = Kohana::lang('ui_main.'.$field_property['field_name']);
					echo "<h4>" . $name . $isrequired . " " . $isprivate . "</h4>";
					echo form::textarea('custom_field['.$field_id.']', $field_value, $extra_fields, false);
					echo "</div>";
				}

				if ($field_options['field_datatype'] == 'javascript')
				{
					if(isset($editor))
					{
						$name = $field_property['field_name'];
						echo "<div title=\"tt-$name\" class=\"report_row\" id=\"custom_field_row_" . $field_id ."\">";
				  $name = Kohana::lang('ui_main.'.$field_property['field_name']);
					echo "<h4>" . $name . $isrequired . " " . $isprivate . "</h4>";
						echo form::textarea('custom_field['.$field_id.']', $field_value, $extra_fields, false);
						echo "</div>";
					}
					else
					{
						echo '<script type="text/javascript">' . $field_property['field_default'] . '</script>';
					}
				}
			}
			else
			{
				echo "<div class=\"report_row\" id=\"custom_field_row_" . $field_id ."\">";
				$name = Kohana::lang('ui_main.'.$field_property['field_name']);
				echo "<h4>" . $name . $isrequired . " " . $isprivate . "</h4>";
				echo form::textarea('custom_field['.$field_id.']', $field_value, $id_name .' class="textarea custom_text" rows="3"');
				echo "</div>";
			}
		}
		elseif ($field_property['field_type'] == 3)
		{ // Date Field
			echo "<div class=\"report_row\" id=\"custom_field_row_" . $field_id ."\">";
			$name = Kohana::lang('ui_main.'.$field_property['field_name']);
			echo "<h4>" . $name . $isrequired . " " . $isprivate . "</h4>";
			echo form::input('custom_field['.$field_id.']', $field_value, ' id="custom_field_'.$field_id.'" class="text"');
			echo "<script type=\"text/javascript\">
				$(document).ready(function() {
				$(\"#custom_field_".$field_id."\").datepicker({
				showOn: \"both\",
				buttonImage: \"".url::file_loc('img')."media/img/icon-calendar.gif\",
				buttonImageOnly: true
				});
				});
			</script>";
			echo "</div>";
		}
		elseif ($field_property['field_type'] >=5 AND $field_property['field_type'] <=7)
		{
			// Multiple-selector Fields
			echo "<div class=\"report_row\" id=\"custom_field_row_" . $field_id ."\">";
			$name = Kohana::lang('ui_main.'.$field_property['field_name']);
			echo "<h4>" . $name . $isrequired . " " . $isprivate . "</h4>";
			$defaults = explode('::',$field_property['field_default']);

			$default = (isset($defaults[1])) ? $defaults[1] : 0;

			if (isset($form['custom_field'][$field_id]))
			{
				if($form['custom_field'][$field_id] != '')
				{
					$default = $form['custom_field'][$field_id];
				}
			}

			$options = explode(',',$defaults[0]);
			$html ='';
			switch ($field_property['field_type'])
			{
				case 5:
					foreach($options as $option)
					{
						$option = trim($option);
						$set_default = ($option == trim($default));
						$tt = Kohana::lang("ui_main.tt-".$option);
						$option_label = Kohana::lang('ui_main.'.$option);
						$html .= "<span title=\"$tt\" class=\"custom-field-option\">";
						$html .= form::label('custom_field['.$field_id.']'," ".$option_label." ");
						$html .= form::radio('custom_field['.$field_id.']',$option, $set_default, $id_name);
						$html .= "</span>";
					}
					break;
				case 6:
					$multi_defaults = !empty($field_property['field_response'])? explode(',', $field_property['field_response']) : NULL;

					$cnt = 0;
					$html .= "<table border=\"0\">";
					foreach($options as $option)
					{
						if ($cnt % 2 == 0)
						{
							$html .= "<tr>";
						}

						$html .= "<td>";
						$set_default = FALSE;

						if (!empty($multi_defaults))
						{
							foreach($multi_defaults as $key => $def)
							{
								$set_default = (trim($option) == trim($def));
								if ($set_default)
									break;
							}
						}
						$option = trim($option);
						$option_label = Kohana::lang('ui_main.'.$option);
						$tt = Kohana::lang("ui_main.tt-".$option);
						$html .= "<span title=\"$tt\" class=\"custom-field-option\">";
						$html .= form::checkbox("custom_field[".$field_id.'-'.$cnt.']', $option, $set_default, $id_name);
						$html .= form::label("custom_field[".$field_id.']'," ".$option_label);
						$html .= "</span>";

						$html .= "</td>";
						if ($cnt % 2 == 1 OR $cnt == count($options)-1)
						{
							$html .= "</tr>";
						}

						$cnt++;
					}
					// XXX Hack to deal with required checkboxes that are submitted with nothing checked
					$html .= "</table>";
					$html .= form::hidden("custom_field[".$field_id."-BLANKHACK]",'',$id_name);
					break;
				case 7:
					$ddoptions = array();
					// Semi-hack to deal with dropdown boxes receiving a range like 0-100
					if (preg_match("/[0-9]+-[0-9]+/",$defaults[0]) AND count($options == 1))
					{
						$dashsplit = explode('-',$defaults[0]);
						$start = $dashsplit[0];
						$end = $dashsplit[1];
						for($i = $start; $i <= $end; $i++)
						{
							$ddoptions[$i] = $i;
						}
					}
					else
					{
						foreach($options as $op)
						{
							$op = trim($op);
							$op = Kohana::lang('ui_main.'.$op);
							$ddoptions[$op] = $op;
						}
					}

					$html .= form::dropdown("custom_field[".$field_id.']',$ddoptions,$default,$id_name);
					break;

			}

			echo $html;
			echo "</div>";
		}
		elseif ($field_property['field_type'] == 8 )
		{
			//custom div
			if ($field_property['field_default'] != "")
			{
				echo "<div class=\"" . $field_property['field_default'] . "\" $id_name>";
			}
			else
			{
				echo "<div class=\"custom_div\" $id_name >";
			}

			$field_options = customforms::get_custom_field_options($field_id);

			if (isset($field_options['field_toggle']) && !isset($editor))
			{
				if ($field_options['field_toggle'] >= 1)
				{
					echo "<script type=\"text/javascript\">
						$(function(){
						
						var isExpanded_".$field_id." = new Boolean(true);
						$('#custom_field_" .$field_id . "_link').click(function() {
								//loadUrl('#custom_field_'.$field_id.'_link');
  							$('#custom_field_" .$field_id . "_inner').toggle('slow', function() {
									if (isExpanded_".$field_id.") {
										$('#custom_field_".$field_id."_link').addClass('expanded');
										isExpanded_".$field_id."=false;
										
										
									} else {
										$('#custom_field_".$field_id ."_link').removeClass('expanded');
										isExpanded_".$field_id."=true;
									}
									 
									// loadUrl('#custom_field_'.$field_id.'_link');
									//loadURL(http://google.com);
    						// Animation complete.
    						location.hash ='#custom_field_".$field_id."_link';
  							});
  							
						});
					});
					</script>";
					echo "<a  href=\"javascript:void(0);\" id=\"custom_field_" . $field_id ."_link\">";
				  $name = Kohana::lang('ui_main.'.$field_property['field_name']);
					echo "<h2>" . $name . "</h2>";
					echo "</a>";

					$inner_visibility = ($field_options['field_toggle'] == 2) ? "visible": "none";

					echo "<div id=\"custom_field_" . $field_id . "_inner\" style=\"display:$inner_visibility;\">";
				}
				else
				{
				  $name = Kohana::lang('ui_main.'.$field_property['field_name']);
					echo "<h2>" . $name . "</h2>";
					echo "<div id=\"custom_field_" . $field_id . "_inner\">";
				}
			}
			else
			{
			$name = Kohana::lang('ui_main.'.$field_property['field_name']);
			echo "<h2>" . $name . "</h2>";
				echo "<div id=\"custom_field_" . $field_id . "_inner\">";
			}
		}
		elseif ($field_property['field_type'] == 9)
		{
			// End of custom div
			echo "</div></div>";
			if (isset($editor))
			{
				echo "<h4 style=\"padding-top:0px;\">-------" . Kohana::lang('ui_admin.divider_end_field') . "--------</h4>";
			}
		}


		if (isset($editor))
		{
			$form_fields = '';
			$visibility_selection = array('0' => Kohana::lang('ui_admin.anyone_role'));
			$roles = ORM::factory('role')->find_all();
			foreach ($roles as $role)
			{
				$visibility_selection[$role->id] = ucfirst($role->name);
			}

			// Check if the field is required
			$isrequired = ($field_property['field_required'])
				? Kohana::lang('ui_admin.yes')
				: Kohana::lang('ui_admin.no');

			$form_fields .= "	<div class=\"forms_fields_edit\" style=\"clear:both\">
			<a href=\"javascript:fieldAction('e','EDIT',".$field_id.",".$form['id'].",".$field_property['field_type'].");\">EDIT</a>&nbsp;|&nbsp;
			<a href=\"javascript:fieldAction('d','DELETE',".$field_id.",".$form['id'].",".$field_property['field_type'].");\">DELETE</a>&nbsp;|&nbsp;
			<a href=\"javascript:fieldAction('mu','MOVE',".$field_id.",".$form['id'].",".$field_property['field_type'].");\">MOVE UP</a>&nbsp;|&nbsp;
			<a href=\"javascript:fieldAction('md','MOVE',".$field_id.",".$form['id'].",".$field_property['field_type'].");\">MOVE DOWN</a>
			</div>";
			echo $form_fields;
		}

		if ($field_property['field_type'] != 8 AND $field_property['field_type'] != 9)
		{
			//if we're doing custom divs we don't want these div's to get in the way.
			//echo "</div>";
		}
	}
?>
</div>