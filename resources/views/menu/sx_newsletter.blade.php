@if ($locale == "it")
	
<div id="newsletter_container">
	<div id="newsletter" class="container" >
		<div class="row">
			
			<div class="col-sm-2 col-md-3"></div>
			
			<div class="col-sm-8 col-md-6">
				
				<div class="newsletter_padding padding-2">
				
					<form id="newsletter_form" class="newsletter_form" name="newsletter_form">
						
						<div style="padding:0 30px 5px ; text-align: center;">
							
							<b class="newsletter_title">Newsletter</b><br /><p class="newsletter_text ">Iscriviti alla nostra newsletter per rimanere aggiornato.</p>
							
							<div class="newsletter_alert"></div>
							
						</div>
						
						<div class="newsletter_content">
						
							<input type="hidden" name="list"  id="txtLista" value="1">
							<input type="hidden" name="group" id="txtGruppo" value="31,50">
							<input type="hidden" name="registranewsletter" id="registranewsletter" value="Y" />
							
							<div class="newsletter_content_input">
								<input type="text" class="full" id="newsletter_email" name="newsletter_email" placeholder="es: mario.rossi@email.com"  required>
								<i class="icon-mail-alt"></i>
							</div>
							
							<div style="padding:20px 30px 0 ; text-align: center; ">
								<label for="newsletter_privacy" class="label_checkbox">
									<input type="checkbox" id="newsletter_privacy" class="newsletter_privacy beautiful_checkbox" value="Y"/>
									<span>{!!trans("labels.dati_pers_2");!!}</span>
								</label><br /><br />
							
								<button name="button" id="button" name="button_newsletter" type="submit" class="sendForm btn btn-cyano" ><i class="icon-mail-alt"></i> {{trans('hotel.invia')}}</button>
							</div>
						</div>
					
					</form>
					
				</div>
				
			</div>
			
			<div class="col-sm-2 col-md-3"></div>
			
		</div>
	</div>
</div>

@endif
