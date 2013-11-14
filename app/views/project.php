<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?=$project?></title>
        <script data-main="<?=URL::to('scripts/shared/files')?>"
            src="<?=URL::to('scripts/vendor/requirejs/require.js')?>"></script>
        <script>
            define("config", function () {return {
                basePath: "<?=URL::to('')?>/",
                project:  "<?=$project?>",
            };});
        </script>
        <link href="<?=URL::to('css/ide.css')?>" rel="stylesheet">
        <link href="<?=URL::to('css/files.css')?>" rel="stylesheet">
    </head>
    <body>
        <?=$files?>
    </body>
</html>
