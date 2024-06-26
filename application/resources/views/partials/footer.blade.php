<section id="footer" class="hidden">
		<div class="container">
			<div class="row text-center text-xs-center text-sm-left text-md-left">
				<div class="col-xs-12 col-sm-6 col-md-6">
					<div class="card bg-light">
                    <div class="body block-header">
                    	<h5>{{__('Help and Support')}}</h5>
                    	<ul class="list-unstyled quick-links">
						<li><a href="{{url('/')}}/{{app()->getLocale()}}/about"><i class="fa fa-angle-double-right"></i>{{__('About')}}</a></li>
						<li><a href="{{url('/')}}/{{app()->getLocale()}}/faq"><i class="fa fa-angle-double-right"></i>{{__('FAQ')}}</a></li>
						<li><a href="{{url('/')}}/{{app()->getLocale()}}/terms-of-use"><i class="fa fa-angle-double-right"></i>{{__('Terms of Use')}}</a></li>
						<li><a href="{{url('/')}}/{{app()->getLocale()}}/privacy-policy"><i class="fa fa-angle-double-right"></i>{{__('Privacy Policy')}}</a></li>
					</ul>
                    </div>
                	</div>
					
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
					<div class="card overflowhidden l-seagreen">
		        		<div class="body">
		        			<h5 class="text-purple">{{__('Accepting Wallet Cash payments in-store')}}</h5>
			            	<p>{{__('Outdo your online competition by giving your customers a better way to pay. Modernize your brick-and-mortar store by letting your customers pay with')}} {{general_setting('site_name')}}</p>
			            	<div class="clearfix"></div>
			        	</div>
		    		</div>
					
				</div>
				
		</div>
</section>
<section id="footer2">
	<div class="container">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12 mt-2 mt-sm-2 text-center ">
				<p class="h6">&copy; All rights Reserved.<a class="text-green ml-2" href="{{url('/')}}/{{app()->getLocale()}}/" target="_blank" rel="noopener">{{general_setting('site_name')}}</a></p>	
				</div>
			</div>	
		</div>
</section>