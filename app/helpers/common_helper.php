<?php




if (!function_exists('check_errors')) {
    /**
     * @return nothing this will turn on all the errors. 
     */
    function check_errors()
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }
}
if (!function_exists('cc_expiry')) {
    function cc_expiry($expiry)
    {
        $expiry = str_replace("/", "-", $expiry);
        $expiry = str_replace(" ", "", $expiry);
        $expiry = explode("-", $expiry);
        if (strlen($expiry[1]) == 2) {
            $dt = DateTime::createFromFormat('y', $expiry[1]);
            $expiry = $expiry[0] . '/' . $dt->format('Y');
            return $expiry = explode("/", $expiry);
        } elseif (strlen($expiry[1]) == 4) {
            $expiry = $expiry[0] . '/' . $expiry[1];
            return $expiry = explode("/", $expiry);
        }
        return;
    }
}

if (!function_exists('cc_expired')) {
    function cc_expired($month, $year)
    {
        if ($month < date('m')) {
            if ($year <= date('Y')) {
                return true;
            }
        } else {
            if ($year < date('Y')) {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('numberMask')) {
    function numberMask($cc, $fillChar = '*', $onlyLastFour = NULL)
    {
        $last4 = substr(str_replace(['-', ' '], '', $cc), -4);
        if ($onlyLastFour != NULL)
            return $last4;
        return str_pad($last4, 16, $fillChar, STR_PAD_LEFT);
    }
}


if (!function_exists('format_interval')) {
    /**
     * Format an interval to show all existing components.
     * If the interval doesn't have a time component (years, months, etc)
     * That component won't be displayed.
     *
     * @param DateInterval $interval The interval
     *
     * @return string Formatted interval string.
     */
    function format_interval(DateInterval $interval, $noWords = NULL, $getOnly = NULL)
    {
        $result = "";
        if ($noWords != NULL) {
            if ($getOnly != NULL) {
                switch ($getOnly) {
                    case 'y':
                        if ($interval->y)
                            $result .= $interval->format("%y");
                        break;
                    case 'm':
                        if ($interval->m)
                            $result .= $interval->format("%m");
                        break;
                    case 'd':
                        if ($interval->d)
                            $result .= $interval->format("%d");
                        break;
                    case 'i':
                        if ($interval->i)
                            $result .= $interval->format("%i");
                        break;
                    case 'h':
                        if ($interval->h)
                            $result .= $interval->format("%h");
                        break;
                    case 's':
                        if ($interval->s)
                            $result .= $interval->format("%s");
                        break;
                }
            } else {
                if ($interval->y) {
                    $result .= $interval->format("%y");
                }
                if ($interval->m) {
                    $result .= $interval->format("%m");
                }
                if ($interval->d) {
                    $result .= $interval->format("%d");
                }
                if ($interval->h) {
                    $result .= $interval->format("%h");
                }
            }
        } else {
            if ($interval->y) {
                $result .= $interval->format("%y years ");
            }
            if ($interval->m) {
                $result .= $interval->format("%m months ");
            }
            if ($interval->d) {
                $result .= $interval->format("%d days ");
            }
            if ($interval->h) {
                $result .= $interval->format("%h hours ");
            }
            if ($interval->i) {
                $result .= $interval->format("%i minutes ");
            }
            if ($interval->s) {
                $result .= $interval->format("%s seconds ");
            }
        }

        return $result;
    }
}

if (!function_exists('bootstrap_alert')) {
    /**
     * It will convert objectArray to Simple Array
     *
     * @param string        $subject will be the bold frist text or alert
     * @param string        $message will be the alert message
     * @param string        $type will be error|success|info|warning
     * @return string      
     */
    function bootstrap_alert($subject, $message, $type)
    {
        if ($type == 'error') {
            return '<div class="alert alert-danger alert-dismissible fade show">
                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                        <strong>' . $subject . '</strong> ' . $message . '
                        <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span></button>
                    </div>';
        }
        if ($type == 'success') {
            return '<div class="alert alert-success alert-dismissible fade show">
                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
                        <strong>' . $subject . '</strong> ' . $message . '
                        <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span></button>
                    </div>';
        }
        if ($type == 'info') {
            return '<div class="alert alert-info alert-dismissible fade show">
                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                        <strong>' . $subject . '</strong> ' . $message . '
                        <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span></button>
                    </div>';
        }
        if ($type == 'warning') {
            return '<div class="alert alert-warning alert-dismissible fade show">
                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                        <strong>' . $subject . '</strong> ' . $message . '
                        <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span></button>
                    </div>';
        }
    }
}


if (!function_exists('object_to_array')) {

    /**
     * It will convert objectArray to Simple Array
     *
     * @param string       $data    The data is the objectArray
     * @return simple array
     */
    function object_to_array($data)
    {

        if (is_object($data))
            $data = get_object_vars($data);
        if (is_array($data))
            return array_map(__FUNCTION__, $data);
        else
            return $data;
    }
}

if (!function_exists('uri_segements')) {
    /**
     * @param array
     * @return string
     */
    function uri_segements($data)
    {
        $ci = get_instance();
        $segment_return = '';
        if (is_array($data))
            foreach ($data as $segment) {
                if (is_numeric($segment))
                    $segment_return .= $ci->uri->segment($segment) . '/';
            }
        else {
            $newArray = explode(",", $data);
            if (is_array($newArray))
                foreach ($newArray as $segment) {
                    if (is_numeric($segment))
                        $segment_return .= $ci->uri->segment($segment) . '/';
                }
        }

        return $segment_return;
    }
}
if (!function_exists('json')) {
    /**
     * @param array $data it will be the array.
     * @return json array with header json
     */
    function json($data)
    {
        header('Content-Type: application/json');
        return json_encode($data);
    }
}
if (!function_exists('is_md5')) {
    /**
     * @param string $md5 it will be md5 hash
     * @return
     */
    function is_md5($md5 = '')
    {
        return strlen($md5) == 32 && ctype_xdigit($md5);
    }
}

if (!function_exists('smtp_settings')) {
    function smtp_settings()
    {
        $ci = get_instance();
        $ci->load->library('options'); // load library 
        $row = $ci->options->load();
        $ci->load->library('email'); // load library 

        if ($row['smtp_host'] && $row['smtp_port'] && $row['smtp_email'] && $row['smtp_password'] && $row['smtp_secure']) {
            $config['protocol'] = 'smtp';
            $config['smtp_host'] = $row['smtp_host'];
            $config['smtp_port'] = $row['smtp_port'];
            $config['smtp_timeout'] = '100';
            $config['smtp_user'] = $row['smtp_email'];
            $config['smtp_pass'] = $row['smtp_password'];
            $config['smtp_crypto'] = $row['smtp_secure'];
        }
        $config['mailtype']     = "html";
        $config['charset']      = "utf-8";
        $config['wordwrap'] = TRUE;
        $config['send_multipart'] = FALSE;
        $ci->email->initialize($config);
        $ci->email->set_newline("\r\n");
    }
}

if (!function_exists('files_url')) {
    /**
     * @return url base_url path to assets folder 
     */
    function files_url($uri = NULL)
    {
        return base_url('public/files/' . $uri);
    }
}

if (!function_exists('assets_url')) {
    /**
     * @return url base_url path to assets folder 
     */
    function assets_url($uri = NULL)
    {
        return base_url('public/assets/' . $uri);
    }
}

if (!function_exists('image_url')) {
    /**
     * @return url base_url path to assets folder 
     */
    function image_url($uri = NULL)
    {
        return base_url('public/images/' . $uri);
    }
}

if (!function_exists('segment')) {
    /**
     * @return string The current segment. 
     */
    function segment($segment)
    {
        $CI = &get_instance();
        return $CI->uri->rsegment($segment);
    }
}

if (!function_exists('url_to_id')) {
    /**
     * @param segment $segment it will be url params.
     */

    function url_to_id($segment)
    {
        $pieces = explode('-', $segment);
        $id = array_pop($pieces);
        if (is_numeric($id)) {
            return $id;
        } else {
            return false;
        }
    }
}

if (!function_exists('url_maker')) {
    /**
     * @param title $title it is the title of the post.
     * @param id $id this will be the last paramter.
     */

    function url_maker($title, $id)
    {
        return convert_accented_characters(url_title(limit_text($title, 45, 6), "dash", TRUE)) . '-' . $id;
    }
}

if (!function_exists('limit_text')) {
    /**
     * @param text $text it is the title of the file.
     * @param allowedText $allowedText first characters to check
     * @param limit $limit this will be the world limit
     */

    function limit_text($text, $allowedTest = 50, $limit = 6)
    {
        if (mb_strlen($text) > $allowedTest) {
            if (str_word_count($text, 0) > $limit) {
                $words = str_word_count($text, 2);
                $pos = array_keys($words);
                $text = substr($text, 0, $pos[$limit]);
            }
        } else {
            $text = strip_tags(mb_substr($text, 0, $allowedTest));
        }
        return $text;
    }
}

if (!function_exists('create_password')) {
    /**
     * @param password $password it is the password given by the users
     */
    function create_password($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }
}


if (!function_exists('compare_password')) {
    /**
     * @param password $password it is the password given by the users
     * @param compare_with $compare_with it is the password already in the database
     */
    function compare_password($password, $compare_with)
    {
        return password_verify($password, $compare_with);
    }
}


if (!function_exists('set_custom_header')) {
    /**
     * @param custom_header $custom_header to set css or any other thing to the header
     * @return nothing It will set value only nothing will be return.
     */
    function set_custom_header($custom_header)
    {
        define("CUSTOM_HEADER", $custom_header);
    }
}
if (!function_exists('set_custom_footer')) {
    /**
     * @param custom_footer $custom_footer to set js or any other thing to the footer
     * @return nothing It will set value only nothing will be return.
     */
    function set_custom_footer($custom_footer)
    {
        define("CUSTOM_FOOTER", $custom_footer);
    }
}
if (!function_exists('get_custom_header')) {
    /**
     * @return CUSTOM_HEADER.
     */
    function get_custom_header()
    {
        return defined('CUSTOM_HEADER') ? CUSTOM_HEADER : '';
    }
}
if (!function_exists('get_custom_footer')) {
    /**
     * @return CUSTOM_FOOTER.
     */
    function get_custom_footer()
    {
        return defined('CUSTOM_FOOTER') ? CUSTOM_FOOTER : '';
    }
}
if (!function_exists('is_post')) {

    /**
     * @return boolean.
     */
    function is_post()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return true;
        }
        return false;
    }
}
if (!function_exists('is_get')) {
    /**
     * @return boolean.
     */
    function is_get()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            return true;
        }
        return false;
    }
}


                        
/* End of file common.php */
