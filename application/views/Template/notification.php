<?php ?>
<script src="<?php echo base_url('plugins/notification/jquerytoaster/js/jquery.min.js');?>"></script>

<?php /*?>
<div class="container" role="main">
			<div class="jumbotron">
				<h1>jQuery Toaster Demo</h1>
				<p>
					<button type="button" id="btnstart" class="btn btn-primary btn-lg" role="button">Start</button>
					<button type="button" id="btnstop"  class="btn btn-danger btn-lg" role="button">Stop</button>
				</p>
			</div>

			<div class="row">
				<div class="col-md-12 col-lg-12">
					<div class="panel panel-info">
						<div class="panel-heading">
							<h3 class="panel-title">Make Your Own Toast</h3>
						</div>
						<div class="panel-body">
						  <form id="preptoast" role="form" class="form-inline">
								<div class="form-group">
									<label class="sr-only" for="toastPriority">Toast Priority</label>
									<select class="form-control" id="toastPriority" placeholder="Priority">
										<option>&lt;use default&gt;</option>
										<option value="success">success</option>
										<option value="info">info</option>
										<option value="warning">warning</option>
										<option value="danger">danger</option>
									</select>
								</div>
								<div class="form-group">
									<label class="sr-only" for="toastTitle">Toast Title</label>
									<input type="text" class="form-control" id="toastTitle" placeholder="Title">
								</div>
								<div class="form-group">
									<label class="sr-only" for="toastMessage">Toast Message</label>
									<input type="text" class="form-control" id="toastMessage" placeholder="Message">
								</div>
								<div class="form-group">
									<button type="submit" class="btn btn-primary">Make Toast</button>
								</div>
							</form>
							<br>
							<p>Code:</p>
							<div id="toastCode"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
<?php */?>

<script src="<?php echo base_url('plugins/notification/jquerytoaster/js/bootstrap.min.js');?>"></script>
<script src="<?php echo base_url('plugins/notification/jquerytoaster/js/jquery.toaster.js');?>"></script>
<script>
			var interval;
			var codetmpl = "<code>%codeobj%</code><br><code>%codestr%</code>";
////////////ready////////
			$(document).ready(function (){
				randomToast();
				$('#btnstart').click(start);
				$('#btnstop').click(stop);
				$('#preptoast').on('submit', maketoast); /// Form Submit dat
				start();
			});
////////////ready////////
			function start (){
				if (!interval){
					interval = setInterval(function (){
						randomToast();
					},15000);
				}
				this.blur();
			}

			function stop (){
				if (interval){
					clearInterval(interval);
					interval = false;
				}
				this.blur();
			}

			function randomToast (){
				//var priority = 'success';
				//var title    = 'Success';
				//var message  = 'It worked!';
				var priority = 'danger';
				var title    = 'แจ้งเตียน';
				var message  = 'ระบบมีข้อพิดพลาด!';
				$.toaster({ priority : priority, title : title, message : message });
    
			}
			function maketoast (evt){
				evt.preventDefault();

				var options =
				{
					priority : $('#toastPriority').val() || null, //Form Summt
					title    : $('#toastTitle').val() || null,
					message  : $('#toastMessage').val() || 'A message is required'
				};

				if (options.priority === '<use default>'){
					options.priority = null;
				}

				var codeobj = [];
				var codestr = [];
				var labels = ['message', 'title', 'priority'];
				for (var i = 0, l = labels.length; i < l; i += 1){
					if (options[labels[i]] !== null){
						codeobj.push([labels[i], "'" + options[labels[i]] + "'"].join(' : '));
					}
					codestr.push((options[labels[i]] !== null) ? "'" + options[labels[i]] + "'" : 'null');
				}

				if(codestr[2] === 'null'){
					codestr.pop();
					if (codestr[1] === 'null'){
						codestr.pop();
					}
				}
				codeobj = "$.toaster({ " + codeobj.join(", ") + " });"
				codestr = "$.toaster(" + codestr.join(", ") + ");"
				$('#toastCode').html(codetmpl.replace('%codeobj%', codeobj).replace('%codestr%', codestr));
				$.toaster(options);
			}
		</script>