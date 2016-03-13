
				<hr>

				<footer class="muted">
					<div><small>Phreeze <?php $this->eprint($this->PHREEZE_VERSION); ?><?php if ($this->PHREEZE_PHAR) { $this->eprint(' (' . basename($this->PHREEZE_PHAR) . ')'); } ?>
					&copy; <?php echo date('Y'); ?> <a href="http://verysimple.com/">verysimple.com</a></small></div>
					<div><small>Licensed for personal and commercial use under the <a href="http://www.gnu.org/licenses/lgpl.html">LGPL</a></small></div>
				</footer>

			</div> <!-- /container -->


			<script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js"></script>
			<script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/backbone.js/1.2.3/backbone-min.js"></script>

			<!-- Le javascript
			================================================== -->
			<!-- Placed at the end of the document so the pages load faster -->
			<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>

			<script type="text/javascript">
				$(document).ready(function() {
					$('.popover-icon').popover({ trigger: "hover", html: true });
				});
			</script>
	</body>
</html>