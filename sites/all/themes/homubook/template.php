<?php

/**
 * @file
 * template.php
 */
function homubook_preprocess_image_style(&$vars) {
        if ($vars['style_name'] == 'user-picture') {
        	$vars['attributes']['class'][] = 'img-responsive img-circle'; // can be 'img-rounded', 'img-circle', or 'img-thumbnail'	
        } else {
        	$vars['attributes']['class'][] = 'img-responsive'; // can be 'img-rounded', 'img-circle', or 'img-thumbnail'
        } 
}

function homubook_preprocess_page(&$variables) {
  // preprocess navbar
   	// search box
    $search_box = drupal_get_form('search_form');
  	$variables['search_box'] = drupal_render($search_box); 

  	// navbar
  	$variables['pro_send_work'] = l(t('PRO Submit Work'), 'pro-send-work',array('attributes' => array('class' => array('btn btn-primary btn-sm navbar-right btn_nav'), 'role' => array('button'))));

  	// footer variable
  	$variables['disclaimer_title'] = t('Disclaimer');
  	$variables['disclaimer'] = t('<br><br>copyright &copy; 2015 <br> homubook.com <br><br> all rights reserved.');
  	
  	$menu = menu_navigation_links('main-menu');
  	$menu_title = menu_load('main-menu');
    $variables['footer_sitemap'] = theme('links__menu_footer_menu', array('links' => $menu));
    $variables['footer_sitemap_title'] = '<h2>'.$menu_title['title'].'</h2>';

    $menu2 = menu_navigation_links('menu-footer-contact');
  	$menu_title2 = menu_load('menu-footer-contact');
    $variables['footer_contact'] = theme('links__menu_contact', array('links' => $menu2));
    $variables['footer_contact_title'] = '<h2>'.$menu_title2['title'].'</h2>';

  // remove front page trash
    if ($variables['is_front']) {
    $variables['title'] = '';
    $variables['page']['content']['system_main']['default_message'] = array(); // This will remove the 'No front page content has been created yet.'
  }
}

/*
 *  Form alter to add missing bootstrap classes and role to search form.
 */
function homubook_form_alter(&$form, &$form_state, $form_id) {
	  if ($form_id == 'search_form') {
	    $form['#attributes']['class'][] = 'navbar-form';
	    $form['#attributes']['role'][] = 'search';
	  }
}

function homubook_preprocess_views_view(&$vars) {
	  $name=$vars['name'];
	  $display_id=$vars['display_id'];

	  if ($name=='list_article') {
	    $vars['row_wrapper_class']='row';
	  } else {
	    $vars['row_wrapper_class']=''; // so we don't have to isset() or !empty() it in our template :)
	  }
}
function homubook_preprocess_views_view_fields(&$vars) {
        global $user;
        $basepath = base_path();
        if (!empty($vars['row']->nid)) {
	        $node = $vars['row']->nid;
	        $current = drupal_get_destination()['destination'];
	        if ($user->uid) {
	          $vars['flag_overlay'] = '<div class="flag_button">'.flag_create_link('favorite', $node).'</div>';
	        } else {
	          $vars['flag_overlay'] = '<div class="flag_button"><a href="'.$basepath.'user/login?destination='.$current.'"><span>+ Favorites</span></a></div>';
	        }
    	}
}

function homubook_preprocess_node(&$variables) {
    $basepath = base_path();
    switch($variables['type']) {
      case 'photo':
        global $user;
        // get referenced portfolio id and pro id
        $portfolio_id = $variables['node']->field_portfolio['und']['0']['target_id'];
        $pro_id = db_query('SELECT field_pro_target_id FROM {field_data_field_pro} WHERE entity_id = :entity_id', array(':entity_id' => $portfolio_id))->fetchfield();
        // get pro node
        $query = new EntityFieldQuery();
        $entities = $query->entityCondition('entity_type', 'node')
                ->propertyCondition('type', 'pro')
                ->propertyCondition('nid', $pro_id)
                ->propertyCondition('status', 1)
                ->range(0,1)
                ->execute();
        if (!empty($entities['node'])) {
          $temp0 = array_keys($entities['node']);
          $temp1 = array_shift($temp0);
          $pro = node_load($temp1);
          $variables['pro'] = node_view($pro);
          $variables['pro_title'] = $pro->title;
        }

        if ($user->uid) {
          $variables['logged_in'] = TRUE;
          $variables['flag_overlay'] = '<div class="flag_button">'.flag_create_link('favorite', $variables['node']->nid).'</div>';
        } else {
          $current = drupal_get_destination()['destination'];
          $variables['logged_in'] = FALSE;
          $variables['flag_overlay'] = '<div class="flag_button"><a href="'.$basepath.'user/login?destination='.$current.'"><span>+ Favorites</span></a></div>';
        }
        break;


    }
}