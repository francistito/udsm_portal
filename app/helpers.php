<?php
if (!function_exists('get_uri')) {
    /**
     * Determine the requested url path name
     *
     * @return string
     */
    function get_uri() {
        return urldecode(
            parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
        );
    }
}

if (!function_exists('asset_url')) {

    /**
     * Return the assets folder url of this application
     *
     * @return string
     */
    function asset_url() {
        return url("/");
    }

}

if (!function_exists('public_url')) {

    /**
     * Return the public url of the application
     *
     * @return type string
     */
    function public_url() {
        return url("/");
    }

}

if (! function_exists('includeRouteFiles')) {

    /**
     * Loops through a folder and requires all PHP files
     * Searches sub-directories as well.
     *
     * @param $folder
     */
    function includeRouteFiles($folder)
    {
        try {
            $rdi = new recursiveDirectoryIterator($folder);
            $it = new recursiveIteratorIterator($rdi);

            while ($it->valid()) {
                if (! $it->isDot() && $it->isFile() && $it->isReadable() && $it->current()->getExtension() === 'php') {
                    require $it->key();
                }

                $it->next();
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}

if (!function_exists('getFallbackLocale')) {

    /**
     * Get the fallback locale
     *
     * @return \Illuminate\Foundation\Application|mixed
     */
    function getFallbackLocale() {
        return config('app.fallback_locale');
    }

}



if (!function_exists('document_url')){
    function documentUrl($doc_id){
        return (new \App\Repositories\System\DocumentResourceRepository())->getDocFullPathUrl($doc_id);

    }
}



/*Base doc path for review attached file*/
if (!function_exists('base_doc_path')) {

    function base_doc_path() {
        /**
         *
         */
        if (test_uri()) {
            return asset('/storage');
        } else {
//            return asset('/public/storage');
            return asset('/storage');
        }
    }

}

if (!function_exists('test_uri')) {
    function test_uri() {
        $uri = get_uri();
        //return (substr($uri, 0, 7) === '/public' Or strtolower(substr($_SERVER['SERVER_SOFTWARE'], 0, 5)) == 'nginx');
        return (strpos($uri, 'public') Or strtolower(substr($_SERVER['SERVER_SOFTWARE'] ?? null, 0, 5)) == 'nginx');


    }
}

if (!function_exists('getLanguageBlock')) {

    /**
     * Get the language block with a fallback
     *
     * @param $view
     * @param array $data
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function getLanguageBlock($view, $data = []) {
        $components = explode("lang", $view);
        $current = $components[0] . "lang." . app()->getLocale() . "." . $components[1];
        $fallback = $components[0] . "lang." . getFallbackLocale() . "." . $components[1];

        if (view()->exists($current)) {
            return view($current, $data);
        } else {
            return view($fallback, $data);
        }
    }

}


if (! function_exists('access')) {
    /**
     * Access (lol) the Access:: facade as a simple function.
     */
    function access()
    {
        return app('access');
    }
}

if (! function_exists('sysdef')) {
    /**
     * Access (lol) the Sysdef:: facade as a simple function.
     */
    function sysdef()
    {
        return app('sysdef');
    }
}

if (! function_exists('code_value')) {
    /**
     * Access (lol) the Code_Value:: facade as a simple function.
     */
    function code_value()
    {
        return app('code_value');
    }
}





if (! function_exists('alert')) {
    /**
     * Access (lol) the Alert:: facade as a simple function.
     */
    function alert()
    {
        return app('alert');
    }
}

if (! function_exists('ad')) {
    /**
     * Access (lol) the Ad:: facade as a simple function.
     */
    function ad()
    {
        return app('ad');
    }
}

if (! function_exists('individual_race_cost')) {
    /**
     * Access (lol) the Ad:: facade as a simple function.
     */
    function individual_race_cost(): int
    {
        return 45000;
    }
}

if (! function_exists('sec_env')) {
    function sec_env($name, $fallback = '') {
        $env = require __DIR__. './../../config/env.php';
        $crypt  = new \Illuminate\Encryption\Encrypter($env["key"]);
        if (isset($_SERVER[$name])) {
            $password = $crypt->decrypt($_SERVER[$name]);
        } else {
            $password = $fallback;
        }
        return $password;
    }
}

/*
 * truncate to n characters of string
 */
if(! function_exists('truncateString')) {
    function truncateString($string, $stringLimit = 300){
        return str_limit($string, $stringLimit, $end = "...");
    }
}

if (! function_exists('str_limit')) {
    /**
     * Limit the number of characters in a string.
     *
     * @param  string  $value
     * @param  int  $limit
     * @param  string  $end
     * @return string
     */
    function str_limit($value, $limit = 100, $end = '...')
    {
        return Str::limit($value, $limit, $end);
    }
}
/*
 * Generate random string with n number of characters, 3 is default, for reference [code_values]
 */
if(! function_exists('randomString')) {
    function randomString($chars = 3) {
        return strtoupper(str_random($chars));
    }
}

if(! function_exists('boldString')) {
    function boldString($string) {
        return '<b>'.$string.'</b>';
    }
}

/**
 * Number format with 2 decimal places with thousands separator 10,000.00
 */

if (! function_exists('number_2_format')) {
    function number_2_format($value)
    {
        return  number_format( $value , 2, '.' , ',' );
    }
}

/* Number format with 1 decimal place with thousands separator 10,000.00*/

if (! function_exists('number_1_format')) {
    function number_1_format($value)
    {
        return  number_format( $value , 1, '.' , ',' );
    }
}

/* Number format with no decimal places with thousands separator 10,000 */

if (! function_exists('number_0_format')) {
    function number_0_format($value)
    {
        return  number_format( $value , 0, '.' , ',' );
    }
}

/*short date format D-M-Y*/
if (! function_exists('short_date_format')) {
    function short_date_format($date)
    {
        return \Carbon\Carbon::parse($date)->format('d-M-Y');
    }
}



/*Standard format date format Y-m-j for storing in the database*/
if (! function_exists('standard_date_format')) {
    function standard_date_format($date)
    {
        return \Carbon\Carbon::parse($date)->format('Y-n-j');
    }
}


/*comparable date format D-M-Y*/
if (! function_exists('comparable_date_format')) {
    function comparable_date_format($date)
    {
        $standard_format = standard_date_format($date);
        return strtotime($standard_format);
    }
}



/*Today's date*/
if (! function_exists('getTodayDate')) {

    function getTodayDate()
    {
        return \Carbon\Carbon::now()->format('Y-n-j');

    }
}

/*NFLIP Launch date*/
if (! function_exists('getLaunchDate')) {
    function getLaunchDate()
    {
        $launch_date = '2018-11-01';
        return \Carbon\Carbon::parse($launch_date)->format('Y-n-j');
    }
}



/* Top Rate*/
if (! function_exists('top_rate')) {
    function top_rate()
    {
        return 5;
    }
}

/*add - after 3 characters, for TIN*/
if (! function_exists('chunk_hyphen')) {
    function chunk_hyphen($string){
        return implode("-", str_split($string, 3));
    }
}

/*capture the first word after dot (.) eg: forum.word.this returns forum*/
if (! function_exists('capture_first')) {
    function capture_first($string){
        $arr = explode(".", $string, 2);
        return $first = $arr[0];
    }
}


if (!function_exists('company_dir')) {
    function company_dir() {
        return public_path() . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'company';
    }
}


if (!function_exists('company_url')) {
    function company_url() {
        return 'storage' . DIRECTORY_SEPARATOR . 'company';
    }
}

if (!function_exists('association_dir')) {
    function association_dir() {
        return public_path() . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'association';
    }
}
if (!function_exists('invitation_dir')) {
    function invitation_dir() {
        return public_path() . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'invitation';
    }
}


if (!function_exists('association_url')) {
    function association_url() {
        return 'storage' . DIRECTORY_SEPARATOR . 'association';
    }
}

if (!function_exists('invitation_url')) {
    function invitation_url() {
        return 'storage' . DIRECTORY_SEPARATOR . 'invitation';
    }
}


if (!function_exists('tempo_per_user_dir')) {
    function tempo_per_user_dir() {
        return public_path() . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'tempo_per_user';
    }
}

//commodity dir folder for storing commodity images
if (!function_exists('commodity_dir')) {
    function commodity_dir() {
        return public_path() . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'commodity';
    }
}

// commodity url for commodities images
if (!function_exists('commodity_url')) {
    function commodity_url() {
        return 'storage' . DIRECTORY_SEPARATOR . 'commodity';
    }
}


//commodity dir folder for storing commodity images
if (!function_exists('parking_lot_dir')) {
    function parking_lot_dir() {
        return public_path() . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'parking_lot';
    }
}

// commodity url for commodities images
if (!function_exists('parking_lot_url')) {
    function parking_lot_url() {
        return 'storage' . DIRECTORY_SEPARATOR . 'parking_lot';
    }
}

//news dir folder for storing news images
if (!function_exists('news_dir')) {
    function news_dir() {
        return public_path() . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'news';
    }
}


// news url for news images
if (!function_exists('news_url')) {
    function news_url() {
        return 'storage' . DIRECTORY_SEPARATOR . 'news';
    }
}

//event dir folder for storing event images
if (!function_exists('event_dir')) {
    function event_dir() {
        return public_path() . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'event';
    }
}

// event url for event images
if (!function_exists('event_url')) {
    function event_url() {
        return 'storage' . DIRECTORY_SEPARATOR . 'event';
    }
}

//forum dir folder for storing forum images
if (!function_exists('forum_dir')) {
    function forum_dir() {
        return public_path() . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'forum';
    }
}
// forum url for forum images
if (!function_exists('forum_url')) {
    function forum_url() {
        return 'storage' . DIRECTORY_SEPARATOR . 'forum';
    }
}

//legislation dir folder for storing legislation files
if (!function_exists('legislation_dir')) {
    function legislation_dir() {
        return public_path() . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'legislation';
    }
}

// legislation url for legislation files
if (!function_exists('legislation_url')) {
    function legislation_url() {
        return 'storage' . DIRECTORY_SEPARATOR . 'legislation';
    }
}

//ads dir folder for storing ads banners
if (!function_exists('ads_dir')) {
    function ads_dir() {
        return public_path() . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'ads';
    }
}

// ads url for ads files
if (!function_exists('ads_url')) {
    function ads_url() {
        return 'storage' . DIRECTORY_SEPARATOR . 'ads';
    }
}

//tender dir
if (!function_exists('tender_dir')) {
    function tender_dir() {
        return public_path() . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'tender';
    }
}


// tender  url for support documents files
if (!function_exists('tender_url')) {
    function tender_url() {
        return 'storage' . DIRECTORY_SEPARATOR . 'tender';
    }
}

//offer dir
if (!function_exists('offer_dir')) {
    function offer_dir() {
        return public_path() . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'offer';
    }
}


// offer  url for support documents files
if (!function_exists('offer_url')) {
    function offer_url() {
        return 'storage' . DIRECTORY_SEPARATOR . 'offer';
    }
}

//offer enquiry dir
if (!function_exists('offer_enquiry_dir')) {
    function offer_enquiry_dir() {
        return public_path() . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'offer_enquiry';
    }
}


// offer  url for support documents files
if (!function_exists('offer_enquiry_url')) {
    function offer_enquiry_url() {
        return 'storage' . DIRECTORY_SEPARATOR . 'offer_enquiry';
    }
}

//tender application dir
if (!function_exists('tender_application_dir')) {
    function tender_application_dir() {
        return public_path() . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'tender_application';
    }
}


// tender application url for  support documents files
if (!function_exists('tender_application_url')) {
    function tender_application_url() {
        return 'storage' . DIRECTORY_SEPARATOR . 'tender_application';
    }
}



if (!function_exists('max_logo_size_string')) {
    function max_logo_size_string() {
        return  sysdef()->data('MAXLOS') . 'MB';
    }
}

if (!function_exists('max_doc_size_string')) {
    function max_doc_size_string() {
        return  sysdef()->data('MAXCDS'). 'MB';
    }
}

if (!function_exists('max_commodity_photo_size')) {
    function max_commodity_photo_size() {
        return   sysdef()->data('MAXCPS');//MB
    }
}

if (!function_exists('max_commodity_photo_uploads')) {
    function max_commodity_photo_uploads() {
        return  sysdef()->data('MAXCPU');
    }
}

if (!function_exists('max_company_document_file_size')) {
    function max_company_document_file_size() {
        return  sysdef()->data('MAXCDS');//MB
    }
}

if (!function_exists('max_event_photo_size')) {
    function max_event_photo_size() {
        return sysdef()->data('MAXEPS');//MB
    }
}

if (!function_exists('max_event_photo_uploads')) {
    function max_event_photo_uploads() {
        return  sysdef()->data('MAXEPU');
    }
}


if (!function_exists('max_news_photo_size')) {
    function max_news_photo_size() {
        return  sysdef()->data('MAXNPS');//MB
    }
}

if (!function_exists('max_news_photo_uploads')) {
    function max_news_photo_uploads() {
        return  sysdef()->data('MAXNPU');
    }
}


if (!function_exists('max_legislation_file_size')) {
    function max_legislation_file_size() {
        return  sysdef()->data('MAXLEFS');//MB
    }
}

if (!function_exists('max_ads_file_size')) {
    function max_ads_file_size() {
        return  sysdef()->data('MAXLEFS');//MB
    }
}

if (!function_exists('max_support_document_size')) {
    function max_support_document_size() {
        return  sysdef()->data('MAXLEFS');//MB
    }
}



if (!function_exists('max_legislation_file_uploads')) {
    function max_legislation_file_uploads() {
        return  sysdef()->data('MAXLEFU');
    }
}

if (!function_exists('max_support_document_uploads')) {
    function max_support_document_uploads() {
        return  sysdef()->data('MAXLEFU');
    }
}

if (!function_exists('mb_to_kb')) {
    function mb_to_kb() {
        return  1024;
    }
}



/*Ad horizontal px*/
if (!function_exists('ad_horizontal_width')) {
    function ad_horizontal_width() {
        return  728;
    }
}

/*Ad horizontal height*/
if (!function_exists('ad_horizontal_height')) {
    function ad_horizontal_height() {
        return  100;
    }
}


/*Ad vertical px*/
if (!function_exists('ad_vertical_width')) {
    function ad_vertical_width() {
        return  200;
    }
}

/*Ad horizontal height*/
if (!function_exists('ad_vertical_height')) {
    function ad_vertical_height() {
        return  200;
    }
}


if( ! function_exists('unique_random') ){
    /**
     *
     * Generate a unique random string of characters
     * uses str_random() helper for generating the random string
     *
     * @param     $table - name of the table
     * @param     $col - name of the column that needs to be tested
     * @param int $chars - length of the random string
     *
     * @return string
     */
    function unique_random($table, $col, $chars = 6){

        $unique = false;

        // Store tested results in array to not test them again
        $tested = [];

        do{

            // Generate random string of characters
            $random = randomStrings($chars);

            // Check if it's already testing
            // If so, don't query the database again
            if( in_array($random, $tested) ){
                continue;
            }

            // Check if it is unique in the database
            $count = \Illuminate\Support\Facades\DB::table($table)->where($col, '=', $random)->count();

            // Store the random character in the tested array
            // To keep track which ones are already tested
            $tested[] = $random;

            // String appears to be unique
            if( $count == 0){
                // Set unique to true to break the loop
                $unique = true;
            }

            // If unique is still false at this point
            // it will just repeat all the steps until
            // it has generated a random string of characters

        }
        while(!$unique);


        return $random;
    }

    /**
     * Generate a unique random string of characters of format [A-Z] [0-9]
     */
    if( ! function_exists('randomStrings') ) {
        function randomStrings($length = 6)
        {
            $str = "";
            $characters = array_merge(range('A', 'Z'), range('0', '9'));
            $max = count($characters) - 1;
            for ($i = 0; $i < $length; $i++) {
                $rand = mt_rand(0, $max);
                $str .= $characters[$rand];
            }
            return $str;
        }
    }


    if( ! function_exists('randomStrings') ) {
        function randomStrings($length = 6)
        {
            $str = "";
            $characters = array_merge(range('A', 'Z'), range('0', '9'));
            $max = count($characters) - 1;
            for ($i = 0; $i < $length; $i++) {
                $rand = mt_rand(0, $max);
                $str .= $characters[$rand];
            }
            return $str;
        }
    }




}
