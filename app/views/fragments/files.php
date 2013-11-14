<div id="files" class="directory">
 <?php
 $dirFunc = function($files, $dirFunc) use ($project) {
  foreach ($files as $file => $data) {
   if (is_array($data)) { ?>
    <div>
        <a class="context"></a>
        <a class="dirname" data-file="<?=$data['path']?>"><?=$file?></a>
        <div class="directory">
            <?php $dirFunc($data['files'], $dirFunc);?>
        </div>
    </div>
   <?php } else { ?>
    <a class="context"></a>
    <a href="<?=URL::to("$project/$data")?>"><?=$file?></a>
   <?php }
  }
 };
 $dirFunc($files, $dirFunc);
 ?>
</div>

<div id="menu">
    <a id="menuDismiss">Dismiss</a>
    
    <a id="rename">Move / Rename</a>
    <a id="copy"  >Copy</a>
    <a id="delete">Delete</a>

    <div id="dirOptions">
        <a>New File</a>
        <a>New Folder</a>
    </div>
</div>
