<?php

function gpxv3_ConfigOptions() {
    /*
    * Can use these later for 100tick, FPS, specifics to server, etc.
    */
    # Should return an array of the module options for each product - maximum of 24
    $configarray = array(
        "Server Name" => array( "Type" => "text", "Size" => "10", "Description" => "Internal Name of the Server, e.g. cs_s" ) 
    );
    return $configarray;
}

function gpxv3_CreateAccount($params) {
    # ** The variables listed below are passed into all module functions **
    $serviceid = $params["serviceid"]; # Unique ID of the product/service in the WHMCS Database
    $pid = $params["pid"]; # Product/Service ID
    $producttype = $params["producttype"]; # Product Type: hostingaccount, reselleraccount, server or other
    $domain = $params["domain"];
    $username = $params["username"];
    $password = $params["password"];
    $clientsdetails = $params["clientsdetails"]; # Array of clients details - firstname, lastname, email, country, etc...
    $customfields = $params["customfields"]; # Array of custom field values for the product
    $configoptions = $params["configoptions"]; # Array of configurable option values for the product

    # Product module option settings from ConfigOptions array above
    $configoption1  = $params["configoption1"];
    $configoption2  = $params["configoption2"];
    $configoption3  = $params["configoption3"];
    $configoption4  = $params["configoption4"];

    # Additional variables if the product/service is linked to a server
    $server             = $params["server"]; # True if linked to a server
    $serverid           = $params["serverid"];
    $serverip           = $params["serverip"];
    $serverusername     = $params["serverusername"];
    $serverpassword     = $params["serverpassword"];
    $serveraccesshash   = $params["serveraccesshash"];
    $serversecure       = $params["serversecure"]; # If set, SSL Mode is enabled in the server config
    
    ####################################################################

    // Server
    $postfields['class']        = 'servers';
    $postfields['action']       = 'create';
    $postfields['key']          = $params["serveraccesshash"];
    
    // Client-Specific
    $postfields['first_name']   = $clientsdetails['firstname'];
    $postfields['last_name']    = $clientsdetails['lastname'];
    $postfields['company']      = $clientsdetails['companyname'];
    $postfields['email']        = $clientsdetails['email'];
    $postfields['address1']     = $clientsdetails['address1'];
    $postfields['address2']     = $clientsdetails['address2'];
    $postfields['city']         = $clientsdetails['city'];
    $postfields['state']        = $clientsdetails['state'];
    $postfields['zip']          = $clientsdetails['postcode'];
    $postfields['country']      = $clientsdetails['country'];
    $postfields['phone']        = $clientsdetails['phonenumber'];

    // Server-Specific
    $postfields['id']                 = $params["serviceid"];
    $postfields['username']           = $params["customfields"]["Username"];
    $postfields['password']           = $params["customfields"]["Password"];
    $postfields['rcon_password']      = $params["customfields"]["Rcon Password"];
    $postfields['private_password']   = $params["customfields"]["Private Password"];
    $postfields['game']               = $params["configoption1"];
    $postfields['slots']              = $params["configoptions"]["Game Slots"]; // Returns 12 etc
    $postfields['is_private']         = $params["configoptions"]["Private Server"]; // Returns 1 or 0

    // Connect to GPX API
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $params["serverip"]);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
    $data = curl_exec($ch);
    curl_close($ch);

    // Success
    if($data == 'success')
    {
        return 'success';
    }
    else
    {
        return $data;
    }
}

function gpxv3_TerminateAccount($params) {
# ** The variables listed below are passed into all module functions **
    $serviceid = $params["serviceid"]; # Unique ID of the product/service in the WHMCS Database
    $pid = $params["pid"]; # Product/Service ID
    $producttype = $params["producttype"]; # Product Type: hostingaccount, reselleraccount, server or other
    $domain = $params["domain"];
    $username = $params["username"];
    $password = $params["password"];
    $clientsdetails = $params["clientsdetails"]; # Array of clients details - firstname, lastname, email, country, etc...
    $customfields = $params["customfields"]; # Array of custom field values for the product
    $configoptions = $params["configoptions"]; # Array of configurable option values for the product

    # Product module option settings from ConfigOptions array above
    $configoption1  = $params["configoption1"];
    $configoption2  = $params["configoption2"];
    $configoption3  = $params["configoption3"];
    $configoption4  = $params["configoption4"];

    # Additional variables if the product/service is linked to a server
    $server             = $params["server"]; # True if linked to a server
    $serverid           = $params["serverid"];
    $serverip           = $params["serverip"];
    $serverusername     = $params["serverusername"];
    $serverpassword     = $params["serverpassword"];
    $serveraccesshash   = $params["serveraccesshash"];
    $serversecure       = $params["serversecure"]; # If set, SSL Mode is enabled in the server config
    
    ####################################################################
    
    // POST Fields
    $postfields['class']          = 'servers';
    $postfields['action']         = 'delete';
    $postfields['key']            = $params["serveraccesshash"];
    $postfields['id']             = $params["serviceid"];
    
    // Connect to GPX API
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $params["serverip"]);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
    $data = curl_exec($ch);
    curl_close($ch);
    
    // Success
    if(trim($data) == 'success')
    {
        return 'success';
    }
    // Failure
    else
    {
        return $data;
    }
}

