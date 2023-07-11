<?php
namespace Database\Seeders;

use App\Hotel;
use App\User;
use Illuminate\Database\Seeder;

class ImportCommercialiClientiCrmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $commerciali_crm = $clienti = array(
	array(
		"id_info" => 459,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 93,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 971,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 990,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 758,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 668,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 107,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 475,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 782,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 867,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 167,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 492,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 599,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 361,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 113,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 609,
		"commerciale" => "Andrea Sacchetti",
	),
	array(
		"id_info" => 555,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 429,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 881,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 978,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 344,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 943,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 719,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 642,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 414,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 6,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 510,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 261,
		"commerciale" => "Rosa Barbieri",
	),
	array(
		"id_info" => 554,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 911,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 398,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 314,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 562,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 498,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 718,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 211,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 514,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 670,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 825,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 109,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 221,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 223,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 430,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 929,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 812,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 790,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 606,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 144,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 92,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 740,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 874,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 104,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 154,
		"commerciale" => "Rosa Barbieri",
	),
	array(
		"id_info" => 743,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 395,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 621,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 699,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 282,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 745,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 473,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 610,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 494,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 572,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 58,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 315,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 735,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 52,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 156,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 335,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 930,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 885,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 744,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 741,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 973,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 862,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 684,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 837,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 620,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 393,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 413,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 257,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 160,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 882,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 883,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 637,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 976,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 721,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 551,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 701,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 227,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 205,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 923,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 576,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 567,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 823,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 102,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 76,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 48,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 979,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 846,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 608,
		"commerciale" => "Rosa Barbieri",
	),
	array(
		"id_info" => 333,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 767,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 723,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 437,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 589,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 877,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 656,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 865,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 247,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 479,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 861,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 360,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 727,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 941,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 291,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 403,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 245,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 53,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 662,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 306,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 452,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 454,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 715,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 289,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 213,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 82,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 89,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 530,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 869,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 838,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 703,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 876,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 366,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 423,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 86,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 146,
		"commerciale" => "Rosa Barbieri",
	),
	array(
		"id_info" => 836,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 262,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 431,
		"commerciale" => "Rosa Barbieri",
	),
	array(
		"id_info" => 206,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 830,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 622,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 300,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 465,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 886,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 541,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 646,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 981,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 852,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 527,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 217,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 367,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 35,
		"commerciale" => "Rosa Barbieri",
	),
	array(
		"id_info" => 875,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 980,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 317,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 87,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 75,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 818,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 732,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 764,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 866,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 338,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 888,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 598,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 908,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 700,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 580,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 244,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 672,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 733,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 766,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 364,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 537,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 746,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 31,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 807,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 225,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 592,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 298,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 815,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 152,
		"commerciale" => "Andrea Sacchetti",
	),
	array(
		"id_info" => 974,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 962,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 587,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 704,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 401,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 200,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 706,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 386,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 106,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 616,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 531,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 896,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 918,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 147,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 958,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 655,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 849,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 859,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 890,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 224,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 853,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 553,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 51,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 234,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 692,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 762,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 829,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 712,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 176,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 293,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 654,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 844,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 627,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 921,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 734,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 542,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1443,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 302,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 847,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 503,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 228,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 839,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 864,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 462,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 271,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 989,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 738,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 387,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 538,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 776,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 81,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 652,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 894,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 594,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 450,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 287,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 378,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 500,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 578,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 640,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 777,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 924,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 153,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 487,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 458,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 779,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 568,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 737,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 464,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 259,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 327,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 991,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 345,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 968,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 134,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 371,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 802,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 795,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 801,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 280,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 513,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 850,
		"commerciale" => "Rosa Barbieri",
	),
	array(
		"id_info" => 772,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 603,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 239,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 68,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 175,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 644,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 7,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 240,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 840,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 285,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 320,
		"commerciale" => "Rosa Barbieri",
	),
	array(
		"id_info" => 29,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 471,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 446,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 275,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 832,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 799,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 791,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 550,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 218,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 752,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 372,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 332,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1022,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 917,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 922,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 151,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 809,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 927,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 258,
		"commerciale" => "Rosa Barbieri",
	),
	array(
		"id_info" => 329,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 569,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 854,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 426,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 987,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 408,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 412,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 90,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 963,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 698,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 902,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 845,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 359,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 817,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 558,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 819,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 691,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 460,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 526,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 501,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 992,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 251,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 858,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 400,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 932,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 887,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 85,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 520,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 177,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 2,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 451,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 824,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 318,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 43,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 188,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 354,
		"commerciale" => "Rosa Barbieri",
	),
	array(
		"id_info" => 786,
		"commerciale" => "Rosa Barbieri",
	),
	array(
		"id_info" => 248,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 72,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 535,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 384,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 255,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 37,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 78,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 185,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 947,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 556,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 797,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 488,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 906,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 189,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 663,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 574,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 13,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 484,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 108,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 204,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 693,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 664,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 584,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 625,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 434,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 424,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 726,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 813,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 724,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 173,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 565,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 162,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 720,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 856,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 682,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 294,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 3,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 286,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 575,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 45,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 421,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 954,
		"commerciale" => "Rosa Barbieri",
	),
	array(
		"id_info" => 79,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 810,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 822,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 310,
		"commerciale" => "Rosa Barbieri",
	),
	array(
		"id_info" => 650,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 394,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 897,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 130,
		"commerciale" => "Rosa Barbieri",
	),
	array(
		"id_info" => 638,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 461,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 469,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 368,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 728,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 295,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 843,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 311,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 659,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 266,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 348,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 441,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 626,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 493,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 112,
		"commerciale" => "Andrea Sacchetti",
	),
	array(
		"id_info" => 582,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 988,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 714,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 707,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 658,
		"commerciale" => "Rosa Barbieri",
	),
	array(
		"id_info" => 407,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 545,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 438,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 717,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 201,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 149,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 697,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 129,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 665,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 482,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 448,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 690,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 442,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 346,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 674,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 26,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 38,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 392,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 619,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 70,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 678,
		"commerciale" => "Rosa Barbieri",
	),
	array(
		"id_info" => 101,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 549,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 195,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 931,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 547,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 28,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 181,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 155,
		"commerciale" => "Rosa Barbieri",
	),
	array(
		"id_info" => 508,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 632,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 420,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 77,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 243,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 635,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 964,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 857,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 179,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 955,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 36,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 915,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 916,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 374,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 215,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 910,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 122,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 210,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 831,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 165,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 920,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 757,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 377,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 419,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 474,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 139,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 649,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 763,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 950,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 502,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 935,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 115,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 680,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 301,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 946,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 254,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 903,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 511,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 722,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 283,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 517,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 337,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 472,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 657,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 380,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 851,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 919,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 347,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 602,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 74,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 563,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 309,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 252,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 788,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 816,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 749,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 199,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 873,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 250,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 443,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 675,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 340,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 391,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 600,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 143,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 590,
		"commerciale" => "Rosa Barbieri",
	),
	array(
		"id_info" => 316,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 913,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 422,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 755,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 389,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 566,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 326,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 892,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 18,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 198,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 666,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 961,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 305,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 303,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 114,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 172,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 753,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 229,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 751,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 88,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 705,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 418,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 540,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 595,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 246,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 196,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 848,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 321,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 960,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 957,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 548,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 546,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 748,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 529,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 710,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 933,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 183,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 771,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 467,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 956,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 677,
		"commerciale" => "Rosa Barbieri",
	),
	array(
		"id_info" => 192,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 334,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 895,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 121,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 534,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 512,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 583,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 425,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 759,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 559,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 926,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 427,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 827,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 984,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 276,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 504,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 439,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 383,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 730,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 811,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 381,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 977,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 322,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 157,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 382,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 148,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 506,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 343,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 17,
		"commerciale" => "Andrea Sacchetti",
	),
	array(
		"id_info" => 667,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 814,
		"commerciale" => "Rosa Barbieri",
	),
	array(
		"id_info" => 970,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 447,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 127,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 679,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 878,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 695,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 842,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 965,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 158,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 784,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 272,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 440,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 914,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 249,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 630,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 187,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 299,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 803,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 30,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 689,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 686,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 63,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 33,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 868,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 84,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 445,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 241,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 435,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 507,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 159,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 468,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 357,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 636,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 40,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 415,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 841,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 98,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 404,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 497,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 22,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1641,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 544,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 396,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 519,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 997,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 358,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 631,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 242,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 808,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 111,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 673,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 694,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 284,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 754,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 44,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 770,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 428,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 796,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 449,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 579,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 615,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 100,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 994,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 995,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 996,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 998,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 999,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1000,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1002,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1003,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1004,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1005,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1006,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1007,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1008,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1010,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1011,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1012,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1013,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1014,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1016,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1020,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1021,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1023,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1025,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1026,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1028,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1029,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1031,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1032,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1033,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1034,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1035,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1036,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1037,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1038,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1039,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1040,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1041,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1042,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1044,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1045,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1046,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1047,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1048,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1049,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1050,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1051,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1052,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1053,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1054,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1055,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1057,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1059,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1060,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1062,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1063,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1064,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1065,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1066,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1068,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1070,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1072,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1073,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1075,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1076,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1078,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1079,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1081,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1083,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1086,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1087,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1088,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1091,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1093,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1094,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1095,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1098,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1099,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1100,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1101,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1103,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1104,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1105,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1108,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1109,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1110,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1111,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1112,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1114,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1115,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1116,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1117,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1118,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1119,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1120,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1121,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1122,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1123,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1124,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1125,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1126,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1127,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1129,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1131,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1132,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1133,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1134,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1135,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1139,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1141,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1143,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1144,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1146,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1147,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1149,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1150,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1151,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1152,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1153,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1155,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1156,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1157,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1160,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1161,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1162,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1163,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1164,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1165,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1166,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1167,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1169,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1170,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1171,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1172,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1173,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1174,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1175,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1177,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1178,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1179,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1180,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1183,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1185,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1187,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1188,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1190,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1191,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1192,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1193,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1200,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1201,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1206,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1208,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1209,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1211,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1212,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1213,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1214,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1215,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1216,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1223,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1224,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1225,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1226,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1228,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1366,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1229,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1230,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1231,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1232,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1233,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1234,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1235,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1236,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1237,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1238,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1240,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1241,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1243,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1242,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1244,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1245,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1247,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1248,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1250,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1254,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1255,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1256,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1257,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1258,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1259,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1436,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1260,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1261,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1263,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1265,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1266,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1267,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1268,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1269,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1270,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1271,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1272,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1274,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1275,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1276,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1278,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1279,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1280,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1281,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1282,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1283,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1284,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1285,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1286,
		"commerciale" => "Rosa Barbieri",
	),
	array(
		"id_info" => 1288,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1290,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1291,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1293,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1294,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1295,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1296,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1297,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1298,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1300,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1301,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1302,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1303,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1304,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1306,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1307,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1309,
		"commerciale" => "Rosa Barbieri",
	),
	array(
		"id_info" => 1313,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1314,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1347,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1316,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1317,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1318,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1320,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1321,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1322,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1323,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1324,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1326,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1327,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1331,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1332,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1334,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1335,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1336,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1337,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1338,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1339,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1343,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 297,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1345,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1346,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1348,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1349,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1350,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1352,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1353,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1354,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1355,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1356,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1360,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1361,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1506,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 363,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1364,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1365,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1367,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1368,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1369,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1370,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1371,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1372,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1373,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1374,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1376,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1377,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1380,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1381,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1382,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1383,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1384,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1385,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1386,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1387,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1388,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1389,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1390,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1391,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1392,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1394,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1397,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1399,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1400,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1401,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1402,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1403,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1404,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1405,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1406,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1407,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1408,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1409,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1411,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1413,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1414,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1416,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1418,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1419,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1420,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1421,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1422,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1423,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1424,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1425,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1426,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1427,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1428,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1429,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1430,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1431,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1432,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1433,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1435,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 478,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1437,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1438,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1439,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1440,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1441,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1442,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1444,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1445,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1446,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1448,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1449,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1450,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1451,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1452,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1453,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1454,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1455,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1456,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1459,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1460,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1458,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1461,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1462,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1464,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1465,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1466,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1467,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1468,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1469,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1470,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1471,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1472,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1473,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1474,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1475,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1477,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1478,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1479,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1480,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1481,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1482,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1484,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1485,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1486,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1487,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1488,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1489,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1490,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1491,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1492,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1493,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1494,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1495,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1496,
		"commerciale" => "Rosa Barbieri",
	),
	array(
		"id_info" => 1499,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1500,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1501,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1502,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1503,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1504,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1505,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1507,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1508,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1509,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1510,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1511,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1512,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1513,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1514,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1515,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1516,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1517,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1518,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1519,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1520,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1521,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1522,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1523,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1524,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1525,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1526,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1527,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1528,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1529,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1530,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1531,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1532,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1533,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1534,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1539,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1540,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1541,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1543,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1544,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1545,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1546,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1547,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1549,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1550,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1552,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1553,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1554,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1555,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1556,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1558,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1559,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1560,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1562,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1563,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1564,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1565,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1567,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1569,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1571,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1572,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1574,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1575,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1576,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1577,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1578,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1579,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1582,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1583,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1584,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1585,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1586,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1589,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1590,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1592,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1594,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1595,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1596,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1246,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1597,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1599,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1601,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1600,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1602,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1603,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1604,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1605,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1606,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1607,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1608,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1609,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1610,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1611,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1612,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1615,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1616,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1617,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 399,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 397,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 405,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 277,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 279,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 231,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 230,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 237,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 238,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 313,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 402,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 353,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1618,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 278,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 281,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 263,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 236,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 805,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 268,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 264,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 410,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 265,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 543,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1619,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1620,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 409,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1622,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1623,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1624,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1625,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1626,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1627,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1628,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1629,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1630,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1631,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1632,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1633,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1635,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1636,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1637,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1638,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1639,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1640,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1642,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1643,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1644,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1645,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1646,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1648,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1650,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1651,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1652,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1653,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1654,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1655,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1656,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1657,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1658,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1660,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1661,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1662,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1663,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1665,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1666,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1667,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1668,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1669,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1670,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1671,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1672,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1673,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1674,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1675,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1676,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1677,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1678,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1679,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1680,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1681,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1682,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1683,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1684,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1685,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1686,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1687,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1688,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1689,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1690,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1691,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1692,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1693,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1694,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1695,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1696,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1697,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1698,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1699,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1700,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1701,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1702,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1703,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1704,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1705,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1706,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1707,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1708,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1709,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1710,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1711,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1712,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1713,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1714,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1715,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1716,
		"commerciale" => "Marzio Mini",
	),
	array(
		"id_info" => 1717,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1718,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1719,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1720,
		"commerciale" => "Rosa Barbieri",
	),
	array(
		"id_info" => 1721,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1722,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1723,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1724,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1725,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1726,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1727,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1728,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1729,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 1730,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1731,
		"commerciale" => "Paolo Panni",
	),
	array(
		"id_info" => 1732,
		"commerciale" => "Mirco Masi",
	),
	array(
		"id_info" => 1733,
		"commerciale" => "Andrea Dalla Corte",
	),
	array(
		"id_info" => 470,
		"commerciale" => "Marzio Mini",
	),
);


$commerciali_ia = User::withRole('commerciale')->where('name','!=','')->pluck('id', 'name')->toArray() + User::where('email','box@info-alberghi.com')->pluck('id', 'name')->toArray() ;

/*
commerciali_ia = array:6 [
  "Andrea Dalla Corte" => 2716
  "Paolo Panni" => 2717
  "Mirco Masi" => 2719
  "Rosa Barbieri" => 2848
  "Marzio Mini" => 3119
  "Andrea Sacchetti" => 2593
]

 */

	foreach ($commerciali_crm as $c) 
		{

		foreach ($commerciali_ia as $nome => $id_ia) 
			{
			
			if($c['commerciale'] == $nome)
				{
				//echo $c['commerciale'] ." id IA ". $id_ia;
				$hotel = Hotel::find($c['id_info']);
				if(!is_null($hotel))
					{
					$hotel->commerciale_id = $id_ia;
					$hotel->save();
					}

				}
		
			}
		}



    }
}
