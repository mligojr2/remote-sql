<?php if (validation_errors()): ?>
	<div class="row errors">
		<div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-4 col-lg-4 col-lg-offset-4">
			<div class="alert alert-danger">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<ul>
					<?php echo validation_errors('<li>'); ?>
				</ul>
			</div>
		</div>
	</div>
<?php endif ?>
<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="jumbotron brand-intro query-con">
			<div class="container">
				<h1>Query Section</h1>
				<p>In this section/page of <code>Remote SQL Server</code> just type the query in the text-field that is below. And then click on submit button and just watch the magic. If the query runs ok then the result will be appeared below the query input field.</p>
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-9 col-lg-10 col-lg-offset-1">
						<?php $attribute = array('role' => 'form', 'class' => 'query-form'); echo form_open('rsql/run_query', $attribute); ?>			
							<div class="form-group">
								<label class="col-sm-2 control-label typeQueryHere">Type Query Here:</label>
								<div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
									<textarea id="code" class="form-control textarea" rows="3"></textarea>
								</div>
								<input type="hidden" name="query" id="queryIns">
							</div>
							<?php $attribute = array('class' => 'btn btn-primary pull-right', 'id' => 'query-submit', 'value' => 'Run'); echo form_submit($attribute); ?>
						<?php echo form_close(); ?>
					</div>
				</div>
				<?php if (isset($result_sets)): ?>
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-9 col-lg-10 col-lg-offset-1 query-result-con">
							<div role="tabpanel">
							    <ul class="nav nav-tabs" role="tablist">
							    	<?php foreach ($result_sets as $key => $result): ?>
							        	<li role="presentation" <?php if ($key == 0): ?> class="active" <?php endif ?>>
							            	<a href="#_tab<?php echo $key ?>" aria-controls="_tab<?php echo $key ?>" role="tab" data-toggle="tab">Result # <?php echo $key+1 ?> (<?php echo $result['total_fields'].'x'.$result['num_rows']; ?>)</a>
							        	</li>
							        <?php endforeach; ?>
							    </ul>
							    <div class="tab-content">
							    	<?php foreach ($result_sets as $key => $result): ?>
							        	<div role="tabpanel" class="tab-pane <?php if ($key == 0): ?> active <?php endif ?>" id="_tab<?php echo $key ?>">
							        		<table class="table">
							        			<?php if (isset($result['fields_name']) AND isset($result['query_result'])): ?>
													<thead>
														<tr>
															<?php foreach ($result['fields_name'] as $field_name): ?>
																<th><?php echo $field_name; ?></th>
															<?php endforeach ?>
														</tr>
													</thead>
													<tbody>
												    	<?php foreach ($result['query_result'] as $_result): ?>
														    <?php echo '<tr>'; ?>
														    	<?php foreach ($result['fields_name'] as $field_name): ?>
														    		<?php echo '<td>' . $_result[$field_name] . '</td>'; ?>
														    	<?php endforeach ?>
												    		<?php echo '</tr>'; ?>
												    	<?php endforeach ?>
											    	</tbody>
												<?php endif ?>
											</table>
							        	</div>
							    	<?php endforeach; ?>
							    </div>
							</div>
						</div>
					</div>
				<?php endif ?>
			</div>
		</div>
	</div>
</div>
<script>
	
	window.onload = function() {
        var mime = 'text/x-mariadb';

        if (window.location.href.indexOf('mime=') > -1) {
         	mime = window.location.href.substr(window.location.href.indexOf('mime=') + 5);
        }

        window.editor = CodeMirror.fromTextArea(document.getElementById('code'), {
          	mode: mime,
         	theme: "monokai",
          	styleActiveLine: true,
          	indentWithTabs: true,
          	smartIndent: true,
          	lineNumbers: true,
          	matchBrackets : true,
          	autofocus: true,
          	extraKeys: {"Tab": "autocomplete"},
          	hintOptions: {tables: {
            	users: {name: null, score: null, birthDate: null},
            	countries: {name: null, population: null, size: null}
          	}}
        });
    };

	var dbConnected = "<?php echo $this->session->flashdata('db_connected'); ?>";
	if (dbConnected) {
		$.toast({
			heading: 'Ohh WOW!:',
		    text: dbConnected,
		    showHideTransition: 'slide',
		    icon: 'success',
		    position: {
		        left: 600,
		        top: 120
		    },
		    stack: false
		});
	};
	var use_not_allowed = "<?php echo $this->session->flashdata('use_not_allowed'); ?>";
	if (use_not_allowed) {
		$.toast({
			heading: 'Query Error:',
		    text: use_not_allowed,
		    showHideTransition: 'slide',
		    icon: 'error',
		    position: {
		        left: 600,
		        top: 120
		    },
		    stack: false, 
		    hideAfter: 5000
		});
	};  
	var dbDropped = "<?php echo $this->session->flashdata('db_dropped'); ?>";
	if (dbDropped) {
		$.toast({
		    heading: 'Query Result:',
		    text: dbDropped,
		    showHideTransition: 'fade',
		    icon: 'success',
		    position: {
		        left: 600,
		        top: 120
		    },
		    stack: false
		});
	}; 

	$('#query-submit').on('click', function() {
		var query = editor.getValue();
		console.log($('#queryIns').val(query));
	});
</script>