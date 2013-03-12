<?php

	shell_exec("crontab -l > /home/http/crontab");
	shell_exec("echo '*/5 * * * * /usr/share/pear/configure_machines.php' >> /home/http/crontab");
	shell_exec("crontab /home/http/crontab");
?>
