<!-- Main Wrapper -->
<style>
    .lback {
  background-image: url("../img/image5.jpg"); /* The image used */
  background-color: #cccccc; /* Used if the image is unavailable */
  height: 630px; /* You must set a specified height */
  background-position: center; /* Center the image */
  background-repeat: no-repeat; /* Do not repeat the image */
  background-size: cover; /* Resize the background image to cover the entire container */
}
</style>
        <div class="main-wrapper lback">
		
			<div class="account-content">
				
				<div class="container">
				
					<!-- Account Logo -->
					<div class="account-logo" style="margin-bottom: -40px;">
                                             
<a href="/"><?=  $this->Html->image('logo.png',['alt'=>SCHOOL]) ?>  </a>
					</div>
					<!-- /Account Logo -->
					<br /><br /><br />
					<div class="account-box">
						<div class="account-wrapper">
							<h3 class="account-title">Forgot Password?</h3>
							<p class="account-subtitle">Enter your email address to get a password reset link</p>
							
							<!-- Account Form -->
							 <?= $this->Form->create(null) ?>
								<div class="form-group">
									<label>Email Address</label>
								<?=$this->Form->control('username',['label'=>false,'class'=>'form-control','required','type'=>'email','placeholder'=>'username/email address'])?>  
								</div>
								<div class="form-group text-center">
									<button class="btn btn-primary account-btn" type="submit">Reset Password</button>
								</div>
								<div class="account-footer">
									<p>Remember your password? <?= $this->Html->link(__('Login'), ['controller'=>'Users','action' => 'login'],['title'=>'login to your account']) ?></p>
								</div>
							<?= $this->Form->end() ?>
							<!-- /Account Form -->
							
						</div>
					</div>
				</div>
			</div>
        </div>
		<!-- /Main Wrapper -->