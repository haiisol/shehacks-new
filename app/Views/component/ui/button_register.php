<?php 
$web = $this->main_model->get_admin_web(); 
$text = isset($text) ? $text : 'Daftar Sekarang';
$class = isset($class) ? $class : 'btn btn-hover-arrow-right';
$attributes = isset($attributes) ? $attributes : '';
?>

<?php if ($this->session->userdata('logged_in_front') == FALSE && $web['register_button'] == 'true') { ?>
    <a 
        href="<?php echo base_url();?>register" 
        class="<?php echo $class; ?>" 
        <?php echo $attributes; ?>
    >
        <span><?php echo $text; ?></span>
    </a>
<?php } ?>