 <ul class="nav nav-pills nav-stacked">
  <li role="presentation" <?php echo (odkazactive("manager/index.php") ? 'class="active"':''); ?>><a href="/manager">Úvod</a></li>
  <li role="presentation" <?php echo (odkazactive("serialy") ? 'class="active"':''); ?>><a href="/manager/serialy">Zoznam seriálov</a></li>
  <li role="presentation" <?php echo (odkazactive("epizody") ? 'class="active"':''); ?>><a href="/manager/epizody">Zoznam epizód</a></li>
</ul>