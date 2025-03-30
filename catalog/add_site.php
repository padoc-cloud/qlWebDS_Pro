<?php

$tmp_tpl        = new TemplateClass();
$tpl_adds_array = [];

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Lines 11 and 12 below will show up below on the the Link Being Submitted Page.
// Enter your your Message in: templates/YourTemplate/message_step1.tpl.php
// Useful when advertising more services or directories on a free and a high traffic submission Directory
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$g_template->SetTemplate(DIR_TEMPLATE . 'message_step1.tpl.php');
$tpl_adds_array['{message_step1}'] = $g_template->Get();

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Lines 19 and 20 below will show up above the Link Selection on the Submit Page
// when you enter your terms in: templates/YourTemplate/message_step2.tpl.php
// Useful, when offering free links. It is recommended having your Meta Fetch off when accepting free links
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$g_template->SetTemplate(DIR_TEMPLATE . 'message_step2.tpl.php');
$tpl_adds_array['{message_step2}'] = $g_template->Get();

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Lines 27 and 28 below will show up below the Link Submitted Confirmation Page
// when you enter your your Message in: templates/YourTemplate/message_success.tpl.php
// Useful when advertising more services or directories on a free and a high traffic exit page
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$g_template->SetTemplate(DIR_TEMPLATE . 'message_success.tpl.php');
$tpl_adds_array['{message_success}'] = $g_template->Get();

$isError = false;
$eMsg    = '<font color="#CC3300"><u>Please CORRECT the following Errors and Re-submit:</u></font>';

$site_added             = false;
$max_connection_reached = false;
$logo_msg               = false;
$video_msg              = false;

$link_type = 0;
$step2     = false;

$if_region['ispayment']      = 0;
$if_region['site_new']       = 0;
$if_region['site_old_added'] = 0;

$tpl_adds_array['{error}'] = '';

$tpl_adds_array['{reciprocal_display}']           = 'display: none;';
$tpl_adds_array['{video_display}']                = 'display: none;';
$tpl_adds_array['{logo_display}']                 = 'display: none;';
$tpl_adds_array['{second submit button display}'] = '';

$tpl_adds_array['{url readonly}']   = '';
$tpl_adds_array['{email readonly}'] = '';

$values = ['title' => '', 'title1'              => '', 'title2'       => '', 'title3'      => '', 'title4'      => '', 'title5'  => '', 'url'     => '', 'url1' => '',
    'url2'                  => '', 'url3'                => '', 'url4'         => '', 'url5'        => '', 'email'       => '', 'company' => '', 'product' => '',
    'address'               => '', 'city'                => '', 'state'        => '', 'zip'         => '', 'country'     => '', 'tel'     => '', 'fax'     => '',
    'description_short'     => '', 'description'         => '', 'facebook_url' => '', 'twitter_url' => '', 'youtube_url' => '',
    'embedded_video_title'  => '', 'embedded_video_code' => '',
    'reciprocal'            => '', 'keywords'            => '', 'link_type'    => '1', 'note'       => '', 'id_code'     => '',
];

// submit guidelines URL
$tpl_adds_array['{submit_guidelines}'] = '';
$page_submit_guidelines                = $g_params->Get('pages', 'page_submit_guidelines');
if ($page_submit_guidelines != 0) {
    $page_name = $g_params->Get('pages', 'page_name_' . $page_submit_guidelines);
    if (MOD_REWRITE) {
        $tpl_adds_array['{submit_guidelines}'] = $page_name . '.html';
    } else {
        $tpl_adds_array['{submit_guidelines}'] = 'index.php?page=' . $page_name;
    }
}

$if_region['pay_and_step1_2'] = 1;
$if_region['pay_and_step2']   = 1;

if ($listing_paid) {
    if (! isset($_POST['submit_step2'])) {
        $paid_site               = $g_site->GetSiteById($p_id);
        $values['company']       = $paid_site['company'];
        $values['email']         = $paid_site['email'];
        $values['url']           = $paid_site['url'];
        $link_type               = $paid_site['link_type'];
        $_POST['submit_step1_2'] = true;
    }
    $tpl_adds_array['{url readonly}']   = 'readonly="readonly"';
    $tpl_adds_array['{email readonly}'] = 'readonly="readonly"';
    $_GET['adds']                       = $_GET['cat_id'];
}

$sub_id   = (int) $_GET['adds'];
$category = $g_categ->GetCategory($sub_id);

