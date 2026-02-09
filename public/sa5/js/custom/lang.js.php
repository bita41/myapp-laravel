<script type="text/javascript">
    window.l = {};
    <?php foreach($lang as $k => $v){
        echo  "l['$v'] = '".addslashes(l($v))."';";
    }?>
    var all_languages = <?php echo json_encode(get_languages_codes());?>
</script>