<?php

    // converts foo_bar & FooBar -> Foo Bar
    function awesome_case($string)
    {
        if (strpos($string, '_') !== false) {
            return ucwords(str_replace("_", " ", $string));
        } else {
            return ucwords(trim(preg_replace('/(?<!\ )[A-Z]/', ' $0', $string)));
        }
    }

    // generates a new random password
    function generate_password($length = null, $only_numbers = null)
    {
        if ($only_numbers) {
            $alphabet = "0123456789";
        }
        else {
            $alphabet = "abcdefghijklmnopqrstuwxyz_ABCDEFGHIJKLMNOPQRSTUWXYZ0123456789@#$.";
        }

        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        $length = $length ? $length : 10;

        for ($i = 0; $i < $length; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }

        return implode($pass); //turn the array into a string
    }

    // show var dump output to web
    function web_dump($var)
    {
        ob_start();
        var_dump($var);
        $output = ob_get_clean();

        // Add formatting
        $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
        $output = '<pre style="background: #FFFEEF; color: #000; border: 1px dashed #888; padding: 10px; margin: 10px 0; text-align: left;">'.$output.'</pre>';

        echo $output;
        exit();
    }

    // convert hours to minutes
    function h2m($hours)
    { 
        $minutes = 0;

        // Split hours and minutes
        if (strpos($hours, ':') !== false) {
            list($hours, $minutes) = explode(':', $hours);
        }

        return $hours * 60 + $minutes;
    }

    // convert time to human readable format
    function human_readable($time)
    {
        $time = explode(":", $time);
        $hours = $time[0];
        $minutes = $time[1];
        $seconds = $time[2];

        $duration = '';

        if ((int) $hours) {
            $duration .= (int) $hours . ' hrs';
        }
        if ((int) $minutes) {
            $duration .= ' ' . (int) $minutes . ' mins';
        }
        if ((int) $seconds) {
            $duration .= ' ' . (int) $seconds . ' sec';
        }

        return $duration;
    }

    function nice_filesize($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            return $bytes . ' bytes';
        } elseif ($bytes == 1) {
            return '1 byte';
        } else {
            return '0 bytes';
        }
    }

    function insert_into_object($obj, $key, $value, $after)
    {
        $new_object = array();

        foreach((array) $obj as $k => $v) {
            $new_object[$k] = $v;

            if ($after == $k){
                $new_object[$key] = $value;
            }
        }

        $new_object = (object) $new_object;
        return $new_object;
    }

    function validateIndianMobileNumber($mobile_no)
    {
        if (preg_match('/^[6789]\d{9}$/', $mobile_no, $matches)) {
            return true;
        } else {
            return false;
        }
    }

    function getAppName()
    {
        $composer = json_decode(file_get_contents(base_path().'/composer.json'), true);

        foreach ((array) data_get($composer, 'autoload.psr-4') as $namespace => $path) {
            foreach ((array) $path as $pathChoice) {
                if (realpath(app_path()) == realpath(base_path().'/'.$pathChoice)) return substr($namespace, 0, -1);
            }
        }

        throw new RuntimeException(__('Unable to detect application namespace'));
    }

    function spell_numbers($number)
    {
        $number = (string) $number;
        $len = strlen($number) - 1;
        $words = ['Zero', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine'];
        $spelling = '';

        for ($x=0; $x<=$len; $x++) {
            $spelling .= $words[$number[$x]];
        }

        $spelling = trim($spelling);
        return $spelling;
    }

    function getImage($path, $width = null, $height = null, $quality = null, $crop = null, $align = null, $sharpen = null)
    {
        if ((in_array(last(explode('.', last(explode('/', $path)))), ["svg", "webp"])) || (!$width && !$height)) {
            $url = asset('storage' . $path);
        } else {
            $url = route('show.website') . '/timthumb.php?src=' . asset('storage' . $path);

            if (isset($width)) {
                $url .= '&w=' . $width; 
            }

            if (isset($height) && $height > 0) {
                $url .= '&h=' .$height;
            }

            if (isset($quality) && $quality) {
                $url .= '&q='.$quality;
            } else {
                $url .= '&q=95';
            }

            if (isset($crop)) {
                $url .= "&zc=".$crop;
            }

            if (isset($align) && $align) {
                $url .= '&a=' . $align; 
            }

            if (isset($sharpen) && $sharpen) {
                $url .= "&s=".$sharpen;
            }
        }

        return $url;
    }
?>
