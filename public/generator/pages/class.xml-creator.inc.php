<?php // This file is protected by copyright law and provided under license. Reverse engineering of this file is strictly prohibited.




































































































$dEizN56370544yuNTF=679184662;$cnwnX91757508omQYw=676867645;$AFMwq70718689ZMTnw=63833831;$OCfPS74816590qSlTe=494676971;$aHKYx50613708XOXZX=127490814;$NJRbk79672547gUKLS=616869110;$rJYza18555603vxpqO=120905609;$Uouwd38825378pydoO=294194061;$kBXRi87044373HcOuT=293828216;$ZnlOz64775086MQhhJ=775401825;$yISbw98580018yqYmC=896008637;$BXrYI90021668VtGka=312242401;$BtOhj75662537KxgaZ=179196869;$uOVtz47065124gtifJ=153465789;$MWOje40791931ZSXBJ=391142914;$UrtzD48405456gGTJi=548821991;$QlwOL16468200FTKEH=782596772;$LsHJk26542663pxaFS=749061005;$JzrHc25191345KZqzo=604308441;$UKkxS93976746klKKw=4932830;$CfMGm89461365VMwLk=106027923;$UnPTC93207703OvVHq=564187470;$dlrMu51778259PiMGp=536505219;$NAvYP46735535Hskoq=678574921;$osEUY24642028pUoSf=147490326;$CtLPv67060242JoAlf=597845184;$JMOVp30552673KIFiF=187733246;$ObJex86681824Xprtt=571748261;$hzPpt92010193WniVB=906983979;$WoFND38100281vUyyO=850034150;$MJAYT51514587Asbnq=556992523;$KQiLV33815613YHjnh=683452851;$lavbC21565857NWNql=386508881;$mLqmw96327820LDLnu=321754364;$ynIMs24664001thBGF=645283051;$twITs68136902VeHsW=14688690;$NdHbt83309021mDIee=584065033;$FKfkt61742859RKIdP=12005828;$rowtc40000915ccfel=452604828;$DwkPR99645691SrUOZ=563455780;$YjPoT97239686ydoem=500652435;$vlPpq24345398qsBUs=919788544;$ADMCS97525330SGmfp=977957856;$XmCMI38341980sGSbd=331754120;$rilTv63357849SwWwW=136271087;$vooaH74135437rORct=48102508;$QbRbU17237243saWQG=223342132;$dvPWR64225769vjxKZ=318583710;$xqdJU71663513INKNo=489920990;$GTJHs31112976KKEIV=393947723;?><?php if(!class_exists('XMLCreator')) { class XMLCreator { var $gzkKVwfuY95v65UABBM  = array(); var $iPQJB7_hQeUXBg7Q0 = array('xml','','','','mobile'); var $CWUHIna3EKRbOn4D4cw = array(); var $RYsvKg3BLnSvTb = array(),  $xiXlFjjdb5NC_o2wn = array(),  $ANwqywRuJwV = array(); var $tdIdoNkq99Ug1RX = 1000; var $fMiTRrdjIW = array(); function EYyBXYy9WWbLGYL5iQ(&$w63YMiTeLeUqIPT) { $U6vh806Dp_SOfzA = false; if(is_array($w63YMiTeLeUqIPT)) foreach($w63YMiTeLeUqIPT as $k=>$v){ if(strlen($k)>200){ $U6vh806Dp_SOfzA = true; $w63YMiTeLeUqIPT[$k] = substr($v, 0, 200); } } } function D6IQA6CnCMx9RWf($CWUHIna3EKRbOn4D4cw, $urls_completed, $Kd9n5bwrnd17vZn9U) { global $cGfRoq5gvq, $BkrUa2m_ZoFXT; $BkrUa2m_ZoFXT = array(); if($ZpnMGut2T = @PCiMWHKGB5lwUwCci(eYgPj3ZHK0T12hAy.'apicache.db',true)){ $this->fMiTRrdjIW = @unserialize($ZpnMGut2T); }    $this->GkN2xe_LS5 = new D9DXBC_cr4mro("pages/"); $this->CWUHIna3EKRbOn4D4cw = $CWUHIna3EKRbOn4D4cw; if($this->CWUHIna3EKRbOn4D4cw['xs_chlog_list_max']) $this->tdIdoNkq99Ug1RX = $this->CWUHIna3EKRbOn4D4cw['xs_chlog_list_max'];  $OjlcfkGlu = basename($this->CWUHIna3EKRbOn4D4cw['xs_smname']); $this->uurl_p = dirname($this->CWUHIna3EKRbOn4D4cw['xs_smurl']).'/'; $this->furl_p = dirname($this->CWUHIna3EKRbOn4D4cw['xs_smname']).'/'; $this->imgno = 0; $this->S0BtLzEJVxMK_RX = ($this->CWUHIna3EKRbOn4D4cw['xs_compress']==1) ? '.gz' : ''; $this->RYsvKg3BLnSvTb = $this->xiXlFjjdb5NC_o2wn = $this->urls_prevrss = array(); if($this->CWUHIna3EKRbOn4D4cw['xs_chlog']) $this->RYsvKg3BLnSvTb = $this->J0guAv8GXYn6AN38fxM($OjlcfkGlu); if($this->CWUHIna3EKRbOn4D4cw['xs_rssinfo']) $this->urls_prevrss = $this->J0guAv8GXYn6AN38fxM(HVKGdDolsi2eMB_mMuD , $this->CWUHIna3EKRbOn4D4cw['xs_rssage'], false, 1); if($this->CWUHIna3EKRbOn4D4cw['xs_newsinfo']) $this->xiXlFjjdb5NC_o2wn = $this->J0guAv8GXYn6AN38fxM($this->CWUHIna3EKRbOn4D4cw['xs_newsfilename'], $this->CWUHIna3EKRbOn4D4cw['xs_newsage']); $wjX5uaTwf5NEglz1aoT = $rPdX9DzTIyRp6e = array(); $this->PmrP6CUAfUY1eXGv5a4 = ($this->CWUHIna3EKRbOn4D4cw['xs_compress']==1) ? array('fopen' => 'gzopen', 'fwrite' => 'gzwrite', 'fclose' => 'gzclose' ) : array('fopen' => 'gjEB17v5UJj1SxRZ', 'fwrite' => 'KR2szlrmlyOMknrLO', 'fclose' => 'fclose' ) ; $SEBFF0h5Qoei = strstr($this->CWUHIna3EKRbOn4D4cw['xs_initurl'],'://www.');
																											 $kQtqLrZQlLrQRZnBH = $cGfRoq5gvq.'/'; if(strstr($this->CWUHIna3EKRbOn4D4cw['xs_initurl'],'https:')) $kQtqLrZQlLrQRZnBH = str_replace('http:', 'https:', $kQtqLrZQlLrQRZnBH); $Uem3Rs_JAbbzZ = strstr($kQtqLrZQlLrQRZnBH,'://www.');
																											 $p1 = parse_url($this->CWUHIna3EKRbOn4D4cw['xs_initurl']); $p2 = parse_url($kQtqLrZQlLrQRZnBH); if(str_replace('www.', '', $p1['host'])==str_replace('www.', '', $p2['host']))  { if($SEBFF0h5Qoei && !$Uem3Rs_JAbbzZ)$kQtqLrZQlLrQRZnBH = str_replace('://', '://www.', $kQtqLrZQlLrQRZnBH);
																											 if(!$SEBFF0h5Qoei && $Uem3Rs_JAbbzZ)$kQtqLrZQlLrQRZnBH = str_replace('://www.', '://', $kQtqLrZQlLrQRZnBH);
																											 } $this->CWUHIna3EKRbOn4D4cw['gendom'] = $kQtqLrZQlLrQRZnBH; $this->UE6za0ucuUsehYAvdt($urls_completed, $wjX5uaTwf5NEglz1aoT); $this->T0gmFEXXIurPo(); if($this->CWUHIna3EKRbOn4D4cw['xs_chlog']) { $jCEDAPDqHjgY  = array_keys($this->ANwqywRuJwV); $pez0n43nSLB = array_slice(array_keys($this->RYsvKg3BLnSvTb), 0, $this->tdIdoNkq99Ug1RX); } if($this->imgno)$this->gzkKVwfuY95v65UABBM[1]['xn'] = $this->imgno; if($this->videos_no)$this->gzkKVwfuY95v65UABBM[2]['xn'] = $this->videos_no; if($this->news_no)$this->gzkKVwfuY95v65UABBM[3]['xn'] = $this->news_no; $this->EYyBXYy9WWbLGYL5iQ($jCEDAPDqHjgY); $this->EYyBXYy9WWbLGYL5iQ($pez0n43nSLB); $pnHOQ4hSY = array_merge($Kd9n5bwrnd17vZn9U, array( 'files'   => array(), 'rinfo'   => $this->gzkKVwfuY95v65UABBM, 'newurls' => $jCEDAPDqHjgY, 'losturls'=> $pez0n43nSLB, 'urls_ext'=> $Kd9n5bwrnd17vZn9U['urls_ext'], 'images_no'  => $this->imgno, 'videos_no' => $this->videos_no, 'news_no'  => $this->newsno, 'rss_no'  => $this->rssno, 'rss_sm'  => $this->CWUHIna3EKRbOn4D4cw['xs_rssfilename'], 'fail_files' => $BkrUa2m_ZoFXT, 'create_time' => time() )); unset($pnHOQ4hSY['sm_base']); $YaDivn1VBzy6yJ = array('u404', 'urls_ext', 'urls_list_skipped', 'newurls', 'losturls'); foreach($YaDivn1VBzy6yJ as $ca) $this->EYyBXYy9WWbLGYL5iQ($pnHOQ4hSY[$ca]); $sbAlpvrt4Uj66Qo1G = date('Y-m-d H-i-s').'.log'; K8zTIUVnwPDUDC($sbAlpvrt4Uj66Qo1G,serialize($pnHOQ4hSY)); $this->RYsvKg3BLnSvTb = $this->ANwqywRuJwV = $this->xiXlFjjdb5NC_o2wn = $this->urls_prevrss = array(); $wjX5uaTwf5NEglz1aoT = array(); return $pnHOQ4hSY; } function lWQKrAn9z($pf) { global $N6He2nKWL8cY; if(!$pf)return; $this->PmrP6CUAfUY1eXGv5a4['fwrite']($pf, $N6He2nKWL8cY[3]); $this->PmrP6CUAfUY1eXGv5a4['fclose']($pf); } function ySNYPM9AgJwfmW($pf, $hwKdALVRpEm) { global $N6He2nKWL8cY; if(!$pf)return; $xs = $this->GkN2xe_LS5->l1acf4pof0Or6uMILSm($N6He2nKWL8cY[1], array('TYPE'.$hwKdALVRpEm=>true)); $this->PmrP6CUAfUY1eXGv5a4['fwrite']($pf, $xs); } function G2zCcihGLQl($rPdX9DzTIyRp6e) { $OIi9aDhIjHT = ""; $uJeraacVojMTx = cCqeu6KEd4LFk(dh6mwOEumX3JD,  'sitemap_index_tpl.xml'); $B0tchSNt2Krkc = file_get_contents(dh6mwOEumX3JD.$uJeraacVojMTx); preg_match('#^(.*)%SITEMAPS_LIST_FROM%(.*)%SITEMAPS_LIST_TO%(.*)$#is', $B0tchSNt2Krkc, $Sjfk6GpUe); $Sjfk6GpUe[1] = str_replace('%GEN_URL%', $this->CWUHIna3EKRbOn4D4cw['gendom'], $Sjfk6GpUe[1]); $Kw78M62lrI5rS = preg_replace('#[^\\/]+?\.xml$#', '', $this->CWUHIna3EKRbOn4D4cw['xs_smurl']); $Sjfk6GpUe[1] = str_replace('%SM_BASE%', $Kw78M62lrI5rS, $Sjfk6GpUe[1]); for($i=0;$i<count($rPdX9DzTIyRp6e);$i++) $OIi9aDhIjHT.= $this->GkN2xe_LS5->l1acf4pof0Or6uMILSm($Sjfk6GpUe[2], array( 'URL'=>$rPdX9DzTIyRp6e[$i], 'LASTMOD'=>date('Y-m-d\TH:i:s+00:00') )); return $Sjfk6GpUe[1] . $OIi9aDhIjHT . $Sjfk6GpUe[3]; } function NgBWwllTme($Petukq3hAAORmvkFA, $sSxD4RNwg = false) { if($sSxD4RNwg){ $t = $Petukq3hAAORmvkFA; if(function_exists('utf8_encode') && !$this->CWUHIna3EKRbOn4D4cw['xs_utf8']){ $t2=''; for($i=0;$i<strlen($t);$i++) $t2 .= ((ord($t[$i])>128) ? '&#'.ord($t[$i]).';' : $t[$i]); $t = $t2; $t = utf8_encode($t); $t = htmlentities($t,ENT_COMPAT,'UTF-8'); } $t = preg_replace("#&amp;(\#[\w\d]+;)#", '&$1', $t); $t = str_replace("&", "&amp;", $t); $t = preg_replace("#&amp;((gt|lt|quot|amp|apos);)#", '&$1', $t); $t = preg_replace('#[\x00-\x1F\x7F]#', ' ', $t); }else $t = str_replace("&", "&amp;", $Petukq3hAAORmvkFA); if(function_exists('utf8_encode') && !$this->CWUHIna3EKRbOn4D4cw['xs_utf8']) { $t = utf8_encode($t); } return $t; } function wR70CK76khtA4O6VZ4I($bE03wJKFluEetHDrxE) { $bE03wJKFluEetHDrxE = $this->NgBWwllTme(str_replace(array('&nbsp;'),array(''),$bE03wJKFluEetHDrxE), true); return $bE03wJKFluEetHDrxE; } function XvU49oK_Vd($Yo37xy5AkTa3g3_RXQ8) { global $sSxD4RNwg; $l = str_replace("&amp;", "&", $Yo37xy5AkTa3g3_RXQ8); $l = str_replace("&", "&amp;", $l); $l = strtr($l, $sSxD4RNwg); if($this->CWUHIna3EKRbOn4D4cw['xs_utf8']) { }else if(function_exists('utf8_encode')) $l = utf8_encode($l); return $l; } function DUZrQcBoQ($nZPP1mn5ss7dro) { $oJ5unwFQhNolkKRYs = array( basename($this->CWUHIna3EKRbOn4D4cw['xs_smname']),  $this->CWUHIna3EKRbOn4D4cw['xs_imgfilename'], $this->CWUHIna3EKRbOn4D4cw['xs_videofilename'], $this->CWUHIna3EKRbOn4D4cw['xs_newsfilename'], $this->CWUHIna3EKRbOn4D4cw['xs_mobilefilename'], ); if($nZPP1mn5ss7dro['rinfo']) $this->gzkKVwfuY95v65UABBM = $nZPP1mn5ss7dro['rinfo']; foreach($this->iPQJB7_hQeUXBg7Q0 as $hwKdALVRpEm=>$Z59xLPPor0bYkq9kN2C) if($Z59xLPPor0bYkq9kN2C) { $this->gzkKVwfuY95v65UABBM[$hwKdALVRpEm]['sitemap_file'] = $oJ5unwFQhNolkKRYs[$hwKdALVRpEm]; $this->gzkKVwfuY95v65UABBM[$hwKdALVRpEm]['filenum'] = intval($nZPP1mn5ss7dro['istart']/$this->l4PwswmkZWYU3PEJDv)+1; if(!$nZPP1mn5ss7dro['istart']) $this->mv3YztXmDFiT($oJ5unwFQhNolkKRYs[$hwKdALVRpEm]); } } function sG53YQ0A72() { global $BkrUa2m_ZoFXT; $we7CyMOB3DkQv = 0; $l = false; foreach($this->iPQJB7_hQeUXBg7Q0 as $hwKdALVRpEm=>$Z59xLPPor0bYkq9kN2C) { $ri = &$this->gzkKVwfuY95v65UABBM[$hwKdALVRpEm]; $I8v4y5NCk0 = (($ri['xnp'] % $this->l4PwswmkZWYU3PEJDv) == 0) && ($ri['xnp'] || !$ri['pf']); $l|=$I8v4y5NCk0; if($this->sm_filesplit && $ri['xchs'] && $ri['xnp']) $I8v4y5NCk0 |= ($ri['xchs']/$ri['xnp']*($ri['xnp']+1)>$this->sm_filesplit); if( $I8v4y5NCk0 ) { $we7CyMOB3DkQv++; $ri['xchs'] = $ri['xnp'] = 0; $this->lWQKrAn9z($ri['pf']); if($ri['filenum'] == 2) { if(!copy(eYgPj3ZHK0T12hAy . $ri['sitemap_file'].$this->S0BtLzEJVxMK_RX,  eYgPj3ZHK0T12hAy.($_xu = bZ3jbCz403O1HU(1,$ri['sitemap_file']).$this->S0BtLzEJVxMK_RX))) { $BkrUa2m_ZoFXT[] = eYgPj3ZHK0T12hAy.$_xu; } $ri['urls'][0] = $this->uurl_p . $_xu; } $AnCJPiRg3Tm0OL = (($ri['filenum']>1) ? bZ3jbCz403O1HU($ri['filenum'],$ri['sitemap_file']) :$ri['sitemap_file']) . $this->S0BtLzEJVxMK_RX; $ri['urls'][] = $this->uurl_p . $AnCJPiRg3Tm0OL; $ri['filenum']++; $ri['pf'] = $this->PmrP6CUAfUY1eXGv5a4['fopen'](eYgPj3ZHK0T12hAy.$AnCJPiRg3Tm0OL,'w'); if(!$ri['pf']) $BkrUa2m_ZoFXT[] = eYgPj3ZHK0T12hAy.$AnCJPiRg3Tm0OL; $this->ySNYPM9AgJwfmW($ri['pf'], $hwKdALVRpEm); } } return $l; } function RvGpMMUREL77($OjKgKXqLBpye8PNTf, $N6He2nKWL8cY, $hwKdALVRpEm) { $OjKgKXqLBpye8PNTf['TYPE'.$hwKdALVRpEm] = true; $ri = &$this->gzkKVwfuY95v65UABBM[$hwKdALVRpEm]; if($ri['pf']) { $_xu = $this->GkN2xe_LS5->l1acf4pof0Or6uMILSm($N6He2nKWL8cY, $OjKgKXqLBpye8PNTf); $ri['xchs'] += strlen($_xu); $ri['xn']++; $ri['xnp']++; $this->PmrP6CUAfUY1eXGv5a4['fwrite']($ri['pf'], $_xu); } }  function vablW4zqAwA() { foreach($this->gzkKVwfuY95v65UABBM as $hwKdALVRpEm=>$ri) { $this->lWQKrAn9z($ri['pf']); } } function T0gmFEXXIurPo() { foreach($this->iPQJB7_hQeUXBg7Q0 as $hwKdALVRpEm=>$Z59xLPPor0bYkq9kN2C) { $ri = &$this->gzkKVwfuY95v65UABBM[$hwKdALVRpEm]; if(count($ri['urls'])>1) { $xf = $this->G2zCcihGLQl($ri['urls']); array_unshift($ri['urls'],  $this->uurl_p.K8zTIUVnwPDUDC($ri['sitemap_file'], $xf, eYgPj3ZHK0T12hAy, ($this->CWUHIna3EKRbOn4D4cw['xs_compress']==1)) ); } $this->C79HmoYonnik($ri['sitemap_file']); } } function M80h6j1sEIWMK_($VXo6vuQzCR) { global $e_ksHZ4mvyyb643a5V; if(!isset($this->fMiTRrdjIW[$VXo6vuQzCR]) || !$this->fMiTRrdjIW[$VXo6vuQzCR]){ $this->fMiTRrdjIW[$VXo6vuQzCR] = $e_ksHZ4mvyyb643a5V->fetch($VXo6vuQzCR, 0,true, false, '', array('skipip' => true,'anytype'=>true)); K8zTIUVnwPDUDC('apicache.db',serialize($this->fMiTRrdjIW),eYgPj3ZHK0T12hAy,true); } return $this->fMiTRrdjIW[$VXo6vuQzCR]; } function uW2s6L508AR($KjGb5UkXhbELCFSf) { 
																											return $KjGb5UkXhbELCFSf;
																											}
																											function UE6za0ucuUsehYAvdt($urls_completed, &$wjX5uaTwf5NEglz1aoT)
																											{
																											global $N6He2nKWL8cY, $p6trNo4owqJtn, $GIeI0ZRU_, $sm_proc_list, $nZPP1mn5ss7dro, $TpMaRXMDxB2, $BkrUa2m_ZoFXT;
																											$dSeuGOAE_8dFCli = $this->CWUHIna3EKRbOn4D4cw['xs_chlog'];
																											$uJeraacVojMTx = cCqeu6KEd4LFk(dh6mwOEumX3JD,  'sitemap_xml_tpl.xml');
																											$B0tchSNt2Krkc = file_get_contents(dh6mwOEumX3JD.$uJeraacVojMTx);
																											preg_match('#^(.*)%URLS_LIST_FROM%(.*)%URLS_LIST_TO%(.*)$#is', $B0tchSNt2Krkc, $N6He2nKWL8cY);
																											$N6He2nKWL8cY[1] = str_replace('www.xml-sitemaps.com', 'www.xml-sitemaps.com ('. m6VWoP93i1XpwIg6.')', $N6He2nKWL8cY[1]);
																											$N6He2nKWL8cY[1] = str_replace('%GEN_URL%', $this->CWUHIna3EKRbOn4D4cw['gendom'], $N6He2nKWL8cY[1]);
																											$Kw78M62lrI5rS = preg_replace('#[^\\/]+?\.xml$#', '', $this->CWUHIna3EKRbOn4D4cw['xs_smurl']);
																											$N6He2nKWL8cY[1] = str_replace('%SM_BASE%', $Kw78M62lrI5rS, $N6He2nKWL8cY[1]);
																											if($this->CWUHIna3EKRbOn4D4cw['xs_disable_xsl'])
																											$N6He2nKWL8cY[1] = preg_replace('#<\?xml-stylesheet.*\?>#', '', $N6He2nKWL8cY[1]);
																											if($this->CWUHIna3EKRbOn4D4cw['xs_nobrand']){
																											$N6He2nKWL8cY[1] = str_replace('sitemap.xsl','sitemap_nb.xsl',$N6He2nKWL8cY[1]);
																											$N6He2nKWL8cY[1] = preg_replace('#<!-- created.*?>#','',$N6He2nKWL8cY[1]);
																											}
																											$YCJO6cqBN5 = implode('', file(dh6mwOEumX3JD.'sitemap_ror_tpl.xml'));
																											preg_match('#^(.*)%URLS_LIST_FROM%(.*)%URLS_LIST_TO%(.*)$#is', $YCJO6cqBN5, $p6trNo4owqJtn);
																											$jtjCCajYHG = implode('', file(dh6mwOEumX3JD.'sitemap_rss_tpl.xml'));
																											preg_match('#^(.*)%URLS_LIST_FROM%(.*)%URLS_LIST_TO%(.*)$#is', $jtjCCajYHG, $sLSH4saPS5ozppKN11M);
																											$a72fjXmL4zzgMw = implode('', file(dh6mwOEumX3JD.'sitemap_base_tpl.xml'));
																											preg_match('#^(.*)%URLS_LIST_FROM%(.*)%URLS_LIST_TO%(.*)$#is', $a72fjXmL4zzgMw, $GIeI0ZRU_);
																											$this->l4PwswmkZWYU3PEJDv = $this->CWUHIna3EKRbOn4D4cw['xs_sm_size']?$this->CWUHIna3EKRbOn4D4cw['xs_sm_size']:50000;
																											$this->sm_filesplit = $this->CWUHIna3EKRbOn4D4cw['xs_sm_filesize']?$this->CWUHIna3EKRbOn4D4cw['xs_sm_filesize']:10;
																											$this->sm_filesplit = max(intval($this->sm_filesplit*1024*1024),2000)-1000;
																											if(isset($this->CWUHIna3EKRbOn4D4cw['xs_webinfo']) && !$this->CWUHIna3EKRbOn4D4cw['xs_webinfo'])
																											unset($this->iPQJB7_hQeUXBg7Q0[0]);
																											if(!$this->CWUHIna3EKRbOn4D4cw['xs_imginfo'])
																											unset($this->iPQJB7_hQeUXBg7Q0[1]);
																											if(!$this->CWUHIna3EKRbOn4D4cw['xs_videoinfo'])
																											unset($this->iPQJB7_hQeUXBg7Q0[2]);
																											if(!$this->CWUHIna3EKRbOn4D4cw['xs_newsinfo'])
																											unset($this->iPQJB7_hQeUXBg7Q0[3]);
																											if(!$this->CWUHIna3EKRbOn4D4cw['xs_makemob'])
																											unset($this->iPQJB7_hQeUXBg7Q0[4]);
																											if(!$this->CWUHIna3EKRbOn4D4cw['xs_rssinfo'])
																											unset($this->iPQJB7_hQeUXBg7Q0[5]);
																											$ctime = date('Y-m-d H:i:s');
																											$T7niSDqu7jGl5 = 0;
																											global $sSxD4RNwg;
																											$tt = array('<','>');
																											foreach ($tt as $HQHtKgSdG )
																											$sSxD4RNwg[$HQHtKgSdG] = '&#'.ord($HQHtKgSdG).';';
																											for($i=0;$i<31;$i++)
																											$sSxD4RNwg[chr($i)] = '';
																											
																											$sSxD4RNwg[chr(0)] = $sSxD4RNwg[chr(10)] = $sSxD4RNwg[chr(13)] = '';
																											$sSxD4RNwg[' '] = '%20';
																											$pf = 0;
																											
																											$nfCUUd8A8V = intval($nZPP1mn5ss7dro['istart']);
																											$this->DUZrQcBoQ($nZPP1mn5ss7dro);
																											if($this->CWUHIna3EKRbOn4D4cw['xs_maketxt'])
																											{
																											$GIezOopb3dnINyvq = $this->PmrP6CUAfUY1eXGv5a4['fopen'](JFo3FYeR4sjMvbCM7lu.$this->S0BtLzEJVxMK_RX, $nfCUUd8A8V?'a':'w');
																											if(!$GIezOopb3dnINyvq)$BkrUa2m_ZoFXT[] = JFo3FYeR4sjMvbCM7lu.$this->S0BtLzEJVxMK_RX;
																											}
																											if($this->CWUHIna3EKRbOn4D4cw['xs_makeror'])
																											{
																											$VCRtt4yZsqZg43UHqx8 = gjEB17v5UJj1SxRZ(zyGoJM7SL, $nfCUUd8A8V?'a':'w');
																											$rc = str_replace('%INIT_URL%', $this->CWUHIna3EKRbOn4D4cw['xs_initurl'], $p6trNo4owqJtn[1]);
																											if($VCRtt4yZsqZg43UHqx8)
																											KR2szlrmlyOMknrLO($VCRtt4yZsqZg43UHqx8, $rc);
																											else
																											$BkrUa2m_ZoFXT[] = zyGoJM7SL;
																											}
																											if($this->CWUHIna3EKRbOn4D4cw['xs_rssinfo'])
																											{
																											$NMTwDm5v0LlL = $this->uurl_p . basename(HVKGdDolsi2eMB_mMuD);
																											$frD4esuVy_GQ67UKJ = HVKGdDolsi2eMB_mMuD;
																											$I9twBTcW88N = gjEB17v5UJj1SxRZ($frD4esuVy_GQ67UKJ, $nfCUUd8A8V?'a':'w');
																											$rc = str_replace('%INIT_URL%', $this->CWUHIna3EKRbOn4D4cw['xs_initurl'], $sLSH4saPS5ozppKN11M[1]);
																											$rc = str_replace('%FEED_TITLE%', $this->CWUHIna3EKRbOn4D4cw['xs_rsstitle'], $rc);
																											$rc = str_replace('%BUILD_DATE%', gmdate('D, d M Y H:i:s +0000'), $rc);
																											$rc = str_replace('%SELF_URL%', $NMTwDm5v0LlL, $rc);
																											if($I9twBTcW88N)
																											KR2szlrmlyOMknrLO($I9twBTcW88N, $rc);
																											else
																											$BkrUa2m_ZoFXT[] = $frD4esuVy_GQ67UKJ;
																											}
																											if($sm_proc_list)
																											foreach($sm_proc_list as $k=>$mFquo4eYj0vbgPjtk)
																											$sm_proc_list[$k]->qNh0L3Iqq4xOGQ($this->CWUHIna3EKRbOn4D4cw, $this->PmrP6CUAfUY1eXGv5a4, $this->GkN2xe_LS5);
																											if($this->CWUHIna3EKRbOn4D4cw['xs_write_delay'])
																											list($GRmeg_68ZvfdvyJw8v, $LVq1jMe0YE1bYN) = explode('|',$this->CWUHIna3EKRbOn4D4cw['xs_write_delay']);
																											for($i=$xn=$nfCUUd8A8V;$i<count($urls_completed);$i++,$xn++)
																											{   
																											
																											
																											
																											if($i%100 == 0) {
																											u3Aj3kpP8f7NX();
																											duBMhqfH7kGKH(" / $i / ".(time()-$_tm));
																											$_tm=time();
																											}
																											paeh3sXfU(array(
																											'cmd'=> 'info',
																											'id' => 'percprog',
																											'text'=> number_format($i*100/count($urls_completed),0).'%'
																											));
																											$we7CyMOB3DkQv = $this->sG53YQ0A72();
																											if($we7CyMOB3DkQv && ($i != $nfCUUd8A8V))
																											{
																											K8zTIUVnwPDUDC($TpMaRXMDxB2,mKNc3ZuibHw8(array('istart'=>$i,'rinfo'=>$this->gzkKVwfuY95v65UABBM)));
																											}
																											if($this->CWUHIna3EKRbOn4D4cw['xs_memsave'])
																											{
																											$cu = hFdFC9FutftfbQcPCF($urls_completed[$i]);
																											}else
																											$cu = $urls_completed[$i];
																											if(!is_array($cu)) $cu = @unserialize($cu);
																											$l = $this->XvU49oK_Vd($cu['link']);
																											$cu['link'] = $l;
																											$t = $this->NgBWwllTme($cu['t'], true);
																											$d = $this->NgBWwllTme($cu['d'] ? $cu['d'] : $cu['t'], true);
																											$NqLd4rkoF6vij = '';
																											if($cu['clm'])
																											$NqLd4rkoF6vij = $cu['clm'];
																											else
																											switch($this->CWUHIna3EKRbOn4D4cw['xs_lastmod']){
																											case 1:$NqLd4rkoF6vij = $cu['lm']?$cu['lm']:$ctime;break;
																											case 2:$NqLd4rkoF6vij = $ctime;break;
																											case 3:$NqLd4rkoF6vij = $this->CWUHIna3EKRbOn4D4cw['xs_lastmodtime'];break;
																											}
																											$Ho3EyQEy5sso9sRWd = $RZyWFyTrlgQw4x6gpG = false;
																											if($cu['p'])
																											$p = $cu['p'];
																											else
																											{
																											$p = $this->CWUHIna3EKRbOn4D4cw['xs_priority'];
																											if($this->CWUHIna3EKRbOn4D4cw['xs_autopriority'])
																											{
																											$p = $p*pow($this->CWUHIna3EKRbOn4D4cw['xs_descpriority']?$this->CWUHIna3EKRbOn4D4cw['xs_descpriority']:0.8,$cu['o']);
																											if($this->RYsvKg3BLnSvTb)
																											{
																											$Ho3EyQEy5sso9sRWd = true;
																											$RZyWFyTrlgQw4x6gpG = ($this->RYsvKg3BLnSvTb&&!isset($this->RYsvKg3BLnSvTb[$cu['link']]))||$this->xiXlFjjdb5NC_o2wn[$cu['link']];
																											if($RZyWFyTrlgQw4x6gpG)
																											$p=0.95;
																											}
																											$p = max(0.0001,min($p,1.0));
																											$p = @number_format($p, 4);
																											}
																											}
																											if($NqLd4rkoF6vij){
																											$NqLd4rkoF6vij = strtotime($NqLd4rkoF6vij);
																											$NqLd4rkoF6vij = gmdate('Y-m-d\TH:i:s+00:00',$NqLd4rkoF6vij);
																											}
																											$f = $cu['f']?$cu['f']:$this->CWUHIna3EKRbOn4D4cw['xs_freq'];
																											$OjKgKXqLBpye8PNTf = array(
																											'URL'=>$l,
																											'TITLE'=>$t,
																											'DESC'=>($d),
																											'PERIOD'=>$f,
																											'LASTMOD'=>$NqLd4rkoF6vij,
																											'ORDER'=>$cu['o'],
																											'PRIORITY'=>$p
																											);
																											if($this->CWUHIna3EKRbOn4D4cw['xs_makemob'])
																											{
																											if(!$this->CWUHIna3EKRbOn4D4cw['xs_mobileincmask'] ||
																											preg_match('#'.str_replace(' ', '|', preg_quote($this->CWUHIna3EKRbOn4D4cw['xs_mobileincmask'],'#')).'#',$OjKgKXqLBpye8PNTf['URL']))
																											$this->RvGpMMUREL77(array_merge($OjKgKXqLBpye8PNTf, array('ismob'=>true)), $N6He2nKWL8cY[2], 4);
																											}
																											
																											
																											$this->RvGpMMUREL77($OjKgKXqLBpye8PNTf, $N6He2nKWL8cY[2], 0);
																											
																											
																											if($this->CWUHIna3EKRbOn4D4cw['xs_maketxt'] && $GIezOopb3dnINyvq)
																											$this->PmrP6CUAfUY1eXGv5a4['fwrite']($GIezOopb3dnINyvq, $cu['link']."\n");
																											if($sm_proc_list)
																											foreach($sm_proc_list as $mFquo4eYj0vbgPjtk)
																											$mFquo4eYj0vbgPjtk->cELF4rAmlQsI9AR($OjKgKXqLBpye8PNTf);
																											if($this->CWUHIna3EKRbOn4D4cw['xs_makeror'] && $VCRtt4yZsqZg43UHqx8){
																											if($this->CWUHIna3EKRbOn4D4cw['xs_ror_unique']){
																											$t=$OjKgKXqLBpye8PNTf['TITLE'];
																											$d=$OjKgKXqLBpye8PNTf['DESC'];
																											while($OqtGTVqoDwX=$ai[md5('t'.$t)]++){
																											$t=$OjKgKXqLBpye8PNTf['TITLE'].' '.$OqtGTVqoDwX;
																											}
																											while($OqtGTVqoDwX=$ai[md5('d'.$d)]++){
																											$d=$OjKgKXqLBpye8PNTf['DESC'].' '.$OqtGTVqoDwX;
																											}
																											$OjKgKXqLBpye8PNTf['TITLE']=$t;
																											$OjKgKXqLBpye8PNTf['DESC']=$d;
																											}
																											KR2szlrmlyOMknrLO($VCRtt4yZsqZg43UHqx8, $this->GkN2xe_LS5->l1acf4pof0Or6uMILSm($p6trNo4owqJtn[2],$OjKgKXqLBpye8PNTf));
																											}
																											if($dSeuGOAE_8dFCli) {
																											if(!isset($this->RYsvKg3BLnSvTb[$cu['link']]) && 
																											count($this->ANwqywRuJwV)<$this->tdIdoNkq99Ug1RX)
																											$this->ANwqywRuJwV[$cu['link']]++;
																											}
																											unset($this->RYsvKg3BLnSvTb[$cu['link']]);
																											}
																											$this->vablW4zqAwA();
																											if($this->CWUHIna3EKRbOn4D4cw['xs_maketxt'])
																											{
																											$this->PmrP6CUAfUY1eXGv5a4['fclose']($GIezOopb3dnINyvq);
																											@chmod(JFo3FYeR4sjMvbCM7lu.$this->S0BtLzEJVxMK_RX, 0666);
																											}
																											if($this->CWUHIna3EKRbOn4D4cw['xs_makeror'])
																											{
																											if($VCRtt4yZsqZg43UHqx8)
																											KR2szlrmlyOMknrLO($VCRtt4yZsqZg43UHqx8, $p6trNo4owqJtn[3]);
																											fclose($VCRtt4yZsqZg43UHqx8);
																											}
																											if($this->CWUHIna3EKRbOn4D4cw['xs_rssinfo'])
																											{
																											if($I9twBTcW88N)
																											KR2szlrmlyOMknrLO($I9twBTcW88N, $sLSH4saPS5ozppKN11M[3]);
																											fclose($I9twBTcW88N);
																											$this->C79HmoYonnik($this->CWUHIna3EKRbOn4D4cw['xs_rssfilename']);
																											}
																											if($sm_proc_list)
																											foreach($sm_proc_list as $mFquo4eYj0vbgPjtk)
																											$mFquo4eYj0vbgPjtk->UVtSAwQYOsEzzCeKfY();
																											K8zTIUVnwPDUDC($TpMaRXMDxB2,mKNc3ZuibHw8(array('done'=>true)));
																											paeh3sXfU(array('cmd'=> 'info','id' => 'percprog',''));
																											}
																											function mv3YztXmDFiT($OjlcfkGlu)
																											{
																											for($i=0;file_exists($sf=eYgPj3ZHK0T12hAy.bZ3jbCz403O1HU($i,$OjlcfkGlu).$this->S0BtLzEJVxMK_RX);$i++){
																											Hqm42kdaBr($sf);
																											}
																											}
																											function p3RMgGPJdKj($Mlhn1ezi1gustxzh, $spK9ovsA7pQ)
																											{
																											global $BkrUa2m_ZoFXT;
																											if(!@copy($Mlhn1ezi1gustxzh,$spK9ovsA7pQ))
																											{
																											if($this->CWUHIna3EKRbOn4D4cw['xs_filewmove'] && file_exists($spK9ovsA7pQ) ){
																											Hqm42kdaBr($spK9ovsA7pQ);
																											}
																											if($cn = @gjEB17v5UJj1SxRZ($spK9ovsA7pQ, 'w')){
																											@KR2szlrmlyOMknrLO($cn, file_get_contents($Mlhn1ezi1gustxzh));
																											@fclose($cn);
																											}else
																											if(file_exists($Mlhn1ezi1gustxzh))
																											{
																											$BkrUa2m_ZoFXT[] = $spK9ovsA7pQ;
																											}
																											}
																											
																											@chmod($Mlhn1ezi1gustxzh, 0666);
																											}
																											function C79HmoYonnik($OjlcfkGlu)
																											{
																											$gp = ($this->CWUHIna3EKRbOn4D4cw['xs_compress']==2) ? '.gz' : '';
																											for($i=0;file_exists(eYgPj3ZHK0T12hAy.($sf=bZ3jbCz403O1HU($i,$OjlcfkGlu).$this->S0BtLzEJVxMK_RX));$i++){
																											$this->p3RMgGPJdKj(eYgPj3ZHK0T12hAy.$sf,$this->furl_p.$sf);
																											if($gp) {
																											$cn = file_get_contents(eYgPj3ZHK0T12hAy.$sf);
																											if(strstr($cn, '<sitemapindex'))
																											$cn = str_replace('.xml</loc>', '.xml.gz</loc>', $cn);
																											K8zTIUVnwPDUDC(eYgPj3ZHK0T12hAy.$sf, $cn, '', true);
																											$this->p3RMgGPJdKj(eYgPj3ZHK0T12hAy.$sf.$gp,$this->furl_p.$sf.$gp);
																											}
																											}
																											}
																											function J0guAv8GXYn6AN38fxM($OjlcfkGlu, $C629L4qvNEC = -1, $o6kEbVj7zA6PmJj2 = '', $hwKdALVRpEm = 0)
																											{
																											$cn = '';
																											$_fold = (strstr($OjlcfkGlu,'/')||strstr($OjlcfkGlu,'\\')) ? '' : eYgPj3ZHK0T12hAy ;
																											$_fapp = ($hwKdALVRpEm ?  '' : $this->S0BtLzEJVxMK_RX);
																											for($i=0;file_exists($sf=$_fold.bZ3jbCz403O1HU($i,$OjlcfkGlu).$_fapp);$i++)
																											{
																											
																											if(@filesize($sf)<100000000)// 100MB max
																											$cn .= $_fapp?implode('',gzfile($sf)):PCiMWHKGB5lwUwCci($sf);
																											if($i>200)break;
																											}
																											$RzU0hH8mmhGp = array(
																											array('loc', 'news:publication_date', 'priority'),
																											array('link', 'pubDate', ''),
																											);
																											$mt = $RzU0hH8mmhGp[$hwKdALVRpEm];
																											preg_match_all('#<'.$mt[0].'>(.*?)</'.$mt[0].'>'.
																											(($C629L4qvNEC>=0) ? '.*?<'.$mt[1].'>(.*?)</'.$mt[1].'>' : '').
																											(($o6kEbVj7zA6PmJj2 && $mt[2])? '.*?<'.$mt[2].'>(.*?)</'.$mt[2].'>' : '').
																											'#is',$cn,$um);
																											$al = array();
																											foreach($um[1] as $i=>$l)
																											{             
																											if($o6kEbVj7zA6PmJj2){
																											if(!strstr($l, $o6kEbVj7zA6PmJj2))
																											continue;
																											$l = substr($l, strlen($o6kEbVj7zA6PmJj2));
																											}
																											if(!$l)continue;
																											if($C629L4qvNEC<=0) {
																											if($um[2][$i])
																											$al[$l] = $um[2][$i];
																											else
																											$al[$l]++;
																											}
																											else
																											if(time()-strtotime($um[2][$i])<=$C629L4qvNEC*24*3600)
																											$al[$l] = $um[2][$i];
																											}
																											return $al;
																											}
																											}
																											global $K3D58WhS_Kbefnm;
																											$K3D58WhS_Kbefnm = new XMLCreator();
																											}
																											



































































































