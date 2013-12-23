 <?php

class Machine_Table_Builder{

	private static $key_tag_map = array(
		'vm_name' => '%NAME%',
		'vm_state' => '%STATUS%',
		'vm_port' => '%ADDRESS%',
		'vm_expires' => '%TIME_REMAINING%'
	);

	private static $status_template_map = array(

 	'online' => '
 	<form action="index.php" method="post">
 	<input type="hidden" name="machine" value="%NAME%"/>
    <input type="hidden" name="page" value="%PAGE%"/>
    <input type="hidden" name="student" value="%STUDENT%"/>
 	<div class="jumbotron lessPad row">
		<div class="col-md-1 statusPill palette-BC-green clearPadding">
			<span class="glyphicon glyphicon-flash palette-white statusPillIcon"></span>
		</div>
		<div class="col-md-11">
			<h3 class="clearMargin">%NAME% &mdash; Status: %STATUS%</h3>
			<p class="padT10"><strong>Port:</strong> %ADDRESS%</p>
			<p class="padT10"><strong>Time Remaining:</strong> %TIME_REMAINING%</p>
			<div class="padT10">
				<button type="submit" name="action" value="power_off" class="btn btn-warning"><span class="glyphicon glyphicon-save"></span> Power Down</button>
				<button type="submit" name="action" value="delete" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span> Delete VM</button>
			</div>
		</div>
      </div>
      </form>
    ',

    'offline' => '
    <form action="index.php" method="post">
    <input type="hidden" name="page" value="%PAGE%"/>
    <input type="hidden" name="student" value="%STUDENT%"/>
    <input type="hidden" name="machine" value="%NAME%"/>
    <div class="jumbotron lessPad row">
		<div class="col-md-1 statusPill palette-BC-medgray clearPadding">
			<span class="glyphicon glyphicon-off palette-darkgray statusPillIcon"></span>
		</div>
		<div class="col-md-11">
			<h3 class="clearMargin">%NAME% &mdash; Status: %STATUS%</h3>
			<p class="padT10"><strong>Port:</strong> %ADDRESS%</p>
			<p class="padT10">This machine is currently offline.</p>
			<div class="padT10">
				<button type="submit" name="action" value="power_on" class="btn btn-success"><span class="glyphicon glyphicon-flash"></span> Power On</button>
				<button type="submit" name="action" value="delete" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span> Delete VM</button>
			</div>
		</div>
      </div>
      </form>
    ',

    'not_deployed' => '
    <form action="index.php" method="post">
    <input type="hidden" name="page" value="%PAGE%"/>
    <input type="hidden" name="student" value="%STUDENT%"/>
    <input type="hidden" name="machine" value="%NAME%"/>
	<div class="jumbotron lessPad row">
		<div class="col-md-1 statusPill palette-BC-blue clearPadding">
			<span class="glyphicon glyphicon-export palette-white statusPillIcon"></span>
		</div>
		<div class="col-md-11">
			<h3 class="clearMargin">%NAME% &mdash; Status: %STATUS%</h3>
			<p class="padT10"><strong>Port:</strong> %ADDRESS%</p>
			<p class="padT10">This virtual machine has not yet been deployed.</p>
			<div class="padT10">
				<button type="submit" name="action" value="deploy" class="btn btn-success"><span class="glyphicon glyphicon-export"></span> Deploy Virtual Machine</button>
			</div>
		</div>
      </div>
      </form>
    ',

    'transition' => '
    <form action="index.php" method="post">
	<div class="jumbotron lessPad row">
		<div class="col-md-1 statusPill palette-BC-blue clearPadding">
			<span class="glyphicon glyphicon-export palette-white statusPillIcon"></span>
		</div>
		<div class="col-md-11">
			<h3 class="clearMargin">%NAME% &mdash; Status: %STATUS%</h3>
			<p class="padT10"><strong>Port:</strong> %ADDRESS%</p>
		</div>
      </div>
      </form>
    '

    );

    public static function build($machine, $student, $page_source){
    	if(is_array($machine) && isset($machine['vm_state'])){
    		$template = (array_key_exists($machine['vm_state'], self::$status_template_map)) ?
    			self::$status_template_map[$machine['vm_state']] : self::$status_template_map['transition'];
    		foreach(self::$key_tag_map as $key => $value){
    			if(isset($machine[$key])){
    				$template = str_replace($value, $machine[$key], $template);
    			}
    		}
    		$template = str_replace('%PAGE%', $page_source, $template);
    		$template = str_replace('%STUDENT%', $student, $template);
    		return $template;
    	}
    	return "";
    }

}

?>