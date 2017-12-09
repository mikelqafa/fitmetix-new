<?php 

return array(

/** set your paypal credential **/

'client_id' =>'AUwbWcO4y0COffyh-_PUR18QyDCGeS6l6sWrNPsFdRImjowTBuCxswNyCcjCJiUSubUvBsWYhUrQUv95',

'secret' => 'ECGWslYUWthW7TlGQ-4QLew34O4STZWYiVE6UlJhtEw0JUIpxPIxXRLsSgXQiXm4jPOQ5xQ_42NibVWO',

/**

* SDK configuration 

*/

'settings' => array(

/**

* Available option 'sandbox' or 'live'

*/

'mode' => 'sandbox',

'currency' => 'USD',

/**

* Specify the max request time in seconds

*/

'http.ConnectionTimeOut' => 1000,

/**

* Whether want to log to a file

*/

'log.LogEnabled' => true,

/**

* Specify the file that want to write on

*/

'log.FileName' => storage_path() . '/logs/paypal.log',

/**

* Available option 'FINE', 'INFO', 'WARN' or 'ERROR'

*

* Logging is most verbose in the 'FINE' level and decreases as you

* proceed towards ERROR

*/

'log.LogLevel' => 'FINE'

),

);

 ?>