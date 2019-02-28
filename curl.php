<?php
// Sends a GET request and returns TRUE or FALSE regarding the request success.
function curl_urlExist($url)
{
    $curl = curl_init($url);
    if ($curl === false)
    {
            return false;
    }
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_FAILONERROR, true);  // this works
    curl_setopt($curl, CURLOPT_HTTPHEADER, Array("User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.15) Gecko/20080623 Firefox/2.0.0.15") ); // request as if Firefox
    curl_setopt($curl, CURLOPT_NOBODY, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, false);
    $connectable = curl_exec($curl);
    ##print $connectable;
    curl_close($curl);
    return $connectable;
}
?>