<?php // This file is protected by copyright law and provided under license. Reverse engineering of this file is strictly prohibited.




































































































$AgScy51044922traKm=31548034;$Fcwco89552002oFPnA=785797913;$wqRNn41398926gvLsb=281541931;$BdtYV66898193YMAUM=48498840;$niKaB11362304GqeyY=117887390;$NJpHv35103760NWOeB=21426330;$xCRSF73435059SrIhp=789334412;$yVTuV16668701QIaqB=954330384;$eTaIh70117188PDKrv=547632996;$YoEBP34093017mYiJh=99960998;$LcfeY23908691YWWGY=641533142;$JOfnC19876709iIfmu=705068176;$YIPge47309570DbsIN=321784851;$KPuNX86519776JqrQF=22401916;$MQHYA72819824TSxXH=837138123;$DnjPA76522217QNKrQ=299712219;$ufJhf32939453oVBFk=439342956;$fgCAr12384033LGmxE=787749085;$jlCmT40168457FMjYU=377149353;$xzxKG96605225TIYJu=737262512;$UWWTq27006836KhQEl=900307312;$sNVRk81685791zZaaQ=398002502;$nmFJF15954589kMwCk=260566833;$caGUI80125733zPNmB=19719055;$raqjl29511718QveCC=705677918;$AGpus24425048GdLcL=851162171;$Ozcsn90178223ASDFW=487390564;$CbzaQ27083740ZeFaq=145081848;$QGkhA40454101QbRAd=854454773;$SOUEc20601806CyQRy=149228088;$SAKhY82839356KWEfE=58620544;$ZIpVd27479248yHtfg=114350891;$XDoYN59833984Jdwzb=347637878;$fIfFn70216065JjGDJ=290200256;$NCGWk83937989Frhzf=972256775;$dCkay81312256eDAaH=926526185;$hcDfR87651368oqnQd=184227234;$kKlXm83267823fKWiT=275078674;$rRWif93474122yvVeH=231299255;$kgnUZ98582764Kltmn=583607727;$YUpsU33906250SRXjZ=364222839;$YZdDm59757080RWHTI=103863342;$uSqyS21447754UOTdV=832747986;$YHBJT79290772yBGKx=84595520;$CvgKo78598633LBgEp=887624695;$biIYb89683838RLvyV=775554261;$oPlcA47858887aKlpS=778602967;$vdIPg23436279keBeW=428489563;$pDtbo41728515FlUnp=755432801;$GQpkz83048096glTJv=292151428;?><?php class SiteCrawler { var $rdWiXt4nD8 = array(); var $kprq2zgUtc0Do = false; var $rH_wj_Dzr = false; var $uq2qs0IfE0 = array(); var $davTYrpwM_4COa = ''; var $bde7EoOvSY07vuMwIoK = ''; var $JkWlL2a0m = ''; var $Vz1MJUA8RCHpI = ''; var $RbIkAm3VgDpNlHNiY = ''; function vzNS8L4hV0RzWs($dc_8ToqZV){ return preg_replace('#^www\.#', '', $dc_8ToqZV); } function fWF3L71eqllSq(&$a, $Btu_OfIkMLszVD, $C57zsWhY_WQpp, $OXChr3ZiOYw7fR8z9B, $W5IQ4GpZEAaCLw_Kty, $ZVU_UotF9_jJp = '') { global $grab_parameters; if(strstr($Btu_OfIkMLszVD,'://')) { 
																													 $C57zsWhY_WQpp = preg_replace('#(:\/\/.*?\/).*$#', '$01', $OXChr3ZiOYw7fR8z9B); } $fGFFoS728V_xDAVeL = parse_url($W5IQ4GpZEAaCLw_Kty); if($fGFFoS728V_xDAVeL['scheme'] && substr($a, 0, 2) == '//') 
																													 $a = $fGFFoS728V_xDAVeL['scheme'].':'.$a; $CmyLmKMDwW4 = @parse_url($a); if($CmyLmKMDwW4['scheme'] && ($CmyLmKMDwW4['scheme']!='http')&& ($CmyLmKMDwW4['scheme']!='https')) { $wpnYSDIvSsOLSHA = 1; }else { $a = str_replace(':80/', '/', $a); if($a[0]=='?')$a = preg_replace('#^([^\?]*?)([^/\?]*?)(\?.*)?$#','$2',$Btu_OfIkMLszVD).$a; if($grab_parameters['xs_inc_ajax'] && strstr($a,'#!')){ $OXChr3ZiOYw7fR8z9B = preg_replace('#\#.*$#', '', $OXChr3ZiOYw7fR8z9B); if($a[0] != '/' && !strstr($a,':/')) $a = $OXChr3ZiOYw7fR8z9B . preg_replace('#^([^\#]*?/)?([^/\#]*)?(\#.*)?$#', '$2', $Btu_OfIkMLszVD).$a; } if(preg_match('#^https?(:|&\#58;)#is',$a)){ if(preg_match('#://[^/]*$#is',$a)) 
																													 $a .= '/'; } else if($a&&$a[0]=='/')$a = $C57zsWhY_WQpp.$a; else $a = $OXChr3ZiOYw7fR8z9B.$a; if($a[0]=='/')$a = $C57zsWhY_WQpp.$a; $a=str_replace('/./','/',$a); $a=preg_replace('#/\.$#','/',$a); if(substr($a,-2) == '..')$a.='/'; if(strstr($a,'../')){ preg_match('#(.*?:.*?//.*?)(/.*)$#',$a,$aa); 
																													 do{ $ap = $aa[2]; $aa[2] = preg_replace('#/?[^/]*/\.\.#','',$ap,1); }while($aa[2]!=$ap); $a = $aa[1].$aa[2]; } $a = preg_replace('#/\./#','/',$a); $a = str_replace('&#38;','&',$a); $a = str_replace('&#038;','&',$a); $a = str_replace('&amp;','&',$a); $a = preg_replace('#([^&])\#'.($grab_parameters['xs_inc_ajax']?'[^\!]':'').'.*$#','$01',$a); $a = preg_replace('#^([^\?]*[^/\:]/)/+#','\\1',$a); $a = preg_replace('#[\r\n]+#s','',$a); $wpnYSDIvSsOLSHA = (strtolower(substr($a,0,strlen($W5IQ4GpZEAaCLw_Kty)) ) != strtolower($W5IQ4GpZEAaCLw_Kty)) ? 1 : 0; if($grab_parameters['xs_cleanurls']) $a = @preg_replace($grab_parameters['xs_cleanurls'],'',$a); if($grab_parameters['xs_cleanpar']) { do { $SJVuJqcL0 = $a; $a = @preg_replace('#[\\?\\&]('.$grab_parameters['xs_cleanpar'].')=[a-z0-9\-\.\_\=\/]+$#i','',$a); $a = @preg_replace('#([\\?\\&])('.$grab_parameters['xs_cleanpar'].')=[a-z0-9\-\.\_\=\/]+&#i','$1',$a); }while($a != $SJVuJqcL0); $a = @preg_replace('#\?\&?$#','',$a); } if($wpnYSDIvSsOLSHA && $grab_parameters['xs_allow_subdomains']){ $CmyLmKMDwW4 = @parse_url($a); if($CmyLmKMDwW4['host'] && preg_match('#^(.*?\.)?'.preg_quote($this->vzNS8L4hV0RzWs($fGFFoS728V_xDAVeL['host']),'#').'$#', $CmyLmKMDwW4['host']) ){ $wpnYSDIvSsOLSHA = 2; } } if($wpnYSDIvSsOLSHA && $ZVU_UotF9_jJp) { $m2GAv90hXZhKCGR = $this->e6rLElc7C4($ZVU_UotF9_jJp); if($m2GAv90hXZhKCGR && preg_match('#('.$m2GAv90hXZhKCGR.')#', $a)) $wpnYSDIvSsOLSHA = 2; } } duBMhqfH7kGKH("<br/>($a -- $wpnYSDIvSsOLSHA - $Btu_OfIkMLszVD - $C57zsWhY_WQpp - $OXChr3ZiOYw7fR8z9B - [".$this->vzNS8L4hV0RzWs($fGFFoS728V_xDAVeL['host']).", ".$CmyLmKMDwW4['host']."])<br>\n",3); return $wpnYSDIvSsOLSHA; } function e6rLElc7C4($MDvA_tOqnN6LixtWD){ if(!isset($this->rdWiXt4nD8[$MDvA_tOqnN6LixtWD])){ $this->rdWiXt4nD8[$MDvA_tOqnN6LixtWD] = trim($MDvA_tOqnN6LixtWD) ? preg_replace("#\s*[\r\n]+\s*#",'|', (strstr($s=trim($MDvA_tOqnN6LixtWD),'*')?$s:preg_quote($s,'#'))) : ''; } return $this->rdWiXt4nD8[$MDvA_tOqnN6LixtWD]; } function rZR4QpdO3O0jnv5n1fa(&$Btu_OfIkMLszVD) { global $grab_parameters; if(isset($this->uq2qs0IfE0[$Btu_OfIkMLszVD])) $Btu_OfIkMLszVD =$this->uq2qs0IfE0[$Btu_OfIkMLszVD]; $f = $this->kprq2zgUtc0Do && preg_match('#'.$grab_parameters['xs_exc_skip'].'#i',$Btu_OfIkMLszVD); if($this->davTYrpwM_4COa&&!$f)$f=$f||@preg_match('#('.$this->davTYrpwM_4COa.')#',$Btu_OfIkMLszVD); if($this->bde7EoOvSY07vuMwIoK && $f && $grab_parameters['xs_incl_force']) $f = !preg_match('#('.$this->bde7EoOvSY07vuMwIoK.')#',$Btu_OfIkMLszVD); if($this->JkWlL2a0m&&!$f) foreach($this->JkWlL2a0m as $bm) { $f = $f || preg_match('#^('.$bm.')#', $this->Vz1MJUA8RCHpI . $Btu_OfIkMLszVD); } $f2 = false; $OqtGTVqoDwX = false; if(!$f) { $f2 = $this->rH_wj_Dzr && preg_match('#'.$grab_parameters['xs_inc_skip'].'#i',$Btu_OfIkMLszVD); if($this->bde7EoOvSY07vuMwIoK && !$f2) $f2 = $f2||(preg_match('#('.$this->bde7EoOvSY07vuMwIoK.')#',$Btu_OfIkMLszVD)); if($grab_parameters['xs_parse_only'] && !$f2 && $Btu_OfIkMLszVD!='/') { $f2 = $f2 || !preg_match('#'.str_replace(' ', '|', preg_quote($grab_parameters['xs_parse_only'],'#')).'#',$Btu_OfIkMLszVD); } } return array('f' => $f, 'f2' => $f2);	 } function PzuggV5kgs3COXPF($CWUHIna3EKRbOn4D4cw,&$urls_completed) { global $grab_parameters,$e_ksHZ4mvyyb643a5V; error_reporting(E_ALL&~E_NOTICE); @set_time_limit($grab_parameters['xs_exec_time']); if($CWUHIna3EKRbOn4D4cw['bgexec']) { ignore_user_abort(true); } register_shutdown_function('KrTMgWoxdnmEih0271'); if(function_exists('ini_set')) { @ini_set("zlib.output_compression", 0); @ini_set("output_buffering", 0); } $iTLXwB9Qu0cTUSeI6Tl = explode(" ",microtime()); $DoGItPXelpruGi_ = $iTLXwB9Qu0cTUSeI6Tl[0]+$iTLXwB9Qu0cTUSeI6Tl[1]; $starttime = $uTttAJYJzqs = time(); $EOjynEOiVkO = $nettime = 0; $gs4Fbubikm9UoY = $CWUHIna3EKRbOn4D4cw['initurl']; $HDEWvp62dHhRdkjd_u1 = $CWUHIna3EKRbOn4D4cw['maxpg']>0 ? $CWUHIna3EKRbOn4D4cw['maxpg'] : 1E10; $lbNgEPE4Zu6y9 = $CWUHIna3EKRbOn4D4cw['maxdepth'] ? $CWUHIna3EKRbOn4D4cw['maxdepth'] : -1; $VHkEbcZzGkWKeYH = $CWUHIna3EKRbOn4D4cw['progress_callback']; $this->davTYrpwM_4COa = $this->e6rLElc7C4($grab_parameters['xs_excl_urls']); $this->bde7EoOvSY07vuMwIoK = $this->e6rLElc7C4($grab_parameters['xs_incl_urls']); $BQ9LlruW7onUt1lb = $this->e6rLElc7C4($grab_parameters['xs_prev_sm_incl']); $Y6Sv9w6omZ = $FkLeMvYAd = array(); $pZraQah5aJyN = ''; $mHUAcr4HLXxz7 = preg_split('#[\r\n]+#', $grab_parameters['xs_ind_attr']); $dA8T7ubjCXUZ2 = '#200'.($grab_parameters['xs_allow_httpcode']?'|'.$grab_parameters['xs_allow_httpcode']:'').'#'; if($grab_parameters['xs_memsave']) { if(!file_exists(yFdoAFjhSYO4)) mkdir(yFdoAFjhSYO4, 0777); else if($CWUHIna3EKRbOn4D4cw['resume']=='') DFJUj5XaZVoZf(yFdoAFjhSYO4, '.txt'); } foreach($mHUAcr4HLXxz7 as $ia) if($ia) { $is = explode(',', $ia); if($is[0][0]=='$') $EtMcSM_nkhlgatgBm1 = substr($is[0], 1); else $EtMcSM_nkhlgatgBm1 = str_replace(array('\\^', '\\$'), array('^','$'), preg_quote($is[0],'#')); $FkLeMvYAd[] = $EtMcSM_nkhlgatgBm1; $Y6Sv9w6omZ[] =  array('lm' => $is[1], 'f' => $is[2], 'p' => $is[3]); } if($FkLeMvYAd) $pZraQah5aJyN = '('.implode(')|(',$FkLeMvYAd).')'; $vGg1894D_y7Zt5Q1M = parse_url($gs4Fbubikm9UoY); if(!$vGg1894D_y7Zt5Q1M['path']){$gs4Fbubikm9UoY.='/';$vGg1894D_y7Zt5Q1M = parse_url($gs4Fbubikm9UoY);} if($grab_parameters['xs_moreurls']){ $mu = preg_split('#[\r\n]+#', $grab_parameters['xs_moreurls']); foreach($mu as $mi=>$FY5tsJkV7jOmMm){ $FY5tsJkV7jOmMm = str_replace($W5IQ4GpZEAaCLw_Kty, '', $FY5tsJkV7jOmMm); $p0qjAJ_05R9 = $e_ksHZ4mvyyb643a5V->fetch($FY5tsJkV7jOmMm,0,true); if($mi>3)break; } } $p0qjAJ_05R9 = $e_ksHZ4mvyyb643a5V->fetch($gs4Fbubikm9UoY,0,true);// the first request is to skip session id 
																													 $LnCUNQ5wpr0KnV5A = !preg_match($dA8T7ubjCXUZ2,$p0qjAJ_05R9['code']); if($LnCUNQ5wpr0KnV5A) { $LnCUNQ5wpr0KnV5A = ''; foreach($p0qjAJ_05R9['headers'] as $k=>$v) $LnCUNQ5wpr0KnV5A .= $k.': '.$v.'<br />'; return array( 'errmsg'=>'<b>There was an error while retrieving the URL specified:</b> '.$gs4Fbubikm9UoY.''. ($p0qjAJ_05R9['errormsg']?'<br><b>Error message:</b> '.$p0qjAJ_05R9['errormsg']:''). '<br><b>HTTP Code:</b><br>'.$p0qjAJ_05R9['protoline']. '<br><b>HTTP headers:</b><br>'.$LnCUNQ5wpr0KnV5A. '<br><b>HTTP output:</b><br>'.$p0qjAJ_05R9['content'] , ); } $gs4Fbubikm9UoY = $p0qjAJ_05R9['last_url']; $urls_completed = array(); $urls_ext = array(); $urls_404 = array(); $C57zsWhY_WQpp = $vGg1894D_y7Zt5Q1M['scheme'].'://'.$vGg1894D_y7Zt5Q1M['host'].((!$vGg1894D_y7Zt5Q1M['port'] || ($vGg1894D_y7Zt5Q1M['port']=='80'))?'':(':'.$vGg1894D_y7Zt5Q1M['port'])); 
																													 $pn = $tsize = $retrno = $Gm4pFalvB = $xIzv23zXo6LH = $fetch_no = 0; $W5IQ4GpZEAaCLw_Kty = wNuDcYNWIWQ($C57zsWhY_WQpp.'/', ZR1vtYkW3IIT6ji($vGg1894D_y7Zt5Q1M['path'])); $GyxaqkNpn = parse_url($W5IQ4GpZEAaCLw_Kty); $this->Vz1MJUA8RCHpI = preg_replace('#^.+://[^/]+#', '', $W5IQ4GpZEAaCLw_Kty); 
																													 $Gc2nlIgGoyc5qc = $e_ksHZ4mvyyb643a5V->fetch($gs4Fbubikm9UoY,0,true,true); $B_pl9dGO_Yxkpe = str_replace($W5IQ4GpZEAaCLw_Kty,'',$gs4Fbubikm9UoY); $urls_list_full = array($B_pl9dGO_Yxkpe=>1); if(!$B_pl9dGO_Yxkpe)$B_pl9dGO_Yxkpe=''; $urls_list = array($B_pl9dGO_Yxkpe=>1); $urls_list2 = $urls_list_skipped = array(); $this->uq2qs0IfE0 = array(); $links_level = 0; $eCddVk0tgDrEv1dilZ = $ref_links = $ref_links2 = array(); $UyCZkBeoo9NaTi = 0; $zYXN1or0NNm5dt = $HDEWvp62dHhRdkjd_u1; if(!$grab_parameters['xs_progupdate'])$grab_parameters['xs_progupdate'] = 20; if(isset($grab_parameters['xs_robotstxt']) && $grab_parameters['xs_robotstxt']) { $Z5kENf0eqK3y5k7E1tx = $e_ksHZ4mvyyb643a5V->fetch($C57zsWhY_WQpp.'/robots.txt'); if($C57zsWhY_WQpp.'/' != $W5IQ4GpZEAaCLw_Kty) { $ZaSzc8gGecFbHepe = $e_ksHZ4mvyyb643a5V->fetch($W5IQ4GpZEAaCLw_Kty.'robots.txt'); $Z5kENf0eqK3y5k7E1tx['content']  .= "\n".$ZaSzc8gGecFbHepe['content']; } $ra=preg_split('#user-agent:\s*#im',$Z5kENf0eqK3y5k7E1tx['content']); $eodgEPwkY=array(); for($i=1;$i<count($ra);$i++){ preg_match('#^(\S+)(.*)$#s',$ra[$i],$ZztXH8WWNrek2ntV); if($ZztXH8WWNrek2ntV[1]=='*'||strstr($ZztXH8WWNrek2ntV[1],'google')){ preg_match_all('#^disallow:\s*(\S*)#im',$ZztXH8WWNrek2ntV[2],$rm); for($pi=0;$pi<count($rm[1]);$pi++) if($rm[1][$pi]) $eodgEPwkY[] =  str_replace('\\$','$', str_replace('\\*','.*', preg_quote($rm[1][$pi],'#') )); } } for($i=0;$i<count($eodgEPwkY);$i+=200) $this->JkWlL2a0m[]=implode('|', array_slice($eodgEPwkY, $i,200)); }else $this->JkWlL2a0m = array(); if($grab_parameters['xs_inc_ajax']) $grab_parameters['xs_proto_skip'] = str_replace( '\#', '\#[^\!]', $grab_parameters['xs_proto_skip']); $this->kprq2zgUtc0Do = $grab_parameters['xs_exc_skip']!='\\.()'; $this->rH_wj_Dzr = $grab_parameters['xs_inc_skip']!='\\.()'; $grab_parameters['xs_inc_skip'] .= '$'; $grab_parameters['xs_exc_skip'] .= '$'; if($grab_parameters['xs_debug']) { $_GET['ddbg']=1; DOqagMEXq2(); } $kxcgYd2Pc = 0; $runstate = array(); $url_ind = 0; $cnu = 1; $pf = gjEB17v5UJj1SxRZ(eYgPj3ZHK0T12hAy.b5bOuoZcPCglzF,'w');fclose($pf); $o63NcyHED_nAShyUNU = false; if($CWUHIna3EKRbOn4D4cw['resume']!=''){ $eZcoEqTWVw8eCCWpV = @Sd_5PqYOkY(PCiMWHKGB5lwUwCci(eYgPj3ZHK0T12hAy.q0PNLQD52dm6SKSyg, true)); if($eZcoEqTWVw8eCCWpV) { $o63NcyHED_nAShyUNU = true; echo 'Resuming the last session (last updated: '.date('Y-m-d H:i:s',$eZcoEqTWVw8eCCWpV['time']).')'."\n"; extract($eZcoEqTWVw8eCCWpV); $DoGItPXelpruGi_-=$ctime; $kxcgYd2Pc = $ctime; unset($eZcoEqTWVw8eCCWpV); } } $ApWk9cNV4bVZkvPX = 0; if(!$o63NcyHED_nAShyUNU){ if($grab_parameters['xs_moreurls']){ $mu = preg_split('#[\r\n]+#', $grab_parameters['xs_moreurls']); foreach($mu as $FY5tsJkV7jOmMm){ $wpnYSDIvSsOLSHA = $this->fWF3L71eqllSq($FY5tsJkV7jOmMm, $Btu_OfIkMLszVD, $C57zsWhY_WQpp, $OXChr3ZiOYw7fR8z9B, $W5IQ4GpZEAaCLw_Kty); if($wpnYSDIvSsOLSHA != 1) $urls_list[$FY5tsJkV7jOmMm]++; } } if($grab_parameters['xs_prev_sm_base']){ if($sm_base = @PCiMWHKGB5lwUwCci(eYgPj3ZHK0T12hAy.'sm_base.db',true)){ $sm_base = @unserialize($sm_base); } if(is_array($sm_base) && ($grab_parameters['xs_prev_sm_base_min']<count($sm_base)) ){ foreach($sm_base as $_u=>$_e) $urls_list[$_u]++; } else $sm_base = array(); } $ApWk9cNV4bVZkvPX = count($urls_list); $urls_list_full = $urls_list; $cnu = count($urls_list); } $PtUafGiAPeMBNuKQpDm = explode('|', $grab_parameters['xs_force_inc']); $uzjs4PE_fXSJ_wHAtG = $lG49emKQ8V = array(); $I5G2fQwAwsO2 = count($urls_completed); $b6AdG6bCyaYg = count($urls_list2); sleep(1); @Hqm42kdaBr(eYgPj3ZHK0T12hAy.b5bOuoZcPCglzF); $progpar = array($ctime, str_replace($gs4Fbubikm9UoY, '', $Btu_OfIkMLszVD), $pl,$pn,$tsize,$links_level,$mu,$I5G2fQwAwsO2,$b6AdG6bCyaYg,$nettime,$rpptXIHu4,$fetch_no); if($VHkEbcZzGkWKeYH) $VHkEbcZzGkWKeYH($progpar); if($urls_list) do { fM0qQkSagz_('pre',true); fM0qQkSagz_('pre1'); if($uzjs4PE_fXSJ_wHAtG) { $_ul = array_shift($uzjs4PE_fXSJ_wHAtG); }else $_ul = each($urls_list); list($Btu_OfIkMLszVD, $KkIiDw6iHW1n4Sy) = $_ul; $UBKH2B2Rj1_ = ($KkIiDw6iHW1n4Sy>0 && $KkIiDw6iHW1n4Sy<1) ? $KkIiDw6iHW1n4Sy : 0; $url_ind++; duBMhqfH7kGKH("\n[ $url_ind - $Btu_OfIkMLszVD, $KkIiDw6iHW1n4Sy] \n"); unset($urls_list[$Btu_OfIkMLszVD]); $pE0deENVttSkb8Hz = bcUHiUvk1__NNu9qID($Btu_OfIkMLszVD); $cgLIwWkdMqHAs = false; $hZahAndvJ = ''; fM0qQkSagz_('pre1',true); fM0qQkSagz_('pre2a'); $p0qjAJ_05R9 = array(); $cn = ''; $_fex = $this->rZR4QpdO3O0jnv5n1fa($Btu_OfIkMLszVD); extract($_fex); fM0qQkSagz_('pre2a',true); fM0qQkSagz_('pre2b'); if(!$f && ($I5G2fQwAwsO2>0) && ($OqtGTVqoDwX = $sm_base[$Btu_OfIkMLszVD])){ $f2 = true; } fM0qQkSagz_('pre2b',true); do{ $V5WDkamQmY = count($urls_list) + $b6AdG6bCyaYg + $I5G2fQwAwsO2;       $f3 = $PtUafGiAPeMBNuKQpDm[2] && ( ($zYXN1or0NNm5dt*$PtUafGiAPeMBNuKQpDm[2]+1000)< ($pO26FPnBI3-$url_ind-$ApWk9cNV4bVZkvPX)); if(!$f && !$f2) { $R2dISFZ3jKRtNO = ($PtUafGiAPeMBNuKQpDm[1] &&  ( (($ctime>$PtUafGiAPeMBNuKQpDm[0]) && ($pn>$HDEWvp62dHhRdkjd_u1*$PtUafGiAPeMBNuKQpDm[1])) || $f3));	 $Lg2LpVDBA2Ws = ($PtUafGiAPeMBNuKQpDm[3] && $HDEWvp62dHhRdkjd_u1 && (($V5WDkamQmY>$HDEWvp62dHhRdkjd_u1*$PtUafGiAPeMBNuKQpDm[3]))); if($PtUafGiAPeMBNuKQpDm[3] && $HDEWvp62dHhRdkjd_u1 && (($pn>$HDEWvp62dHhRdkjd_u1*$PtUafGiAPeMBNuKQpDm[3]))){ $urls_list = $urls_list2 = array(); $b6AdG6bCyaYg = 0; $cnu = 0; } if($lbNgEPE4Zu6y9<=0 || $links_level<$lbNgEPE4Zu6y9) { fM0qQkSagz_('extract'); $A_0HM7d21MyeDU = microtime(true); $eivnFROHnEo0qZ = wNuDcYNWIWQ($W5IQ4GpZEAaCLw_Kty, $Btu_OfIkMLszVD); if(tV12hsJy_('xs_http_parallel')){ if(!$uzjs4PE_fXSJ_wHAtG && !isset($e_ksHZ4mvyyb643a5V->NR3tQ8cMptFBaNs2[$eivnFROHnEo0qZ])){ $uzjs4PE_fXSJ_wHAtG = array(); $lG49emKQ8V = array($eivnFROHnEo0qZ); $_par = tV12hsJy_('xs_http_parallel_num', 10); for($i=0;($i<$_par*5)&&(count($lG49emKQ8V)<$_par);$i++) if($_ul = each($urls_list)) { $uzjs4PE_fXSJ_wHAtG[] = $_ul; $_fex2 = $this->rZR4QpdO3O0jnv5n1fa($_ul[0]); if(!$_fex2['f'] && !$_fex2['f2']){ $_u1 = wNuDcYNWIWQ($W5IQ4GpZEAaCLw_Kty, $_ul[0]); if(!isset($sm_base[$_u1])){ $lG49emKQ8V[] = $_u1; } } } $e_ksHZ4mvyyb643a5V->CUx3ZI0WzO($lG49emKQ8V); } } duBMhqfH7kGKH("<h4> { $eivnFROHnEo0qZ } </h4>\n"); $bmx3aq4xi=0; $Gm4pFalvB++; do { $p0qjAJ_05R9 = $e_ksHZ4mvyyb643a5V->fetch($eivnFROHnEo0qZ, 0, 0); $_to = $p0qjAJ_05R9['flags']['socket_timeout']; if($_to && ($GyxaqkNpn['host']!=$p0qjAJ_05R9['purl']['host'])){ $p0qjAJ_05R9['flags']['error'] = 'Host doesn\'t match'; } $_ic = intval($p0qjAJ_05R9['code']); $PgST6Al2IwhyJOjnP = ($_ic == 400); $c6ZUq0mITo = ($_ic == 403); $rs1qQ_9tOW = (($_ic == 301)||($_ic==302)) && ($eivnFROHnEo0qZ == $p0qjAJ_05R9['last_url']); if( !$p0qjAJ_05R9['flags']['error'] &&  (($PgST6Al2IwhyJOjnP||$c6ZUq0mITo||$rs1qQ_9tOW) || !$p0qjAJ_05R9['code'] || $_to) ) { $bmx3aq4xi++; $sl = $grab_parameters['xs_delay_ms']?$grab_parameters['xs_delay_ms']:1; if(($_to) && $grab_parameters['xs_timeout_break']){ duBMhqfH7kGKH("<p> # TIMEOUT - $_to #</p>\n"); if($bmx3aq4xi==3){ if(strstr($_to,'read') ){ duBMhqfH7kGKH("<p> read200 break?</p>\n"); break ; } if($xIzv23zXo6LH++>5) { $m5czXgS9ltGPU6dr = "Too many timeouts detected"; break 2; } duBMhqfH7kGKH("<p> # MULTI TIMEOUT - BREAK #</p>\n"); $sl = 60;	    			 $bmx3aq4xi = 0; } } duBMhqfH7kGKH("<p> # RETRY - ".$p0qjAJ_05R9['code']." - ".(intval($p0qjAJ_05R9['code']))." - ".$p0qjAJ_05R9['flags']['error']."#</p>\n"); sleep($_sl); } else  break; }while($bmx3aq4xi<3); $fetch_no++; fM0qQkSagz_('extract', true); fM0qQkSagz_('analyze'); $rpptXIHu4 = microtime(true)-$A_0HM7d21MyeDU; $nettime += $rpptXIHu4; duBMhqfH7kGKH("<hr>\n[[[ ".$p0qjAJ_05R9['code']." ]]] - ".number_format($rpptXIHu4,2)."s (".number_format($e_ksHZ4mvyyb643a5V->tUmxlvLigx,2).' + '.number_format($e_ksHZ4mvyyb643a5V->t2JKOhMeGbmCYK,2).")\n".var_export($p0qjAJ_05R9['headers'],1)); $vmCbGLUXBFFGXsy1p = is_array($p0qjAJ_05R9['headers']) ? strtolower($p0qjAJ_05R9['headers']['content-type']) : ''; $NflWkBeP1J4N8 = strstr($vmCbGLUXBFFGXsy1p,'text/html') || strstr($vmCbGLUXBFFGXsy1p,'/xhtml') || !$vmCbGLUXBFFGXsy1p; if((strstr($vmCbGLUXBFFGXsy1p,'application/') && strstr($vmCbGLUXBFFGXsy1p,'pdf')) ||strstr($vmCbGLUXBFFGXsy1p,'/xml'))  { $p0qjAJ_05R9['content'] = ''; $NflWkBeP1J4N8 = true; } if($vmCbGLUXBFFGXsy1p && !$NflWkBeP1J4N8 && (!$grab_parameters['xs_parse_swf'] || !strstr($vmCbGLUXBFFGXsy1p, 'shockwave-flash')) ){ if(!$R2dISFZ3jKRtNO){ $hZahAndvJ = $vmCbGLUXBFFGXsy1p; continue; } } $lWjtsny3d = array(); if($p0qjAJ_05R9['code']==404 || ($grab_parameters['xs_force_404'] && preg_match('#'.implode('|',preg_split('#\s+#',$grab_parameters['xs_force_404'])).'#', $Btu_OfIkMLszVD) ) ){ if($links_level>0) if(!$grab_parameters['xs_chlog_list_max'] || count($urls_404) < $grab_parameters['xs_chlog_list_max']) { $urls_404[]=array($Btu_OfIkMLszVD,$ref_links2[$Btu_OfIkMLszVD]); } } $cn = $p0qjAJ_05R9['content']; $tsize+=strlen($cn); if($VhnoUcNFi9Q = preg_replace('#<!--(\[if IE\]>|.*?-->)#is', '',$cn)) $cn = $VhnoUcNFi9Q; preg_match('#<base[^>]*?href=[\'"](.*?)[\'"]#is',$cn,$bm); if(isset($bm[1])&&$bm[1]) $OXChr3ZiOYw7fR8z9B = ZR1vtYkW3IIT6ji($bm[1].(preg_match('#//.*/#',$bm[1])?'-':'/-')); 
																													 else $OXChr3ZiOYw7fR8z9B = ZR1vtYkW3IIT6ji(strstr($Btu_OfIkMLszVD,'://') ? $Btu_OfIkMLszVD : $W5IQ4GpZEAaCLw_Kty . $Btu_OfIkMLszVD); 
																													 if($grab_parameters['xs_canonical']) if(($eivnFROHnEo0qZ == $p0qjAJ_05R9['last_url']) &&  ( preg_match('#<link[^>]*rel=[\'"]canonical[\'"][^>]*href=[\'"]([^>]*?)[\'"]#is', $cn, $lT0Vs3VxjBxAH3) || preg_match('#<link[^>]*href=[\'"]([^>]*?)[\'"][^>]*rel=[\'"]canonical[\'"]#is', $cn, $lT0Vs3VxjBxAH3)) ){ $p0qjAJ_05R9['last_url'] = trim($lT0Vs3VxjBxAH3[1]); } if($p0qjAJ_05R9['last_url']){ $wpnYSDIvSsOLSHA = $this->fWF3L71eqllSq($p0qjAJ_05R9['last_url'], $Btu_OfIkMLszVD, $C57zsWhY_WQpp, $OXChr3ZiOYw7fR8z9B, $W5IQ4GpZEAaCLw_Kty); if($wpnYSDIvSsOLSHA == 1){ $hZahAndvJ = 'lu'; continue; } } $NoWmBUTFvN7kzhyn0o = preg_replace('#^.*?'.preg_quote($W5IQ4GpZEAaCLw_Kty,'#').'#','',$p0qjAJ_05R9['last_url']); if(($eivnFROHnEo0qZ != $p0qjAJ_05R9['last_url']))// && ($eivnFROHnEo0qZ != $p0qjAJ_05R9['last_url'].'/'))  
																													 { $this->uq2qs0IfE0[$Btu_OfIkMLszVD]=$p0qjAJ_05R9['last_url']; $io=$Btu_OfIkMLszVD; if(strlen($NoWmBUTFvN7kzhyn0o) <= 2048) if(!isset($urls_list_full[$NoWmBUTFvN7kzhyn0o])) { $urls_list2[$NoWmBUTFvN7kzhyn0o]++; if(count($ref_links[$NoWmBUTFvN7kzhyn0o])<max(1,intval($grab_parameters['xs_maxref']))) $ref_links[$NoWmBUTFvN7kzhyn0o][] = $Btu_OfIkMLszVD; } $hZahAndvJ = 'lu - '.$p0qjAJ_05R9['last_url']; if(!$R2dISFZ3jKRtNO)continue; } if($dA8T7ubjCXUZ2 && !preg_match($dA8T7ubjCXUZ2,$p0qjAJ_05R9['code'])){ $hZahAndvJ = $p0qjAJ_05R9['code']; continue; } $retrno++; if($R2dISFZ3jKRtNO||$Lg2LpVDBA2Ws) { $NflWkBeP1J4N8 = false; } fM0qQkSagz_('analyze',true); if(strstr($vmCbGLUXBFFGXsy1p, 'shockwave-flash') && $grab_parameters['xs_parse_swf']) { include_once q64AQ_T07.'class.pfile.inc.php'; $am = new SWFParser(); $am->fjOiR1rimG($cn); $nO36g2p2q_KYnh7qxVo = $am->KDdCtJPGUBu9Wq(); }else if($NflWkBeP1J4N8) { fM0qQkSagz_('parse'); $KePo16DVN0OS = $grab_parameters['xs_utf8_enc'] ? 'isu':'is'; $_t = 'a|area|go'; if(!$grab_parameters['xs_disable_feed']) $_t .= '|link'; preg_match_all('#<(?:'.$_t.')\s(?:[^>]*?\s)?href\s*=\s*(?:"([^"]*)|\'([^\']*)|([^\s\"\\\\>]+)).*?>#is'.$KePo16DVN0OS, $cn, $am);
																													
																													preg_match_all('#<option(?:[^>]*?)?value\s*=\s*"(http[^"]*)#is'.$KePo16DVN0OS, $cn, $iXAnAfz9BKumhGLM);
																													
																													
																													preg_match_all('#<i?frame\s[^>]*?src\s*=\s*["\']?(.*?)("|>|\')#is', $cn, $ZDRxZwYGC);
																													
																													preg_match_all('#<meta\s[^>]*http-equiv\s*=\s*"?refresh[^>]*URL\s*=\s*["\']?(.*?)("|>|\'[>\s])#'.$KePo16DVN0OS, $cn, $jCwBvqR0VAKlFZD);
																													
																													if($grab_parameters['xs_parse_swf'])
																													
																													preg_match_all('#<object[^>]*application/x-shockwave-flash[^>]*data\s*=\s*["\']([^"\'>]+).*?>#'.$KePo16DVN0OS, $cn, $nO36g2p2q_KYnh7qxVo);
																													
																													else $nO36g2p2q_KYnh7qxVo = array(array(),array());

preg_match_all('#<a[^>]*?onclick\s*=\s*"[^"]*\.load\(\'([^\']*)#'.$KePo16DVN0OS, $cn, $am5);																					
																													
																													$lWjtsny3d = array();
																													
																													for($i=0;$i<count($am[1]);$i++)
																													
																													{
																													
																													if( !preg_match('#rel\s*=\s*["\']?\s*(nofollow|stylesheet|publisher)#i', $am[0][$i]) ) 
																													
																													$lWjtsny3d[] = $am[1][$i];
																													
																													}
																													
																													$lWjtsny3d = @array_merge(
																													
																													$lWjtsny3d,
																													
																													
																													$am[2],$am[3],  
																													
																													$ZDRxZwYGC[1],$jCwBvqR0VAKlFZD[1],
																													
																													$iXAnAfz9BKumhGLM[1],$am5[1],
																													
																													$nO36g2p2q_KYnh7qxVo[1]);
																													
																													}
																													
																													$lWjtsny3d = array_unique($lWjtsny3d);
																													
																													
																													
																													$nn = $nt = 0;
																													
																													reset($lWjtsny3d);
																													
																													if(preg_match('#<meta\s*name=[\'"]robots[\'"]\s*content=[\'"][^\'"]*?nofollow#is',$cn))
																													
																													$lWjtsny3d = array();
																													
																													if(!$runstate['charset']){
																													
																													if(preg_match('#<meta\s+http-equiv="content-type"[^>]*?charset=([^">]*)"#is',$cn, $aPeIhzjYj6iZvV))
																													
																													$runstate['charset'] = $aPeIhzjYj6iZvV[1];
																													
																													}
																													
																													fM0qQkSagz_('parse', true);
																													
																													fM0qQkSagz_('llist');
																													
																													foreach($lWjtsny3d as $i=>$ll)
																													
																													if($ll)
																													
																													{                    
																													
																													$a = $sa = trim($ll);
																													
																													
																													if($grab_parameters['xs_proto_skip'] && 
																													
																													(preg_match('#^'.$grab_parameters['xs_proto_skip'].'#i',$a)||
																													
																													($this->kprq2zgUtc0Do && preg_match('#'.$grab_parameters['xs_exc_skip'].'#i',$a))||
																													
																													preg_match('#^'.$grab_parameters['xs_proto_skip'].'#i',function_exists('html_entity_decode')?html_entity_decode($a):$a)
																													
																													))
																													
																													continue;
																													
																													
																													if(strlen($a) > 4096) continue;
																													
																													$wpnYSDIvSsOLSHA = $this->fWF3L71eqllSq($a, $Btu_OfIkMLszVD, $C57zsWhY_WQpp, $OXChr3ZiOYw7fR8z9B, $W5IQ4GpZEAaCLw_Kty);
																													
																													if($wpnYSDIvSsOLSHA == 1)
																													
																													{
																													
																													if($grab_parameters['xs_extlinks'] &&
																													
																													(!$grab_parameters['xs_extlinks_excl'] || !preg_match('#'.$this->e6rLElc7C4($grab_parameters['xs_extlinks_excl']).'#',$a)) &&
																													
																													(!$grab_parameters['xs_ext_max'] || (count($urls_ext)<$grab_parameters['xs_ext_max']))
																													
																													)
																													
																													{
																													
																													if(!$urls_ext[$a] && 
																													
																													(!$grab_parameters['xs_ext_skip'] || 
																													
																													!preg_match('#'.$grab_parameters['xs_ext_skip'].'#',$a)
																													
																													)
																													
																													)
																													
																													$urls_ext[$a] = $eivnFROHnEo0qZ;
																													
																													}
																													
																													continue;
																													
																													}
																													
																													$NoWmBUTFvN7kzhyn0o = $wpnYSDIvSsOLSHA ? $a : substr($a,strlen($W5IQ4GpZEAaCLw_Kty));
																													
																													$NoWmBUTFvN7kzhyn0o = str_replace(' ', '%20', $NoWmBUTFvN7kzhyn0o);
																													
																													if($urls_list_full[$NoWmBUTFvN7kzhyn0o] || ($NoWmBUTFvN7kzhyn0o == $Btu_OfIkMLszVD))
																													
																													continue;
																													
																													if($grab_parameters['xs_exclude_check'])
																													
																													{
																													
																													$_f=$_f2=false;
																													
																													$_f=$this->davTYrpwM_4COa&&preg_match('#('.$this->davTYrpwM_4COa.')#',$NoWmBUTFvN7kzhyn0o);
																													
																													if($this->JkWlL2a0m&&!$_f)
																													
																													foreach($this->JkWlL2a0m as $bm)
																													
																													$_f = $_f||preg_match('#^('.$bm.')#',$this->Vz1MJUA8RCHpI.$NoWmBUTFvN7kzhyn0o);
																													
																													
																													
																													if($_f)continue;
																													
																													}
																													
																													duBMhqfH7kGKH("<u>[$NoWmBUTFvN7kzhyn0o]</u><br>\n",2);//exit;
																													
																													$urls_list2[$NoWmBUTFvN7kzhyn0o]++;
																													
																													if($grab_parameters['xs_maxref'] && count($ref_links[$NoWmBUTFvN7kzhyn0o])<$grab_parameters['xs_maxref'])
																													
																													$ref_links[$NoWmBUTFvN7kzhyn0o][] = $Btu_OfIkMLszVD;
																													
																													$nt++;
																													
																													}
																													
																													unset($lWjtsny3d);
																													
																													fM0qQkSagz_('llist', true);
																													
																													}
																													
																													}
																													
																													
																													$b6AdG6bCyaYg = count($urls_list2);
																													
																													fM0qQkSagz_('analyze', true);
																													
																													fM0qQkSagz_('post');
																													
																													if($grab_parameters['xs_incl_only'] && !$f){
																													
																													global $qIAc2k9fh0vlEajUm;
																													
																													if(!isset($qIAc2k9fh0vlEajUm)){
																													
																													$qIAc2k9fh0vlEajUm = $grab_parameters['xs_incl_only'];
																													
																													if(!preg_match('#[\*\$]#',$qIAc2k9fh0vlEajUm))
																													
																													$qIAc2k9fh0vlEajUm = preg_quote($qIAc2k9fh0vlEajUm,'#');
																													
																													$qIAc2k9fh0vlEajUm = '#'.str_replace(' ', '|', $qIAc2k9fh0vlEajUm).'#';
																													
																													}
																													
																													$f = $f || !preg_match($qIAc2k9fh0vlEajUm,$W5IQ4GpZEAaCLw_Kty.$Btu_OfIkMLszVD);
																													
																													}
																													
																													if(!$f) {
																													
																													$f = $f||preg_match('#<meta\s*name=[\'"]robots[\'"]\s*content=[\'"][^\'"]*?noindex#is',$cn);
																													
																													if($f)$hZahAndvJ = 'mrob';
																													
																													}
																													
																													if(!$f)
																													
																													{
																													
																													if(!$OqtGTVqoDwX) {
																													
																													$OqtGTVqoDwX = array(
																													
																													
																													'link' => preg_replace('#//+$#','/', 
																													
																													preg_replace('#^([^/\:\?]/)/+#','\\1', 
																													
																													(strstr($Btu_OfIkMLszVD, '://') ? $Btu_OfIkMLszVD : $W5IQ4GpZEAaCLw_Kty . $Btu_OfIkMLszVD)
																													
																													))
																													
																													);
																													
																													if($grab_parameters['xs_makehtml']||$grab_parameters['xs_makeror']||$grab_parameters['xs_rssinfo'])
																													
																													{
																													
																													preg_match('#<title>([^<]*?)</title>#is', $p0qjAJ_05R9['content'], $wariOKKebDD);
																													
																													$OqtGTVqoDwX['t'] = strip_tags($wariOKKebDD[1]);
																													
																													}
																													
																													if($grab_parameters['xs_metadesc'])
																													
																													{
																													
																													preg_match('#<meta\s[^>]*(?:http-equiv|name)\s*=\s*"?description[^>]*content\s*=\s*["]?([^>\"]*)#is', $cn, $bhW2AGNqu1FqHH7Zt);
																													
																													if($bhW2AGNqu1FqHH7Zt[1])
																													
																													$OqtGTVqoDwX['d'] = $bhW2AGNqu1FqHH7Zt[1];
																													
																													}
																													
																													if($grab_parameters['xs_makeror']||$grab_parameters['xs_autopriority'])
																													
																													$OqtGTVqoDwX['o'] = max(0,$links_level);
																													
																													if($UBKH2B2Rj1_)
																													
																													$OqtGTVqoDwX['p'] = $UBKH2B2Rj1_;
																													
																													if(preg_match('#<meta\s[^>]*(?:http-equiv|name)\s*=\s*"?last-modified[^>]*content\s*=\s*["]?([^>\"]*)#is', $cn, $bhW2AGNqu1FqHH7Zt)){
																													
																													$OqtGTVqoDwX['clm'] = str_replace('@',' ',$bhW2AGNqu1FqHH7Zt[1]);
																													
																													}
																													
																													if(preg_match('#<meta\s[^>]*(?:http-equiv|name)\s*=\s*"?changefreq[^>]*content\s*=\s*["]?([^>\"]*)#is', $cn, $bhW2AGNqu1FqHH7Zt)){
																													
																													$OqtGTVqoDwX['f'] = $bhW2AGNqu1FqHH7Zt[1];
																													
																													}else
																													
																													if(preg_match('#<meta\s[^>]*(?:http-equiv|name)\s*=\s*"?revisit-after[^>]*content\s*=\s*["]?([^>\"]*)#is', $cn, $bhW2AGNqu1FqHH7Zt)){
																													
																													if(preg_match('#(\d+)\s*hour#',$bhW2AGNqu1FqHH7Zt[1])){
																													
																													$OqtGTVqoDwX['f'] = 'hourly';
																													
																													}
																													
																													if(preg_match('#(\d+)\s*month#',$bhW2AGNqu1FqHH7Zt[1])){
																													
																													$OqtGTVqoDwX['f'] = 'monthly';
																													
																													}
																													
																													if(preg_match('#(\d+)\s*day#',$bhW2AGNqu1FqHH7Zt[1], $bhW2AGNqu1FqHH7Zt)){
																													
																													$d = $bhW2AGNqu1FqHH7Zt[1]+0;
																													
																													if($d<4)$OqtGTVqoDwX['f'] = 'daily';
																													
																													else
																													
																													if($d<22)$OqtGTVqoDwX['f'] = 'weekly';
																													
																													else
																													
																													$OqtGTVqoDwX['f'] = 'monthly';
																													
																													}
																													
																													}
																													
																													if(preg_match('#'.$pZraQah5aJyN.'#',$W5IQ4GpZEAaCLw_Kty.$Btu_OfIkMLszVD,$Azn3vphuaYE))
																													
																													{
																													
																													for($_i=0;$_i<count($Azn3vphuaYE);$_i++)
																													
																													{
																													
																													if($Azn3vphuaYE[$_i+1])
																													
																													break;
																													
																													}
																													
																													if($Y6Sv9w6omZ[$_i]) {
																													
																													if(!$OqtGTVqoDwX['clm'])
																													
																													$OqtGTVqoDwX['clm'] = $Y6Sv9w6omZ[$_i]['lm'];
																													
																													if(!$OqtGTVqoDwX['f'])
																													
																													$OqtGTVqoDwX['f'] = $Y6Sv9w6omZ[$_i]['f'];
																													
																													$OqtGTVqoDwX['p'] = $Y6Sv9w6omZ[$_i]['p'];
																													
																													}
																													
																													}
																													
																													
																													
																													
																													
																													if($grab_parameters['xs_lastmod_notparsed'] && $f2)
																													
																													{
																													
																													$p0qjAJ_05R9 = $e_ksHZ4mvyyb643a5V->fetch($eivnFROHnEo0qZ, 0, 1, false, "", array('req'=>'HEAD'));
																													
																													
																													}
																													
																													if(!$OqtGTVqoDwX['lm'] && isset($p0qjAJ_05R9['headers']['last-modified']))
																													
																													$OqtGTVqoDwX['lm']=$p0qjAJ_05R9['headers']['last-modified'];
																													
																													}
																													
																													fM0qQkSagz_('post', true);
																													
																													fM0qQkSagz_('post-save1');
																													
																													duBMhqfH7kGKH("\n((include ".$OqtGTVqoDwX['link']."))<br />\n");
																													
																													$cgLIwWkdMqHAs = true;
																													
																													if($grab_parameters['xs_memsave'])
																													
																													{
																													
																													WyXkTyAK3kSMA($pE0deENVttSkb8Hz, $OqtGTVqoDwX);
																													
																													$urls_completed[] = $pE0deENVttSkb8Hz;
																													
																													}else
																													
																													$urls_completed[] = serialize($OqtGTVqoDwX);
																													
																													$I5G2fQwAwsO2++;
																													
																													
																													fM0qQkSagz_('post-save1',true);
																													
																													fM0qQkSagz_('post-save2');
																													
																													if($grab_parameters['xs_prev_sm_base']
																													
																													&& $BQ9LlruW7onUt1lb &&
																													
																													preg_match('#('.$BQ9LlruW7onUt1lb.')#',$Btu_OfIkMLszVD)){
																													
																													$sm_base[$Btu_OfIkMLszVD] = $OqtGTVqoDwX;
																													
																													}
																													
																													$zYXN1or0NNm5dt = $HDEWvp62dHhRdkjd_u1 - $I5G2fQwAwsO2;
																													
																													fM0qQkSagz_('post-save2',true);
																													
																													}
																													
																													}while(false);// zerowhile
																													
																													fM0qQkSagz_('post-progress1');
																													
																													if($url_ind>=$cnu)
																													
																													{
																													
																													unset($urls_list);
																													
																													$url_ind = 0;
																													
																													$urls_list = $urls_list2;
																													
																													
																													$urls_list_full += $urls_list;
																													
																													$cnu = count($urls_list);
																													
																													unset($ref_links2);
																													
																													$ref_links2 = $ref_links;
																													
																													unset($ref_links); unset($urls_list2);
																													
																													$ref_links = array();
																													
																													$urls_list2 = array();
																													
																													$links_level++;
																													
																													duBMhqfH7kGKH("\n<br>NEXT LEVEL:$links_level<br />\n");
																													
																													}
																													
																													if(!$cgLIwWkdMqHAs){
																													
																													
																													duBMhqfH7kGKH("\n({skipped ".$Btu_OfIkMLszVD." - $hZahAndvJ})<br />\n");
																													
																													if(!$grab_parameters['xs_chlog_list_max'] ||
																													
																													count($urls_list_skipped) < $grab_parameters['xs_chlog_list_max']) {
																													
																													$urls_list_skipped[$Btu_OfIkMLszVD]=$hZahAndvJ;
																													
																													}
																													
																													}
																													
																													fM0qQkSagz_('post-progress1',true);
																													
																													fM0qQkSagz_('post-progress2');
																													
																													$pn++;
																													
																													$iTLXwB9Qu0cTUSeI6Tl=explode(" ",microtime());
																													
																													$ctime = $iTLXwB9Qu0cTUSeI6Tl[0]+$iTLXwB9Qu0cTUSeI6Tl[1] - $DoGItPXelpruGi_;
																													
																													u3Aj3kpP8f7NX();
																													
																													$pl=min($cnu-$url_ind,$zYXN1or0NNm5dt);
																													
																													fM0qQkSagz_('post-progress2',true);
																													
																													fM0qQkSagz_('post-progress3');
																													
																													if( ($cnu==$url_ind || $pl==0||$pn==1 || ($pn%$grab_parameters['xs_progupdate'])==0)
																													
																													|| ($ctime - $Kxk2n3T4kfA > 5)
																													
																													|| $I5G2fQwAwsO2>=$HDEWvp62dHhRdkjd_u1)
																													
																													{
																													
																													
																													$Kxk2n3T4kfA = $z45zMtoh5QPc;
																													
																													if(strstr($Gc2nlIgGoyc5qc['content'],'header'))break;
																													
																													global $m8;
																													
																													$mu = function_exists('memory_get_usage') ? memory_get_usage() : '-';
																													
																													$EOjynEOiVkO = max($EOjynEOiVkO, $mu);
																													
																													if($mu>$m8+1000000){
																													
																													$m8 = $mu;
																													
																													$cc = ' style="color:red"';
																													
																													}else 
																													
																													$cc='';
																													
																													if(intval($mu))
																													
																													$mu = number_format($mu/1024,1).' Kb';
																													
																													duBMhqfH7kGKH("\n(<span".$cc.">memory".($cc?' up':'').": $mu</span>)<br>\n");
																													
																													$L3KcxI6fDP = ($I5G2fQwAwsO2>=$HDEWvp62dHhRdkjd_u1) || ($url_ind>=$cnu);
																													
																													$progpar = array(
																													
																													$ctime, // 0. running time
																													
																													str_replace($gs4Fbubikm9UoY, '', $Btu_OfIkMLszVD),  // 1. current URL
																													
																													$pl,                    // 2. urls left
																													
																													$pn,                    // 3. processed urls
																													
																													$tsize,                 // 4. bandwidth usage
																													
																													$links_level,           // 5. depth level
																													
																													$mu,                    // 6. memory usage
																													
																													$I5G2fQwAwsO2, // 7. added in sitemap
																													
																													$b6AdG6bCyaYg,     // 8. in the queue
																													
																													$nettime,	// 9. network time
																													
																													$rpptXIHu4, // 10. last net time
																													
																													$fetch_no // 11. fetched urls
																													
																													);
																													
																													if($CWUHIna3EKRbOn4D4cw['bgexec']){
																													
																													if((time()-$WSnADWH33qv_CpxekT)>tV12hsJy_('xs_state_interval',5)){
																													
																													$WSnADWH33qv_CpxekT = time();
																													
																													$progpar[] = g9mWWYIteSa7dD7();
																													
																													K8zTIUVnwPDUDC(Og4KRtb1cdnHxZIO89,mKNc3ZuibHw8($progpar));
																													
																													}
																													
																													}
																													
																													if($VHkEbcZzGkWKeYH && !$f)
																													
																													$VHkEbcZzGkWKeYH($progpar);
																													
																													
																													}
																													
																													else
																													
																													{
																													
																													$VHkEbcZzGkWKeYH(array('cmd'=>'ping', 'bg' => $CWUHIna3EKRbOn4D4cw['bgexec']));
																													
																													}
																													
																													fM0qQkSagz_('post-progress3',true);
																													
																													fM0qQkSagz_('post-progress4');
																													
																													if(!$m5czXgS9ltGPU6dr) {
																													
																													
																													if($m5czXgS9ltGPU6dr = MMwXOhanJ6q()){
																													
																													if(!@Hqm42kdaBr($m5czXgS9ltGPU6dr))
																													
																													$m5czXgS9ltGPU6dr=0;
																													
																													}
																													
																													}
																													
																													if($grab_parameters['xs_exec_time'] && 
																													
																													((time()-$uTttAJYJzqs) > $grab_parameters['xs_exec_time']) ){
																													
																													$m5czXgS9ltGPU6dr = 'Time limit exceeded - '.($grab_parameters['xs_exec_time']).' - '.(time()-$uTttAJYJzqs);
																													
																													}
																													
																													if($grab_parameters['xs_savestate_time']>0 &&
																													
																													( 
																													
																													($ctime-$kxcgYd2Pc>$grab_parameters['xs_savestate_time'])
																													
																													|| $L3KcxI6fDP
																													
																													|| $m5czXgS9ltGPU6dr
																													
																													)
																													
																													)
																													
																													{
																													
																													$kxcgYd2Pc = $ctime;
																													
																													duBMhqfH7kGKH("(saving dump)<br />\n");
																													
																													$eZcoEqTWVw8eCCWpV = compact('url_ind',
																													
																													'urls_list','urls_list2','cnu',
																													
																													'ref_links','ref_links2',
																													
																													'urls_list_full','urls_completed',
																													
																													'urls_404',
																													
																													'nt','tsize','pn','links_level','ctime', 'urls_ext','fetch_no',
																													
																													'starttime', 'retrno', 'nettime', 'urls_list_skipped',
																													
																													'imlist', 'progpar', 'runstate', 'sm_base'
																													
																													);
																													
																													$eZcoEqTWVw8eCCWpV['time']=time();
																													
																													$dBMhmV_w5CH2T5n=mKNc3ZuibHw8($eZcoEqTWVw8eCCWpV);
																													
																													K8zTIUVnwPDUDC(q0PNLQD52dm6SKSyg,$dBMhmV_w5CH2T5n,eYgPj3ZHK0T12hAy,true);
																													
																													unset($eZcoEqTWVw8eCCWpV);
																													
																													unset($dBMhmV_w5CH2T5n);
																													
																													}
																													
																													if($grab_parameters['xs_delay_req'] && $grab_parameters['xs_delay_ms'] &&
																													
																													(($Gm4pFalvB%$grab_parameters['xs_delay_req'])==0))
																													
																													{
																													
																													sleep(intval($grab_parameters['xs_delay_ms']));
																													
																													}
																													
																													fM0qQkSagz_('post-progress4', true);
																													
																													}while(!$L3KcxI6fDP && !$m5czXgS9ltGPU6dr);
																													
																													duBMhqfH7kGKH("\n\n<br><br>Crawling completed<br>\n");
																													
																													if($_GET['ddbgexit']){
																													
																													echo '<hr><hr><h2>Dbg exit</h2>';
																													
																													echo $e_ksHZ4mvyyb643a5V->WuOtxQB2cfkjS.' / '.$e_ksHZ4mvyyb643a5V->nettime.'<hr>';
																													
																													echo g9mWWYIteSa7dD7().'<hr>';
																													
																													exit;
																													
																													}
																													
																													return array(
																													
																													'u404'=>$urls_404,
																													
																													'starttime'=>$starttime,
																													
																													'topmu' => $EOjynEOiVkO,
																													
																													'ctime'=>$ctime,
																													
																													'tsize'=>$tsize,
																													
																													'retrno' => $retrno,
																													
																													'nettime' => $nettime,
																													
																													'errmsg'=>'',
																													
																													'initurl'=>$gs4Fbubikm9UoY,
																													
																													'initdir'=>$W5IQ4GpZEAaCLw_Kty,
																													
																													'ucount'=>$I5G2fQwAwsO2,
																													
																													'crcount'=>$pn,
																													
																													'fetch_no'=>$fetch_no,
																													
																													'time'=>time(),
																													
																													'params'=>$CWUHIna3EKRbOn4D4cw,
																													
																													'interrupt'=>$m5czXgS9ltGPU6dr,
																													
																													'runstate' => $runstate,
																													
																													'sm_base' => $sm_base,
																													
																													'urls_ext'=>$urls_ext,
																													
																													'urls_list_skipped' => $urls_list_skipped,
																													
																													'max_reached' => $I5G2fQwAwsO2>=$HDEWvp62dHhRdkjd_u1
																													
																													);
																													
																													}
																													
																													}
																													
																													$MaMJn8Q5cIWqbSsi3R = new SiteCrawler();
																													
																													function KrTMgWoxdnmEih0271(){
																													
																													@Hqm42kdaBr(eYgPj3ZHK0T12hAy.gLuwORIUpN);
																													
																													if(@file_exists(eYgPj3ZHK0T12hAy.Og4KRtb1cdnHxZIO89))
																													
																													@rename(eYgPj3ZHK0T12hAy.Og4KRtb1cdnHxZIO89,eYgPj3ZHK0T12hAy.gLuwORIUpN);
																													
																													}
																													
																													



































































































