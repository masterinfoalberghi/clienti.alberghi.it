
	<div class=" col-sm-6 clearfix">
	
		

			
		
		<ul class="links-list user-info pull-right pull-right-xs pull-none-xsm" style="padding:0 0 15px 0;">
			
			@if (Auth::user()->getUiEditingHotelId())
			<li id="label_impersonifica_hotel">
				<a href="javascript:void">
					Hotel impersonificato: <span class="badge badge-success">{{ Auth::user()->getUiEditingHotel() }}</span>
				</a>
			</li>
			<li class="sep"></li>
			@endif
		
			<li>
				<img src="https://www.gravatar.com/avatar/{{ md5( strtolower( trim( Auth::user()->email ))) }}?size=45&d=mp" />
			</li>
			<li style="padding:0 10px;">
				{{ Auth::user()->getUserName() }}<br />
				@if(Auth::user()->hasRole('admin', 'operatore', 'commerciale'))
					<b>{{ ucwords(Auth::user()->role) }}</b>
				@endif
				</a>
			</li>
					
			<li>
				<a href="{{ url("admin/auth/logout") }}">
					Log Out <i class="entypo-logout right"></i>
				</a>
			</li>
		</ul>
	
	</div>

