<ul class="list-group">
<?php
  foreach($reports as $k => $v)
  {
?>
    <li class="list-group-item"><a href="<?=$v['url']?>" type="button" class="btn btn-light btn-lg btn-block"><?=$v['name']?></a></li>
<?php
  }
?>
</ul>