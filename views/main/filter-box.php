			<!-- filters box -->
			<div id="the-filters" class="map-menu-box">
			
				<?php
				
				// Action::main_sidebar_pre_filters - Add Items to the Entry Page before filters
				Event::run('ushahidi_action.main_sidebar_pre_filters');
				
				
				function get_category_tree_view()
					{
						$category_data = get_category_tree_data(TRUE);
						
						// Generate and return the HTML
						return _generate_treeview_html($category_data);
					}
					
					/**
					* Get categories as an tree array
					* @param bool Get category count?
					* @param bool Include hidden categories
					* @return array
					**/
					function get_category_tree_data($count = FALSE, $include_hidden = FALSE)
					{
						
						if (isset($_GET['all'])) {
							$include_hidden = TRUE;
						} else  {
							$include_hidden = FALSE;
						} 

						
// 						$logged_in_id = Auth::instance()->get_user()->id;
						
						// echo "USUARIO: $logged_in_id";
						//Show hidden cats if its logged.
//  						if ($logged_in) {	$include_hidden = TRUE;	}
						
						// To hold the category data
						$category_data = array();
						
						// Database table prefix
						$table_prefix = Kohana::config('database.default.table_prefix');
						
						// Database instance
						$db = new Database();
						
						
						// Fetch the other categories
						if ($count)
						{
							$sql = "SELECT c.id, c.parent_id, c.category_title, c.category_color, c.category_image, c.category_image_thumb, COUNT(i.id) report_count "
								. "FROM ".$table_prefix."category c "
								. "LEFT JOIN ".$table_prefix."category c_parent ON (c.parent_id = c_parent.id) "
								. "LEFT JOIN ".$table_prefix."incident_category ic ON (ic.category_id = c.id) "
								. "LEFT JOIN ".$table_prefix."incident i ON (ic.incident_id = i.id AND i.incident_active = 1 ) "
								. "WHERE 1=1 "
								. (!$include_hidden ? "AND c.category_visible = 1 " : "")
								. (!$include_hidden ? "AND (c_parent.category_visible = 1 OR c.parent_id = 0)" : "") // Parent must be visible, or must be top level
								. "GROUP BY c.id "
								. "ORDER BY c.category_position ASC";
						}
						else
						{
							$sql = "SELECT c.id, c.parent_id, c.category_title, c.category_color, c.category_image, c.category_image_thumb "
								. "FROM ".$table_prefix."category c "
								. "LEFT JOIN ".$table_prefix."category c_parent ON (c.parent_id = c_parent.id) "
								. "WHERE 1=1 "
								. (!$include_hidden ? "AND c.category_visible = 1 " : "")
								. (!$include_hidden ? "AND (c_parent.category_visible = 1 OR c.parent_id = 0)" : "") // Parent must be visible, or must be top level
								. "ORDER BY c.category_position ASC";
						}
						
						// Create nested array - all in one pass
						foreach ($db->query($sql) as $category)
						{
							// If we didn't fetch report_count set fake value
							if (!$count)
							{
							$category->report_count = 0;
							}
							
							// If this is a parent category, just add it to the array
							if ($category->parent_id == 0)
							{
								// save children and report_count if already been created.
								$children = isset($category_data[$category->id]['children']) ? $category_data[$category->id]['children'] : array();
								$report_count = isset($category_data[$category->id]['report_count']) ? $category_data[$category->id]['report_count'] : 0;
								
								$category_data[$category->id] = array(
									'category_id' => $category->id,
									'category_title' => html::escape(Category_Lang_Model::category_title($category->id)),
									'category_description' => html::escape(Category_Lang_Model::category_description($category->id)),
									'category_color' => $category->category_color,
									'category_image' => $category->category_image,
									'children' => $children,
									'category_image_thumb' => $category->category_image_thumb,
									'parent_id' => $category->parent_id,
									'report_count' => $category->report_count + $report_count
								);
							}
							// If this is a child, add it underneath its parent category
							else
							{
								// If we haven't processed the parent yet, add placeholder parent category
								if (! array_key_exists($category->parent_id, $category_data))
								{
									$category_data[$category->parent_id] = array(
										'category_id' => $category->parent_id,
										'category_title' => '',
										'category_description' => '',
										'parent_id' => 0,
										'category_color' => '',
										'category_image' => '',
										'category_image_thumb' => '',
										'children' => array(),
										'report_count' => 0
									);
								}
								
								// Add children
								$category_data[$category->parent_id]['children'][$category->id] = array(
									'category_id' => $category->id,
									'category_title' => html::escape(Category_Lang_Model::category_title($category->id)),
									'category_description' => html::escape(Category_Lang_Model::category_description($category->id)),
									'parent_id' => $category->parent_id,
									'category_color' => $category->category_color,
									'category_image' => $category->category_image,
									'category_image_thumb' => $category->category_image_thumb,
									'report_count' => $category->report_count,
									'children' => array()
								);
								// Add to parent report count too
								$category_data[$category->parent_id]['report_count'] += $category->report_count;
							}
							//$total = $total + 1;
						}
// 						print_r ($category_data);
						
						return $category_data;
					}
					
					
					
					/**
					* Traverses an array containing category data and returns a tree view
					*
					* @param array $category_data
					* @return string
					*/
					
					function _generate_treeview_html($category_data)
					{
						// To hold the treeview HTMl
						$tree_html = "";
						$x=1;
						$loop = 1;
						foreach ($category_data as $id => $category)
						{
							// Determine the category class
							$category_class = ($category['parent_id'] > 0)? "report-listing-category-child" : "";
							

							
							if ($category['parent_id'] > 0) { // I am a child
										$category_image = $category['category_image'] ? html::image(array('src'=> url::convert_uploaded_to_abs($category['category_image']), 'style'=>'float:left;padding-right:5px;margin-top: 3px;')) : NULL;
							
										$tree_html .= "<li class=\"".$category_class."\">"
											. "<a href=\"#\" class=\"cat_selected\" id=\"cat_".$id."\" title=\"{$category['category_description']}\">"
											. "<span class=\"item-swatch\" style=\"background-color: #".$category['category_color']."\">$category_image</span>"
											. "<span class=\"item-title\">".html::strip_tags($category['category_title'])."</span>"
											. "<span class=\"item-count\">".$category['report_count']."</span>"
											. "</a></li>";
							} else {
							
							// I am parent
							
							if($loop % 2 == 1) {
									$type = "even";
							} else {
									$type = "odd";
							}
							
						// Action::main_sidebar_pre_filters - Add Items to the Entry Page before filters

				$default_map_all ="";
				$default_map_all_icon ="";
				$color_css = "";
				$all_cat_image = html::image(array(							'src'=>$default_map_all_icon						));
				
							

					
					$category_image = $category['category_image'] ? html::image(array('src'=> url::convert_uploaded_to_abs($category['category_image']), 'style'=>'float:left;padding-right:5px;margin-top: 3px;')) : NULL;
										$start ="";
						$total_cats = Incident_Model::get_total_reports(TRUE);
						if ($x==1) { $start='<ul id="category_switch" class="category-filters">					<li class="item-odd">
						<a title="'. Kohana::lang('ui_main.all_categories').'" class="active" id="cat_0" href="#">
							
							<span class="category-title">'.Kohana::lang('ui_main.all_categories').'</span>
							<span id="total-count" class="item-count">' . $total_cats. '</span>
						</a>
					</li>';$x=2; } else { $start='</ul>'; }
										
										
										
										$tree_html .= $start."<li class=\"item-".$type." ".$category_class."\">"
											. "<a href=\"#\" class=\"cat_selected\" id=\"cat_".$id."\" title=\"{$category['category_description']}\">"
											. "<span class=\"item-swatch\" style=\"background-color: #".$category['category_color']."\">$category_image</span>"
											. "<span class=\"item-title\">".html::strip_tags($category['category_title'])."</span>"
											. "<span class=\"item-count\">".$category['report_count']."</span>"
											. '</a><div id="child_'.$id.'" class="hide"><ul>';
								
							}
							
							
											
							$tree_html .= _generate_treeview_html($category['children']);
							$loop = $loop + 1;
						}
						
						// Return
						
						return $tree_html;
					}
				
				
				
				$color_css = 'class="category-icon swatch" style="background-color:#'.$default_map_all.'"';
				$all_cat_image = '';
					if ($default_map_all_icon != NULL)
					{
						$all_cat_image = html::image(array(
							'src'=>$default_map_all_icon
						));
						$color_css = 'class="category-icon"';
					}
				
				$print = get_category_tree_view();
				
				echo $print;
	
				echo '</ul>';

				
				
				
				
				?>
				
			
						
			</div>
			<!-- / filters box -->
