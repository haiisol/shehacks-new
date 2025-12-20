<?php 
$text = isset($text) ? $text : 'Daftar Sekarang';
$class = isset($class) ? $class : 'btn btn-hover-arrow-right';
$attributes = isset($attributes) ? $attributes : '';
?>

<?php if ($logged_in_front === FALSE && $register_button) { ?>
    <a 
        href="<?php echo base_url();?>register" 
        class="<?php echo $class; ?>" 
        <?php echo $attributes; ?>
    >
        <span><?php echo $text; ?></span>
    </a>
<?php } ?>