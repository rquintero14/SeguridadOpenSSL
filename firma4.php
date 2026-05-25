    <?PHP

    $config = array('config'=>'C:\wamp64\bin\php\php8.3.28\extras\ssl\openssl.cnf');
    $pkey = openssl_pkey_new($config);
    $crs = openssl_csr_new('MyCRS',$pkey,$config);

    ?>