 <?php

class Machine_Table_Builder{

	private static $key_tag_map = array(
		'name' => '%NAME%',
		'status' => '%STATUS%',
		'address' => '%ADDRESS%',
		'time_remaining' => '%TIME_REMAINING';
	);

	private static $status_template_map = array(

 	'online' => '
 	<form action="index.php" method="post">
    <input type="hidden" name="page" value="user_index"/>
    <input type="hidden" name="machine" value="%NAME"/>
 	<div class="jumbotron lessPad row">
		<div class="col-md-1 statusPill palette-BC-green clearPadding">
			<span class="glyphicon glyphicon-flash palette-white statusPillIcon"></span>
		</div>
		<div class="col-md-11">
			<h3 class="clearMargin">[%NAME%] &mdash; Status: %STATUS%</h3>
			<p class="padT10"><strong>IP Address:</strong> %ADDRESS%</p>
			<div class="padT10">
				<button type="submit" value="power_off" class="btn btn-warning"><span class="glyphicon glyphicon-save"></span> Power Down</button>
				<button type="submit" value="delete" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span> Delete VM</button>
			</div>
		</div>
      </div>
      </form>
    ',

    'offline' => '
    <form action="index.php" method="post">
    <input type="hidden" name="page" value="user_index"/>
    <input type="hidden" name="machine" value="%NAME"/>
    <div class="jumbotron lessPad row">
		<div class="col-md-1 statusPill palette-BC-medgray clearPadding">
			<span class="glyphicon glyphicon-off palette-darkgray statusPillIcon"></span>
		</div>
		<div class="col-md-11">
			<h3 class="clearMargin">[%NAME%] &mdash; Status: %STATUS%</h3>
			<p class="padT10">This machine is currently offline.</p>
			<div class="padT10">
				<button type="submit" value="power_on" class="btn btn-success"><span class="glyphicon glyphicon-flash"></span> Power On</button>
				<button type="submit" value="delete" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span> Delete VM</button>
			</div>
		</div>
      </div>
      </form>
    ',

    'not_deployed' => '
    <form action="index.php" method="post">
    <input type="hidden" name="page" value="user_index"/>
    <input type="hidden" name="machine" value="%NAME"/>
	<div class="jumbotron lessPad row">
		<div class="col-md-1 statusPill palette-BC-blue clearPadding">
			<span class="glyphicon glyphicon-export palette-white statusPillIcon"></span>
		</div>
		<div class="col-md-11">
			<h3 class="clearMargin">[%NAME%] &mdash; Status: %STATUS%</h3>
			<p class="padT10">This virtual machine has not yet been deployed.</p>
			<div class="padT10">
				<button type="submit" value="deploy" class="btn btn-success"><span class="glyphicon glyphicon-export"></span> Deploy Virtual Machine</button>
			</div>
		</div>
      </div>
      </form>
    '

    );

    private static function build_machine_table($machine){
    	if(is_array($machine) && isset($machine['status'])){
    		$template = self::status_template_map[$machine['status']];
    		foreach(self::key_tag_map as $key => $value){
    			if(isset($machine[$key])){
    				$template = str_replace(%value, $machine[$key], $template);
    			}
    		}
    		return $template;
    	}
    	return "";
    }

}

?>