<?php
function multiexplode($delimiters,$string) {
    
    $ready = str_replace($delimiters, $delimiters[0], $string);
    $launch = explode($delimiters[0], $ready);
    return  $launch;
}

$re_src = multiexplode(array('/','?'), $_SERVER['REQUEST_URI']);

$devmode = getenv('DEV_MODE');

switch($re_src[1])
{
    case 'favicon.ico':
		break;
	case '01234':
		{
			switch($re_src[2])
			{
				case 'db_session_test':
					if($devmode == 1)
					{
					}
					break;
				default:
					break;
			}
		}
		break;
	default:
        require 'datastoreTest.php';
//@		require 'front/404notfound.php';
		break;
}
