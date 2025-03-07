 
  <header id="s-top">
			<div class="wrappertop">
				<h2 class="float-left">
                	<table border="0">
                    	<tr>
                        	<td>
                            	<img src="<?php echo base_url();?>assets/images/logo.png" width="70"/> 
                            </td>
                            <td>&nbsp;
                            
                            </td>
                            <td>
                            	Lembaga Ilmu Pengetahuan Indonesia
                    			<br>Puslit Kimia
                            </td>
                        </tr>
                    </table> 
				</h2>
				<div class="float-right">
					<p>
						Welcome visitor, please
						
						 <?php if (isset($current_user->email)) : ?>
					  
							<?php if (has_permission('Site.Content.View')) : ?>
							 
								<?php echo anchor(SITE_AREA, 'Control Panel'); ?>
							 

							<?php endif; ?>
							 
						 
								<a href="<?php echo site_url('logout');?>">
									<?php echo lang('bf_action_logout') ?>
								</a>
							 
						 
					<?php else :  ?>

						 
						<a href="<?php echo site_url('login');?>" class="login-btn">
								<?php echo lang('bf_action_login') ?>
							</a>
					here (For staff member only)
					<?php endif; ?>
					</p>
                    <?php
					$module= $this->uri->segment(1);
					?>
					<form action="<?php echo base_url();?>index.php/" method="get">
                    <select id="s_cat" name="s_cat" class="input-small">
                        <option <?php if($module=="penelitian") echo "selected"; ?> value="penelitian">Penelitian</option>
                        <option <?php if($module=="produk") echo "selected"; ?> value="produk">Produk</option>
                        <option value="kerjasama">Kerjasama</option>
                        <option value="temuilmiah">Temu Ilmiah</option>
                        <option value="hki">Hak Kekayaan Intelektual</option>
                        <option value="pi">Publikasi</option>
                        <option value="peneliti">Sumber Daya Manusia</option>
                    </select>
						<input id="keyword" placeholder="Masukan Keyword" value="<?php echo isset($keyword)? $keyword:""; ?>" name="keyword" type="text" />
					</form>
                    
					 
				</div>
			</div>
		</header>
		<!-- #s-top -->
		<nav id="main-menu">
			<div class="wrapper">
				<ul>
					
                    <li>
						<a href="<?php echo base_url(); ?>">
                        	<img src="<?php echo base_url()."assets/images/home-14.png";?>" alt="Home" width="35px" title="Home" />
                        </a> 
					</li>
                    <li>
						<a href="<?php echo base_url()?>index.php/peneliti">SDM</a>
					</li>
                    <li>
						<a href="<?php echo base_url()?>index.php/penelitian">Penelitian</a>
					</li>
					
                    <li>
                    	<a href="<?php echo base_url()?>index.php/pi">Publikasi</a>
					</li>	 
                    <li>
                    	<a href="<?php echo base_url()?>index.php/hki">HKI</a>
					 
					</li>
                     <li>
						<a href="<?php echo base_url()?>index.php/produk">Produk</a>
					</li>
                    <li>
						<a href="<?php echo base_url()?>index.php/temuilmiah">Temu Ilmiah</a>
					</li>
                    <li>
						<a href="<?php echo base_url()?>index.php/kerjasama">Kerjasama</a>
					</li>
                   
				</ul>
				 
			</div>
		</nav>
        <div id="divloading">
        
        </div>
        