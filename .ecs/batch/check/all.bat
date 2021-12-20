:: Run easy-coding-standard (ecs) via this batch file inside your IDE e.g. PhpStorm (Windows only)
:: Install inside PhpStorm the  "Batch Script Support" plugin
cd..
cd..
cd..
cd..
cd..
cd..
:: src
vendor\bin\ecs check vendor/respinar/contao-voucher-bundle/src --config vendor/respinar/contao-voucher-bundle/.ecs/config/default.php
:: tests
vendor\bin\ecs check vendor/respinar/contao-voucher-bundle/tests --config vendor/respinar/contao-voucher-bundle/.ecs/config/default.php
:: legacy
vendor\bin\ecs check vendor/respinar/contao-voucher-bundle/src/Resources/contao --config vendor/respinar/contao-voucher-bundle/.ecs/config/legacy.php
:: templates
vendor\bin\ecs check vendor/respinar/contao-voucher-bundle/src/Resources/contao/templates --config vendor/respinar/contao-voucher-bundle/.ecs/config/template.php
::
cd vendor/respinar/contao-voucher-bundle/.ecs./batch/check
