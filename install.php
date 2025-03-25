<?php
    // This file sets up the application by creating or updating database tables.
    // It includes configuration and database parameter files.
    // It determines the PHP version and sets the appropriate class directory.
    // It connects to the database and checks for existing tables.
    // It updates existing tables or creates new ones if necessary.
    // It inserts initial data into the database tables.
    // It registers an admin user if it's a new installation.
    // It generates a registration_.html file for confirmation.
    // It outputs success or error messages for the installation process.

    include 'config.php';

    $id          = 0;
    $info        = '';
    $conf_no     = '';
    $new_install = true;

    include 'db_params.php';

	// New code here
	$versionParts = explode('.', phpversion());
	$majorVersion = (int)$versionParts[0];
	// End of new code

    // Modified by: 2025-03-024
  	if (version_compare( phpversion(), '5.0' ) < 0) {  
		define('IS_PHP5' , false);
		define('CLASS_DIR', 'php4_classes/'); 
	} else if (version_compare( phpversion(), '5.0' ) >= 0) {    // modified by: 2025-03-24
		define('IS_PHP5' , true);                                // don't need to change this variable
		define('CLASS_DIR', 'php' . $majorVersion . '_classes/'); 
	}

    include CLASS_DIR . 'database.class.php';
    include CLASS_DIR . 'users.class.php';

    if (IS_PHP5) {
        $DB = DataBase::getInstance();
    } else {
        $DB =& DataBase::getInstance();
    }

    if (! $DB->Open()) {
        echo 'Error: Couldn`t connect to Database';
        exit;
    } else {
        echo "DB opened successfully!";
    }

    $isError = false;
    $eMsg    = '';

    $install_tables = [];
	$a_data_query = [];
    $script_url = SCRIPT_URL_DEV;

    $form = ['url', 'pass', 'user', 'email', 'conf_no', 'query_params_data',
        'query_captcha_tbl',
        'query_categories_tbl',
        'query_connection_tbl',
        'query_params_tbl',
        'query_payment_tbl',
        'query_session_tbl',
        'query_sites_tbl',
        'query_users_tbl',
        'query_visits_tbl',
    ];

    foreach ($form as $key) {
		if (isset($_POST[$key])) {
            $post_str = str_replace('DB_TABLE_PREFIX', DB_TABLE_PREFIX, $_POST[$key]);
            $post_str = str_replace("\'", "'", $post_str);
            switch ($key) {
                case 'url':
                    $script_url = $_POST[$key];
                    break;
                case 'pass':
                case 'user':
                case 'email':
                    $aUser[$key] = $_POST[$key];
                    break;
                case 'conf_no':
                    $conf_no = $_POST[$key];
                    break;
                case 'query_params_data':
                    $query_params_data = $post_str;
                    $a_data_query      = explode("***", $query_params_data);
                    break;
                default:
                    $tbl_key                  = strtolower(DB_TABLE_PREFIX . strbetweenstrs($key, 'query_', '_tbl'));
                    $install_tables[$tbl_key] = $post_str;
            }
        }
    }

    $query     = "show tables like '" . DB_TABLE_PREFIX . "%'";
    $getTables = $DB->GetTable($query);

    if (count($getTables) > 0) {

        // some tables exist
        $new_install = false;

        // change names of the columns
        $col_names = [
            'payment' => [
                'paymeny_period' => ['payment_period', 'tinyint'],
                'payed'          => ['paid', 'tinyint'],
            ],
            'sites'   => [
                'oryginal_description' => ['original_description', 'text'],
                'payed_link'           => ['paid_link', 'tinyint'],
            ],
        ];
        foreach ($col_names as $tbl_name => $cols) {

            // fix table name cause it is case sensitive
            $tbl_name = DB_TABLE_PREFIX . $tbl_name;

            $query      = "show columns from $tbl_name";
            $getColumns = $DB->GetTable($query);

            $updated = false;

            foreach ($cols as $old => $new) {
                $error = false;
                foreach ($getColumns as $col) {
                    if ($old === $col['Field']) {

                        // rename column
                        $up_query = 'ALTER TABLE `' . $tbl_name . '` CHANGE ' . $old . ' ' . $new[0] . ' ' . $new[1] . '; ';
                        if ($DB->query($up_query)) {
                            $updated = true;
                        } else {
                            $error = true;
                        }
                        break;
                    }
                    if ($error) {
                        break;
                    }
                }
            }

            if ($updated) {
                $info .= 'Table `' . $tbl_name . '` <span style="color: green">columns renamed</span><br>';
            }
            if ($error) {
                $info .= 'Table `' . $tbl_name . '` columns name change <span style="color: red">error</span><br>';
                $isError = true;
            }
        }

        $hmany      = count($getTables);
        $getTables_ = [];

        for ($i = 0; $i < $hmany; $i++) {
            $tmp_table = $getTables[$i];

            foreach ($tmp_table as $key => $table) {
                $getTables_[] = $table;
            }
        }
        $i = 0;
        foreach ($getTables_ as $table) {
            $getTables[$i] = strtolower($table);
            $i++;
        }

        $eMsgT = '';

        $q_array = [];

        foreach ($install_tables as $tbl_name => $tbl_query) {
            if (in_array($tbl_name, $getTables)) {
                $tbl_query   = strbetweenstrs($tbl_query, '` (', ') E');
                $q_array_tmp = explode(',', $tbl_query);
                unset($q_array);
                foreach ($q_array_tmp as $q_line) {
                    if ((strpos($q_line, 'KEY `') === false) and
                        (strpos($q_line, 'PRIMARY KEY') === false) and
                        (strpos($q_line, 'UNIQUE KEY') === false) and
                        strlen($q_line) > 10) {
                        $q_array[] = $q_line;
                    }
                }

                // fix table name cause it is case sensitive
                $tbl_name = DB_TABLE_PREFIX . substr($tbl_name, strlen(DB_TABLE_PREFIX), strlen($tbl_name) - strlen(DB_TABLE_PREFIX));

                $query      = "show columns from $tbl_name";
                $getColumns = $DB->GetTable($query);

                $up_query = '';
                $updated  = false;
                $error    = false;

                foreach ($q_array as $q_col) {
                    $found = false;
                    foreach ($getColumns as $col) {
                        if (strpos($q_col, '`' . $col['Field'] . '`') > 0) {
                            $found = true;
                            break;
                        }
                    }
                    if (! $found) {

                        // need to add this field
                        $up_query = 'ALTER TABLE `' . $tbl_name . '` ADD ' . $q_col . '; ';
                        if ($DB->query($up_query)) {
                            $updated = true;
                        } else {
                            $error = true;
                        }
                        if ($updated and ! (strpos($q_col, 'company') === false) and ! (strpos($tbl_name, 'sites') === false)) {
                            $dr_query = 'ALTER TABLE `' . $tbl_name . '` DROP INDEX `url`;';
                            if (! $DB->query($dr_query)) {
                                $updated = false;
                                $error   = true;
                            }
                        }
                    }
                }

                if ($updated) {
                    $info .= 'Table `' . $tbl_name . '` <span style="color: green">updated</span><br>';
                }
                if ($error) {
                    $info .= 'Table `' . $tbl_name . '` update <span style="color: red">error</span><br>';
                    $isError = true;
                }

            } else {

                // fix table name cause it is case sensitive
                $tbl_name = DB_TABLE_PREFIX . substr($tbl_name, strlen(DB_TABLE_PREFIX), strlen($tbl_name) - strlen(DB_TABLE_PREFIX));

                if ($DB->query($tbl_query)) {
                    $info .= 'Table `' . $tbl_name . '` <span style="color: green">created</span><br>';
                } else {
                    $info .= 'Table `' . $tbl_name . '` creation <span style="color: red">error</span><br>';
                    $isError = true;
                }
            }
        }
    } else {
        // create tables
        foreach ($install_tables as $key => $value) {
            if ($DB->query($value)) {
                $info .= 'Table `' . $key . '` <span style="color: green">created</span><br>';
            } else {
                $info .= 'Table `' . $key . '` creation <span style="color: red">error</span><br>';
                $isError = true;
            }
        }
        for ($i = 0; $i < count($a_data_query); $i++) {
            if (! $DB->query($a_data_query[$i])) {
                $info .= 'Addition of initial data to `params` table <span style="color: red">error</span><br>';
                $isError = true;
            }
        }
    }

    if (! $isError) {

        if ($new_install) {
            $g_users               = new UsersClass;
            $aUser['access_level'] = AL_ADMIN;
            $id                    = $g_users->RegisterUser($aUser);
        }

        if (! empty($conf_no)) {
            $reg = fopen('registration_.html', 'w');
            if ($reg) {
                fwrite($reg, $conf_no . '<br><br>Please DO NOT remove this file from your server!');
            }
            fclose($reg);
        }
    }

    echo('<div class="end">');
    echo $info;
    if (! $isError) {
        echo(
            '<p style="color: #789; font-weight: bold; padding: 2px; border: 1px solid #aaa;">
			<font color="#CC0033">IMPORTANT: <br>Make changes to the following files on your Server!</font>
			<br><br>
			1. If you have not done it yet, change the permissions to the following directories: <br />
			chmod 777 backup/ <br />
			chmod 777 cache/ <br />
			chmod 777 coupons/ <br />
			chmod 777 logos/ <br />
			chmod 777 logs/ <br /><br />
			and to the following files: <br />
			chmod 666 asf.txt <br />
			chmod 666 registration_.html <br />
			chmod 666 sitemap.xml.gz <br />
			chmod 666 users_online.txt
			<br /><br />
			2. Delete the following file: <br>
			install.php
			<br><br>
			3. Customize the htaccess.txt file (instructions are in the file) <br>and upload it to your server
			<br><br>
			4. On your server - rename htaccess.txt file to: <br>
			.htaccess (notice the "." before htaccess. The file must be named: .htaccess)
			</p>
			<p style="color: #789; font-weight: bold; padding: 2px; border: 1px solid #aaa;">
			<font color="#CC0033">IMPORTANT: <br>DO NOT remove registration_.html or any other files from your server as this would make the script inoperative!</font>
			</p>
			<p>If all tables were created successfully, go to <a href="' . $script_url . '/admin/" title="Login to Set Up your Directory"><b>Admin Area</b></a><br></p>
			<p style="text-align: center; font-weight: bold;"><font color="green">Thank you for choosing qlWebDS - Directory Script</font></p>
			</div>'
        );
    }

    // }

    // misc function
    // return first string located between two given strings
    function strbetweenstrs($str, $start, $stop)
    {
        $ret = false;
        $ok  = true;

        $start_length = strlen($start);
        if ($start_length == 0) {
            $start_pos = 0;
        } else {
            $start_pos = strpos($str, $start);
            if (! ($start_pos === false)) {
                $start_pos = $start_pos + $start_length;
            } else {
                $ok = false;
            }
        }

        if ($ok) {
            $stop_length = strlen($stop);
            if ($stop_length == 0) {
                $stop_pos = strlen($str) + 1;
            } else {
                $stop_pos = strpos($str, $stop);
                if ($stop_pos) {
                    if ($stop_pos > $start_pos) {
                        $ret = substr($str, $start_pos, $stop_pos - $start_pos);
                    }
                }
            }
        }

        return $ret;
    }

?>
