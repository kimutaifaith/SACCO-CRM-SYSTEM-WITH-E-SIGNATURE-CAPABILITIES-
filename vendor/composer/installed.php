<?php return array(
    'root' => array(
        'name' => '__root__',
        'pretty_version' => '1.0.0+no-version-set',
        'version' => '1.0.0.0',
        'reference' => NULL,
        'type' => 'library',
        'install_path' => __DIR__ . '/../../',
        'aliases' => array(),
        'dev' => true,
    ),
    'versions' => array(
        '__root__' => array(
            'pretty_version' => '1.0.0+no-version-set',
            'version' => '1.0.0.0',
            'reference' => NULL,
            'type' => 'library',
            'install_path' => __DIR__ . '/../../',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'phpmailer/phpmailer' => array(
            'pretty_version' => 'v6.9.1',
            'version' => '6.9.1.0',
            'reference' => '039de174cd9c17a8389754d3b877a2ed22743e18',
            'type' => 'library',
            'install_path' => __DIR__ . '/../phpmailer/phpmailer',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'textmagic/sdk' => array(
            'pretty_version' => 'dev-master',
            'version' => 'dev-master',
            'reference' => 'b81cd04df7ab6fd21f0ddd75bf987cf3f65fe9c6',
            'type' => 'library',
            'install_path' => __DIR__ . '/../textmagic/sdk',
            'aliases' => array(
                0 => '9999999-dev',
            ),
            'dev_requirement' => false,
        ),
        'twilio/sdk' => array(
            'pretty_version' => '7.14.0',
            'version' => '7.14.0.0',
            'reference' => '823f2630c14a67e904e1bf63f9b0d7053812d751',
            'type' => 'library',
            'install_path' => __DIR__ . '/../twilio/sdk',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
    ),
);