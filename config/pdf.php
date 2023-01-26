<?php

return [
	'mode'                  => 'utf-8',
	'format'                => 'A4',
	'author'                => '',
	'subject'               => '',
	'keywords'              => '',
	'creator'               => 'Laravel Pdf',
	'display_mode'          => 'fullpage',
	'tempDir'               => base_path('../temp/'),
	'pdf_a'                 => false,
	'pdf_a_auto'            => false,
	'icc_profile_path'      => '',
    // 'font_path' => base_path('resources/fonts/'),
	// 'font_data' => [
	// 	'cairo' => [
	// 		'R'  => 'Cairo-Regular.ttf',    // regular font
	// 		'B'  => 'Cairo-Bold.ttf',       // optional: bold font
	// 		'useOTL' => 0xFF,    // required for complicated langs like Persian, Arabic and Chinese
	// 		'useKashida' => 75,  // required for complicated langs like Persian, Arabic and Chinese
    //     ],
    //     'lateef' => [
	// 		'R'  => 'Lateef-Regular.ttf',    // regular font
	// 		'B'  => 'Lateef-Bold.ttf',       // optional: bold font
	// 		'useOTL' => 0xFF,    // required for complicated langs like Persian, Arabic and Chinese
	// 		'useKashida' => 75,  // required for complicated langs like Persian, Arabic and Chinese
	// 	]
		// ...add as many as you want.
        // ['useOTL'] => 0xFF,	Enable use of OTL features.
        // ['useKashida'] => 75,	Enable use of kashida for text justification in Arabic text
	// ]

];
