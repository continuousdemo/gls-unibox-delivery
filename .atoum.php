<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'tool.php';

$runner->enableDebugMode();

if (!isCI()) {
    $stdout = new \mageekguy\atoum\writers\std\out;
    $report = new \mageekguy\atoum\reports\realtime\nyancat;
    $script->addReport($report->addWriter($stdout));
}