function gpxv3_SuspendAccount($params) {
# ** The variables listed below are passed into all module functions **
    $serviceid = $params["serviceid"]; # Unique ID of the product/service in the WHMCS Database
    $pid = $params["pid"]; # Product/Service ID
    $producttype = $params["producttype"]; # Product Type: hostingaccount, reselleraccount, server or other
    $domain = $params["domain"];
    $username = $params["username"];
    $password = $params["password"];
    $clientsdetails = $params["clientsdetails"]; # Array of clients details - firstname, lastname, email, country, etc...
    $customfields = $params["customfields"]; # Array of custom field values for the product
    $configoptions = $params["configoptions"]; # Array of configurable option values for the product

    # Product module option settings from ConfigOptions array above
    $configoption1  = $params["configoption1"];
    $configoption2  = $params["configoption2"];
    $configoption3  = $params["configoption3"];
    $configoption4  = $params["configoption4"];

    # Additional variables if the product/service is linked to a server
    $server             = $params["server"]; # True if linked to a server
    $serverid           = $params["serverid"];
    $serverip           = $params["serverip"];
    $serverusername     = $params["serverusername"];
    $serverpassword     = $params["serverpassword"];
    $serveraccesshash   = $params["serveraccesshash"];
    $serversecure       = $params["serversecure"]; # If set, SSL Mode is enabled in the server config
    
    ####################################################################
    
    // POST Fields
    $postfields['class']          = 'servers';
    $postfields['action']         = 'suspend';
    $postfields['key']            = $params["serveraccesshash"];
    $postfields['id']             = $params["serviceid"];

    // Connect to GPX API
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $params["serverip"]);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
    $data = curl_exec($ch);
    curl_close($ch);
    
    // Success
    if(trim($data) == 'success')
    {
        return 'success';
    }
    // Failure
    else
    {
        return $data;
    }
}

function gpxv3_UnsuspendAccount($params) {
# ** The variables listed below are passed into all module functions **
    $serviceid = $params["serviceid"]; # Unique ID of the product/service in the WHMCS Database
    $pid = $params["pid"]; # Product/Service ID
    $producttype = $params["producttype"]; # Product Type: hostingaccount, reselleraccount, server or other
    $domain = $params["domain"];
    $username = $params["username"];
    $password = $params["password"];
    $clientsdetails = $params["clientsdetails"]; # Array of clients details - firstname, lastname, email, country, etc...
    $customfields = $params["customfields"]; # Array of custom field values for the product
    $configoptions = $params["configoptions"]; # Array of configurable option values for the product

    # Product module option settings from ConfigOptions array above
    $configoption1  = $params["configoption1"];
    $configoption2  = $params["configoption2"];
    $configoption3  = $params["configoption3"];
    $configoption4  = $params["configoption4"];

    # Additional variables if the product/service is linked to a server
    $server             = $params["server"]; # True if linked to a server
    $serverid           = $params["serverid"];
    $serverip           = $params["serverip"];
    $serverusername     = $params["serverusername"];
    $serverpassword     = $params["serverpassword"];
    $serveraccesshash   = $params["serveraccesshash"];
    $serversecure       = $params["serversecure"]; # If set, SSL Mode is enabled in the server config
    
    ####################################################################
    
    // POST Fields
    $postfields['class']          = 'servers';
    $postfields['action']         = 'unsuspend';
    $postfields['key']            = $params["serveraccesshash"];
    $postfields['id']             = $params["serviceid"];

    // Connect to GPX API
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $params["serverip"]);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
    $data = curl_exec($ch);
    curl_close($ch);
    
    // Success
    if(trim($data) == 'success')
    {
        return 'success';
    }
    // Failure
    else
    {
        return $data;
    }
}

