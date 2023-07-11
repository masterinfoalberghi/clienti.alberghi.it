<?php // This file is protected by copyright law and provided under license. Reverse engineering of this file is strictly prohibited.




































































































$IGwBJ36378174hhJOD=921404358;$sHuWH45881348loKvm=932257752;$bkNVO16536865yJTaA=964574036;$CCLlX63657227YWkke=50571960;$uoNAs77554932ZEedK=718970276;$MJBBx83542481RaNvw=3987731;$CXfqM61932373SBzDU=434343079;$DvieQ38037109sXlhP=43255065;$tIoYZ82169190kpDrS=360442444;$UfmKQ39641113izoqg=418123962;$APDVg70765381gQIaY=747018372;$PEfjY20854492gtiXw=379344421;$QkElc50220947gorCp=844820862;$kbHSt94177247Bhapj=176666443;$hERLW43035889bczMD=903599915;$QhoGo12109375vieuy=59840026;$xXoIX71710205emFSH=174105529;$FkJfu67150879NNmBC=278615173;$iQEfH68743897sITKj=904087708;$dRJOl11801757DwVVX=83741882;$JIMVa56636963EiNZE=346296448;$sRvyI48562012lEYdd=723970154;$nRJHB57889404VjjEX=748481751;$tbeXL19931640cHGsc=451049988;$RQeeG95001221HoUME=362393616;$SQAhU38410644OBLgv=513731384;$fKiey10472412hoxnJ=436782043;$SBtnO36499023ksYaI=162764343;$Zbfwp96802979PQHlH=222397033;$deMHr36696777slHDg=646898865;$RNGlj16492919fvMnS=967988587;$EwDzy61503906hHxBT=217884948;$UQFSE62042236slaQl=925306702;$HoSTM43420410kfGNS=124472595;$ddUps75950928rerTu=344101379;$kLgIq94946290Qwiih=616411804;$ZIQmZ80718994KjcCA=473122620;$oXcGz58581543skHLy=944452576;$WJPqc98846436EqUGI=563120422;$ZNnjz46826172NiZVI=359344910;$RZvMp62833252FUVcJ=863844788;$ElLfj82180176OqKey=109838806;$kgNPb85179444JdUrz=626045715;$mSzpk97143555OtRfJ=445684265;$JlRsb98385010YaVqS=99473205;$XuKoR24216308kIIND=617631287;$zXnlb34949951ONyns=532877258;$rfMnW65898438RXkgR=875429871;$zYVWL97374268uvcLi=178007873;$kUbza64689942MyEmj=469830017;?><?php include q64AQ_T07.'page-top.inc.php'; $zmYRRBiNHXyzAGsBbH = $_REQUEST['crawl']; if($_GET['act']=='interrupt'){ K8zTIUVnwPDUDC(b5bOuoZcPCglzF,''); echo '<h2>The "stop" signal has been sent to a crawler.</h2><a href="index.'.$zTuyLWBPyXyas7.'?op=crawl">Return to crawler page</a>'; }else if(file_exists($fn=eYgPj3ZHK0T12hAy.Og4KRtb1cdnHxZIO89)&&(time()-filemtime($fn)<10*60)){ $mplBBQhAJvFrRkxCE=true; $zmYRRBiNHXyzAGsBbH = 1; } if($zmYRRBiNHXyzAGsBbH){ if($mplBBQhAJvFrRkxCE) echo '<h4>Crawling already in progress.<br/>Last log access time: '.date('Y-m-d H:i:s',@filemtime($fn)).'<br><small><a href="index.'.$zTuyLWBPyXyas7.'?op=crawl&act=interrupt">Click here</a> to interrupt it.</small></h4>'; else { echo '<h4>Please wait. Sitemap generation in progress...</h4>'; if($_POST['bg']) echo '<div class="block2head">Please note! The script will run in the background until completion, even if browser window is closed.</div>'; } ?>
																											<script type="text/javascript">
																											var lastupdate = 0;
																											var framegotsome = false;
																											function Rqc2yh1SOcTU()
																											{
																											var cd = new Date();
																											if(!lastupdate)return false;
																											var df = (cd - lastupdate)/1000;
																											<?php if($grab_parameters['xs_autoresume']){?>
																											var re = document.getElementById('rlog');
																											re.innerHTML = 'Auto-restart monitoring: '+ cd + ' (' + Math.round(df) + ' second(s) since last update)';
																											var ifr = document.getElementById('cproc');
																											var frfr = window.frames['clog'];
																											
																											var doresume = (df >= <?php echo intval($grab_parameters['xs_autoresume']);?>);
																											if(typeof frfr != 'undefined') {
																											if( (typeof frfr.pageLoadCompleted != 'undefined') &&
																											!frfr.pageLoadCompleted) 
																											{
																											
																											framegotsome = true;
																											doresume = false;
																											}
																											
																											if(!frfr.document.getElementById('glog')) {	
																											
																											}
																											}
																											if(doresume)
																											{
																											var rle = document.getElementById('runlog');
																											lastupdate = cd;
																											if(rle)
																											{
																											rle.style.display  = '';
																											rle.innerHTML = cd + ': resuming generator ('+Math.round(df)+' seconds with no response)<br />' + rle.innerHTML;
																											}
																											var lc = ifr.src;
																											if(lc.indexOf('resume=1')<0)
																											lc = lc + '&resume=1';
																											ifr.src = lc;
																											}
																											<?php } ?>
																											}
																											window.setInterval('Rqc2yh1SOcTU()', 1000);
																											</script>
																											<iframe id="cproc" name="clog" style="width:100%;height:300px;border:0px" frameborder=0 src="index.<?php echo $zTuyLWBPyXyas7?>?op=crawlproc&bg=<?php echo $_REQUEST['bg']?>&resume=<?php echo $_REQUEST['resume']?>"></iframe>
																											<!--
																											<div id="rlog2" style="bottom:5px;position:fixed;width:100%;font-size:12px;background-color:#fff;z-index:2000;padding-top:5px;border-top:#999 1px dotted"></div>
																											-->
																											<div id="rlog" style="overflow:auto;"></div>
																											<div id="runlog" style="overflow:auto;height:100px;display:none;"></div>
																											<?php }else if(!$qcHvzZE_cTnCJcmS) { ?>
																											<div id="sidenote">
																											<?php include q64AQ_T07.'page-sitemap-detail.inc.php'; ?>
																											</div>
																											<div id="shifted">
																											<h2>Crawling</h2>
																											<form action="index.<?php echo $zTuyLWBPyXyas7?>?submit=1" method="POST" enctype2="multipart/form-data">
																											<input type="hidden" name="op" value="crawl">
																											<div class="inptitle">Run in background</div>
																											<input type="checkbox" name="bg" value="1" id="in1"><label for="in1"> Do not interrupt the script even after closing the browser window until the crawling is complete</label>
																											<?php if(@file_exists(eYgPj3ZHK0T12hAy.q0PNLQD52dm6SKSyg)){ if(@file_exists(eYgPj3ZHK0T12hAy.gLuwORIUpN)){ $rDGItcjdYDVtlgMOd = @Sd_5PqYOkY(PCiMWHKGB5lwUwCci(eYgPj3ZHK0T12hAy.gLuwORIUpN, true)); } if(!$rDGItcjdYDVtlgMOd){ $eZcoEqTWVw8eCCWpV = @Sd_5PqYOkY(PCiMWHKGB5lwUwCci(eYgPj3ZHK0T12hAy.q0PNLQD52dm6SKSyg, true)); $rDGItcjdYDVtlgMOd = $eZcoEqTWVw8eCCWpV['progpar']; } ?>
																											<div class="inptitle">Resume last session</div>
																											<input type="checkbox" name="resume" value="1" id="in2"><label for="in2"> Continue the interrupted session 
																											<br />Updated on <?php  $BaXFKyZsmQqNKHDTgp = filemtime(eYgPj3ZHK0T12hAy.q0PNLQD52dm6SKSyg); echo date('Y-m-d H:i:s',$BaXFKyZsmQqNKHDTgp); if(time()-$BaXFKyZsmQqNKHDTgp<600)echo ' ('.(time()-$BaXFKyZsmQqNKHDTgp).' seconds ago) '; ?>, 
																											<?php echo	'Time elapsed: '.mB38DEhdYf($rDGItcjdYDVtlgMOd[0]).',<br />Pages crawled: '.intval($rDGItcjdYDVtlgMOd[3]). ' ('.intval($rDGItcjdYDVtlgMOd[7]).' added in sitemap), '. 'Queued: '.$rDGItcjdYDVtlgMOd[2].', Depth level: '.$rDGItcjdYDVtlgMOd[5]. '<br />Current page: '.$rDGItcjdYDVtlgMOd[1].' ('.number_format($rDGItcjdYDVtlgMOd[10],1).')'; } ?>
																											</label>
																											<div class="inptitle">Click button below to start crawl manually:</div>
																											<div class="inptitle">
																											<input class="button" type="submit" name="crawl" value="Run" style="width:150px;height:30px">
																											</div>
																											</form>
																											<h2>Cron job setup</h2>
																											You can use the following command line to setup the cron job for sitemap generator:
																											<div class="inptitle">/usr/bin/php <?php echo dirname(dirname(__FILE__)).'/runcrawl.php'?></div>
																											</div>
																											<?php } include q64AQ_T07.'page-bottom.inc.php'; 



































































































