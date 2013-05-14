<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

$title = $params->get('title', '');
$icon = $params->get('icon', '');
$description = $params->get('description', '');

?>
<div class="feature-item">
<div class="icon"><img src="<?php echo $icon; ?>" alt="<?php echo $title; ?>" /></div>
<h2><?php echo $title; ?></h2>
<p><?php echo $description; ?> </p>

</div>