/*
 * None of these are needed yet
 * 
function gpxv3_ChangePassword($params) {

	# Code to perform action goes here...

    if ($successful) {
		$result = "success";
	} else {
		$result = "Error Message Goes Here...";
	}
	return $result;

}

function gpxv3_ChangePackage($params) {

	# Code to perform action goes here...

    if ($successful) {
		$result = "success";
	} else {
		$result = "Error Message Goes Here...";
	}
	return $result;

}

function gpxv3_ClientArea($params) {

    # Output can be returned like this, or defined via a clientarea.tpl template file (see docs for more info)

	$code = '<form action="http://'.$serverip.'/controlpanel" method="post" target="_blank">
<input type="hidden" name="user" value="'.$params["username"].'" />
<input type="hidden" name="pass" value="'.$params["password"].'" />
<input type="submit" value="Login to Control Panel" />
<input type="button" value="Login to Webmail" onClick="window.open(\'http://'.$serverip.'/webmail\')" />
</form>';
	return $code;

}

function gpxv3_AdminLink($params) {

	$code = '<form action=\"http://'.$params["serverip"].'/controlpanel" method="post" target="_blank">
<input type="hidden" name="user" value="'.$params["serverusername"].'" />
<input type="hidden" name="pass" value="'.$params["serverpassword"].'" />
<input type="submit" value="Login to Control Panel" />
</form>';
	return $code;

}

function gpxv3_LoginLink($params) {

	echo "<a href=\"http://".$params["serverip"]."/controlpanel?gotousername=".$params["username"]."\" target=\"_blank\" style=\"color:#cc0000\">login to control panel</a>";

}

function gpxv3_reboot($params) {

	# Code to perform reboot action goes here...

    if ($successful) {
		$result = "success";
	} else {
		$result = "Error Message Goes Here...";
	}
	return $result;

}

function gpxv3_shutdown($params) {

	# Code to perform shutdown action goes here...

    if ($successful) {
		$result = "success";
	} else {
		$result = "Error Message Goes Here...";
	}
	return $result;

}

function gpxv3_ClientAreaCustomButtonArray() {
    $buttonarray = array(
	 "Reboot Server" => "reboot",
	);
	return $buttonarray;
}

function gpxv3_AdminCustomButtonArray() {
    $buttonarray = array(
	 "Reboot Server" => "reboot",
	 "Shutdown Server" => "shutdown",
	);
	return $buttonarray;
}

function gpxv3_extrapage($params) {
    $pagearray = array(
     'templatefile' => 'example',
     'breadcrumb' => ' > <a href="#">Example Page</a>',
     'vars' => array(
        'var1' => 'demo1',
        'var2' => 'demo2',
     ),
    );
	return $pagearray;
}

function gpxv3_UsageUpdate($params) {

	$serverid = $params['serverid'];
	$serverhostname = $params['serverhostname'];
	$serverip = $params['serverip'];
	$serverusername = $params['serverusername'];
	$serverpassword = $params['serverpassword'];
	$serveraccesshash = $params['serveraccesshash'];
	$serversecure = $params['serversecure'];

	# Run connection to retrieve usage for all domains/accounts on $serverid

	# Now loop through results and update DB

	foreach ($results AS $domain=>$values) {
        update_query("tblhosting",array(
         "diskused"=>$values['diskusage'],
         "dislimit"=>$values['disklimit'],
         "bwused"=>$values['bwusage'],
         "bwlimit"=>$values['bwlimit'],
         "lastupdate"=>"now()",
        ),array("server"=>$serverid,"domain"=>$values['domain']));
    }

}

function gpxv3_AdminServicesTabFields($params) {

    $result = select_query("mod_customtable","",array("serviceid"=>$params['serviceid']));
    $data = mysql_fetch_array($result);
    $var1 = $data['var1'];
    $var2 = $data['var2'];
    $var3 = $data['var3'];
    $var4 = $data['var4'];

    $fieldsarray = array(
     'Field 1' => '<input type="text" name="modulefields[0]" size="30" value="'.$var1.'" />',
     'Field 2' => '<select name="modulefields[1]"><option>Val1</option</select>',
     'Field 3' => '<textarea name="modulefields[2]" rows="2" cols="80">'.$var3.'</textarea>',
     'Field 4' => $var4, # Info Output Only
    );
    return $fieldsarray;

}

function gpxv3_AdminServicesTabFieldsSave($params) {
    update_query("mod_customtable",array(
        "var1"=>$_POST['modulefields'][0],
        "var2"=>$_POST['modulefields'][1],
        "var3"=>$_POST['modulefields'][2],
    ),array("serviceid"=>$params['serviceid']));
}
*/

?>
