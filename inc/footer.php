<?php require_once 'init.php'; ?>
        </main>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script
        src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        ></script>
        <!-- Leaflet Routing Machine JS -->
        <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
        <script>
                const RACINE = "<?php echo RACINE; ?>";
                const  node_env = "<?php echo NODE_ENV; ?>";
        </script>
        <script src="<?php echo RACINE.'js/main.js' ?>"></script>
        <script src="<?php echo RACINE.'js/client.js' ?>"></script>
    </body>
</html>