
		<div class="bodyDiv">

							<div class="container" id="container">
									<div class="form-container sign-up-container">
										<form action="index.php?action=LoginController" method="POST">
											<h1>Create Account</h1>
											<div class="switch-button">
											    <label class="toggle">
												    <input type="checkbox" name="type" checked>
												    <span class="labels" data-on="Student" data-off="Teacher"></span>
												  </label>
									  	</div>
											<input type="text" placeholder="Name" name="name_up" required/>
											<input type="email" placeholder="Email" name="email_up" required/>
											<input type="password" placeholder="Password"  name="pass_up" required/>
											<button name="submitUp">Sign Up</button>
										</form>

									</div>
									<div class="form-container sign-in-container">
										<form action="index.php?action=LoginController" method="POST">
											<h1>Sign in</h1>
											<input type="email" placeholder="Email" name="email_login" required/>
											<input type="password" placeholder="Password" name="pass_login" required/>
											<a href="<?php echo URL_BASE.'/index.php?action=PassController';?>">Forgot your password?</a>
											<button name="submitIn">Sign In</button>
										</form>
									</div>
									<div class="overlay-container">
										<div class="overlay">
											<div class="overlay-panel overlay-left">
												<h1>Welcome Back!</h1>
												<p>To keep connected with us please login with your personal info</p>
												<button class="ghost" id="signIn">Sign In</button>
											</div>
											<div class="overlay-panel overlay-right">
												<h1>Hello, Friend!</h1>
												<p>Enter your personal details and start journey with us</p>
												<button class="ghost" id="signUp">Sign Up</button>
											</div>
										</div>
									</div>
								</div>

								<!-- partial -->
								  <script  src="<?php echo URL_BASE.'./views/_common/js/script.js';?>"></script>

			</div>
