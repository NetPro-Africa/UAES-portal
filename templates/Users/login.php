<!-- Main Wrapper -->
<style>
    .lback {
  background-image: url("../img/slider_0.jpg"); /* The image used */
  background-color: #cccccc; /* Used if the image is unavailable */
  height: 627px; /* You must set a specified height */
  background-position: center; /* Center the image */
  background-repeat: no-repeat; /* Do not repeat the image */
  background-size: cover; /* Resize the background image to cover the entire container */
}
</style>


        <div class="main-wrapper lback">
			<div class="account-content">
				
				<div class="container">
				
					<!-- Account Logo -->
					<div class="account-logo" style="margin-bottom: -45px;">
                                                    
<a href="/"><?=  $this->Html->image('android-chrome-512x512.png',['alt'=>SCHOOL]) ?>  </a>
					</div>
					<!-- /Account Logo -->
					<br /><br /><br />
					<div class="account-box">
						<div class="account-wrapper">
							<h3 class="account-title">Welcome To UAES Portal</h3>
<!--							<p class="account-subtitle">Access to our dashboard</p>-->
							
							<!-- Account Form -->
							 <?= $this->Form->create(null) ?> <?= $this->Flash->render() ?>
								<div class="form-group">
                                                                    <label>Email Address </label> <a target="blank" href="#" class="float-right" title="login to staff portal">Staff Portal</a>
				<?= $this->Form->control('username',['label'=>false,'class'=>'form-control','required',
        'type'=>'email','placeholder'=>'email address']) ?>
								</div>
								<div class="form-group">
									<div class="row">
										<div class="col">
											<label>Password</label>
										</div>
										<div class="col-auto">
                                                                              <?=  $this->Html->link(' Forgot password?', ['controller' => 'Users', 'action' => 'forgotpassword'], ['title' => 'forgot password', 'class' => 'text-muted'])?>
											
										</div>
									</div>
					 <?= $this->Form->control('password', ['label'=>false,'class'=>'form-control','required',
                                     'placeholder'=>'password']) ?>
								</div>
								<div class="form-group text-center">
									<button class="btn btn-primary account-btn" type="submit">Login</button>
								</div>
								
                                                         <br />
                       <!-- google single signin button --> <br />
                                     <br />
                      <a href="https://uaes.education/appsfolder/uaes-app.apk" title="download UAES Mobile app">Download UAES Mobile App</a>
                    
							    <?= $this->Form->end() ?>
							<!-- /Account Form -->
							
						</div>
					</div>
				</div>
			</div>
        </div>
		<!-- /Main Wrapper -->
                

                   
                    
              