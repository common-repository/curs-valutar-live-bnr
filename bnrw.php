<?php  
/* 
Plugin Name: Curs Valutar Live BNR
Plugin URI: http://www.casedevanzare.ro 
Version: 1.0
Author: Stan Nicolae
Description: Afiseaza ca widget sau oriunde si pe orice pagina Cursul Valutar live al BNR - Banca Nationala a Romaniei. Curs actualizat in fiecare ora!
*/

	add_filter('widget_text', 'do_shortcode');
	define('BNRW_PATH', plugin_dir_path( __FILE__ ));
	require_once(BNRW_PATH.'/includes/functions.php');
	
	
?>