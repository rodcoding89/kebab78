<?php 
	require_once dirname(__DIR__).'/inc/init.php';
?>
</main>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
        const RACINE = "<?php echo RACINE; ?>";
        const  node_env = "<?php echo NODE_ENV; ?>";
</script>
<script src="<?php echo RACINE.'js/backoffice.js' ?>"></script>
</body>
</html>