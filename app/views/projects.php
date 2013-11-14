<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Projects</title>
        <link href="<?=URL::to('css/ide.css')?>" rel="stylesheet">
    </head>
    <body>
        <h1>Projects</h1>
         <?php foreach ($projects as $project) { ?>
            <a href="<?=URL::to($project)?>"><?=$project?></a><br>
        <?php } ?>
    </body>
</html>
