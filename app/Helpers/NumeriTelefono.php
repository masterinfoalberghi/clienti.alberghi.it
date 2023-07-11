<?php


namespace App\Helpers;


class NumeriTelefono 
{
    protected static $prefixNationalNumbers = [
		"it" =>  [
			'004191',
			'010',
			'011',
			'0121',
			'0122',
			'0123',
			'0124',
			'0125',
			'0131',
			'0141',
			'0142',
			'0143',
			'0144',
			'015',
			'0161',
			'0163',
			'0165',
			'0166',
			'0171',
			'0172',
			'0173',
			'0174',
			'0175',
			'0182',
			'0183',
			'0184',
			'0185',
			'0187',
			'019',
			'02',
			'030',
			'031',
			'0321',
			'0322',
			'0323',
			'0324',
			'0331',
			'0332',
			'0341',
			'0342',
			'0343',
			'0344',
			'0345',
			'0346',
			'035',
			'0362',
			'0363',
			'0364',
			'0365',
			'0371',
			'0372',
			'0373',
			'0374',
			'0375',
			'0376',
			'0377',
			'0381',
			'0382',
			'0383',
			'0384',
			'0385',
			'0386',
			'039',
			'040',
			'041',
			'0421',
			'0422',
			'0423',
			'0424',
			'0425',
			'0426',
			'0427',
			'0428',
			'0429',
			'0431',
			'0432',
			'0433',
			'0434',
			'0435',
			'0436',
			'0437',
			'0438',
			'0439',
			'0442',
			'0444',
			'0445',
			'045',
			'0461',
			'0462',
			'0463',
			'0464',
			'0465',
			'0471',
			'0472',
			'0473',
			'0474',
			'0481',
			'049',
			'050',
			'051',
			'0521',
			'0522',
			'0523',
			'0524',
			'0525',
			'0532',
			'0533',
			'0534',
			'0535',
			'0536',
			'0541',
			'0542',
			'0543',
			'0544',
			'0545',
			'0546',
			'0547',
			'055',
			'0564',
			'0565',
			'0566',
			'0571',
			'0572',
			'0573',
			'0574',
			'0575',
			'0577',
			'0578',
			'0583',
			'0584',
			'0585',
			'0586',
			'0587',
			'0588',
			'059',
			'06',
			'070',
			'071',
			'0721',
			'0722',
			'0731',
			'0732',
			'0733',
			'0734',
			'0735',
			'0736',
			'0737',
			'0742',
			'0743',
			'0744',
			'0746',
			'075',
			'0761',
			'0763',
			'0765',
			'0766',
			'0771',
			'0773',
			'0774',
			'0775',
			'0776',
			'0781',
			'0782',
			'0783',
			'0784',
			'0785',
			'0789',
			'079',
			'080',
			'081',
			'0823',
			'0824',
			'0825',
			'0827',
			'0828',
			'0831',
			'0832',
			'0833',
			'0835',
			'0836',
			'085',
			'0861',
			'0862',
			'0863',
			'0864',
			'0865',
			'0871',
			'0872',
			'0873',
			'0874',
			'0875',
			'0881',
			'0882',
			'0883',
			'0884',
			'0885',
			'089',
			'090',
			'091',
			'0921',
			'0922',
			'0923',
			'0924',
			'0925',
			'0931',
			'0932',
			'0933',
			'0934',
			'0935',
			'0941',
			'0942',
			'095',
			'0961',
			'0962',
			'0963',
			'0964',
			'0965',
			'0966',
			'0967',
			'0968',
			'0971',
			'0972',
			'0973',
			'0974',
			'0975',
			'0976',
			'0981',
			'0982',
			'0983',
			'0984',
			'0985',
			'099',
			'800'
		]
	];

	protected static $prefixMobileNumbers = [
		"it" =>
		[
			'000',
			'320',
			'322',
			'323',
			'324',
			'327',
			'328',
			'329',
			'330',
			'331',
			'333',
			'334',
			'335',
			'336',
			'337',
			'338',
			'339',
			'340',
			'342',
			'344',
			'345',
			'346',
			'347',
			'348',
			'349',
			'350',
			'351',
			'352',
			'353',
			'354',
			'355',
			'356',
			'357',
			'358',
			'359',
			'360',
			'366',
			'368',
			'370',
			'371',
			'373',
			'375',
			'376',
			'377',
			'378',
			'379',
			'380',
			'382',
			'388',
			'389',
			'391',
			'392',
			'393',
			'390',
			'391',
			'392',
			'393',
			'397',
		]
	];

    public static function getArrayOfNumbers($phone)
    {
        $phone_array = [];

        if (str_contains($phone, "/")) {
            $arr = explode("/", $phone);
            foreach($arr as $pn) {
                $phone_array[] = trim($pn);
            }
        } else {
            $phone_array[] = trim($phone);
        }
        return $phone_array;
    }

    public static function formatNumber($nums, $number_type)
    {   
        // filtro i caratteri non numerici
        $nums = preg_replace('/[^0-9]/', '',  $nums);

        // elimino gli spazi
        $nums = preg_replace('/\s+/', '', $nums);
		
        return trim(Self::formatter($nums, $number_type));
    }

    private static function formatter($phone_number_unformat, $number_type) 
    {
        $stringCheck = [
			"mobile" => [3],
			"fisso" => [2, 3, 4, 6],
		];

		$prefixCheck = [
			"mobile" => Self::$prefixMobileNumbers['it'],
			"fisso" => Self::$prefixNationalNumbers['it']
		];

		$numberLength = [
			4 => 4,
			5 => 5,
			6 => 6,
			7 => 3,
			8 => 4,
			9 => 4
		];

		$check = NULL;
		$result = 0;
		$prefix_number = NULL;
		$phone_number = NULL;

		if (strlen($phone_number_unformat) >= 6 && strlen($phone_number_unformat) <= 13) {
			foreach ($stringCheck[$number_type] as $e) {

				$check = substr($phone_number_unformat, 0, $e);

				if (array_search($check, $prefixCheck[$number_type], true) != NULL) {

					$prefix_number = $check;

					$phone_number = substr($phone_number_unformat, $e);

					$pn_component_1 = substr($phone_number, 0, $numberLength[strlen($phone_number)]);
					$pn_component_2 = substr($phone_number, $numberLength[strlen($phone_number)]);

					$phone_number = $pn_component_1 . ' ' . $pn_component_2;

					$result = $prefix_number . ' ' . $phone_number;
				}
			}

			return $result;
        }
    }
}