// check category level
if (! $category or (! LINK_ON_TOP_CATEG and $category['level'] < 2) or $category['level'] > 4) {
    exit;
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// first check if site is already in directory
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if (isset($_POST['company']) and ! $listing_paid) {

    $values['url'] = $g_site->ParseURL($_POST['url']);
    if ($values['url']) {

        // check if site is already in directory
        $site_row = $g_site->GetSiteByUrl($values['url']);
    } else {
        $company = trim($_POST['company']);
        if (strlen($company) == 0) {
            $values['company'] = false;
        } else {
            $values['company'] = $company;
        }
        if ($values['company']) {

            // check if listing is already in directory
            $site_row = $g_site->GetListingByCompanyUrl($values['company'], $values['url']);
        }
    }
    if (is_array($site_row)) {

        $site_added = true;

        if ($category['level'] == 1 and ($site_row['link_type'] != LT_FEAT_TOP)) {
            $isError = true;
            $eMsg    = LANG_SITE_CANT_BE_IN_TOP;

        } else {

            // connect with category
            if ($sub_id) {
                if ($g_site->AddConnection($site_row['id'], $sub_id)) {
                    $site_added = true;
                } else {
                    $max_connection_reached = true;
                }
            }

        }

    }

}

if (! $site_added and isset($_POST['submit_step1'])) {

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // checking POST data from step 1
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $step2 = true;

    // check URL
    // check company
    $company = trim($_POST['company']);
    if (strlen($company) == 0) {
        $eMsg .= '<p>' . ADD_SITE_01 . '</p>';
        $isError = true;
    } else {
        $values['company'] = $company;
    }

    // check URL (if not empty)
    if (strlen(trim($_POST['url'])) != 0) {
        $values['url'] = $g_site->ParseURL($_POST['url']);
        if ($values['url']) {

        } else {
            $eMsg .= '<p>' . ADD_SITE_05 . '</p>';
            $isError = true;
        }
    }

    // check e-mail
    $values['email'] = $g_site->CheckEmail($_POST['email']);
    if ($values['email']) {

    } else {
        $values['email'] = '';
        $eMsg .= '<p>' . ADD_SITE_03 . '</p>';
        $isError = true;
    }

    // if captcha refresh
    if (isset($_POST['title']) or isset($_POST['description'])) {
        $values['title']       = $_POST['title'];
        $values['description'] = $_POST['description'];
        $values['keywords']    = $_POST['keywords'];

    } else if (! $isError and COLLECT_META and ! PAY_BEFORE_SUBMIT) {

        // get headers
        if (strlen(trim($_POST['url'])) != 0) {
            $header = $g_site->GetHeader($values['url']);
            $values = array_merge($values, $header);
        }
    }
} else if ($listing_paid and isset($_POST['submit_step1_2'])) {
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // listing paid do nothing in this part of the code
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////

} else if (! $site_added and (isset($_POST['submit_step1_2']) or isset($_POST['captcha_refresh1_2']))) {
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // check POST data from step 1_2
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    foreach ($values as $key => $value) {
        switch ($key) {

            case 'url':
            case 'email':
            case 'company':
                $values[$key] = $_POST[$key];
                break;

            case 'link_type':
                if (isset($_POST[$key])) {
                    $values[$key] = (int) $_POST[$key];
                    $link_type    = $values[$key];
                }

                // check if checked payment is avaiable
                if (! isset($g_payment["ctype[$link_type]"])) {
                    $eMsg .= '<p>' . ADD_SITE_06 . '</p>';
                    $isError = true;
                }

                if ($category['level'] == 1 and $link_type != LT_FEAT_TOP) {
                    $eMsg .= '<p>' . ADD_SITE_06 . '</p>';
                    $isError = true;
                }
                break;

        } // end switch

    } // end for

    if (isset($_POST['captcha_refresh1_2'])) {
        $isError                 = true;
        $eMsg                    = '';
        $_POST['submit_step1_2'] = true;
    } else {

        // check captcha
        if (USE_CAPTCHA) {
            if (! session_id()) {
                session_start();
            }

            $sid     = session_id();
            $captcha = new CaptchaClass();
            if ($captcha->CheckCaptcha($sid, $_POST['captcha'])) {

            } else {
                $eMsg .= '<p>' . ADD_SITE_04 . '</p>';
                $isError = true;
            }
        }
    }

    if (PAY_BEFORE_SUBMIT and COLLECT_META) {
        // get headers
        if (strlen(trim($_POST['url'])) != 0) {
            $header = $g_site->GetHeader($values['url']);
            $values = array_merge($values, $header);
        }
    }

} else if (! $site_added and (isset($_POST['submit_step2']) or isset($_POST['captcha_refresh']))) {

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // check POST data from step 2
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    if (isset($_POST['link_type'])) {
        $link_type = (int) $_POST['link_type'];
    }

    foreach ($values as $key => $value) {
        switch ($key) {

            case 'url1':
            case 'url2':
            case 'url3':
                if (($link_type === LT_ADD5) || ($link_type === LT_ADD3)) {
                    $values[$key] = $g_site->ParseURL($_POST[$key]);
                    if (! $values[$key]) {
                        // $eMsg .= '<p>'.ADD_SITE_02.' '.$key.'</p>';
                        // $isError = true;
                    }
                } else { $values[$key] = '';}
                break;

            case 'title1':
            case 'title2':
            case 'title3':
                if (($link_type === LT_ADD5) || ($link_type === LT_ADD3)) {
                    $values[$key] = trim($_POST[$key]);

                    // check title lenght
                    $len = strlen($values[$key]);
                    if (($len < 3) or ($len > MAX_TITLE_LENGHT)) {
                        // $eMsg .= '<p>'.LANG_ERROR_TITLE_LENGHT.' '.$key.'</p>';
                        // $isError = true;
                    }

                } else { $values[$key] = '';}
                break;

            case 'url4':
            case 'url5':
                if ($link_type === LT_ADD5) {
                    $values[$key] = $g_site->ParseURL(trim($_POST[$key]));
                    if (! $values[$key]) {
                        // $eMsg .= '<p>'.ADD_SITE_02.' '.$key.'</p>';
                        // $isError = true;
                    }
                } else { $values[$key] = '';}
                break;

            case 'title4':
            case 'title5':
                if ($link_type === LT_ADD5) {
                    $values[$key] = trim($_POST[$key]);

                    // check title lenght
                    $len = strlen($values[$key]);
                    if (($len < 3) or ($len > MAX_TITLE_LENGHT)) {
                        // $eMsg .= '<p>'.LANG_ERROR_TITLE_LENGHT.' '.$key.'</p>';
                        // $isError = true;
                    }
                } else { $values[$key] = '';}
                break;

            case 'url':
                if (strlen(trim($_POST[$key])) != 0) {
                    $values[$key] = $g_site->ParseURL(trim($_POST[$key]));
                    if (! $values[$key]) {
                        $eMsg .= '<p>' . ADD_SITE_02 . ' ' . $key . '</p>';
                        $isError = true;
                    }
                }
                break;

            case 'title':
                $values[$key] = trim($_POST[$key]);
                $values[$key] = str_replace("  ", " ", $values[$key]);
                $values[$key] = str_replace(",", "", $values[$key]);
                $values[$key] = str_replace(".", "", $values[$key]);
                // check title lenght
                $len = strlen($values[$key]);
                if (($len < 3) or ($len > MAX_TITLE_LENGHT)) {
                    $eMsg .= '<p>' . LANG_ERROR_TITLE_LENGHT . '</p>';
                    $isError = true;
                }
                break;

            case 'description_short':
                $values[$key] = trim($_POST[$key]);
                $values[$key] = str_replace("  ", " ", $values[$key]);
                // check short description lenght
                $len = strlen($values[$key]);
                if (($len < MIN_DESCR_SHORT_LENGHT) or ($len > MAX_DESCR_SHORT_LENGHT)) {

                    $eMsg .= '<p>' . LANG_ERROR_DESCR_SHORT_LENGHT . '</p>';
                    $isError = true;
                }
                break;

            case 'description':
                $values[$key] = trim($_POST[$key]);
                $values[$key] = str_replace("  ", " ", $values[$key]);
                // check description lenght
                $len = strlen($values[$key]);
                if (($len < MIN_DESCR_LENGHT) or ($len > MAX_DESCR_LENGHT)) {

                    $eMsg .= '<p>' . LANG_ERROR_DESCR_LENGHT . '</p>';
                    $isError = true;
                }
                break;

            case 'facebook_url':
                $values[$key] = trim($_POST[$key]);
                break;

            case 'twitter_url':
                $values[$key] = trim($_POST[$key]);
                break;

            case 'youtube_url':
                $values[$key] = trim($_POST[$key]);
                break;

            case 'embedded_video_title':
                $values[$key] = trim($_POST[$key]);
                break;

            case 'embedded_video_code':
                $values[$key] = trim($_POST[$key]);
                break;

            case 'keywords':
                $values[$key] = trim($_POST[$key]);
                $values[$key] = str_replace("  ", " ", $values[$key]);
                $values[$key] = str_replace(", ", ",", $values[$key]);
                $values[$key] = str_replace(" , ", ",", $values[$key]);
                $values[$key] = str_replace(" ,", ",", $values[$key]);
                if (strlen($values[$key]) > 58) {
                    $values[$key] = substr($values[$key], 0, 58);
                    if (substr($values[$key], 57) != ' ') {
                        $last_comma_pos = strrpos($values[$key], ",");
                        if ($last_comma_pos) {
                            $values[$key] = substr($values[$key], 0, $last_comma_pos);
                        }
                    }
                }
                break;

            case 'email':
                // check e-mail
                $values['email'] = $g_site->CheckEmail($_POST['email']);
                if ($values['email']) {

                } else {
                    $eMsg .= '<p>' . ADD_SITE_03 . '</p>';
                    $isError = true;
                }
                break;

            case 'company':
                if (strlen(trim($_POST[$key])) == 0) {
                    $eMsg .= '<p>' . ADD_SITE_01 . '</p>';
                    $isError      = true;
                    $values[$key] = false;
                } else {
                    $values[$key] = $_POST[$key];
                }
                break;

            case 'tel':
                $values['tel_digits'] = preg_replace("/[^0-9]/", "", $_POST[$key]);
                $values[$key]         = $_POST[$key];
                break;

            case 'product':
            case 'address':
            case 'city':
            case 'state':
            case 'zip':
            case 'country':
            case 'fax':
                $values[$key] = $_POST[$key];
                break;

            case 'link_type':
                if (isset($_POST[$key])) {
                    $values[$key] = (int) $_POST[$key];
                    $link_type    = $values[$key];
                }

                // check if checked payment is avaiable
                if (! isset($g_payment["ctype[$link_type]"])) {

                    $eMsg .= '<p>' . ADD_SITE_06 . '</p>';
                    $isError = true;

                }

                if ($category['level'] == 1 and $link_type != LT_FEAT_TOP) {

                    $eMsg .= '<p>' . ADD_SITE_06 . '</p>';
                    $isError = true;

                }
                break;

            case 'reciprocal':
                // check if reciprocal is needed
                if ($g_payment["crecip[$link_type]"] == 1) {
                    if (! $g_site->CheckRecip($_POST[$key], $g_payment['url'])) {
                        $eMsg .= '<p>' . ADD_SITE_07 . '</p>';
                        $isError = true;
                    } else {
                        $values[$key] = $_POST[$key];
                    }
                }
                break;

            default:
                $values[$key] = trim($_POST[$key]);
                break;

        } // end switch

    } // end for

    $logo_filename = '';
    $max_logo_size = MAX_LOGO_SIZE; // 125 kb

    if (! $isError) {
        if (isset($_FILES) and strlen($_FILES['logo_upload']['name']) > 0) {
            if ($_FILES['logo_upload']['size'] <= $max_logo_size) {

                $cUpload = new UploadClass(LOGO_DIR, 'logo_upload', 1, ['gif', 'jpg', 'jpeg', 'png']);

                $logo_filename = str_replace(" ", "_", $values['company']) . LOGO_NAME_SUFFIX;

                $logo_filename = $cUpload->UploadPhoto($logo_filename, true);

                if ($logo_filename) {
                    $logo_filename = strtolower($logo_filename);

                    if ($cUpload->ResizeImg(LOGO_DIR . '/' . $logo_filename, LOGO_DIR . '/' . $logo_filename, 468, 60)) {
                        // image resized
                    } else {
                        $eMsg .= '<p>' . ADD_SITE_11 . '</p>';
                        $isError = true;
                    }

                } else {
                    $eMsg .= '<p>' . $cUpload->eName . '</p>';
                    $isError = true;
                }

            } else {
                $eMsg .= '<p>' . ADD_SITE_10 . '</p>';
                $isError = true;
            }

            // something went wrong... clean up
            if ($isError) {
                if (! empty($logo_filename) and file_exists(LOGO_DIR . "/" . $logo_filename)) {
                    unlink(LOGO_DIR . "/" . $logo_filename);
                }
                $logo_filename = '';
            }
        }
    } else {
        if (isset($_FILES) and strlen($_FILES['logo_upload']['name']) > 0) {
            $eMsg .= '<p>' . ADD_SITE_12 . '</p>';
            $logo_msg = true;
        }

    }

    if (isset($_POST['captcha_refresh'])) {
        $isError               = true;
        $_POST['submit_step2'] = true;
        $eMsg                  = '';
        if (isset($_FILES) and strlen($_FILES['logo_upload']['name']) > 0 and ! $logo_msg) {
            $eMsg .= '<p>' . ADD_SITE_12 . '</p>';
        }
    } else {

        // check link type
        if ($link_type == 0) {$isError = true;}

        // check reciprocal
        if ($g_payment["crecip[$link_type]"] == 1 and ! isset($values['reciprocal'])) {
            $eMsg .= '<p>' . ADD_SITE_07 . '</p>';
            $isError = true;
        }

        // check captcha
        if (! PAY_BEFORE_SUBMIT) {

            if (USE_CAPTCHA) {
                if (! session_id()) {
                    session_start();
                }

                $sid     = session_id();
                $captcha = new CaptchaClass();
                if ($captcha->CheckCaptcha($sid, $_POST['captcha'])) {

                } else {
                    $eMsg .= '<p>' . ADD_SITE_04 . '</p>';
                    $isError = true;
                    if (isset($_FILES) and strlen($_FILES['logo_upload']['name']) > 0 and ! $logo_msg) {
                        $eMsg .= '<p>' . ADD_SITE_12 . '</p>';
                    }
                }
            }
        }

        if (isset($_POST['submit_step2'])) {
            // check filter
            if ($banned_word = $g_site->CheckWords($values['title'], $values['description_short'], $values['description'], $values['embedded_video_title'], $values['keywords'], $values['url'], $g_params->GetParams('banned_words'))) {
                $eMsg .= '<p>' . ADD_SITE_08 . ': ' . $banned_word . '</p>';
                $isError = true;
            }

            // check IP
            if ($banned_word = $g_site->CheckIp($_SERVER['REMOTE_ADDR'], $g_params->GetParams('banned_ips'))) {
                $eMsg .= '<p>' . ADD_SITE_09 . '</p>';
                $isError = true;
            }
        }
    }

} else if (! $site_added) {

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // POST data, step 1
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $tpl_adds_array['{company value}'] = '';
    $tpl_adds_array['{url value}']     = '';
    $tpl_adds_array['{email value}']   = '';

}

$tpl_adds_array['{address}'] = $g_addr;

//////////////////////////////////////////////////////////
// fill array with language values (form language)
//////////////////////////////////////////////////////////
$payment_period = $g_params->Get('payment', 'payment_period');
if ($payment_period > 0) {
    if ($payment_period == 1) {{ $lang_period = LANG_1MONTHS;}
        if ($payment_period == 6) {$lang_period = LANG_6MONTHS;}} else { $lang_period = LANG_12MONTHS;}
    $tpl_adds_array['{lang subscription}'] = LANG_SUBSCRIPTION . ': ' . $lang_period;

} else {
    $tpl_adds_array['{lang subscription}'] = LANG_NO_SUBSCRIPTION;
}

$tpl_adds_array['{category}'] = $category['name'];

$tpl_adds_array['{lang add_site}']                = LANG_ADD_SITE;
$tpl_adds_array['{lang required}']                = LANG_USER_REQUIRED;
$tpl_adds_array['{lang listing_title}']           = LANG_LISTING_TITLE;
$tpl_adds_array['{lang title}']                   = LANG_TITLE;
$tpl_adds_array['{max_title_lenght}']             = MAX_TITLE_LENGHT;
$tpl_adds_array['{lang url}']                     = LANG_URL;
$tpl_adds_array['{lang description_short}']       = LANG_DESCR_SHORT;
$tpl_adds_array['{min_description_short_lenght}'] = MIN_DESCR_SHORT_LENGHT;
$tpl_adds_array['{max_description_short_lenght}'] = MAX_DESCR_SHORT_LENGHT;
$tpl_adds_array['{lang description}']             = LANG_DESCR;
$tpl_adds_array['{min_description_lenght}']       = MIN_DESCR_LENGHT;
$tpl_adds_array['{max_description_lenght}']       = MAX_DESCR_LENGHT;
$tpl_adds_array['{lang facebook_url}']            = LANG_FACEBOOK_URL;
$tpl_adds_array['{lang twitter_url}']             = LANG_TWITTER_URL;
$tpl_adds_array['{lang youtube_url}']             = LANG_YOUTUBE_URL;
$tpl_adds_array['{lang embedded_video_title}']    = LANG_EMBEDDED_VIDEO_TITLE;
$tpl_adds_array['{lang embedded_video_code}']     = LANG_EMBEDDED_VIDEO_CODE;
$tpl_adds_array['{lang keywords}']                = LANG_KEYW;
$tpl_adds_array['{lang keywords note}']           = LANG_KEYW_NOTE;
$tpl_adds_array['{lang email}']                   = LANG_EMAIL;
$tpl_adds_array['{lang company}']                 = LANG_COMPANY;
$tpl_adds_array['{lang product}']                 = LANG_PRODUCT;
$tpl_adds_array['{lang address}']                 = LANG_ADDR;
$tpl_adds_array['{lang city}']                    = LANG_CITY;
$tpl_adds_array['{lang state}']                   = LANG_STATE;
$tpl_adds_array['{lang zip}']                     = LANG_ZIP;
$tpl_adds_array['{lang country}']                 = LANG_COUNTRY;
$tpl_adds_array['{lang tel}']                     = LANG_TEL;
$tpl_adds_array['{lang fax}']                     = LANG_FAX;
$tpl_adds_array['{lang add listing no website}']  = LANG_ADD_LISTING_NO_WEBSITE;
$tpl_adds_array['{lang reciprocal}']              = LANG_RECIPROCAL;
$tpl_adds_array['{lang note}']                    = LANG_NOTE;
$tpl_adds_array['{lang allow_video}']             = LANG_ALLOW_VIDEO;
$tpl_adds_array['{lang allow_logo}']              = LANG_ALLOW_LOGO;
$tpl_adds_array['{lang upload_logo}']             = LANG_UPLOAD_LOGO;
$tpl_adds_array['{lang next}']                    = LANG_NEXTS_BTN;
$tpl_adds_array['{lang submit}']                  = LANG_SUBMIT_BTN;
$tpl_adds_array['{lang back}']                    = LANG_BACK;
$tpl_adds_array['{lang url}']                     = LANG_URL;
$tpl_adds_array['{email}']                        = LANG_EMAIL;

$tpl_adds_array['{home}'] = $g_params->Get('site', 'site_name');

//////////////////////////////////////
// language link names
//////////////////////////////////////
$tpl_adds_array['{link type1}'] = LINK_TYPE1;
$tpl_adds_array['{link type2}'] = LINK_TYPE2;
$tpl_adds_array['{link type3}'] = LINK_TYPE3;
$tpl_adds_array['{link type4}'] = LINK_TYPE4;
$tpl_adds_array['{link type5}'] = LINK_TYPE5;
$tpl_adds_array['{link type6}'] = LINK_TYPE6;
$tpl_adds_array['{link type7}'] = LINK_TYPE7;
$tpl_adds_array['{link type8}'] = LINK_TYPE8;
$tpl_adds_array['{link type9}'] = LINK_TYPE9;

$tpl_adds_array['{deep_link_1}'] = LANG_DEEP_LINK_1;
$tpl_adds_array['{deep_link_2}'] = LANG_DEEP_LINK_2;
$tpl_adds_array['{deep_link_3}'] = LANG_DEEP_LINK_3;
$tpl_adds_array['{deep_link_4}'] = LANG_DEEP_LINK_4;
$tpl_adds_array['{deep_link_5}'] = LANG_DEEP_LINK_5;

$tpl_adds_array['{deep_link_expl}'] = LANG_DEEP_LINK_EXPL;

$tpl_adds_array['{currency}'] = CURRENCY;

for ($i = 1; $i <= LINK_TYPES; $i++) {
    if ($g_payment["crecip[$i]"] == 1) {
        $tpl_adds_array['{is_reciprocal_' . $i . '}']   = LINK_RECIPROCAL;
        $tpl_adds_array['{disp_reciprocal_' . $i . '}'] = 'true';
    } else {
        $tpl_adds_array['{is_reciprocal_' . $i . '}']   = '';
        $tpl_adds_array['{disp_reciprocal_' . $i . '}'] = 'false';
    }
}

for ($i = 1; $i <= LINK_TYPES; $i++) {
    if ($g_payment["logoup[$i]"] == 1) {
        $tpl_adds_array['{is_logo_' . $i . '}'] = 'true';
    } else {
        $tpl_adds_array['{is_logo_' . $i . '}'] = 'false';
    }
}

for ($i = 1; $i <= LINK_TYPES; $i++) {
    if ($g_payment["videoup[$i]"] == 1) {
        $tpl_adds_array['{is_video_' . $i . '}'] = 'true';
    } else {
        $tpl_adds_array['{is_video_' . $i . '}'] = 'false';
    }
}

$if_region['top_category'] = $category['level'];
if ($category['level'] == 1) {
    $tpl_adds_array['{top_category_info}'] = TOP_CATEGORY_INFO;
}

// reciprocal link:
$recip_url   = $g_payment['url'];
$recip_title = $g_payment['title'];

$tpl_adds_array['{my_reciprocal value}'] = '<a href="' . $recip_url . '" target="_blank">' . $recip_title . '</a>';

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// process with $_POST values
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if ((isset($_POST['submit_step1']) and (! $isError)) or ((isset($_POST['submit_step1_2']) and $isError)) or ((isset($_POST['submit_step2']) and $isError))) {

    //////////////////////////////////////
    // fill prices
    //////////////////////////////////////
    foreach ($g_payment as $key => $value) {

        if (preg_match("/^type\[[0-" . LINK_TYPES . "]\]$/", $key)) {

            $value = number_format((float) ($value), 2);
            if ($value <= 0) {$value = 'Free';}

            $tpl_adds_array['{payment ' . $key . '}'] = $value;

        } else if ($key[1] == 'c') {
            // $tpl_adds_array['{style '.$key.'}'] = $value;
        }
    }

    //////////////////////////////////////
    // check active link types
    //////////////////////////////////////
    $k    = 0;
    $last = $i;
    for ($i = 1; $i <= LINK_TYPES; $i++) {

        if (! $g_payment["ctype[$i]"] or ($category['level'] == 1 and $i != LT_FEAT_TOP)) { // ctype[] - checkbox if active link type
            $tpl_adds_array["{style type[$i]}"] = 'style="display: none;"';

        } else {

            $last = $i;
            if ($link_type == $i) {
                $tpl_adds_array["{checked type[$i]}"] = 'checked';
            } else {
                $tpl_adds_array["{checked type[$i]}"] = '';
            }

            if ($k == 1) {
                $tpl_adds_array["{style type[$i]}"] = 'class="colored"';
            } else {
                $tpl_adds_array["{style type[$i]}"] = '';
            }
            $k = 1 - $k;

            if ($category['level'] == 1 and $i == LT_FEAT_TOP) {
                $tpl_adds_array["{checked type[$i]}"] = 'checked';
            }

        }
    }

    if ($link_type == 0) {
        // $tpl_adds_array["{checked type[2]}"] = 'checked';

        // find first free
        $found = false;
        for ($i = 1; $i <= LINK_TYPES; $i++) {
            if ($g_payment["ctype[$i]"] and ($tpl_adds_array['{payment type[' . $i . ']}'] == 'Free')) {
                $tpl_adds_array["{checked type[" . $i . "]}"] = 'checked';
                $found                                        = true;
                break;
            }
        }

        // if no free, find the least expensive
        $pay_value = false;
        if (! $found) {
            for ($i = 1; $i <= LINK_TYPES; $i++) {
                if ($g_payment["ctype[$i]"]) {
                    if (! $pay_value) {
                        $pay_value = $tpl_adds_array['{payment type[' . $i . ']}'];
                        $pay_key   = $i;
                    } else {
                        if ($pay_value > $tpl_adds_array['{payment type[' . $i . ']}']) {
                            $pay_value = $tpl_adds_array['{payment type[' . $i . ']}'];
                            $pay_key   = $i;
                        }
                    }
                }
            }
            $tpl_adds_array["{checked type[" . $pay_key . "]}"] = 'checked';
        }

    }

    if (PAY_BEFORE_SUBMIT and isset($_POST['submit_step2'])) {
        for ($i = 1; $i <= LINK_TYPES; $i++) {

            if ($link_type == $i) {
                $tpl_adds_array["{checked type[$i]}"] = 'checked';
            } else {
                $tpl_adds_array["{style type[$i]}"] = 'style="display: none;"';
            }
        }
    }

    for ($i = 1; $i <= LINK_TYPES; $i++) {
        if (($tpl_adds_array["{checked type[$i]}"] == 'checked') and ($g_payment["videoup[$i]"] == 1)) {
            $tpl_adds_array['{video_display}'] = '';
        }
        if (($tpl_adds_array["{checked type[$i]}"] == 'checked') and ($g_payment["logoup[$i]"] == 1)) {
            $tpl_adds_array['{logo_display}'] = '';
        }
        if (($tpl_adds_array["{checked type[$i]}"] == 'checked') and ($g_payment["crecip[$i]"] == 1)) {
            $tpl_adds_array['{reciprocal_display}'] = '';
        }
    }

    // set buttons' names
    if (PAY_BEFORE_SUBMIT and (isset($_POST['submit_step1']) or isset($_POST['submit_step1_2']))) {
        $tpl_adds_array['{submit button}']          = 'submit_step1_2';
        $tpl_adds_array['{captcha refresh button}'] = 'captcha_refresh1_2';
    } else {
        $tpl_adds_array['{submit button}']          = 'submit_step2';
        $tpl_adds_array['{captcha refresh button}'] = 'captcha_refresh';
    }

    if (! PAY_BEFORE_SUBMIT and (isset($_POST['submit_step1']) or isset($_POST['submit_step2']))) {
        $tpl_adds_array['{second submit button display}'] = 'style="display: none;"';
    }

    // freeze data elements
    if (PAY_BEFORE_SUBMIT) {
        $tpl_adds_array['{url readonly}']   = 'readonly="readonly"';
        $tpl_adds_array['{email readonly}'] = 'readonly="readonly"';
    }

}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// filling forms
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($site_added === true) {

    //////////////////////////////////////
    // site was already in directory
    //////////////////////////////////////
    if ($max_connection_reached) {
        $if_region['site_old_added'] = 2;
    } else {
        $if_region['site_old_added'] = 1;
    }

    $tpl_adds_array['{url}']      = $site_row['url'];
    $tpl_adds_array['{category}'] = $category['name'];
    $tmp_tpl->SetTemplate(DIR_TEMPLATE . 'add_site_success.tpl.php');

} else if ((isset($_POST['submit_step1']) and (! $isError))) {

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // successful step 1, show second form
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////
    if (USE_CAPTCHA) {
        $tpl_adds_array['{captcha text}']  = LANG_CAPTCHA;
        $tpl_adds_array['{captcha text2}'] = LANG_CAPTCHA2;
        $tpl_adds_array['{captcha input}'] = '<input name="captcha" maxlength="15" size="15" type="text">';
        $tpl_adds_array['{captcha img}']   = '<img src="captcha.php" style="border: 1px solid red;">';
        $aIfRegion['iscaptcha']            = 1;

    } else {
        $tpl_adds_array['{captcha text}']  = '';
        $tpl_adds_array['{captcha text2}'] = '';
        $tpl_adds_array['{captcha input}'] = '';
        $tpl_adds_array['{captcha img}']   = '';
        $aIfRegion['iscaptcha']            = 0;
    }

    // fill input values
    foreach ($values as $key => $value) {
        $tpl_key                  = '{' . $key . ' value}';
        $tpl_adds_array[$tpl_key] = $values[$key];
    }
    $country_value = $values['country'];

    // initialize terms checkbox
    $tpl_adds_array['{terms_checked}'] = '';

    // initialize country drop down
    foreach ($g_countries as $key => $value) {
        $tpl_adds_array['{sel_' . $key . '}'] = '';
    }
    $tpl_adds_array['{sel_0}'] = 'selected="selected"';

    if (PAY_BEFORE_SUBMIT) {
        $if_region['pay_and_step2'] = 0;
    }

    // fill step 2 template
    $tmp_tpl->SetTemplate(DIR_TEMPLATE . 'add_site_step2.tpl.php');
    $tmp_tpl->IfRegion($aIfRegion);

} else if (isset($_POST['submit_step1_2']) and ($isError)) {

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // failure step 1_2, show form and errors
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    if (USE_CAPTCHA) {
        $tpl_adds_array['{captcha text}']  = LANG_CAPTCHA;
        $tpl_adds_array['{captcha text2}'] = LANG_CAPTCHA2;
        $tpl_adds_array['{captcha input}'] = '<input name="captcha" maxlength="15" size="15" type="text">';
        $tpl_adds_array['{captcha img}']   = '<img src="captcha.php" style="border: 1px solid red;">';
        $aIfRegion['iscaptcha']            = 1;

    } else {
        $tpl_adds_array['{captcha text}']  = '';
        $tpl_adds_array['{captcha text2}'] = '';
        $tpl_adds_array['{captcha input}'] = '';
        $tpl_adds_array['{captcha img}']   = '';
        $aIfRegion['iscaptcha']            = 0;
    }

    // fill input values
    foreach ($values as $key => $value) {
        $tpl_key                  = '{' . $key . ' value}';
        $tpl_adds_array[$tpl_key] = $values[$key];
    }

    // populate terms checkbox
    if (isset($_POST['terms'])) {
        $tpl_adds_array['{terms_checked}'] = 'checked';
    }

    if (strlen($eMsg)) {
        $tpl_adds_array['{error}'] = '<div class="info">' . $eMsg . '</div>';
    } else {
        $tpl_adds_array['{error}'] = '';
    }

    $if_region['pay_and_step2'] = 0;

    // set step 1_2 template
    $tmp_tpl->SetTemplate(DIR_TEMPLATE . 'add_site_step2.tpl.php');
    $tmp_tpl->IfRegion($aIfRegion);

} else if (isset($_POST['submit_step1_2']) and (! $isError)) {

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // successful step 1_2
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //check if free submission
    $amount = $g_payment['type[' . $link_type . ']'];
    if (($amount <= 0) or $listing_paid) {
        // proceed to step 2
        if (USE_CAPTCHA) {
            $tpl_adds_array['{captcha text}']  = LANG_CAPTCHA;
            $tpl_adds_array['{captcha text2}'] = LANG_CAPTCHA2;
            $tpl_adds_array['{captcha input}'] = '<input name="captcha" maxlength="15" size="15" type="text">';
            $tpl_adds_array['{captcha img}']   = '<img src="captcha.php" style="border: 1px solid red;">';
            $aIfRegion['iscaptcha']            = 1;

        } else {
            $tpl_adds_array['{captcha text}']  = '';
            $tpl_adds_array['{captcha text2}'] = '';
            $tpl_adds_array['{captcha input}'] = '';
            $tpl_adds_array['{captcha img}']   = '';
            $aIfRegion['iscaptcha']            = 0;
        }

        // fill input values
        foreach ($values as $key => $value) {
            $tpl_key                  = '{' . $key . ' value}';
            $tpl_adds_array[$tpl_key] = $values[$key];
        }

        //////////////////////////////////////
        // fill prices
        //////////////////////////////////////
        foreach ($g_payment as $key => $value) {

            if (preg_match("/^type\[[0-" . LINK_TYPES . "]\]$/", $key)) {

                $value = number_format((float) ($value), 2);
                if ($value <= 0) {$value = 'Free';}

                $tpl_adds_array['{payment ' . $key . '}'] = $value;

            } else if ($key[1] == 'c') {
                // $tpl_adds_array['{style '.$key.'}'] = $value;
            }
        }

        for ($i = 1; $i <= LINK_TYPES; $i++) {

            if ($link_type == $i) {
                $tpl_adds_array["{checked type[$i]}"] = 'checked';
            } else {
                $tpl_adds_array["{style type[$i]}"] = 'style="display: none;"';
            }
        }

        $country_value = $values['country'];

        // initialize terms checkbox
        $tpl_adds_array['{terms_checked}'] = '';

        // initialize country drop down
        foreach ($g_countries as $key => $value) {
            $tpl_adds_array['{sel_' . $key . '}'] = '';
        }
        $tpl_adds_array['{sel_0}'] = 'selected="selected"';

        $tpl_adds_array['{submit button}'] = 'submit_step2';
        $if_region['pay_and_step1_2']      = 0;
        $if_region['pay_and_step2']        = 1;

        // set step 2 template again w/o TOS and captcha
        $tmp_tpl->SetTemplate(DIR_TEMPLATE . 'add_site_step2.tpl.php');
        $tmp_tpl->IfRegion($aIfRegion);
    } else {
        //
        // go to paypal
        //
        $values['link_type']    = $link_type;
        $values['featured']     = $g_featured[$link_type];
        $values['date']         = date(DATETIME_FORMAT);
        $values['last_checked'] = date(DATETIME_FORMAT);

        $amount = $g_payment['type[' . $link_type . ']'];
        if ($amount > 0) {
            $values['paid_link'] = 1;
        } else {
            $values['paid_link'] = 0;
        }

        // other stuff
        $values['last_payment']         = date(DATETIME_FORMAT);
        $values['user_ip']              = $_SERVER['REMOTE_ADDR'];
        $values['status']               = SITE_WAITING_FOR_PAYMENT;
        $values['original_description'] = $values['description'];

        // fill payment array
        $payment['amount']         = $amount;
        $payment['email']          = '';
        $payment['paid']           = PM_NOT_PAID;
        $payment['currency']       = CURRENCY;
        $payment['payment_period'] = PAYMENT_PERIOD;
        $payment['date']           = date(DATETIME_FORMAT);
        $payment['error']          = LANG_INFO_PAYMENT3;
        $payment['is_error']       = 1;

        // if user is logged in, add listing under the logged user
        if ($g_user->Level() == AL_USER) {
            $user              = $g_user->GetUser();
            $values['user_id'] = $user['id'];
        }

        $id = $g_site->AddSite($values, $payment, $sub_id);

        if ($id > 0) {

            $tpl_adds_array['{url_disp}'] = '';
            if (trim($values['url']) == '') {
                $tpl_adds_array['{url_disp}'] = 'do_not_disp';
            }
            $tpl_adds_array['{company}'] = $values['company'];
            $tpl_adds_array['{address}'] = $values['url'];
            $tpl_adds_array['{id}']      = $id;
            $if_region['site_new']       = 3;

            $if_region['subscription'] = 0;

            include 'catalog/payment.php';
            $if_region['ispayment'] = 1;

            $values['id'] = $id;

            $tmp_tpl->SetTemplate(DIR_TEMPLATE . 'add_site_success.tpl.php');

        } else {
            $tmp_tpl->SetTemplate(DIR_TEMPLATE . 'add_site_failure.tpl.php');
        }

        //
        //end go to paypal
        //

    }

} else if (isset($_POST['submit_step2']) and ($isError)) {

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // failure step 2, show form and errors
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    if (USE_CAPTCHA) {
        $tpl_adds_array['{captcha text}']  = LANG_CAPTCHA;
        $tpl_adds_array['{captcha text2}'] = LANG_CAPTCHA2;
        $tpl_adds_array['{captcha input}'] = '<input name="captcha" maxlength="15" size="15" type="text">';
        $tpl_adds_array['{captcha img}']   = '<img src="captcha.php" style="border: 1px solid red;">';
        $aIfRegion['iscaptcha']            = 1;

    } else {
        $tpl_adds_array['{captcha text}']  = '';
        $tpl_adds_array['{captcha text2}'] = '';
        $tpl_adds_array['{captcha input}'] = '';
        $tpl_adds_array['{captcha img}']   = '';
        $aIfRegion['iscaptcha']            = 0;
    }

    // fill input values
    foreach ($values as $key => $value) {
        $tpl_key                  = '{' . $key . ' value}';
        $tpl_adds_array[$tpl_key] = $values[$key];
    }
    $country_value = $values['country'];

    // populate terms checkbox
    if (isset($_POST['terms'])) {
        $tpl_adds_array['{terms_checked}'] = 'checked';
    }

    if (strlen($eMsg)) {
        $tpl_adds_array['{error}'] = '<div class="info">' . $eMsg . '</div>';
    } else {
        $tpl_adds_array['{error}'] = '';
    }

    if (PAY_BEFORE_SUBMIT) {
        $if_region['pay_and_step1_2'] = 0;
    }

    // set step 2 template
    $tmp_tpl->SetTemplate(DIR_TEMPLATE . 'add_site_step2.tpl.php');
    $tmp_tpl->IfRegion($aIfRegion);

} else if (isset($_POST['submit_step2']) and (! $isError)) {

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // successful step 2, save site
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $values['link_type']    = $link_type;
    $values['featured']     = $g_featured[$link_type];
    $values['date']         = date(DATETIME_FORMAT);
    $values['last_checked'] = date(DATETIME_FORMAT);

    // get PageRank
    if (COLLECT_PAGE_RANK) {

        include 'catalog/pr.php';
        //$pr = trim(getrank($values['url']));
        $pr = trim(pagerank($values['url']));
        if (is_numeric($pr) and $pr > 0) {
            $values['pr'] = $pr;
        }
    }

    // nofollow?
    // if ($link_type === LT_NOFOLLOW) {  $values['nofollow'] = 1;  }

    // is paid link?
    $amount = $g_payment['type[' . $link_type . ']'];
    if ($amount > 0) {
        $values['paid_link'] = 1;
    } else {
        $values['paid_link'] = 0;
    }

    // other stuff
    $values['last_payment'] = date(DATETIME_FORMAT);
    $values['user_ip']      = $_SERVER['REMOTE_ADDR'];

    // add immiediately if free link?
    if ($amount == 0 and LINK_INSTANTLY_APPEAR) {
        $values['status'] = SITE_VIEW;
    } else {
        $values['status'] = SITE_WAITING;
    }

    if ($listing_paid) {
        if (LINK_INSTANTLY_APPEAR or PAID_LINK_INSTANTLY_APPEAR) {
            $values['status'] = SITE_VIEW;
        }
    } else {
        // fill payment array
        $payment['amount']         = $amount;
        $payment['email']          = '';
        $payment['paid']           = PM_NOT_PAID;
        $payment['currency']       = CURRENCY;
        $payment['payment_period'] = PAYMENT_PERIOD;
        $payment['date']           = date(DATETIME_FORMAT);
        $payment['error']          = LANG_INFO_PAYMENT3;
        $payment['is_error']       = 1;
    }

    // if user is logged in, add listing under the logged user
    if ($g_user->Level() == AL_USER) {
        $user              = $g_user->GetUser();
        $values['user_id'] = $user['id'];
    }

    // add site
    $values['original_description'] = $values['description'];

    if ($listing_paid) {
        // update site
        $id = $g_site->UpdateSite($values, $p_id);
    } else {
      $payment['log'] = "log";
      $id = $g_site->AddSite($values, $payment, $sub_id);
    }

    if (($id > 0) or ($listing_paid and isset($id))) {
        if ($listing_paid) {
            $id = $p_id;
        }
        $values['id_code']  = $id . '-' . CodeGen();
        $tmp_arr['id_code'] = $values['id_code'];
        $g_site->UpdateSite($tmp_arr, $id);

        // e-mail to admin
        if ($g_params->Get('site', 'notify_admin')) {

            if ($g_payment["crecip[$link_type]"] == 1) {
                $email_tpl['email_body'] = 'New Reciprocal Listing was submitted by ' . $values['company'] . '<br>To: ' . $category['name'] . '<br>E-mail Address: ' . $values['email'] . '<br>Reciprocal Link can be found: ' . $values['reciprocal'] . '';
                $email_tpl['title']      = 'Reciprocal Link Submitted in {my_site_url}';
            } else {
                $email_tpl['email_body'] = 'New Listing was Submitted: {user_site_url} by ' . $values['company'] . '<br>To: ' . $category['name'] . '<br>E-mail Address: ' . $values['email'] . '';
                $email_tpl['title']      = 'New Listing in {my_site_url}';
            }
            $adminEmail          = $values;
            $adminEmail['email'] = ADMIN_EMAIL;
            $adminEmail['id']    = 0;
            $g_site->SendEmail($email_tpl, $adminEmail);

        }

        // rename logo file if uploaded
        if (! empty($logo_filename)) {
            rename(LOGO_DIR . '/' . $logo_filename, LOGO_DIR . '/' . $id . LOGO_NAME_SUFFIX);
        }

        $tpl_adds_array['{url_disp}'] = '';
        if (trim($values['url']) == '') {
            $tpl_adds_array['{url_disp}'] = 'do_not_disp';
        }

        if ($values['status'] == SITE_VIEW) {

            $tpl_adds_array['{title}']       = $values['title'];
            $tpl_adds_array['{address}']     = $values['url'];
            $tpl_adds_array['{description}'] = $values['description'];
            $tpl_adds_array['{id}']          = $id;
            $if_region['site_new']           = 2;

        } else {
            $if_region['site_new']           = 1;
            $tpl_adds_array['{address}']     = $values['url'];
            $tpl_adds_array['{title}']       = $values['title'];
            $tpl_adds_array['{description}'] = $values['description'];
        }

        $if_region['subscription'] = 0;

        if ($amount > 0 and ! $listing_paid) {

            include 'catalog/payment.php';
            $if_region['ispayment'] = 1;

        } else {
            $if_region['ispayment'] = 0;

            $values['id'] = $id;

            if (LINK_INSTANTLY_APPEAR or ($listing_paid and PAID_LINK_INSTANTLY_APPEAR)) {
                $g_site->SendEmail($g_params->GetParams('site_approved_email'), $values);
            } else {
                $g_site->SendEmail($g_params->GetParams('site_pending_email'), $values);
            }

        }

        $tmp_tpl->SetTemplate(DIR_TEMPLATE . 'add_site_success.tpl.php');

    } else {
        $tmp_tpl->SetTemplate(DIR_TEMPLATE . 'add_site_failure.tpl.php');
    }

} else {

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // first step input values
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $tpl_adds_array['{address}'] = $g_addr;

    if ($isError) {
        $tpl_adds_array['{company value}'] = $values['company'];
        $tpl_adds_array['{url value}']     = $values['url'];
        $tpl_adds_array['{email value}']   = $values['email'];
        $tpl_adds_array['{error}']         = '<div class="info">' . $eMsg . '</div>';
    }

    // set template
    $tmp_tpl->SetTemplate(DIR_TEMPLATE . 'add_site_step1.tpl.php');

}

if (($g_params->Get('for_user', 'allow_users') and $g_params->Get('for_user', 'user_logged_to_add') and
    ! ($g_user->Level() == AL_USER)) or ($g_user->Level() === AL_ADMIN)) {
    $tpl_adds_array['{error}'] = USER_NOT_LOGGED_ADD_ERROR;
    $tpl_adds_array['{error}'] = str_replace('{user login link}', '<a href="?page=login"><u>log in</u></a>', $tpl_adds_array['{error}']);
    $tpl_adds_array['{error}'] = str_replace('{user register link}', '<a href="?page=registration"><u>register</u></a>', $tpl_adds_array['{error}']);
    $tmp_tpl->SetTemplate(DIR_TEMPLATE . 'user_error.tpl.php');
} else {
    // countries dropdown logic
    $coForm        = new FormClass();
    $coform_fields = ['CBO|country'];
    $coForm->SetCombo('country', $g_countries, 'value', 'name');
    $country_array               = ['country' => $country_value];
    $coform_array                = $coForm->MakeForm($coform_fields, $country_array);
    $tpl_adds_array['{country}'] = $coform_array['{country}'];

    $tmp_tpl->IfRegion($if_region);
}
$tmp_tpl->ReplaceIn($tpl_adds_array);

$tpl_main = $tmp_tpl->Get();

function CodeGen()
{

    $uppercase = range('A', 'P');
    $uppercase = array_merge(range('R', 'Z'), $uppercase);

    $numeric = range(2, 9);

    $CharPool   = array_merge($uppercase, $numeric);
    $PoolLength = count($CharPool) - 1;

    for ($i = 0; $i < 10; $i++) {
        $l_code .= $CharPool[mt_rand(0, $PoolLength)];
    }

    return $l_code;
}
