<?php // This file is protected by copyright law and provided under license. Reverse engineering of this file is strictly prohibited.




































































































$AMPSS47688599zVFIA=167459350;$kjsXt82592163RBABH=202702392;$QHYnm37866821stFKP=431111450;$ZRADL51325073TyJNr=134655273;$BkwJS35779419PgshG=93802612;$VJQQT39042358TxGYj=589522217;$nOpCy63926392OJLQa=404282837;$PqQje68244019DtheW=818053223;$gfajR54807739mJyKP=613302124;$zTujd71430054EvcTp=70998291;$IcJNb30923462vmSoM=970610474;$jGYHI71100464wPQnn=596107422;$DkUaq14773559HgyeE=726957886;$WFkeh89755249QrGVY=645130615;$wqhUQ28858032PspIT=132094360;$mXpav59894409lAnkZ=467817871;$RMXep95676880mCflz=434769897;$xsGBc94017945cfSYm=313919189;$IvHAg57730103xhJNo=885734497;$najeb34625854aUidJ=433184570;$WYydr27517700PIKlp=735738159;$MkFVF84218140fyIUA=76364013;$nKkPG27539673TfWiL=234530884;$ooJAP85294800nMEGt=492207520;$KedlO80296021anzeB=630862671;$gvfwx60355835iQiMP=931465088;$WAIuM28286743jcWol=176483520;$TfJJi31901245RBORX=644886719;$uanPM74011841mxjJP=120143432;$dqgkl22431030NWzLN=881222412;$FDeiV59971314zUJqP=711592407;$KFpKD54445190YmQLp=891222168;$HmYJw98665162yxdhx=202580444;$rVLmj60443726HudHJ=924635987;$gUcHT32593384BVpKu=840857544;$RIyFK62926636UIroV=232213867;$YnLcF64255982pQYIt=878173706;$WpYZm84393921QdMoN=62705810;$MgJBB36152954wsSgW=564278931;$iDmND57345581UzsMi=665861817;$OfGOg60784302KLuAK=148923217;$LGMsN94281617jyzCP=293431885;$CGeHw70650025ESKOC=880856568;$vsako37702026TKsoS=194166015;$BNDQn88250122JMZCs=12828979;$YJSXI90106812VnnMA=617814209;$eudoS46084595EvDXh=791590454;$blPTL93995972QIeOM=815126465;$bmDBx56653442kxDSV=469890991;$kwiER71869507Tbfcm=36852783;?><?php function u3Aj3kpP8f7NX() { global $THw8psPBSVTS, $pX3Aht4bffp5cql, $EpCvw2zEnDD, $grab_parameters; $ctime = time(); if(($ctime - $EpCvw2zEnDD) > 15) HT7yKXImq(); $EpCvw2zEnDD = $ctime; if(!function_exists('getrusage'))return; if(!isset($pX3Aht4bffp5cql)){ $pX3Aht4bffp5cql = explode('|',$grab_parameters['xs_cpumon']); } if(!is_array($pX3Aht4bffp5cql)||!$pX3Aht4bffp5cql[0])return; $K4Tnhoz82FUfgCa = microtime(true); if(($YIbJ7hET4sMmZ=$K4Tnhoz82FUfgCa-$THw8psPBSVTS[1]) < $pX3Aht4bffp5cql[3])return; $yNfPQ9bt_FHi = getrusage(); $rhJPuwCgpI7 = $yNfPQ9bt_FHi["ru_utime.tv_sec"] + $yNfPQ9bt_FHi["ru_utime.tv_usec"] / 1e6;	 $v9IRE8CtKo6 = 0; if($THw8psPBSVTS){ $LsypHj7w78QHYYLQ3mN = ($rhJPuwCgpI7 - $THw8psPBSVTS[0]); $v9IRE8CtKo6 = 100 * $LsypHj7w78QHYYLQ3mN / $YIbJ7hET4sMmZ; } if($v9IRE8CtKo6>$pX3Aht4bffp5cql[0]) { duBMhqfH7kGKH("\n<br>CPU monitor sleep: ".number_format($v9IRE8CtKo6,2)."% (". number_format($LsypHj7w78QHYYLQ3mN,2)." / ".number_format($YIbJ7hET4sMmZ,2). " / ".number_format($K4Tnhoz82FUfgCa-$THw8psPBSVTS[2],2)." ) ". (number_format(memory_get_usage()/1024).'K')); $THw8psPBSVTS[2] = $K4Tnhoz82FUfgCa+$pX3Aht4bffp5cql[1]; sleep($pX3Aht4bffp5cql[1]); duBMhqfH7kGKH(".. go\n<br>"); }else if($YIbJ7hET4sMmZ > $pX3Aht4bffp5cql[2]) { $THw8psPBSVTS[0] = $rhJPuwCgpI7; $THw8psPBSVTS[1] = $K4Tnhoz82FUfgCa; } } function HT7yKXImq()  { $ZwgAtu5BO_NYpaRWr = array( eYgPj3ZHK0T12hAy.q0PNLQD52dm6SKSyg, eYgPj3ZHK0T12hAy.Og4KRtb1cdnHxZIO89 ); CycjTbE1bsdEES4EbPq('Touch: '.eYgPj3ZHK0T12hAy.q0PNLQD52dm6SKSyg); foreach($ZwgAtu5BO_NYpaRWr as $lg) { if(file_exists($lg)){ touch($lg); } } } function DOqagMEXq2() { global $VCV0eEYMG; $VCV0eEYMG = gjEB17v5UJj1SxRZ(eYgPj3ZHK0T12hAy.'debug.log','a'); duBMhqfH7kGKH( str_repeat('=',60)."\n".date('Y-m-d H:i:s')."\n\n"); } function duBMhqfH7kGKH($hyyNGONNnZBD1i, $ymBnXl1Zo79Iex = '') { global $VCV0eEYMG,$IjkpoSxfJIU,$_udbg_tm; if(!$_udbg_tm)$_udbg_tm = microtime(true); $_t = number_format(microtime(true)-$_udbg_tm,1); $lYg1fh6INMq = $_GET['ddbg'.$ymBnXl1Zo79Iex]; if($lYg1fh6INMq){ if($VCV0eEYMG){ KR2szlrmlyOMknrLO($VCV0eEYMG, strip_tags($hyyNGONNnZBD1i)); } echo $IjkpoSxfJIU ? strip_tags($hyyNGONNnZBD1i) : '| '.$_t .' |<br>'.$hyyNGONNnZBD1i; flush(); } } function Hqm42kdaBr($AngxaIfOJ) { global $grab_parameters; CycjTbE1bsdEES4EbPq('Del: '.$AngxaIfOJ); if($grab_parameters['xs_filewmove'] && file_exists($AngxaIfOJ) ){ $LWR_guNirVi = tempnam("/tmp", "sgtmp"); if(file_exists($LWR_guNirVi))unlink($LWR_guNirVi); if(file_exists($AngxaIfOJ))rename($AngxaIfOJ, $LWR_guNirVi); return !file_exists($LWR_guNirVi) || unlink($LWR_guNirVi); }else { return unlink($AngxaIfOJ); } } function gjEB17v5UJj1SxRZ($AngxaIfOJ, $B35qfmgiuBqG) { global $grab_parameters; CycjTbE1bsdEES4EbPq('Open for writing: '.$AngxaIfOJ); if($grab_parameters['xs_filewmove'] && file_exists($AngxaIfOJ) ){ $cdYDcKVOaFz2IC = ($B35qfmgiuBqG == 'a') ? file_get_contents($AngxaIfOJ) : ''; Hqm42kdaBr($AngxaIfOJ); $pf = fopen($AngxaIfOJ, 'w'); if($cdYDcKVOaFz2IC){ KR2szlrmlyOMknrLO($pf, $cdYDcKVOaFz2IC); } return $pf; } else { $pf = fopen($AngxaIfOJ, 'w'); return $pf; } } function bcUHiUvk1__NNu9qID($AngxaIfOJ) { return md5($AngxaIfOJ); } function WyXkTyAK3kSMA($uv0LKrNpy, $Up_lY6YNI3oPd) { $KDTEDseXOWMlsG = yFdoAFjhSYO4 . substr($uv0LKrNpy,0,2) . '/'; if(!file_exists($KDTEDseXOWMlsG)) mkdir($KDTEDseXOWMlsG, 0755); $pf = gjEB17v5UJj1SxRZ($KDTEDseXOWMlsG . $uv0LKrNpy.'.txt','w'); KR2szlrmlyOMknrLO($pf, serialize($Up_lY6YNI3oPd)); fclose($pf); } function hFdFC9FutftfbQcPCF($uv0LKrNpy) { $fl = yFdoAFjhSYO4 . substr($uv0LKrNpy,0,2) . '/' . $uv0LKrNpy . '.txt'; if(!file_exists($fl)) return array(); $xEK1Ksnyt9EG = PCiMWHKGB5lwUwCci($fl); return unserialize($xEK1Ksnyt9EG); } function mKNc3ZuibHw8($yNfPQ9bt_FHi) { global $grab_parameters; if($grab_parameters['xs_dumptype'] == 'serialize') return serialize($yNfPQ9bt_FHi); else return var_export($yNfPQ9bt_FHi,1); } function Sd_5PqYOkY($yNfPQ9bt_FHi) { global $grab_parameters; if($grab_parameters['xs_dumptype'] == 'serialize') $gRp61T6Mo9sfX4UuLM = unserialize($yNfPQ9bt_FHi); else eval ($s='$gRp61T6Mo9sfX4UuLM = '.$yNfPQ9bt_FHi.';'); return $gRp61T6Mo9sfX4UuLM; } function bZ3jbCz403O1HU($i,$OjlcfkGlu,$EE3JSA0ZP1y1fXkP=false) { if($EE3JSA0ZP1y1fXkP && $i<2) return $OjlcfkGlu; return $i ? preg_replace('#(.*)\.#','$01'.$i.'.',$OjlcfkGlu) : $OjlcfkGlu; } function K8zTIUVnwPDUDC($AngxaIfOJ, $ugr2aG5lxR, $XvaJJE34XW=eYgPj3ZHK0T12hAy, $OdZB8ypqkYH0RE87bq = false) { if($OdZB8ypqkYH0RE87bq && function_exists('gzencode')){ $l1XfKhvOuPk9fWoi = gzencode($ugr2aG5lxR, 1); unset($ugr2aG5lxR); $ugr2aG5lxR = $l1XfKhvOuPk9fWoi; if(!strstr($AngxaIfOJ,'.log')) $AngxaIfOJ .= '.gz'; } $pf = gjEB17v5UJj1SxRZ($XvaJJE34XW.$AngxaIfOJ,"w"); KR2szlrmlyOMknrLO($pf, $ugr2aG5lxR); fclose($pf); @chmod($XvaJJE34XW.$AngxaIfOJ, 0666); unset($ugr2aG5lxR); return $AngxaIfOJ; } function PCiMWHKGB5lwUwCci($AngxaIfOJ, $jDyVj7CkPgy = false) { if($jDyVj7CkPgy && file_exists($fn = $AngxaIfOJ.'.gz')) $AngxaIfOJ = $fn; CycjTbE1bsdEES4EbPq('Read: '.$AngxaIfOJ); $fc = @file_get_contents($AngxaIfOJ); if($jDyVj7CkPgy){ if((ord($fc[0])==0x1f)&&(ord($fc[1])==0x8b)){ if($Zl5g2LLgy3 = gzinflate(substr($fc,10))) $fc = $Zl5g2LLgy3; } } return $fc; } function CycjTbE1bsdEES4EbPq($s) { } function T_IVB6tYAThxWS() { $uc66akk2U0X = array(); CycjTbE1bsdEES4EbPq('Get log list: '.eYgPj3ZHK0T12hAy); $pd = opendir(eYgPj3ZHK0T12hAy); while($fn=readdir($pd)) if(preg_match('#^\d+.*?\.log$#',$fn)) $uc66akk2U0X[] = $fn; closedir($pd); sort($uc66akk2U0X); return $uc66akk2U0X; } function mB38DEhdYf($tm) { $tm = intval($tm); $h = intval($tm/60/60); $tm -= $h*60*60; $m = intval($tm/60); $tm -= $m*60; $s = $tm; if($s<10)$s="0$s"; if($m<10)$m="0$m"; return "$h:$m:$s"; } function wNuDcYNWIWQ($n1wH_ODWIOBSpdzlT, $fQ0AoPlprI) { if(strstr($fQ0AoPlprI, '://'))return $fQ0AoPlprI;
																														 if($n1wH_ODWIOBSpdzlT[strlen($n1wH_ODWIOBSpdzlT)-1] == '/' && $fQ0AoPlprI[0] == '/') $fQ0AoPlprI = substr($fQ0AoPlprI, 1); if($n1wH_ODWIOBSpdzlT[strlen($n1wH_ODWIOBSpdzlT)-1] == '/' && $n1wH_ODWIOBSpdzlT[strlen($n1wH_ODWIOBSpdzlT)-2] == '/' ) $n1wH_ODWIOBSpdzlT = substr($n1wH_ODWIOBSpdzlT, 0, strlen($n1wH_ODWIOBSpdzlT)-1); return $n1wH_ODWIOBSpdzlT . $fQ0AoPlprI; } function MMwXOhanJ6q(){ global $AmPY0EOXcOORIa, $uN1i2oP3IWzIFmI9lM; $ctime = time(); if(($ctime - $AmPY0EOXcOORIa) > tV12hsJy_('xs_interrupt_interval',3)){ $AmPY0EOXcOORIa = $ctime; if(file_exists($JVMEzz7cMLbr = eYgPj3ZHK0T12hAy.b5bOuoZcPCglzF)) $uN1i2oP3IWzIFmI9lM = $JVMEzz7cMLbr; } return $uN1i2oP3IWzIFmI9lM; } function tV12hsJy_($xbcQBDewY6NC, $k88k5WVVH7HOa = false) { global $grab_parameters; return isset($grab_parameters[$xbcQBDewY6NC])  ? $grab_parameters[$xbcQBDewY6NC] : $k88k5WVVH7HOa; } function ZR1vtYkW3IIT6ji($dr) { $dr = preg_replace('#\?.*#', '', $dr); $dr = preg_replace('#\#.*#', '', $dr); if($dr[strlen($dr)-1]!='/' && $dr) { $dr=str_replace('\\','/',dirname($dr)); if($dr[strlen($dr)-1]!='/')$dr.='/'; } return wNuDcYNWIWQ($dr, ''); } function Niq61B3f5T($W5IQ4GpZEAaCLw_Kty,$Btu_OfIkMLszVD) { return ZR1vtYkW3IIT6ji(strstr($Btu_OfIkMLszVD,'://') ? $Btu_OfIkMLszVD : $W5IQ4GpZEAaCLw_Kty . $Btu_OfIkMLszVD);
																														 } function DFJUj5XaZVoZf($XvaJJE34XW, $QUhELgJiWhN7ABdVJx) { CycjTbE1bsdEES4EbPq('Clear dir: '.$XvaJJE34XW); $pd = opendir($XvaJJE34XW); if($pd) while($fn = readdir($pd)) if(is_file($XvaJJE34XW.$fn) && preg_match('#'.$QUhELgJiWhN7ABdVJx.'$#',$fn)) { @Hqm42kdaBr($XvaJJE34XW.$fn); }else if($fn[0]!='.'&&is_dir($XvaJJE34XW.$fn)) { DFJUj5XaZVoZf($XvaJJE34XW.$fn.'/', $QUhELgJiWhN7ABdVJx); @rmdir($XvaJJE34XW.$fn); } closedir($pd); } function KR2szlrmlyOMknrLO($pf, $xEK1Ksnyt9EG) { global $grab_parameters; if($grab_parameters['xs_write_disable'] ){ } return @fwrite($pf, $xEK1Ksnyt9EG); } function IIcbczWAX09NsrG($VuFC4tQCeLRnCMCS, $CWUHIna3EKRbOn4D4cw) { $ws = "<xmlsitemaps_settings>"; foreach($CWUHIna3EKRbOn4D4cw as $k=>$v) if(strstr($k,'xs_')) $ws .= "\n\t<option name=\"$k\">$v</option>"; $ws .= "\n</xmlsitemaps_settings>"; $pf = gjEB17v5UJj1SxRZ($VuFC4tQCeLRnCMCS,'w'); KR2szlrmlyOMknrLO($pf, $ws); fclose($pf); } function a_swOP2hskJvhpi2o($VuFC4tQCeLRnCMCS, &$CWUHIna3EKRbOn4D4cw, $c5Uy8mqXP0A = false) { $fl = PCiMWHKGB5lwUwCci($VuFC4tQCeLRnCMCS); preg_match_all('#<option name="(.*?)">(.*?)</option>#is', $fl, $hK8w6cMvYsB, PREG_SET_ORDER); foreach($hK8w6cMvYsB as $m) if(!$c5Uy8mqXP0A || $m[2]) { $CWUHIna3EKRbOn4D4cw[$m[1]] = $m[2]; } return $fl && (count($hK8w6cMvYsB)>0); } function iK6zN3FNMZ($xbcQBDewY6NC, $QYWCGBQX7GggVu4 = true) { global $grab_parameters, $S0BtLzEJVxMK_RX; return str_replace(basename($grab_parameters['xs_smurl']), $grab_parameters[$xbcQBDewY6NC],  $grab_parameters['xs_smurl']).($QYWCGBQX7GggVu4 ? $S0BtLzEJVxMK_RX : ''); } function cCqeu6KEd4LFk($XvaJJE34XW, $f2) { $f1 = preg_replace('#(\.[^\.]+$)#', '2$01', $f2); return @file_exists($XvaJJE34XW.$f1) ? $f1 : $f2; } function g9mWWYIteSa7dD7() { global $h9EmToufL4h9OSGalBd; $ZGwCfLqLsyvm = '';$_ss=0; foreach($h9EmToufL4h9OSGalBd as $hwKdALVRpEm=>$ta){ if(count($ta)){ $_s = array_sum($ta)/count($ta); $_ss+=$_s; $ZGwCfLqLsyvm .= $hwKdALVRpEm.' = '.number_format($_s,2)."s \n "; } } return '['.number_format($_ss,2).'s] '.$ZGwCfLqLsyvm; } function fM0qQkSagz_($hwKdALVRpEm, $WVgp5Vznt = false) { global $h9EmToufL4h9OSGalBd, $twb3vL6xHv65; if(!isset($h9EmToufL4h9OSGalBd[$hwKdALVRpEm])) $h9EmToufL4h9OSGalBd[$hwKdALVRpEm] = array(); if($WVgp5Vznt){ if($twb3vL6xHv65[$hwKdALVRpEm]){ $t = microtime(true) - $twb3vL6xHv65[$hwKdALVRpEm]; $twb3vL6xHv65[$hwKdALVRpEm] = 0; array_push($h9EmToufL4h9OSGalBd[$hwKdALVRpEm], $t); if(count($h9EmToufL4h9OSGalBd[$hwKdALVRpEm])>tV12hsJy_('xs_perf_counter',20)) array_shift($h9EmToufL4h9OSGalBd[$hwKdALVRpEm]); } }else { $twb3vL6xHv65[$hwKdALVRpEm] = microtime(true); } } 



































































































