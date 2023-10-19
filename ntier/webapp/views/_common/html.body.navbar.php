<?php

	
							echo "	<div class='navb'>
										<nav>
											<a href=";
											echo URL_BASE.'/index.php?action=';echo ">Home</a>";
											if(isset($_SESSION['name'])){
												echo "<a href=";
												echo URL_BASE.'/index.php?action=AccountController&display=';
												echo ">Account</a>";

											}
											else {
												echo "<a href=";
												echo URL_BASE.'/index.php?action=LoginController';
												echo ">Login</a>";
											}
											
											echo "<a href='#'>Contact</a>
											<a href='#'>Portfolio</a>
											<a href=";
											echo URL_BASE.'/../index.php';
											echo ">Config</a>
											<div class='animation start-home'></div>
										</nav>
									</div>";
					
					



				if(isset($_SESSION['name'])){
																
					echo '<div class="disconnect-div"><a class="disButton" href=';
					echo URL_BASE.'/index.php?action=DisconnectController';
					echo ">Disconnect</a></div>";
				}
				

?>