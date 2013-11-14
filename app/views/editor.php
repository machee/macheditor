<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?=$title?> - <?=$project?></title>
        <script data-main="<?=URL::to('scripts/editor')?>"
            src="<?=URL::to('scripts/vendor/requirejs/require.js')?>"></script>
        <script>
            define("config", function () {return {
                basePath: "<?=URL::to('')?>/",
                project:  "<?=$project?>",
                file:     "<?=$file?>",
                aceMode:  "<?=$aceMode?>"
            };});
        </script>
        <link href="<?=URL::to('css/ide.css')?>" rel="stylesheet">
        <link href="<?=URL::to('css/files.css')?>" rel="stylesheet">
        <link href="<?=URL::to('css/editor.css')?>" rel="stylesheet">
        <meta name="viewport" id="viewport" content="user-scalable=1">
    </head>
    <body>
        <?=$files?>
        
        <div id="toolbar">
            <a id="save">Save</a>
        </div>
        
        <div id="editor"><?=$content?></div>
        
    </body>
</html>
