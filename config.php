<?php

	// CAUTION!!! Do NOT change below this line!!!
  	// Removing or changing the code below might eventually make your site STOP working!!!
  	
	// configuration file
	define('DEVELOPER', 'Contact USA Inc.');
	define('DEVELOPER_WEBSITE', 'qlWebScripts.com');
	define('DEVELOPER_URL', 'http://www.qlWebScripts.com');
	define('SCRIPT_FAMILY', 'qlWeb Internet Scripts');
	define('SCRIPT_GROUP', 'qlWeb');
	define('SCRIPT_NAME', 'Directory Script');
	define('SCRIPT_NAME_SHORT', 'DS');
	define('SCRIPT', 'qlWebDS');
	define('FULL_VERSION', 'qlWebDS Pro-6.6.0a-B-1DL-4/2025');
	define('VERSION', 'Pro');
	define('VERSION_NUMBER', '6.6.0a');
	define('SUB_VERSION', 'a 4/2025');
	define('COPYRIGHT', 'Copyright (c) Contact USA Inc. (http://www.qlWebScripts.com) All Rights Reserved.');
	define('YEARS', '2007-25');
	define('FOOT', '<br> &nbsp; Powered by: <a href="http://www.qlWebScripts.com" target="_blank" title="qlWebDS Pro-6.6.0a-B-1DL-4/2025 - Directory Script"><u>qlWebDS Pro</u></a>');
	define('THUMBNAIL_CODE_URL', 'http://open.thumbshots.org/image.aspx?url=');
	define('THUMBNAIL_BACKLINK', '&nbsp; | &nbsp;Thumbnails by: <a href="http://www.thumbshots.com" target="_blank" title="Thumbnails Previews by Thumbshots"><u>Thumbshots Thumbnails</u></a>');
	define('ALEXA_CODE_URL_TRAFFIC', 'http://xslt.alexa.com/site_stats/js/s/a?url=');
	define('ALEXA_CODE_URL_JAVA', 'http://xslt.alexa.com/site_stats/js/s/a?url=');
	define('ALEXA_CODE_URL_GRAPH', 'http://traffic.alexa.com/graph?w=268&amp;h=100&amp;r=6m&amp;z=&amp;y=r&amp;u={address}&amp;u=%20border=');
	define('WAYBACK_MACHINE_CODE_URL', 'http://web.archive.org/web/');
	define('LICENSE_TYPE', '1DL');
	define('BRAND_TYPE', 'B');

	global $developer, $developer_website, $developer_url, $script_family, $script_group, $script_name, $script_name_short, $script, $full_version, $version, $version_number, $sub_version, $copyright, $years, $foot, $thumbnail_code_url, $thumbnail_backlink, $alexa_code_url_traffic, $alexa_code_url_java, $alexa_code_url_graph, $wayback_machine_code_url, $license_type, $brand_type;

	// users
	define('AL_USER', 2);
	define('AL_ADMIN', 1);
	define('AL_NORMAL',0);

	define('USER_ACTIVE', 1);
	define('USER_INACTIVE', 0);

	define('USER_TABLE', 'users');
	define('USER_PASS_MD5', true);
	define('SESSION_LIFETIME', 3600);

	// link types
	define('LT_FREE', 1);
	define('LT_NORMAL', 2);
	define('LT_RECIP', 3);
	define('LT_FEAT', 4);
	define('LT_FEAT_TOP', 5);
	define('LT_ADD3', 6);
	define('LT_ADD5', 7); 
	define('LT_CUSTOM1', 8);
	define('LT_CUSTOM2', 9);

	define('LINK_TYPES', 9);

	// payment periods
	define('PP_1MONTHS', 1);
	define('PP_3MONTHS', 3);
	define('PP_6MONTHS', 6);
	define('PP_12MONTHS', 12);
	define('PP_UNLIMITED', 0);

	// date
	define('DATETIME_FORMAT', 'Y-m-d H:i:s');
	define('DATE_FORMAT', 'Y-m-d');

	// logo size
	define('MAX_LOGO_SIZE', 128000); // 125 kB

	// logos location
	define('LOGO_DIR', 'logos');

	// logo name suffix
	define('LOGO_NAME_SUFFIX', '_logo.gif');

	// listing visits from the same IP frequency in hours
	define('VISITS_BREAK', 1);

	// this is the time in **minutes** to consider someone online
	define('USERS_ONLINE_SESSION_TIME', 360);

	$g_featured = array(
		LT_FREE=>0, 
		LT_NORMAL=>0,
		LT_RECIP=>1,
		LT_FEAT_TOP=>1,
		LT_FEAT=>1,
		LT_ADD3=>1,
		LT_ADD5=>1,
		LT_CUSTOM1=>1,
		LT_CUSTOM2=>1
	);

	// currencies
	$g_currencies = array(
		array('value'=>'USD', 'name'=>'USD-U.S. Dollar'),
		array('value'=>'GBP', 'name'=>'GBP-British Pound'),
		array('value'=>'JPY', 'name'=>'JPY-Yen'),
		array('value'=>'EUR', 'name'=>'EUR-Euro'),
		array('value'=>'PLN', 'name'=>'PLN-Polish Zloty'),
		array('value'=>'CHF', 'name'=>'CHF-Swiss Franc'),
		array('value'=>'CAD', 'name'=>'CAD-Canadian Dollar'),
		array('value'=>'SGD', 'name'=>'SGD-Singapore Dollar'),
		array('value'=>'AUD', 'name'=>'AUD-Australian Dollar'),
		array('value'=>'HKD', 'name'=>'HKD-Hong Kong Dollar'),
		array('value'=>'NZD', 'name'=>'NZD-New Zealand Dollar'),
		array('value'=>'CZK', 'name'=>'CZK-Czech Koruna'),
		array('value'=>'DKK', 'name'=>'DKK-Danish Kroner'),
		array('value'=>'SEK', 'name'=>'SEK-Swedish Kroner'),
		array('value'=>'NOK', 'name'=>'NOK-Norwegian Kroner'),
		array('value'=>'HUF', 'name'=>'HUF-Hungarian Forint'),
		array('value'=>'ILS', 'name'=>'ILS-Israeli Shekel'),
		array('value'=>'MXN', 'name'=>'MXN-Mexican Peso'),
	);

	// countries
	$g_countries = array (
		array('value'=>'', 'name'=>'--- Select Your Country ---'),
		array('value'=>'USA', 'name'=>'United States'),
		array('value'=>'United Kingdom', 'name'=>'United Kingdom'),
		array('value'=>'Australia', 'name'=>'Australia'),
		array('value'=>'Canada', 'name'=>'Canada'),
		array('value'=>'France', 'name'=>'France'),
		array('value'=>'Germany', 'name'=>'Germany'),
		array('value'=>'Poland', 'name'=>'Poland'),
		array('value'=>'Portugal', 'name'=>'Portugal'),
		array('value'=>'Russia', 'name'=>'Russia'),
		array('value'=>'Spain', 'name'=>'Spain'),
		array('value'=>'', 'name'=>'--- Select Country ---'),
		array('value'=>'Afghanistan', 'name'=>'Afghanistan'),
		array('value'=>'Albania', 'name'=>'Albania'),
		array('value'=>'Algeria', 'name'=>'Algeria'),
		array('value'=>'American Samoa', 'name'=>'American Samoa'),
		array('value'=>'Andorra', 'name'=>'Andorra'),
		array('value'=>'Angola', 'name'=>'Angola'),
		array('value'=>'Anguilla', 'name'=>'Anguilla'),
		array('value'=>'Antarctica', 'name'=>'Antarctica'),
		array('value'=>'Antigua and Barbuda', 'name'=>'Antigua and Barbuda'),
		array('value'=>'Argentina', 'name'=>'Argentina'),
		array('value'=>'Armenia', 'name'=>'Armenia'),
		array('value'=>'Aruba', 'name'=>'Aruba'),
		array('value'=>'Australia', 'name'=>'Australia'),
		array('value'=>'Austria', 'name'=>'Austria'),
		array('value'=>'Azerbaijan', 'name'=>'Azerbaijan'),
		array('value'=>'Bahamas', 'name'=>'Bahamas'),
		array('value'=>'Bahrain', 'name'=>'Bahrain'),
		array('value'=>'Bangladesh', 'name'=>'Bangladesh'),
		array('value'=>'Barbados', 'name'=>'Barbados'),
		array('value'=>'Belarus', 'name'=>'Belarus'),
		array('value'=>'Belgium', 'name'=>'Belgium'),
		array('value'=>'Belize', 'name'=>'Belize'),
		array('value'=>'Benin', 'name'=>'Benin'),
		array('value'=>'Bermuda', 'name'=>'Bermuda'),
		array('value'=>'Bhutan', 'name'=>'Bhutan'),
		array('value'=>'Bolivia', 'name'=>'Bolivia'),
		array('value'=>'Bosnia and Herzegovina', 'name'=>'Bosnia and Herzegovina'),
		array('value'=>'Botswana', 'name'=>'Botswana'),
		array('value'=>'Bouvet Island', 'name'=>'Bouvet Island'),
		array('value'=>'Brazil', 'name'=>'Brazil'),
		array('value'=>'British Indian Ocean', 'name'=>'British Indian Ocean'),
		array('value'=>'Brunei', 'name'=>'Brunei'),
		array('value'=>'Bulgaria', 'name'=>'Bulgaria'),
		array('value'=>'Burkina Faso', 'name'=>'Burkina Faso'),
		array('value'=>'Burma', 'name'=>'Burma (Myanmar)'),
		array('value'=>'Burundi', 'name'=>'Burundi'),
		array('value'=>'Canada', 'name'=>'Canada'),
		array('value'=>'Cambodia', 'name'=>'Cambodia'),
		array('value'=>'Cameroon', 'name'=>'Cameroon'),
		array('value'=>'Cape Verde', 'name'=>'Cape Verde'),
		array('value'=>'Cayman Islands', 'name'=>'Cayman Islands'),
		array('value'=>'Central African Republic', 'name'=>'Central African Republic'),
		array('value'=>'Chad', 'name'=>'Chad'),
		array('value'=>'Jersey', 'name'=>'Jersey (Channel Islands)'),
		array('value'=>'Chile', 'name'=>'Chile'),
		array('value'=>'China', 'name'=>'China'),
		array('value'=>'Christmas Island', 'name'=>'Christmas Island'),
		array('value'=>'Cocos Islands', 'name'=>'Cocos (Keeling) Islands'),
		array('value'=>'Colombia', 'name'=>'Colombia'),
		array('value'=>'Comoros', 'name'=>'Comoros'),
		array('value'=>'Congo', 'name'=>'Congo'),
		array('value'=>'Democtratic Republic of the Congo', 'name'=>'Congo, Democtratic Republic of the'),
		array('value'=>'Cook Islands', 'name'=>'Cook Islands'),
		array('value'=>'Costa Rica', 'name'=>'Costa Rica'),
		array('value'=>'Croatia', 'name'=>'Croatia'),
		array('value'=>'Cuba', 'name'=>'Cuba'),
		array('value'=>'Cyprus', 'name'=>'Cyprus'),
		array('value'=>'Czech Republic', 'name'=>'Czech Republic'),
		array('value'=>'Denmark', 'name'=>'Denmark'),
		array('value'=>'Djibouti', 'name'=>'Djibouti'),
		array('value'=>'Dominica', 'name'=>'Dominica'),
		array('value'=>'Dominican Republic', 'name'=>'Dominican Republic'),
		array('value'=>'Ecuador', 'name'=>'Ecuador'),
		array('value'=>'Egypt', 'name'=>'Egypt'),
		array('value'=>'El Salvador', 'name'=>'El Salvador'),
		array('value'=>'Equatorial Guinea', 'name'=>'Equatorial Guinea'),
		array('value'=>'Eritrea', 'name'=>'Eritrea'),
		array('value'=>'Estonia', 'name'=>'Estonia'),
		array('value'=>'Ethiopia', 'name'=>'Ethiopia'),
		array('value'=>'Falkland Islands', 'name'=>'Falkland Islands'),
		array('value'=>'Faroe Islands', 'name'=>'Faroe Islands'),
		array('value'=>'Fiji', 'name'=>'Fiji'),
		array('value'=>'Finland', 'name'=>'Finland'),
		array('value'=>'France', 'name'=>'France'),
		array('value'=>'French Guiana', 'name'=>'French Guiana'),
		array('value'=>'French Polynesia', 'name'=>'French Polynesia'),
		array('value'=>'French Southern Territories', 'name'=>' Southern Territories'),
		array('value'=>'Gabon', 'name'=>'Gabon'),
		array('value'=>'Gambia', 'name'=>'Gambia'),
		array('value'=>'Georgia', 'name'=>'Georgia'),
		array('value'=>'Germany', 'name'=>'Germany'),
		array('value'=>'Ghana', 'name'=>'Ghana'),
		array('value'=>'Gibraltar', 'name'=>'Gibraltar'),
		array('value'=>'Greece', 'name'=>'Greece'),
		array('value'=>'Greenland', 'name'=>'Greenland'),
		array('value'=>'Grenada', 'name'=>'Grenada'),
		array('value'=>'Guadeloupe', 'name'=>'Guadeloupe'),
		array('value'=>'Guam', 'name'=>'Guam'),
		array('value'=>'Guatemala', 'name'=>'Guatemala'),
		array('value'=>'Guinea', 'name'=>'Guinea'),
		array('value'=>'Guinea-Bissau', 'name'=>'Guinea-Bissau'),
		array('value'=>'Guyana', 'name'=>'Guyana'),
		array('value'=>'Haiti', 'name'=>'Haiti'),
		array('value'=>'Heard and Mcdonald Islands', 'name'=>'Heard and Mcdonald Islands'),
		array('value'=>'Honduras', 'name'=>'Honduras'),
		array('value'=>'Hong Kong', 'name'=>'Hong Kong'),
		array('value'=>'Hungary', 'name'=>'Hungary'),
		array('value'=>'Iceland', 'name'=>'Iceland'),
		array('value'=>'India', 'name'=>'India'),
		array('value'=>'Indonesia', 'name'=>'Indonesia'),
		array('value'=>'Iran', 'name'=>'Iran'),
		array('value'=>'Iraq', 'name'=>'Iraq'),
		array('value'=>'Ireland', 'name'=>'Ireland'),
		array('value'=>'Isle of Man', 'name'=>'Isle of Man'),
		array('value'=>'Israel', 'name'=>'Israel'),
		array('value'=>'Italy', 'name'=>'Italy'),
		array('value'=>'Ivory Coast', 'name'=>'Ivory Coast'),
		array('value'=>'Jamaica', 'name'=>'Jamaica'),
		array('value'=>'Japan', 'name'=>'Japan'),
		array('value'=>'Johnston Atoll', 'name'=>'Johnston Atoll'),
		array('value'=>'Jordan', 'name'=>'Jordan'),
		array('value'=>'Kenya', 'name'=>'Kenya'),
		array('value'=>'Khazakstan', 'name'=>'Khazakstan'),
		array('value'=>'Kiribati', 'name'=>'Kiribati'),
		array('value'=>'North Korea', 'name'=>'Korea, North'),
		array('value'=>'South Korea', 'name'=>'Korea, South'),
		array('value'=>'Kosovo', 'name'=>'Kosovo'),
		array('value'=>'Kuwait', 'name'=>'Kuwait'),
		array('value'=>'Kyrgyzstan', 'name'=>'Kyrgyzstan'),
		array('value'=>'Laos', 'name'=>'Laos'),
		array('value'=>'Latvia', 'name'=>'Latvia'),
		array('value'=>'Lebanon', 'name'=>'Lebanon'),
		array('value'=>'Lesotho', 'name'=>'Lesotho'),
		array('value'=>'Liberia', 'name'=>'Liberia'),
		array('value'=>'Libya', 'name'=>'Libya'),
		array('value'=>'Liechtenstein', 'name'=>'Liechtenstein'),
		array('value'=>'Lithuania', 'name'=>'Lithuania'),
		array('value'=>'Luxembourg', 'name'=>'Luxembourg'),
		array('value'=>'Macao', 'name'=>'Macao'),
		array('value'=>'Macedonia', 'name'=>'Macedonia'),
		array('value'=>'Madagascar', 'name'=>'Madagascar'),
		array('value'=>'Malawi', 'name'=>'Malawi'),
		array('value'=>'Malaysia', 'name'=>'Malaysia'),
		array('value'=>'Maldives', 'name'=>'Maldives'),
		array('value'=>'Mali', 'name'=>'Mali'),
		array('value'=>'Malta', 'name'=>'Malta'),
		array('value'=>'Marshall Islands', 'name'=>'Marshall Islands'),
		array('value'=>'Martinique', 'name'=>'Martinique'),
		array('value'=>'Mauritania', 'name'=>'Mauritania'),
		array('value'=>'Mauritius', 'name'=>'Mauritius'),
		array('value'=>'Mayotte', 'name'=>'Mayotte'),
		array('value'=>'Mexico', 'name'=>'Mexico'),
		array('value'=>'Micronesia', 'name'=>'Micronesia'),
		array('value'=>'Moldova', 'name'=>'Moldova'),
		array('value'=>'Monaco', 'name'=>'Monaco'),
		array('value'=>'Mongolia', 'name'=>'Mongolia'),
		array('value'=>'Montenegro', 'name'=>'Montenegro'),
		array('value'=>'Montserrat', 'name'=>'Montserrat'),
		array('value'=>'Morocco', 'name'=>'Morocco'),
		array('value'=>'Mozambique', 'name'=>'Mozambique'),
		array('value'=>'Namibia', 'name'=>'Namibia'),
		array('value'=>'Nauru', 'name'=>'Nauru'),
		array('value'=>'Navassa Island', 'name'=>'Navassa Island'),
		array('value'=>'Nepal', 'name'=>'Nepal'),
		array('value'=>'Netherlands', 'name'=>'Netherlands (Holland)'),
		array('value'=>'Netherlands Antilles', 'name'=>'Netherlands Antilles'),
		array('value'=>'New Caledonia', 'name'=>'New Caledonia'),
		array('value'=>'New Zealand', 'name'=>'New Zealand'),
		array('value'=>'Nicaragua', 'name'=>'Nicaragua'),
		array('value'=>'Niger', 'name'=>'Niger'),
		array('value'=>'Nigeria', 'name'=>'Nigeria'),
		array('value'=>'Niue', 'name'=>'Niue'),
		array('value'=>'Norfolk Island', 'name'=>'Norfolk Island'),
		array('value'=>'Northern Mariana Islands', 'name'=>'Northern Mariana Islands'),
		array('value'=>'Norway', 'name'=>'Norway'),
		array('value'=>'Oman', 'name'=>'Oman'),
		array('value'=>'Pakistan', 'name'=>'Pakistan'),
		array('value'=>'Palau', 'name'=>'Palau'),
		array('value'=>'Palestine', 'name'=>'Palestine'),
		array('value'=>'Panama', 'name'=>'Panama'),
		array('value'=>'Papua New Guinea', 'name'=>'Papua New Guinea'),
		array('value'=>'Paraguay', 'name'=>'Paraguay'),
		array('value'=>'Peru', 'name'=>'Peru'),
		array('value'=>'Philippines', 'name'=>'Philippines'),
		array('value'=>'Pitcairn Islands', 'name'=>'Pitcairn Islands'),
		array('value'=>'Poland', 'name'=>'Poland'),
		array('value'=>'Portugal', 'name'=>'Portugal'),
		array('value'=>'Puerto Rico', 'name'=>'Puerto Rico'),
		array('value'=>'Qatar', 'name'=>'Qatar'),
		array('value'=>'Reunion', 'name'=>'Reunion'),
		array('value'=>'Romania', 'name'=>'Romania'),
		array('value'=>'Russia', 'name'=>'Russia'),
		array('value'=>'Rwanda', 'name'=>'Rwanda'),
		array('value'=>'San Marino', 'name'=>'San Marino'),
		array('value'=>'Sao Tome and Principe', 'name'=>'Sao Tome and Principe'),
		array('value'=>'Saudi Arabia', 'name'=>'Saudi Arabia'),
		array('value'=>'Senegal', 'name'=>'Senegal'),
		array('value'=>'Serbia', 'name'=>'Serbia'),
		array('value'=>'Seychelles', 'name'=>'Seychelles'),
		array('value'=>'Sierra Leone', 'name'=>'Sierra Leone'),
		array('value'=>'Singapore', 'name'=>'Singapore'),
		array('value'=>'Slovakia', 'name'=>'Slovakis'),
		array('value'=>'Slovenia', 'name'=>'Slovenia'),
		array('value'=>'Solomon Islands', 'name'=>'Solomon Islands'),
		array('value'=>'Somalia', 'name'=>'Somalia'),
		array('value'=>'South Africa', 'name'=>'South Africa'),
		array('value'=>'Spain', 'name'=>'Spain'),
		array('value'=>'Sri Lanka', 'name'=>'Sri Lanka'),
		array('value'=>'Saint Helena', 'name'=>'Saint Helena'),
		array('value'=>'Saint Kitts and Nevis', 'name'=>'Saint Kitts and Nevis'),
		array('value'=>'Saint Lucia', 'name'=>'Saint Lucia'),
		array('value'=>'Saint Pierre and Miquelon', 'name'=>'Saint Pierre and Miquelon'),
		array('value'=>'Saint Vincent', 'name'=>'Saint Vincent'),
		array('value'=>'Sudan', 'name'=>'Sudan'),
		array('value'=>'South Sudan', 'name'=>'Sudan, South'),
		array('value'=>'Suriname', 'name'=>'Suriname'),
		array('value'=>'Svalbard and Jan Mayen', 'name'=>'Svalbard and Jan Mayen'),
		array('value'=>'Swaziland', 'name'=>'Swaziland'),
		array('value'=>'Sweden', 'name'=>'Sweden'),
		array('value'=>'Switzerland', 'name'=>'Switzerland'),
		array('value'=>'Syria', 'name'=>'Syria'),
		array('value'=>'Taiwan', 'name'=>'Taiwan'),
		array('value'=>'Tajikistan', 'name'=>'Tajikistan'),
		array('value'=>'Tanzania', 'name'=>'Tanzania'),
		array('value'=>'Thailand', 'name'=>'Thailand'),
		array('value'=>'Tibet', 'name'=>'Tibet'),
		array('value'=>'Timor-Leste', 'name'=>'Timor-Leste'),
		array('value'=>'Togo', 'name'=>'Togo'),
		array('value'=>'Tokelau', 'name'=>'Tokelau'),
		array('value'=>'Tonga', 'name'=>'Tonga'),
		array('value'=>'Trinidad and Tobago', 'name'=>'Trinidad and Tobago'),
		array('value'=>'Tunisia', 'name'=>'Tunisia'),
		array('value'=>'Turkey', 'name'=>'Turkey'),
		array('value'=>'Turkmenistan', 'name'=>'Turkmenistan'),
		array('value'=>'Turks and Caicos Islands', 'name'=>'Turks and Caicos Islands'),
		array('value'=>'Tuvalu', 'name'=>'Tuvalu'),
		array('value'=>'Uganda', 'name'=>'Uganda'),
		array('value'=>'Ukraine', 'name'=>'Ukraine'),
		array('value'=>'United Arab Emirates', 'name'=>'United Arab Emirates'),
		array('value'=>'United Kingdom', 'name'=>'United Kingdom'),
		array('value'=>'USA', 'name'=>'United States'),
		array('value'=>'Uruguay', 'name'=>'Uruguay'),
		array('value'=>'Uzbekistan', 'name'=>'Uzbekistan'),
		array('value'=>'Vanuatu', 'name'=>'Vanuatu'),
		array('value'=>'Vatican City', 'name'=>'Vatican City (Holy See)'),
		array('value'=>'Venezuela', 'name'=>'Venezuela'),
		array('value'=>'Vietnam', 'name'=>'Vietnam'),
		array('value'=>'British Virgin Islands', 'name'=>'Virgin Islands, British'),
		array('value'=>'U.S. Virgin Islands', 'name'=>'Virgin Islands, U.S.'),
		array('value'=>'Wallis and Futuna', 'name'=>'Wallis and Futuna'),
		array('value'=>'Western Sahara', 'name'=>'Western Sahara'),
		array('value'=>'Samoa', 'name'=>'Samoa'),
		array('value'=>'Yemen', 'name'=>'Yemen'),
		array('value'=>'Zambia', 'name'=>'Zambia'),
		array('value'=>'Zimbabwe', 'name'=>'Zimbabwe'),
	);

?>
