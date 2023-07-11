<?php

namespace App\Http\Controllers;

use App\CategoriaServizi;
use App\Hotel;
use App\Http\Controllers\Controller;
use App\ImmagineGalleryLingua;
use App\InfoBenessere;
use App\InfoPiscina;
use App\MailSchedaRead;
use App\ScadenzaVot;
use App\ScadenzaVtt;
use App\SlotVetrina;
use App\Utility;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;

class TaskController extends Controller
{

    const MAIL_TO = "ilaria@info-alberghi.com";

    public function __construct()
    {
        // Temporarily increase memory limit to 512MB
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 0);
    }

    private function _getDataFormUrl(&$da, &$a)
    {

        ///////////////////////////////////////////////////////////////////
        // se non passo le date trovo le mail di oggi dalle 0 alle 24 !! //
        ///////////////////////////////////////////////////////////////////
        if ($da == "") {
            $da = date('Y-m-d') . ' 00:00:00';
        } else {
            $gg = substr($da, 0, 2);
            $mm = substr($da, 2, 2);
            $aa = substr($da, -4);

            $da = "$aa-$mm-$gg 00:00:00";

        }

        if ($a == "") {
            $a = date('Y-m-d') . ' 23:59:59';
        } else {
            $gg = substr($a, 0, 2);
            $mm = substr($a, 2, 2);
            $aa = substr($a, -4);

            $a = "$aa-$mm-$gg 23:59:59";

        }

    }

    public function getMailSchedaDaA($hotel_id = 0, $da = "", $a = "")
    {

        $this->_getDataFormUrl($da, $a);

        $fields = array('hotel_id', 'tipologia', 'nome', 'arrivo', 'partenza', 'date_flessibili', 'adulti', 'bambini', 'camere', 'eta_bambini', 'trattamento', 'email', 'telefono', 'richiesta', 'data_invio', 'IP', 'created_at', 'referer');

        $mail = DB::table('tblMailScheda')
            ->select(DB::raw(implode(',', $fields)))
            ->where('hotel_id', $hotel_id)
            ->whereRaw("data_invio between '$da' and '$a'")
            ->get();

        //dd($mail);

        if (count($mail)) {
            $table = [];

            foreach ($mail as $m) {
                unset($arr);
                foreach ($fields as $field) {
                    $arr[] = $m->$field;
                }
                $table[] = $arr;
            }

            //dd($table);
            $filename = storage_path("mail_scheda.csv");

            $handle = fopen($filename, 'w+');

            fputcsv($handle, $fields);

            foreach ($table as $row) {
                fputcsv($handle, $row);
            }

            fclose($handle);

            $headers = array(
                'Content-Type' => 'text/csv',
            );

            return response()->download($filename, "mail_scheda_" . $hotel_id . "_" . str_replace(' ', '-', $da) . "_" . str_replace(' ', '-', $a) . ".csv", $headers);
        } else {
            echo "<br><br>NESSUNA MAIL NEL PERIDOO SELEZIONATO";
        }

    }

    public function getMailMultiplaDaA($hotel_id = 0, $da = "", $a = "")
    {

        $this->_getDataFormUrl($da, $a);

        $fields = array('tblMailMultiple.id', 'tblMailMultiple.tipologia', 'tblMailMultiple.nome', 'tblMailMultiple.arrivo', 'tblMailMultiple.partenza', 'tblMailMultiple.date_flessibili', 'tblMailMultiple.trattamento', 'tblMailMultiple.adulti', 'tblMailMultiple.bambini', 'tblMailMultiple.camere', 'tblMailMultiple.eta_bambini', 'tblMailMultiple.email', 'tblMailMultiple.richiesta', 'tblMailMultiple.data_invio', 'tblMailMultiple.IP', 'tblMailMultiple.referer');

        $mail = DB::table('tblHotelMailMultiple')
            ->join('tblMailMultiple', 'tblHotelMailMultiple.mailMultipla_id', '=', 'tblMailMultiple.id')
            ->select(DB::raw(implode(',', $fields)))
            ->where('tblHotelMailMultiple.hotel_id', $hotel_id)
            ->whereRaw("data_invio between '$da' and '$a'")
            ->get();

        if (count($mail)) {
            foreach ($fields as $value) {
                $col_fields[] = substr($value, strrpos($value, '.') + 1);
            }

            $table = [];

            foreach ($mail as $m) {
                unset($arr);
                foreach ($col_fields as $field) {
                    $arr[] = $m->$field;
                }
                $table[] = $arr;
            }

            //dd($table);
            $filename = storage_path("mail_scheda.csv");

            $handle = fopen($filename, 'w+');

            $col_fields = [];

            fputcsv($handle, $col_fields);

            foreach ($table as $row) {
                fputcsv($handle, $row);
            }

            fclose($handle);

            $headers = array(
                'Content-Type' => 'text/csv',
            );

            return response()->download($filename, 'mail_multipla' . $hotel_id . '.csv', $headers);
        } else {
            echo "<br><br>NESSUNA MAIL NEL PERIDOO SELEZIONATO";
        }

    }

    /**
     * [getMailClienti crea un csv di tutte le mail dei clienti attivi con greenbooking]
     * @return [type] [description]
     */
    public function getMailClientiGreen()
    {
        $mail_green = DB::table('tblHotel')
            ->where('attivo', 1)
            ->where('green_booking', 1)
            ->select('email', "email_secondaria")
            ->get();

        foreach ($mail_green as $value) {
            if ($value->email_secondaria != "") {
                $table[] = $value->email_secondaria;
            } else {
                $table[] = $value->email;
            }

        }

        $filename = storage_path("mail_green.csv");

        $handle = fopen($filename, 'w+');

        fputcsv($handle, array('email'));

        foreach ($table as $row) {
            fputcsv($handle, array($row));
        }

        fclose($handle);

        $headers = array(
            'Content-Type' => 'text/csv',
        );

        return response()->download($filename, 'mail_green.csv', $headers);

    }

    /**
     * [getMailUtenti crea un csv di tutte le mail degli utenti (NON gli albergatori, ma i "milanesi" che srivono sui vari form) ;
     * utilizza le tabelle tblMailMultipla, tblMailScheda]
     * @return [type] [description]
     */
    public function getMailUtenti()
    {

        $table = [];

        $mail_scheda = DB::table('tblMailScheda')->select('email')->distinct()->get();
        $mail_multipla = DB::table('tblMailMultiple')->select('email')->distinct()->get();
        

        $total = array_merge($mail_scheda, $mail_multipla);

        foreach ($total as $value) {
            $t[] = $value->email;
        }

        $table = array_unique($t);

        //dd($table);

        $filename = storage_path("mail_utenti.csv");

        $handle = fopen($filename, 'w+');

        fputcsv($handle, array('email'));

        foreach ($table as $row) {
            fputcsv($handle, array($row));
        }

        fclose($handle);

        $headers = array(
            'Content-Type' => 'text/csv',
        );

        return response()->download($filename, 'mail_utenti.csv', $headers);

    }

    /**
     * [getMailClienti crea un csv di tutte le mail dei clienti attivi]
     * @return [type] [description]
     */
    public function getMailClienti()
    {

        $mail_clienti = DB::table('tblHotel')
            ->where('attivo', 1)
            ->select('email', 'email_secondaria')
            ->get();

        foreach ($mail_clienti as $value) {
            if ($value->email_secondaria != "") {
                $table[] = $value->email_secondaria;
            } else {
                $table[] = $value->email;
            }

        }

        $filename = storage_path("mail_clienti.csv");
        $handle = fopen($filename, 'w+');
        fputcsv($handle, array('email'));

        foreach ($table as $row) {
            fputcsv($handle, array($row));
        }

        fclose($handle);

        $headers = array(
            'Content-Type' => 'text/csv',
        );

        return response()->download($filename, 'mail_clienti.csv', $headers);

    }

    /**
     * [getInfoPiscinaHotels trovo gli hotel con la relazione infoPiscina]
     * @return [type] [description]
     */
    public function getInfoPiscinaHotels()
    {
        $hotels = Hotel::whereHas('infoPiscina', function ($query) {
            $query->where('sup', '>', '0');
        })
            ->pluck('nome', 'id')->toArray();

        //dd($hotels);

        foreach ($hotels as $id => $hotel) {
            $table[] = array($id, $hotel);
        }

        $filename = storage_path("infoPiscina.csv");

        $handle = fopen($filename, 'w+');

        fputcsv($handle, array('id', 'nome'));

        foreach ($table as $row) {
            fputcsv($handle, $row);
        }

        fclose($handle);

        $headers = array(
            'Content-Type' => 'text/csv',
        );

        return response()->download($filename, 'infoPiscina.csv', $headers);

    }

    /**
     * [getMailClienti crea un csv di tutte le keyword cercate]
     * @return [type] [description]
     */
    public function getKeywordRicerca()
    {
        $keyword = DB::table('tblKeywordRicerca')->select('keyword', 'created_at')->orderBy('created_at', 'desc')->get();

        foreach ($keyword as $value) {
            $table[] = array($value->keyword, $value->created_at);
        }

        $filename = storage_path("keyword.csv");

        $handle = fopen($filename, 'w+');

        fputcsv($handle, array('email'));

        foreach ($table as $row) {
            fputcsv($handle, $row);
        }

        fclose($handle);

        $headers = array(
            'Content-Type' => 'text/csv',
        );

        return response()->download($filename, 'keyword.csv', $headers);

    }

    public function prova()
    {
        $hotel_id = 92;
        $crm_response = Utility::getCommercialeFromCrm($hotel_id);
        dd($crm_response);

    }

    public function photoTagTranslate()
    {
        echo "inizio<br>";

        flush();
        ob_flush();

        $tags_it = ImmagineGalleryLingua::where('lang_id', 'it')->get();

        $count = 1;
        $count_gia_tradotti = 1;
        $tot = $tags_it->count();

        foreach ($tags_it as $tag) {
            if ($tag->caption != "") {
                foreach (Utility::linguePossibili() as $lingua) {
                    if ($lingua != 'it') {

                        $immagineGalleryLang = ImmagineGalleryLingua::firstOrNew(array('master_id' => $tag->master_id, 'lang_id' => $lingua));

                        if (!isset($immagineGalleryLang->caption) || $immagineGalleryLang->caption == '') {
                            $immagineGalleryLang->caption = Utility::translate($tag->caption, $lingua);
                            $immagineGalleryLang->moderato = false;

                            $immagineGalleryLang->save();

                            echo "Tradotti $count tag <br>";
                            flush();
                            ob_flush();
                            $count += 1;
                        } else {
                            echo "Già Tradotti $count_gia_tradotti<br>";
                            flush();
                            ob_flush();
                            $count_gia_tradotti += 1;
                        }

                    }
                }
            }
        }
        echo "FINE !";

        flush();
        ob_flush();

    }

    public function setParcheggio()
    {
        ////////////////////////////////////////////////////////////////////////////
        // "parcheggio scoperto" nel gruppo "Servizi in Hotel" id_servizio = 223     //
        ////////////////////////////////////////////////////////////////////////////
        //$hotel_ids = DB::table('tblHotel')->where('attivo', 1)->select('id')->get();

        $hotels = Hotel::with([
            'servizi' => function ($query) {
                $query
                    ->where('servizio_id', 223);
            },
        ])
            ->attivo()
            ->get();

        $con_parking = 0;
        foreach ($hotels as $count => $hotel) {
            // dovrei inserire 1043 righe nella tabella tblHotelServizi
            if (!$hotel->servizi->count()) {
                $hotel->servizi()->attach(223);
            }
        }

    }

    // ho un array di hotel_id dei clienti che avevano il parcheggio tra i servizi gratuiti
    public function setParcheggioGratis()
    {
        $hotel_ids_pg = array(
            array( // row #0
                1455,
            ),
            array( // row #1
                467,
            ),
            array( // row #2
                1439,
            ),
            array( // row #3
                1457,
            ),
            array( // row #4
                1442,
            ),
            array( // row #5
                1440,
            ),
            array( // row #6
                1437,
            ),
            array( // row #7
                908,
            ),
            array( // row #8
                1441,
            ),
            array( // row #9
                1436,
            ),
            array( // row #10
                403,
            ),
            array( // row #11
                240,
            ),
            array( // row #12
                527,
            ),
            array( // row #13
                122,
            ),
            array( // row #14
                334,
            ),
            array( // row #15
                284,
            ),
            array( // row #16
                188,
            ),
            array( // row #17
                392,
            ),
            array( // row #18
                707,
            ),
            array( // row #19
                654,
            ),
            array( // row #20
                846,
            ),
            array( // row #21
                1318,
            ),
            array( // row #22
                851,
            ),
            array( // row #23
                248,
            ),
            array( // row #24
                971,
            ),
            array( // row #25
                812,
            ),
            array( // row #26
                914,
            ),
            array( // row #27
                442,
            ),
            array( // row #28
                90,
            ),
            array( // row #29
                149,
            ),
            array( // row #30
                246,
            ),
            array( // row #31
                593,
            ),
            array( // row #32
                127,
            ),
            array( // row #33
                977,
            ),
            array( // row #34
                1208,
            ),
            array( // row #35
                114,
            ),
            array( // row #36
                803,
            ),
            array( // row #37
                1098,
            ),
            array( // row #38
                1124,
            ),
            array( // row #39
                504,
            ),
            array( // row #40
                408,
            ),
            array( // row #41
                205,
            ),
            array( // row #42
                613,
            ),
            array( // row #43
                970,
            ),
            array( // row #44
                223,
            ),
            array( // row #45
                239,
            ),
            array( // row #46
                816,
            ),
            array( // row #47
                771,
            ),
            array( // row #48
                36,
            ),
            array( // row #49
                143,
            ),
            array( // row #50
                201,
            ),
            array( // row #51
                718,
            ),
            array( // row #52
                1,
            ),
            array( // row #53
                1108,
            ),
            array( // row #54
                529,
            ),
            array( // row #55
                1323,
            ),
            array( // row #56
                1016,
            ),
            array( // row #57
                309,
            ),
            array( // row #58
                878,
            ),
            array( // row #59
                1361,
            ),
            array( // row #60
                830,
            ),
            array( // row #61
                441,
            ),
            array( // row #62
                737,
            ),
            array( // row #63
                890,
            ),
            array( // row #64
                506,
            ),
            array( // row #65
                361,
            ),
            array( // row #66
                514,
            ),
            array( // row #67
                157,
            ),
            array( // row #68
                537,
            ),
            array( // row #69
                1322,
            ),
            array( // row #70
                1039,
            ),
            array( // row #71
                275,
            ),
            array( // row #72
                913,
            ),
            array( // row #73
                1275,
            ),
            array( // row #74
                200,
            ),
            array( // row #75
                147,
            ),
            array( // row #76
                252,
            ),
            array( // row #77
                1160,
            ),
            array( // row #78
                540,
            ),
            array( // row #79
                450,
            ),
            array( // row #80
                185,
            ),
            array( // row #81
                923,
            ),
            array( // row #82
                428,
            ),
            array( // row #83
                902,
            ),
            array( // row #84
                102,
            ),
            array( // row #85
                547,
            ),
            array( // row #86
                530,
            ),
            array( // row #87
                1459,
            ),
            array( // row #88
                364,
            ),
            array( // row #89
                211,
            ),
            array( // row #90
                1238,
            ),
            array( // row #91
                1364,
            ),
            array( // row #92
                1460,
            ),
            array( // row #93
                569,
            ),
            array( // row #94
                987,
            ),
            array( // row #95
                983,
            ),
            array( // row #96
                813,
            ),
            array( // row #97
                1095,
            ),
            array( // row #98
                720,
            ),
            array( // row #99
                196,
            ),
            array( // row #100
                689,
            ),
            array( // row #101
                314,
            ),
            array( // row #102
                562,
            ),
            array( // row #103
                251,
            ),
            array( // row #104
                555,
            ),
            array( // row #105
                451,
            ),
            array( // row #106
                227,
            ),
            array( // row #107
                255,
            ),
            array( // row #108
                574,
            ),
            array( // row #109
                631,
            ),
            array( // row #110
                904,
            ),
            array( // row #111
                767,
            ),
            array( // row #112
                286,
            ),
            array( // row #113
                1167,
            ),
            array( // row #114
                419,
            ),
            array( // row #115
                148,
            ),
            array( // row #116
                730,
            ),
            array( // row #117
                383,
            ),
            array( // row #118
                225,
            ),
            array( // row #119
                244,
            ),
            array( // row #120
                229,
            ),
            array( // row #121
                1045,
            ),
            array( // row #122
                429,
            ),
            array( // row #123
                63,
            ),
            array( // row #124
                1363,
            ),
            array( // row #125
                825,
            ),
            array( // row #126
                1462,
            ),
            array( // row #127
                17,
            ),
            array( // row #128
                1224,
            ),
            array( // row #129
                791,
            ),
            array( // row #130
                86,
            ),
            array( // row #131
                1325,
            ),
            array( // row #132
                1329,
            ),
            array( // row #133
                1111,
            ),
            array( // row #134
                835,
            ),
            array( // row #135
                1358,
            ),
            array( // row #136
                1299,
            ),
            array( // row #137
                1110,
            ),
            array( // row #138
                994,
            ),
            array( // row #139
                1217,
            ),
            array( // row #140
                1324,
            ),
            array( // row #141
                853,
            ),
            array( // row #142
                1451,
            ),
            array( // row #143
                848,
            ),
            array( // row #144
                1449,
            ),
            array( // row #145
                1448,
            ),
            array( // row #146
                382,
            ),
            array( // row #147
                979,
            ),
            array( // row #148
                1216,
            ),
            array( // row #149
                817,
            ),
            array( // row #150
                144,
            ),
            array( // row #151
                471,
            ),
            array( // row #152
                622,
            ),
            array( // row #153
                728,
            ),
            array( // row #154
                1445,
            ),
            array( // row #155
                625,
            ),
            array( // row #156
                87,
            ),
            array( // row #157
                414,
            ),
            array( // row #158
                415,
            ),
            array( // row #159
                827,
            ),
            array( // row #160
                1114,
            ),
            array( // row #161
                838,
            ),
            array( // row #162
                1122,
            ),
            array( // row #163
                187,
            ),
            array( // row #164
                1007,
            ),
            array( // row #165
                46,
            ),
            array( // row #166
                461,
            ),
            array( // row #167
                615,
            ),
            array( // row #168
                847,
            ),
            array( // row #169
                991,
            ),
            array( // row #170
                815,
            ),
            array( // row #171
                430,
            ),
            array( // row #172
                735,
            ),
            array( // row #173
                423,
            ),
            array( // row #174
                752,
            ),
            array( // row #175
                440,
            ),
            array( // row #176
                487,
            ),
            array( // row #177
                401,
            ),
            array( // row #178
                882,
            ),
            array( // row #179
                1223,
            ),
            array( // row #180
                420,
            ),
            array( // row #181
                206,
            ),
            array( // row #182
                1345,
            ),
            array( // row #183
                447,
            ),
            array( // row #184
                215,
            ),
            array( // row #185
                840,
            ),
            array( // row #186
                213,
            ),
            array( // row #187
                1438,
            ),
            array( // row #188
                243,
            ),
            array( // row #189
                249,
            ),
            array( // row #190
                293,
            ),
            array( // row #191
                974,
            ),
            array( // row #192
                378,
            ),
            array( // row #193
                630,
            ),
            array( // row #194
                254,
            ),
            array( // row #195
                1143,
            ),
            array( // row #196
                1043,
            ),
            array( // row #197
                1006,
            ),
            array( // row #198
                843,
            ),
            array( // row #199
                1463,
            ),
            array( // row #200
                746,
            ),
            array( // row #201
                1029,
            ),
            array( // row #202
                1155,
            ),
            array( // row #203
                833,
            ),
            array( // row #204
                299,
            ),
            array( // row #205
                868,
            ),
            array( // row #206
                1066,
            ),
            array( // row #207
                795,
            ),
            array( // row #208
                668,
            ),
            array( // row #209
                1172,
            ),
            array( // row #210
                1166,
            ),
            array( // row #211
                1464,
            ),
            array( // row #212
                98,
            ),
            array( // row #213
                1376,
            ),
            array( // row #214
                1465,
            ),
            array( // row #215
                1467,
            ),
            array( // row #216
                1468,
            ),
            array( // row #217
                478,
            ),
            array( // row #218
                1144,
            ),
            array( // row #219
                1129,
            ),
            array( // row #220
                1133,
            ),
            array( // row #221
                1161,
            ),
            array( // row #222
                842,
            ),
            array( // row #223
                723,
            ),
            array( // row #224
                298,
            ),
            array( // row #225
                965,
            ),
            array( // row #226
                176,
            ),
            array( // row #227
                559,
            ),
            array( // row #228
                906,
            ),
            array( // row #229
                1059,
            ),
            array( // row #230
                210,
            ),
            array( // row #231
                1435,
            ),
            array( // row #232
                575,
            ),
            array( // row #233
                301,
            ),
            array( // row #234
                7,
            ),
            array( // row #235
                1469,
            ),
            array( // row #236
                1470,
            ),
            array( // row #237
                1261,
            ),
            array( // row #238
                289,
            ),
            array( // row #239
                637,
            ),
            array( // row #240
                1071,
            ),
            array( // row #241
                686,
            ),
            array( // row #242
                1070,
            ),
            array( // row #243
                1051,
            ),
            array( // row #244
                1052,
            ),
            array( // row #245
                1050,
            ),
            array( // row #246
                1053,
            ),
            array( // row #247
                1072,
            ),
            array( // row #248
                1040,
            ),
            array( // row #249
                1036,
            ),
            array( // row #250
                687,
            ),
            array( // row #251
                1012,
            ),
            array( // row #252
                1472,
            ),
            array( // row #253
                1473,
            ),
            array( // row #254
                1474,
            ),
            array( // row #255
                1475,
            ),
            array( // row #256
                1476,
            ),
            array( // row #257
                1330,
            ),
            array( // row #258
                1336,
            ),
            array( // row #259
                224,
            ),
            array( // row #260
                434,
            ),
            array( // row #261
                425,
            ),
            array( // row #262
                995,
            ),
            array( // row #263
                642,
            ),
            array( // row #264
                584,
            ),
            array( // row #265
                831,
            ),
            array( // row #266
                53,
            ),
            array( // row #267
                1348,
            ),
            array( // row #268
                18,
            ),
            array( // row #269
                712,
            ),
            array( // row #270
                156,
            ),
            array( // row #271
                818,
            ),
            array( // row #272
                915,
            ),
            array( // row #273
                916,
            ),
            array( // row #274
                822,
            ),
            array( // row #275
                333,
            ),
            array( // row #276
                198,
            ),
            array( // row #277
                38,
            ),
            array( // row #278
                807,
            ),
            array( // row #279
                973,
            ),
            array( // row #280
                1260,
            ),
            array( // row #281
                134,
            ),
            array( // row #282
                1477,
            ),
            array( // row #283
                1479,
            ),
            array( // row #284
                1481,
            ),
            array( // row #285
                327,
            ),
            array( // row #286
                323,
            ),
            array( // row #287
                764,
            ),
            array( // row #288
                896,
            ),
            array( // row #289
                1482,
            ),
            array( // row #290
                1135,
            ),
            array( // row #291
                84,
            ),
            array( // row #292
                1150,
            ),
            array( // row #293
                1350,
            ),
            array( // row #294
                1225,
            ),
            array( // row #295
                1256,
            ),
            array( // row #296
                1257,
            ),
            array( // row #297
                431,
            ),
            array( // row #298
                608,
            ),
            array( // row #299
                45,
            ),
            array( // row #300
                35,
            ),
            array( // row #301
                679,
            ),
            array( // row #302
                474,
            ),
            array( // row #303
                1004,
            ),
            array( // row #304
                710,
            ),
            array( // row #305
                998,
            ),
            array( // row #306
                1203,
            ),
            array( // row #307
                337,
            ),
            array( // row #308
                677,
            ),
            array( // row #309
                678,
            ),
            array( // row #310
                320,
            ),
            array( // row #311
                130,
            ),
            array( // row #312
                590,
            ),
            array( // row #313
                531,
            ),
            array( // row #314
                1291,
            ),
            array( // row #315
                1344,
            ),
            array( // row #316
                666,
            ),
            array( // row #317
                1334,
            ),
            array( // row #318
                1064,
            ),
            array( // row #319
                52,
            ),
            array( // row #320
                386,
            ),
            array( // row #321
                1333,
            ),
            array( // row #322
                1003,
            ),
            array( // row #323
                113,
            ),
            array( // row #324
                300,
            ),
            array( // row #325
                517,
            ),
            array( // row #326
                460,
            ),
            array( // row #327
                1296,
            ),
            array( // row #328
                266,
            ),
            array( // row #329
                153,
            ),
            array( // row #330
                1002,
            ),
            array( // row #331
                1337,
            ),
            array( // row #332
                1127,
            ),
            array( // row #333
                167,
            ),
            array( // row #334
                449,
            ),
            array( // row #335
                479,
            ),
            array( // row #336
                1229,
            ),
            array( // row #337
                183,
            ),
            array( // row #338
                465,
            ),
            array( // row #339
                321,
            ),
            array( // row #340
                823,
            ),
            array( // row #341
                819,
            ),
            array( // row #342
                772,
            ),
            array( // row #343
                627,
            ),
            array( // row #344
                1443,
            ),
            array( // row #345
                1327,
            ),
            array( // row #346
                992,
            ),
            array( // row #347
                578,
            ),
            array( // row #348
                173,
            ),
            array( // row #349
                1332,
            ),
            array( // row #350
                395,
            ),
            array( // row #351
                155,
            ),
            array( // row #352
                1484,
            ),
            array( // row #353
                387,
            ),
            array( // row #354
                606,
            ),
            array( // row #355
                1087,
            ),
            array( // row #356
                1086,
            ),
            array( // row #357
                348,
            ),
            array( // row #358
                1454,
            ),
            array( // row #359
                722,
            ),
            array( // row #360
                371,
            ),
            array( // row #361
                1201,
            ),
            array( // row #362
                1331,
            ),
            array( // row #363
                1485,
            ),
            array( // row #364
                1486,
            ),
            array( // row #365
                1487,
            ),
            array( // row #366
                721,
            ),
            array( // row #367
                1489,
            ),
            array( // row #368
                1490,
            ),
            array( // row #369
                883,
            ),
            array( // row #370
                1432,
            ),
            array( // row #371
                1433,
            ),
            array( // row #372
                494,
            ),
            array( // row #373
                598,
            ),
            array( // row #374
                1446,
            ),
            array( // row #375
                589,
            ),
            array( // row #376
                1452,
            ),
            array( // row #377
                1453,
            ),
            array( // row #378
                1491,
            ),
            array( // row #379
                1493,
            ),
            array( // row #380
                28,
            ),
            array( // row #381
                1494,
            ),
            array( // row #382
                1447,
            ),
            array( // row #383
                1117,
            ),
            array( // row #384
                1228,
            ),
            array( // row #385
                1123,
            ),
            array( // row #386
                824,
            ),
            array( // row #387
                887,
            ),
            array( // row #388
                1495,
            ),
            array( // row #389
                228,
            ),
            array( // row #390
                1496,
            ),
            array( // row #391
                1125,
            ),
            array( // row #392
                837,
            ),
            array( // row #393
                1118,
            ),
            array( // row #394
                850,
            ),
            array( // row #395
                626,
            ),
            array( // row #396
                1008,
            ),
            array( // row #397
                44,
            ),
            array( // row #398
                443,
            ),
            array( // row #399
                1434,
            ),
            array( // row #400
                261,
            ),
            array( // row #401
                158,
            ),
            array( // row #402
                599,
            ),
            array( // row #403
                484,
            ),
            array( // row #404
                534,
            ),
            array( // row #405
                418,
            ),
            array( // row #406
                1237,
            ),
            array( // row #407
                1272,
            ),
            array( // row #408
                1339,
            ),
            array( // row #409
                762,
            ),
            array( // row #410
                1326,
            ),
            array( // row #411
                980,
            ),
            array( // row #412
                459,
            ),
            array( // row #413
                652,
            ),
            array( // row #414
                1021,
            ),
            array( // row #415
                1352,
            ),
            array( // row #416
                768,
            ),
            array( // row #417
                1250,
            ),
            array( // row #418
                1126,
            ),
            array( // row #419
                1284,
            ),
            array( // row #420
                727,
            ),
            array( // row #421
                1354,
            ),
            array( // row #422
                1343,
            ),
            array( // row #423
                1020,
            ),
            array( // row #424
                810,
            ),
            array( // row #425
                1219,
            ),
            array( // row #426
                832,
            ),
            array( // row #427
                844,
            ),
            array( // row #428
                943,
            ),
            array( // row #429
                424,
            ),
            array( // row #430
                1320,
            ),
            array( // row #431
                1152,
            ),
            array( // row #432
                864,
            ),
            array( // row #433
                1316,
            ),
            array( // row #434
                836,
            ),
            array( // row #435
                1033,
            ),
            array( // row #436
                1151,
            ),
            array( // row #437
                931,
            ),
            array( // row #438
                1116,
            ),
            array( // row #439
                1215,
            ),
            array( // row #440
                1317,
            ),
            array( // row #441
                1068,
            ),
            array( // row #442
                347,
            ),
            array( // row #443
                37,
            ),
            array( // row #444
                1498,
            ),
            array( // row #445
                1499,
            ),
            array( // row #446
                1500,
            ),
            array( // row #447
                545,
            ),
            array( // row #448
                1115,
            ),
            array( // row #449
                1353,
            ),
            array( // row #450
                769,
            ),
            array( // row #451
                1100,
            ),
            array( // row #452
                1502,
            ),
            array( // row #453
                1501,
            ),
            array( // row #454
                146,
            ),
            array( // row #455
                1293,
            ),
            array( // row #456
                1112,
            ),
            array( // row #457
                316,
            ),
            array( // row #458
                1149,
            ),
            array( // row #459
                107,
            ),
            array( // row #460
                1105,
            ),
            array( // row #461
                1241,
            ),
            array( // row #462
                1067,
            ),
            array( // row #463
                384,
            ),
            array( // row #464
                1240,
            ),
            array( // row #465
                1231,
            ),
            array( // row #466
                782,
            ),
            array( // row #467
                368,
            ),
            array( // row #468
                873,
            ),
            array( // row #469
                1034,
            ),
            array( // row #470
                377,
            ),
            array( // row #471
                1371,
            ),
            array( // row #472
                1011,
            ),
            array( // row #473
                1014,
            ),
            array( // row #474
                250,
            ),
            array( // row #475
                544,
            ),
            array( // row #476
                1242,
            ),
            array( // row #477
                189,
            ),
            array( // row #478
                1230,
            ),
            array( // row #479
                1096,
            ),
            array( // row #480
                1236,
            ),
            array( // row #481
                546,
            ),
            array( // row #482
                1346,
            ),
            array( // row #483
                566,
            ),
            array( // row #484
                592,
            ),
            array( // row #485
                421,
            ),
            array( // row #486
                875,
            ),
            array( // row #487
                1147,
            ),
            array( // row #488
                1382,
            ),
            array( // row #489
                978,
            ),
            array( // row #490
                1366,
            ),
            array( // row #491
                553,
            ),
            array( // row #492
                1503,
            ),
            array( // row #493
                1140,
            ),
            array( // row #494
                139,
            ),
            array( // row #495
                1022,
            ),
            array( // row #496
                694,
            ),
            array( // row #497
                1504,
            ),
            array( // row #498
                565,
            ),
            array( // row #499
                1356,
            ),
            array( // row #500
                1347,
            ),
            array( // row #501
                280,
            ),
            array( // row #502
                726,
            ),
            array( // row #503
                1505,
            ),
            array( // row #504
                865,
            ),
            array( // row #505
                859,
            ),
            array( // row #506
                138,
            ),
            array( // row #507
                699,
            ),
            array( // row #508
                682,
            ),
            array( // row #509
                845,
            ),
            array( // row #510
                609,
            ),
            array( // row #511
                1507,
            ),
            array( // row #512
                1506,
            ),
            array( // row #513
                621,
            ),
            array( // row #514
                705,
            ),
            array( // row #515
                1509,
            ),
            array( // row #516
                1508,
            ),
            array( // row #517
                1131,
            ),
            array( // row #518
                1510,
            ),
            array( // row #519
                1351,
            ),
            array( // row #520
                1368,
            ),
            array( // row #521
                664,
            ),
            array( // row #522
                757,
            ),
            array( // row #523
                680,
            ),
            array( // row #524
                1450,
            ),
            array( // row #525
                1511,
            ),
            array( // row #526
                359,
            ),
            array( // row #527
                1288,
            ),
            array( // row #528
                1078,
            ),
            array( // row #529
                508,
            ),
            array( // row #530
                1513,
            ),
            array( // row #531
                690,
            ),
            array( // row #532
                1514,
            ),
            array( // row #533
                1076,
            ),
            array( // row #534
                1360,
            ),
            array( // row #535
                259,
            ),
            array( // row #536
                1516,
            ),
            array( // row #537
                1517,
            ),
            array( // row #538
                1518,
            ),
            array( // row #539
                1342,
            ),
            array( // row #540
                976,
            ),
            array( // row #541
                610,
            ),
            array( // row #542
                69,
            ),
            array( // row #543
                1521,
            ),
            array( // row #544
                335,
            ),
            array( // row #545
                646,
            ),
            array( // row #546
                692,
            ),
            array( // row #547
                13,
            ),
            array( // row #548
                1233,
            ),
            array( // row #549
                1234,
            ),
            array( // row #550
                941,
            ),
            array( // row #551
                1243,
            ),
            array( // row #552
                1359,
            ),
            array( // row #553
                1031,
            ),
            array( // row #554
                1163,
            ),
            array( // row #555
                1255,
            ),
            array( // row #556
                1365,
            ),
            array( // row #557
                862,
            ),
            array( // row #558
                940,
            ),
            array( // row #559
                538,
            ),
            array( // row #560
                1141,
            ),
            array( // row #561
                734,
            ),
            array( // row #562
                1244,
            ),
            array( // row #563
                1235,
            ),
            array( // row #564
                748,
            ),
            array( // row #565
                1252,
            ),
            array( // row #566
                132,
            ),
            array( // row #567
                802,
            ),
            array( // row #568
                1524,
            ),
            array( // row #569
                1523,
            ),
            array( // row #570
                412,
            ),
            array( // row #571
                1035,
            ),
            array( // row #572
                48,
            ),
            array( // row #573
                322,
            ),
            array( // row #574
                1000,
            ),
            array( // row #575
                1212,
            ),
            array( // row #576
                1525,
            ),
            array( // row #577
                1526,
            ),
            array( // row #578
                1527,
            ),
            array( // row #579
                1528,
            ),
            array( // row #580
                858,
            ),
            array( // row #581
                234,
            ),
            array( // row #582
                165,
            ),
            array( // row #583
                549,
            ),
            array( // row #584
                839,
            ),
            array( // row #585
                659,
            ),
            array( // row #586
                753,
            ),
            array( // row #587
                1248,
            ),
            array( // row #588
                644,
            ),
            array( // row #589
                1157,
            ),
            array( // row #590
                877,
            ),
            array( // row #591
                68,
            ),
            array( // row #592
                446,
            ),
            array( // row #593
                556,
            ),
            array( // row #594
                1164,
            ),
            array( // row #595
                903,
            ),
            array( // row #596
                262,
            ),
            array( // row #597
                703,
            ),
            array( // row #598
                1259,
            ),
            array( // row #599
                1263,
            ),
            array( // row #600
                1254,
            ),
            array( // row #601
                1046,
            ),
            array( // row #602
                698,
            ),
            array( // row #603
                1265,
            ),
            array( // row #604
                1093,
            ),
            array( // row #605
                475,
            ),
            array( // row #606
                602,
            ),
            array( // row #607
                1380,
            ),
            array( // row #608
                1530,
            ),
            array( // row #609
                1531,
            ),
            array( // row #610
                344,
            ),
            array( // row #611
                917,
            ),
            array( // row #612
                1418,
            ),
            array( // row #613
                1171,
            ),
            array( // row #614
                437,
            ),
            array( // row #615
                1532,
            ),
            array( // row #616
                595,
            ),
            array( // row #617
                1444,
            ),
            array( // row #618
                548,
            ),
            array( // row #619
                751,
            ),
            array( // row #620
                717,
            ),
            array( // row #621
                984,
            ),
            array( // row #622
                501,
            ),
            array( // row #623
                1385,
            ),
            array( // row #624
                1266,
            ),
            array( // row #625
                1426,
            ),
            array( // row #626
                576,
            ),
            array( // row #627
                1041,
            ),
            array( // row #628
                1429,
            ),
            array( // row #629
                691,
            ),
            array( // row #630
                697,
            ),
            array( // row #631
                809,
            ),
            array( // row #632
                1271,
            ),
            array( // row #633
                700,
            ),
            array( // row #634
                1537,
            ),
            array( // row #635
                1226,
            ),
            array( // row #636
                1245,
            ),
            array( // row #637
                649,
            ),
            array( // row #638
                159,
            ),
            array( // row #639
                1538,
            ),
            array( // row #640
                1044,
            ),
            array( // row #641
                755,
            ),
            array( // row #642
                1539,
            ),
            array( // row #643
                656,
            ),
            array( // row #644
                381,
            ),
            array( // row #645
                763,
            ),
            array( // row #646
                513,
            ),
            array( // row #647
                1540,
            ),
            array( // row #648
                1541,
            ),
            array( // row #649
                1103,
            ),
            array( // row #650
                1543,
            ),
            array( // row #651
                1170,
            ),
            array( // row #652
                29,
            ),
            array( // row #653
                981,
            ),
            array( // row #654
                1178,
            ),
            array( // row #655
                1174,
            ),
            array( // row #656
                1101,
            ),
            array( // row #657
                1042,
            ),
            array( // row #658
                793,
            ),
            array( // row #659
                1545,
            ),
            array( // row #660
                1183,
            ),
            array( // row #661
                715,
            ),
            array( // row #662
                1387,
            ),
            array( // row #663
                661,
            ),
            array( // row #664
                1384,
            ),
            array( // row #665
                1374,
            ),
            array( // row #666
                318,
            ),
            array( // row #667
                1268,
            ),
            array( // row #668
                1544,
            ),
            array( // row #669
                779,
            ),
            array( // row #670
                332,
            ),
            array( // row #671
                374,
            ),
            array( // row #672
                394,
            ),
            array( // row #673
                957,
            ),
            array( // row #674
                1028,
            ),
            array( // row #675
                1267,
            ),
            array( // row #676
                247,
            ),
            array( // row #677
                1156,
            ),
            array( // row #678
                1388,
            ),
            array( // row #679
                1055,
            ),
            array( // row #680
                317,
            ),
            array( // row #681
                1047,
            ),
            array( // row #682
                502,
            ),
            array( // row #683
                684,
            ),
            array( // row #684
                897,
            ),
            array( // row #685
                310,
            ),
            array( // row #686
                954,
            ),
            array( // row #687
                303,
            ),
            array( // row #688
                1547,
            ),
            array( // row #689
                1018,
            ),
            array( // row #690
                391,
            ),
            array( // row #691
                422,
            ),
            array( // row #692
                594,
            ),
            array( // row #693
                1546,
            ),
            array( // row #694
                745,
            ),
            array( // row #695
                911,
            ),
            array( // row #696
                1548,
            ),
            array( // row #697
                1550,
            ),
            array( // row #698
                302,
            ),
            array( // row #699
                367,
            ),
            array( // row #700
                1280,
            ),
            array( // row #701
                1010,
            ),
            array( // row #702
                1554,
            ),
            array( // row #703
                177,
            ),
            array( // row #704
                930,
            ),
            array( // row #705
                1179,
            ),
            array( // row #706
                770,
            ),
            array( // row #707
                1389,
            ),
            array( // row #708
                1555,
            ),
            array( // row #709
                1383,
            ),
            array( // row #710
                1015,
            ),
            array( // row #711
                1394,
            ),
            array( // row #712
                1054,
            ),
            array( // row #713
                655,
            ),
            array( // row #714
                1173,
            ),
        );

        $conta = 0;
        $array_to_insert = [];
        foreach ($hotel_ids_pg as $array_value) {
            $hotel_id = $array_value[0];
            // se questo hotel non ha né il p. gratuito coperto, né quello scoperto 222,224
            // assegno quello scoperto gratuito 222
            $row1 = $user = DB::table('tblHotelServizi')->where('hotel_id', $hotel_id)->where('servizio_id', '222')->first();
            $row2 = $user = DB::table('tblHotelServizi')->where('hotel_id', $hotel_id)->where('servizio_id', '224')->first();

            if (is_null($row1) && is_null($row2)) {
                /*$conta++;
                echo "$conta -- $hotel_id non ha pg ma l'aveva!! <br><br>";*/

                // insert
                $to_insert['hotel_id'] = $hotel_id;
                $to_insert['servizio_id'] = 222;
                $array_to_insert[] = $to_insert;
            }
        }

        if (count($array_to_insert)) {
            //var_dump($array_to_insert);
            DB::table('tblHotelServizi')->insert($array_to_insert);
        }

    }

    /**
     * [getInfoPiscinaHotelForCrm: restituisce un array con 'nome_colonna' => 'valore' in formato json:
     * serve al crm per allineare la sezione infopiscina con quella online sul sito
     * @return [type] [description]
     */
    public function getInfoPiscinaHotelForCrm($hotel_id = 0)
    {
        if (!$hotel_id) {
            return;
        }
        $ip = InfoPiscina::where('hotel_id', $hotel_id)->first();

        if (!is_null($ip)) {
            return Response::json($ip->toArray());
        } else {
            return Response::json([]);
        }
    }

    /**
     * [getInfoBenessereHotelForCrm: restituisce un array con 'nome_colonna' => 'valore' in formato json:
     * serve al crm per allineare la sezione infobenesseree con quella online sul sito
     * @return [type] [description]
     */
    public function getInfoBenessereHotelForCrm($hotel_id = 0)
    {
        if (!$hotel_id) {
            return;
        }
        $ib = InfoBenessere::where('hotel_id', $hotel_id)->first();

        if (!is_null($ib)) {
            return Response::json($ib->toArray());
        } else {
            return Response::json([]);
        }
    }

    /**
     * [getServiziHotel: restituisce i servizi associati a questo hotel in formato json:
     * serve al crm per allineare il foglio servizi con quelli online sul sito
     * @return [type] [description]
     */
    public function getServiziHotelForCrm($hotel_id = 0)
    {
        if (!$hotel_id) {
            return;
        }
        $hotel = Hotel::find($hotel_id);

        return Response::json(
            $hotel->servizi->pluck('pivot.note', 'id')
        );

    }

    /**
     * [getServiziAggiuntiviHotelForCrm: restituisce i servizi aggiutivi associati a questo hotel tramite i grupppi di servizio in formato json:
     * serve al crm per allineare il foglio servizi con quelli online sul sito
     * @return [type] [description]
     */
    public function getServiziAggiuntiviHotelForCrm($hotel_id = 0)
    {
        if (!$hotel_id) {
            return;
        }
        $hotel = Hotel::find($hotel_id);

        $categorie = CategoriaServizi::has('servizi')->with(['servizi',
            'servizi.servizi_lingua' => function ($query) {
                $query->where('lang_id', '=', 'it');
            },
            'servizi.gruppo',
            'serviziPrivati'])
            ->orderBY('position')->get();

        // Per ogni categoria
        // ho dei servizi privati del cliente
        $array_servizi_privati = [];

        foreach ($categorie as $categoria) {

            $servizi = $categoria->serviziPrivati()->where('hotel_id', $hotel_id)->get();

            if (!is_null($servizi)) {
                foreach ($servizi as $servizio_privato) {

                    if (is_null($servizio_privato->translate('it')->first())) {
                        $sp = 'to_translate';
                    } else {
                        $sp = $servizio_privato->translate('it')->first()->nome;
                    }

                    $array_servizi_privati[$categoria->id][] = $sp;

                }
            }

        } // loop categorie

        //dd($array_servizi_privati);

        return Response::json(
            $array_servizi_privati
        );

    }

    private function _sendMailReminder($hotel = null, $offerta = null, $validita_offerta = "", $tipo = "")
    {
        if (!is_null($hotel) && !is_null($offerta)) {

            // sendGrid service to send mail
            Utility::swapToSendGrid();

            $from = "admin@info-alberghi.com";
            $nome = "Amministrazione Info Alberghi";

            $oggetto = "Reminder evidenze $tipo";

            // destinatario
            if (App::environment() == "local") {
                $email_to = 'giovanni@info-alberghi.com';
            } else {
                $email_to = Self::MAIL_TO;
            }

            if (empty($tipo)) {
                $titolo_offerta = $offerta->titolo;
            } else {
                $titolo_offerta = $offerta->note;
            }

            $hotel_name = $hotel->nome;
            $localita = $hotel->localita->nome;
            $hotel_id = $hotel->id;
            $oggetto = "Ricordati che deve morire!";
            $validita_offerta = str_replace(", ", "<br />", $validita_offerta);

            /**
             * Spedisco l'email
             */

            Mail::send('emails.reminderEvidenze',

                compact(

                    'titolo_offerta',
                    'validita_offerta',
                    'hotel_name',
                    'localita',
                    'hotel_id',
                    'tipo',
                    'oggetto'

                ), function ($message) use ($from, $oggetto, $nome, $email_to) {
                    $message->from($from, $nome);
                    $message->to($email_to);
                    $message->subject($oggetto);
                });

        } // end if
    }

    /**
     * [cronScadenzeVot verifica se ci sono delle scadenze PER OGGI NON INVIATE]
     * @return [void] [manda la mail di reminder]
     */
    public function cronScadenzeVot()
    {

        $scadenze = ScadenzaVot::with(['offertaTop.cliente.localita', 'offertaTop.translate'])->diOggi()->nonInviata()->get();

        if ($scadenze->count()) {
            foreach ($scadenze as $scadenza) {
                $hotel = $scadenza->offertaTop->cliente;
                $offerta = $scadenza->offertaTop->translate->first();
                $validita_offerta = $scadenza->offertaTop->getMesiValiditaAsStr();
                self::_sendMailReminder($hotel, $offerta, $validita_offerta);
                $scadenza->inviata = true;
                $scadenza->save();
            }
        }

    }

    /**
     * [cronScadenzeVtt verifica se ci sono delle scadenze PER OGGI NON INVIATE]
     * @return [void] [manda la mail di reminder]
     */
    public function cronScadenzeVtt()
    {

        $scadenze = ScadenzaVtt::with(['offertaTop.cliente.localita', 'offertaTop.translate'])->diOggi()->nonInviata()->get();

        if ($scadenze->count()) {
            foreach ($scadenze as $scadenza) {
                $hotel = $scadenza->offertaTop->cliente;
                $offerta = $scadenza->offertaTop->translate->first();
                $validita_offerta = $scadenza->offertaTop->getMesiValiditaAsStr();
                self::_sendMailReminder($hotel, $offerta, $validita_offerta, $tipo = " bambini gratis");
                $scadenza->inviata = true;
                $scadenza->save();
            }
        }

    }

    public function BackUpDocFoldler()
    {

        $source = Config::get("filesystems.doc_path");
        $dest = Config::get("filesystems.doc_bu_path");

        if ($source != '' && $dest != '') {
            $command = "cd $dest && tar cfz doc.tgz $source";
            shell_exec($command);
            echo "Finito copia";
        } else {
            echo "Copia non eseguita. Sorgente e destinazione sono vuote!";
        }

    }

    public function CheckFile()
    {

        $file_to_check = ['sitemap.xml', 'sitemap_immagini_cdn.xml', 'robots.txt', 'doc/img_testi/', 'doc/rassegna/', 'doc/statistiche/'];
        $result_array = [];
        foreach ($file_to_check as $filename) {
            if (!file_exists(public_path() . '/' . $filename)) {
                $result_array[] = public_path() . '/' . $filename . ' NON esiste';
            }
        }

        if (count($result_array)) {
            $result_array[] = ' ------> Data: ' . Carbon::now()->format('d/m/y H:i');
            $mail_text = implode(", ", $result_array);

            Mail::raw($mail_text, function ($message) {
                $message->from('info@info-alberghi.com', 'Info Alberghi');
                $message->subject('Check file critici');
                $message->to('luigi@info-alberghi.com');
                //->cc('bar@example.com');
            });

        }

    }

    /**
     * Cancello le password di reset di più di 3 girni
     */
    public static function deleteReset()
    {

        DB::table("password_resets")->where("created_at", "<=", Carbon::now()->subDays(3)->toDateString())->delete();

    }

    /**
     * Cancello le accettazioni privacy di più di 36 mesi
     *
     */
    public static function deleteAcceptance()
    {

        $term_storage_archived_data = Config::get("privacy.term_storage_archived_data");
        DB::table("tblAcceptancePrivacy")->where("created_at", "<", Carbon::now()->subMonths($term_storage_archived_data))->delete();

    }

    public function allineaSlots()
    {
        $slots = SlotVetrina::with(['cliente'])->get();

        foreach ($slots as $slot) {
            $cliente = $slot->cliente;

            $slot->hotel_nome = $cliente->nome;
            $slot->hotel_categoria_id = $cliente->categoria_id;
            $slot->hotel_prezzo_min = $cliente->prezzo_min;
            $slot->hotel_prezzo_max = $cliente->prezzo_max;

            $slot->save();
        }

    }

    public function StatsMarzio($anno = "2018")
    {
        $marzio_hotels = DB::table("clienti_marzio_mini")->get();

        $marzio_hotels_ids = $marzio_hotels->pluck('id');

        //dd($marzio_hotels);

        foreach ($marzio_hotels as $hotel) {
            $result_marzio_hotels[$hotel->id] = ['ID' => $hotel->id, 'nome' => $hotel->nome, 'indirizzo' => $hotel->indirizzo, 'localita' => $hotel->localita];
        }

        /**

        select hotel_id, sum(conteggio) as n
        from tblMailSchedaRead
        where anno = '2018'
        and mese between 1 and 12
        and hotel_id IN (17, 1643)
        group by hotel_id

         */

        // mail scheda
        $query = DB::table("tblMailSchedaRead")->select(DB::raw('hotel_id, sum(conteggio) as n'));
        $query->where('anno', $anno);
        $query->whereBetween('mese', [1, 12]);
        $query->whereIn('hotel_id', $marzio_hotels_ids);
        $query->groupBy('hotel_id');
        $mail_scheda = $query->get();

        // riempio l'array con i risultati

        foreach ($mail_scheda as $value) {
            $result_marzio_hotels[$value->hotel_id] += ['mail_scheda' => $value->n];
        }

        // mail multipla
        $query = DB::table("tblStatsMailMultipleRead")->select(DB::raw('hotel_id, sum(conteggio) as n'));
        $query->where('anno', $anno);
        $query->whereBetween('mese', [1, 12]);
        $query->whereIn('hotel_id', $marzio_hotels_ids);
        $query->groupBy('hotel_id');
        $mail_scheda = $query->get();

        // riempio l'array con i risultati

        foreach ($mail_scheda as $value) {
            $result_marzio_hotels[$value->hotel_id] += ['mail_multipla' => $value->n];
        }

        // mail telefonate
        $query = DB::table("tblStatsHotelCallRead")->select(DB::raw('hotel_id, sum(calls) as n'));
        $query->where('anno', $anno);
        $query->whereBetween('mese', [1, 12]);
        $query->whereIn('hotel_id', $marzio_hotels_ids);
        $query->groupBy('hotel_id');
        $mail_scheda = $query->get();

        // riempio l'array con i risultati

        foreach ($mail_scheda as $value) {
            $result_marzio_hotels[$value->hotel_id] += ['telefonate' => $value->n];
        }

        // mail accessi alla scheda
        $query = DB::table("tblStatsHotelRead")->select(DB::raw('hotel_id, sum(visits) as n'));
        $query->where('anno', $anno);
        $query->whereBetween('mese', [1, 12]);
        $query->whereIn('hotel_id', $marzio_hotels_ids);
        $query->groupBy('hotel_id');
        $mail_scheda = $query->get();

        // riempio l'array con i risultati

        foreach ($mail_scheda as $value) {
            $result_marzio_hotels[$value->hotel_id] += ['visite' => $value->n];
        }

        //dd($result_marzio_hotels);

        $filename = storage_path("statsMarzio.csv");

        $handle = fopen($filename, 'w+');

        $columns = ['ID', 'nome', 'indirizzo', 'localita', 'mail_scheda', 'mail_multipla', 'telefonate', 'visite'];

        fputcsv($handle, $columns);

        foreach ($result_marzio_hotels as $data) {

            fputcsv($handle, $data);
        }

        fclose($handle);

        $headers = array(
            'Content-Type' => 'text/csv',
        );

        return response()->download($filename, 'statsMarzio.csv', $headers);

    }
    
    /**
     * Chiamata ajax per l'iscirzione alla newsletter tramite le API di MailUp
     * @param  Request $request
     * @return [type]
     */

   
    public function iscrizione_newsletter(Request $request)
    {

        $Email = $request->get('Email');
        $response = Utility::mailUpSubscribe($Email);
        header('Content-Type: application/json');
        die('{"Message":' . json_encode($response) . "}");

    }

}
