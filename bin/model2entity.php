<?php

$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('.'));
foreach ($it as $f) {
    if (realpath($f) === realpath(__FILE__)) {
        continue;
    }

    if (substr($f, -4) !== '.php') {
        continue;
    }

    $php = file_get_contents($f);

    if (strpos($php, 'ModUser_Model_UserInterface') === false) {
        continue;
    }

    $php = strtr(
        $php,
        array(
            'ModUser_Model_UserInterface' => 'UserInterface',
        )
    );
    $php = "<?php\n\nuse Maniple\\ModUser\\Entity\\UserInterface;\n" . substr($php, 6); // skip <?php\n
    file_put_contents($f, $php);
}
