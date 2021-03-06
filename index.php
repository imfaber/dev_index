<?php require('config.php'); ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<title><?php echo (isset($page_title)) ? $page_title : 'My Local Sites' ?></title>
	<meta name="viewport" content="width=device-width">
	<link rel="stylesheet" href="css/main.css">
</head>

<body>

	<div class="canvas">

		<header>

			<h1><?php echo (isset($page_title)) ? $page_title : 'My Local Sites' ?></h1>

			<nav>
				<ul>
					<?php
					foreach ( $dev_tools as $tool ) {
						printf( '<li><a href="%1$s" target="_blank">%2$s</a></li>', $tool['url'], $tool['name'] );
					}
					?>
				</ul>
			</nav>

		</header>

		<content class="cf">
			<?php
			foreach ( $dir as $d ) {
				$dirsplit = explode('/', $d);
				$dirname = $dirsplit[count($dirsplit)-2];

				printf( '<ul class="sites %1$s">', $dirname );

				foreach( glob( $d ) as $file )  {

					$project = basename($file);

					if ( in_array( $project, $hidden_sites )) continue;

					echo '<li>';

					$siteroot = sprintf( 'http://%1$s.%2$s', $project, $dirname);

		            // Display an icon for the site
					$icon_output = '<span class="no-img"></span>';
					foreach( $icons as $icon ) {

						if ( file_exists( $file . '/' . $icon ) ) {
							$icon_output = sprintf( '<img src="../'.$project.'/%2$s">', $siteroot, $icon );
							break;
		            	} // if ( file_exists( $file . '/' . $icon ) )

		            } // foreach( $icons as $icon )
		            echo $icon_output;

		            // Display a link to the site
		            $display_name = $project;
		            if ( array_key_exists( $project, $site_options ) ) {
		            	if ( is_array( $site_options[$project] ) )
		            		$display_name = array_key_exists( 'display_name', $site_options[$project] ) ? $site_options[$project]['display_name'] : $project;
		            	else
		            		$display_name = $site_options[$project];
		            }
		            $projecturl = array_key_exists('vhost_url', $site_options[$project]) ? $site_options[$project]['vhost_url'] : '../'.$project;
		            //printf( '<a class="site" href="%1$s">%2$s</a>', $siteroot, $display_name );
		            printf( '<a class="site" href="%1$s">%2$s</a>', $projecturl, $display_name );


					// Display an icon with a link to the admin area
		            $admin_url = '';
					// We'll start by checking if the site looks like it's a WordPress site
		            if ( is_dir( $file . '/wp-admin' ) )
		            	$admin_url = sprintf( 'http://%1$s/wp-admin', $siteroot );

					// If the user has defined an admin_url for the project we'll use that instead
		            if ( is_array( $site_options[$project] ) && array_key_exists( 'admin_url', $site_options[$project] ) )
		            	$admin_url = $site_options[$project]['admin_url'];

		            // If there's an admin url then we'll show it - the icon will depend on whether it looks like WP or not
		            if ( ! empty( $admin_url ) )
		            	printf( '<a class="%2$s icon" href="%1$s">Admin</a>', $admin_url, is_dir( $file . '/wp-admin' ) ? 'wp' : 'admin' );


		            echo '</li>';

				} // foreach( glob( $d ) as $file )

				echo '</ul>';

		   	} // foreach ( $dir as $d )
		   	?>
		   </content>



		   <footer class="cf">
		   	<p></p>
		   </footer>

		 </div>
		</body>
		</html>
