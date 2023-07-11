<div class="sticker-sidebar col-md-3 hidden-sm" data-offset="142">
	
    <aside id="sidebar">
     			        			        
        <div class="sidebar sidebar-verde margin-right-3 " >
			{!! $menu_tematico !!}
			<div class="clearfix"></div>

			{!! Form::open([ 'url' => '/mail-multipla', "style" => ""]) !!}
		
				{!! Form::hidden('wishlist',0) !!}
				@if (isset($actual_link))
					{!! Form::hidden('referer', $actual_link) !!}
				@endif

				<button  type="submit" id="MailMultiplaSubmit" style="width: 100%; padding: 15px 0; " class="btn-arancio btn btn-big tooltip" title="{{trans("labels.menu_mail_multipla")}}">{{trans('labels.menu_mail_multipla')}}</button>
			
			{!! Form::close() !!}
        </div> 
        
    </aside>
    
</div>


