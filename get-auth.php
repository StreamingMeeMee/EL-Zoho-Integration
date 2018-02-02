<?php
#--------------------------------
require_once("inc/emp_base.php");
require_once('vendor/autoload.php');

use CristianPontes\ZohoCRMClient\ZohoCRMClient;


#---------------------------------------
function zohoGetAuthToken()
#---------------------------------------
{
$ret = '';

$username = "chris@elmediagroup.com";
#$password = "ifP8IBeBpLu9";
$password = 'music@212';
$appname = 'EMG-Platform';

    $param = 'SCOPE=ZohoCRM/crmapi&EMAIL_ID=' . $username . '&PASSWORD=' . $password . '&DISPLAY_NAME=' . $appname;

    $ch = curl_init("https://accounts.zoho.com/apiauthtoken/nb/create");

    SysMsg(MSG_INFO, 'url:[' . $param . ']');
 
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
    $result = curl_exec($ch);
    /*This part of the code below will separate the Authtoken from the result.
        Remove this part if you just need only the result*/
    $anArray = explode("\n",$result);
    $authToken = explode("=",$anArray['2']);
    $cmp = strcmp($authToken['0'],"AUTHTOKEN");
    SysMsg(MSG_INFO, '[' . $anArray['2']. ']');

    curl_close( $ch );

    if ($cmp == 0) {
        SysMsg(MSG_INFO, "Created Authtoken is : ".$authToken['1'] );
        $ret = $authToken['1'];
    } else {
        $ret = '';
    }

    return $ret;
}

#----------------------------------
function zohoGetRecords( $auth )
#----------------------------------
{
$ret = '';

    $client = new ZohoCRMClient('Accounts', $auth );

    $records = $client->getRecords()
#    ->selectColumns('All')
#    ->sortBy('Last Name')->sortAsc()
    ->toIndex( 200 )
    ->request();

    return $records;
}

#----------------------------------
# M A I N
#----------------------------------

$auth = '9e789b8de510462e5d6d574c0b7a6a96';     # 2018-jan-31 17:23 ET

    setDebugON();

    if( !isset( $auth ) ) {
        $auth = zohoGetAuthToken();
    }

#    $json = zohoGetRecords( $auth );
#    $records = json_decode( $json );
    $records = zohoGetRecords( $auth );

    print_r( $records );
?>
