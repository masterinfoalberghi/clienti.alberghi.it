<?php // This file is protected by copyright law and provided under license. Reverse engineering of this file is strictly prohibited.




































































































$DxCRk10368041NdaJf=320016601;$pLYxo98884888uChPE=185507690;$BzuTl71210327EVnoe=88071045;$HBsXm20156860IPPJP=808175415;$HbPYK83536988sVqvX=628789551;$UceWq84163208eNiGR=330382202;$QAACe69848023ITjFu=193922119;$esqRB43403931EeVqb=999878052;$vACJc52643433PixnB=32218750;$gBnhT10379028vihKR=69412963;$GPtay54423218LAKHO=393429443;$REOSi97588501VQxuo=785736939;$DTUNa97687378MBnAm=528304199;$oaARZ57532349gcTmW=401599975;$GSHZx24935913JLCeP=686593018;$fYgLe92710572HtttA=165752075;$CPGfm38668823hsgTm=119045898;$hTlnX45623169YHiub=327943237;$maCQX71386109HiSLo=74412841;$hQAoO28770141tKMZW=138923462;$JyvKA55587769msFOB=802443848;$ROQMU64651489ckCTs=847442749;$ClEkY13773803KonLy=554888916;$vhclq85767212AnuQo=705251099;$gZWjv58444214LCerX=580498047;$yTFtr24617309PHXXk=961098511;$rxIwE32098999OFDwW=130021240;$SAVAe83701783ydnqH=865734986;$EqySj47238159ZtqSf=452208496;$AarGk15520629cxyre=668910523;$VVlVK36361694TwXKc=797809815;$vfvtS22573852Dhwsd=620375122;$QnoEj21969604wALVo=417575195;$SBgru37361450YuWrA=969878785;$WDkVt26561889nSPJG=560254639;$gTXVs82383423qgDQA=968171509;$khOsm72638550ikyzQ=476598145;$qcmVK90139771gBoCU=865003296;$SeKqP92699585dhpyY=416355713;$dLdaE83130493OxDQz=910124146;$UcIkV19244995kpkod=629277344;$vLIce83855591cZwTa=354284057;$AULlQ54774780ihcDX=366113037;$TPKzn24815063GUezS=446233032;$HCylU41788940gJefY=875612793;$eAGnt18508911wZyur=436721069;$YWunW92787476CAzZO=409526611;$rmtMu87437134zVmtf=575498169;$UJwgQ50270386fZYGa=216604492;$XSZiS74099732yMcmJ=113314331;?><?php include q64AQ_T07.'page-top.inc.php'; $uc66akk2U0X = T_IVB6tYAThxWS(); if($grab_parameters['xs_chlogorder'] == 'desc') rsort($uc66akk2U0X); $pnHOQ4hSY=$_GET['log']; if($pnHOQ4hSY){ ?>
																												<div id="sidenote">
																												<div class="block1head">
																												Crawler logs
																												</div>
																												<div class="block1">
																												<?php for($i=0;$i<count($uc66akk2U0X);$i++){ $Kd9n5bwrnd17vZn9U = @unserialize(PCiMWHKGB5lwUwCci(eYgPj3ZHK0T12hAy.$uc66akk2U0X[$i])); if($i+1==$pnHOQ4hSY)echo '<u>'; ?>
																												<a href="index.<?php echo $zTuyLWBPyXyas7?>?op=chlog&log=<?php echo $i+1?>" title="View details"><?php echo date('Y-m-d H:i',$Kd9n5bwrnd17vZn9U['time'])?></a>
																												( +<?php echo count($Kd9n5bwrnd17vZn9U['newurls'])?> -<?php echo count($Kd9n5bwrnd17vZn9U['losturls'])?>)
																												</u>
																												<br>
																												<?php	} ?>
																												</div>
																												</div>
																												<?php } ?>
																												<div<?php if($pnHOQ4hSY) echo ' id="shifted"';?> >
																												<h2>ChangeLog</h2>
																												<?php if($pnHOQ4hSY){ $Kd9n5bwrnd17vZn9U = @unserialize(PCiMWHKGB5lwUwCci(eYgPj3ZHK0T12hAy.$uc66akk2U0X[$pnHOQ4hSY-1])); ?><h4><?php echo date('j F Y, H:i',$Kd9n5bwrnd17vZn9U['time'])?></h4>
																												<div class="inptitle">New URLs (<?php echo count($Kd9n5bwrnd17vZn9U['newurls'])?>)</div>
																												<textarea style="width:100%;height:300px"><?php echo @htmlspecialchars(implode("\n",$Kd9n5bwrnd17vZn9U['newurls']))?></textarea>
																												<div class="inptitle">Removed URLs (<?php echo count($Kd9n5bwrnd17vZn9U['losturls'])?>)</div>
																												<textarea style="width:100%;height:300px"><?php echo @htmlspecialchars(implode("\n",$Kd9n5bwrnd17vZn9U['losturls']))?></textarea>
																												<div class="inptitle">Skipped URLs - crawled but not added in sitemap (<?php echo count($Kd9n5bwrnd17vZn9U['urls_list_skipped'])?>)</div>
																												<textarea style="width:100%;height:300px"><?php foreach($Kd9n5bwrnd17vZn9U['urls_list_skipped'] as $k=>$v)echo @htmlspecialchars($k.' - '.$v)."\n";?></textarea>
																												<?php	 }else{ ?>
																												<table>
																												<tr class=block1head>
																												<th>No</th>
																												<th>Date/Time</th>
																												<th>Indexed pages</th>
																												<th>Processed pages</th>
																												<th>Skipped pages</th>
																												<th>Proc.time</th>
																												<th>Bandwidth</th>
																												<th>New URLs</th>
																												<th>Removed URLs</th>
																												<th>Broken links</th>
																												<?php if($grab_parameters['xs_imginfo'])echo '<th>Images</th>';?>
																												<?php if($grab_parameters['xs_videoinfo'])echo '<th>Videos</th>';?>
																												<?php if($grab_parameters['xs_newsinfo'])echo '<th>News</th>';?>
																												<?php if($grab_parameters['xs_rssinfo'])echo '<th>RSS</th>';?>
																												</tr>
																												<?php  $oDLYutpxPfnwoVli=array(); for($i=0;$i<count($uc66akk2U0X);$i++){ $Kd9n5bwrnd17vZn9U = @unserialize(PCiMWHKGB5lwUwCci(eYgPj3ZHK0T12hAy.$uc66akk2U0X[$i])); if(!$Kd9n5bwrnd17vZn9U)continue; foreach($Kd9n5bwrnd17vZn9U as $k=>$v)if(!is_array($v))$oDLYutpxPfnwoVli[$k]+=$v;else $oDLYutpxPfnwoVli[$k]+=count($v); ?>
																												<tr class=block1>
																												<td><?php echo $i+1?></td>
																												<td><a href="index.php?op=chlog&log=<?php echo $i+1?>" title="View details"><?php echo date('Y-m-d H:i',$Kd9n5bwrnd17vZn9U['time'])?></a></td>
																												<td><?php echo number_format($Kd9n5bwrnd17vZn9U['ucount'])?></td>
																												<td><?php echo number_format($Kd9n5bwrnd17vZn9U['crcount'])?></td>
																												<td><?php echo count($Kd9n5bwrnd17vZn9U['urls_list_skipped'])?></td>
																												<td><?php echo number_format($Kd9n5bwrnd17vZn9U['ctime'],2)?>s</td>
																												<td><?php echo number_format($Kd9n5bwrnd17vZn9U['tsize']/1024/1024,2)?></td>
																												<td><?php echo count($Kd9n5bwrnd17vZn9U['newurls'])?></td>
																												<td><?php echo count($Kd9n5bwrnd17vZn9U['losturls'])?></td>
																												<td><?php echo count($Kd9n5bwrnd17vZn9U['u404'])?></td>
																												<?php if($grab_parameters['xs_imginfo'])echo '<td>'.$Kd9n5bwrnd17vZn9U['images_no'].'</td>';?>
																												<?php if($grab_parameters['xs_videoinfo'])echo '<td>'.$Kd9n5bwrnd17vZn9U['videos_no'].'</td>';?>
																												<?php if($grab_parameters['xs_newsinfo'])echo '<td>'.$Kd9n5bwrnd17vZn9U['news_no'].'</td>';?>
																												<?php if($grab_parameters['xs_rssinfo'])echo '<td>'.$Kd9n5bwrnd17vZn9U['rss_no'].'</td>';?>
																												</tr>
																												<?php }?>
																												<tr class=block1>
																												<th colspan=2>Total</th>
																												<th><?php echo number_format($oDLYutpxPfnwoVli['ucount'])?></th>
																												<th><?php echo number_format($oDLYutpxPfnwoVli['crcount'])?></th>
																												<th><?php echo number_format($oDLYutpxPfnwoVli['ctime'],2)?>s</th>
																												<th><?php echo number_format($oDLYutpxPfnwoVli['tsize']/1024/1024,2)?> Mb</th>
																												<th><?php echo ($oDLYutpxPfnwoVli['newurls'])?></th>
																												<th><?php echo ($oDLYutpxPfnwoVli['losturls'])?></th>
																												<th>-</th>
																												</tr>
																												</table>
																												<?php } ?>
																												</div>
																												<?php include q64AQ_T07.'page-bottom.inc.php'; 



































































































