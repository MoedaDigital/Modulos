<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form-pp-std-uk" data-toggle="tooltip"
				title="<?php echo $button_save; ?>" class="btn btn-primary">
				<i class="fa fa-save"></i>
			</button>
			<a href="<?php echo $cancel; ?>" data-toggle="tooltip"
				title="<?php echo $button_cancel; ?>" class="btn btn-default"><i
				class="fa fa-reply"></i></a>
			</div>
			<h1><?php echo $heading_title; ?></h1>
			<ul class="breadcrumb">
				<?php foreach ($breadcrumbs as $breadcrumb) { ?>
				<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
				<?php } ?>
			</ul>
		</div>
	</div>
	<div class="container-fluid">
		<?php if (isset($error['error_warning'])) { ?>
		<div class="alert alert-danger">
			<i class="fa fa-exclamation-circle"></i> <?php echo $error['error_warning']; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">
				<i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
			</div>
			<div class="panel-body">
				<form action="<?php echo $action; ?>" method="post"
						enctype="multipart/form-data" id="form-pp-std-uk"
						class="form-horizontal">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
							<li><a href="#tab-status" data-toggle="tab"><?php echo $tab_order_status; ?></a></li>
						</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="tab-general">
							<div class="form-group required">
								<label class="col-sm-2 control-label" for="entry-email"><?php echo $entry_email; ?></label>
								<div class="col-sm-10">
									<input type="text" name="moedadigital_email"
									value="<?php echo $moedadigital_email; ?>"
									placeholder="<?php echo $entry_email; ?>" id="entry-email"
									class="form-control" />
									<?php if ($error_email) { ?>
									<div class="text-danger"><?php echo $error_email; ?></div>
									<?php } ?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-debug"><span
									data-toggle="tooltip" title="<?php echo $help_debug; ?>"><?php echo $entry_debug; ?></span></label>
									<div class="col-sm-10">
										<select name="moedadigital_debug" id="input-debug"
										class="form-control">
										<?php if ($moedadigital_debug) { ?>
										<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
										<option value="0"><?php echo $text_disabled; ?></option>
										<?php } else { ?>
										<option value="1"><?php echo $text_enabled; ?></option>
										<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<!-- <div class="form-group">
								<label class="col-sm-2 control-label" for="input-transaction"><?php echo $entry_transaction; ?></label>
								<div class="col-sm-10">
									<select name="moedadigital_transaction" id="input-transaction"
										class="form-control">
					                    <?php if (!$moedadigital_transaction) { ?>
					                    <option value="0" selected="selected"><?php echo $text_authorization; ?></option>
					                    <?php } else { ?>
					                    <option value="0"><?php echo $text_authorization; ?></option>
					                    <?php } ?>
					                    <?php if ($moedadigital_transaction) { ?>
					                    <option value="1" selected="selected"><?php echo $text_sale; ?></option>
					                    <?php } else { ?>
					                    <option value="1"><?php echo $text_sale; ?></option>
					                    <?php } ?>
					                </select>
								</div>
							</div> -->
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-token"><span
									data-toggle="tooltip" title="<?php echo $help_token; ?>"><?php echo $entry_token; ?></span></label>
								<div class="col-sm-10">
									<input type="text" name="moedadigital_token"
									value="<?php echo $moedadigital_token; ?>"
									placeholder="<?php echo $entry_token; ?>" id="input-token"
									class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-lojaapp"><span
									data-toggle="tooltip" title="<?php echo $help_lojaapp; ?>"><?php echo $entry_lojaapp; ?></span></label>
								<div class="col-sm-10">
									<input type="text" name="moedadigital_lojaapp"
									value="<?php echo $moedadigital_lojaapp; ?>"
									placeholder="<?php echo $entry_lojaapp; ?>" id="input-lojaapp"
									class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-checkout-name"><span
									data-toggle="tooltip" title="<?php echo $help_checkout_name; ?>"><?php echo $entry_checkout_name; ?></span></label>
								<div class="col-sm-10">
									<input type="text" name="moedadigital_checkout_name"
									value="<?php echo $moedadigital_checkout_name; ?>"
									placeholder="<?php echo $entry_checkout_name; ?>" id="input-chekout-name"
									class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-aplicacao"><span
									data-toggle="tooltip" title="<?php echo $help_aplicacao; ?>"><?php echo $entry_aplicacao; ?></span></label>
								<div class="col-sm-10">
									<input type="text" name="moedadigital_num_aplicacao"
									value="<?php echo $moedadigital_num_aplicacao; ?>"
									placeholder="<?php echo $entry_aplicacao; ?>" id="input-aplicacao"
									class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
								<div class="col-sm-10">
									<input type="text" name="moedadigital_sort_order"
									value="<?php echo $moedadigital_sort_order; ?>"
									placeholder="<?php echo $entry_sort_order; ?>"
									id="input-sort-order" class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-status"><span
									data-toggle="tooltip" title="<?php echo $help_status; ?>"><?php echo $entry_status; ?></span></label>
									<div class="col-sm-10">
										<select name="moedadigital_status" id="input-status"
										class="form-control">
										<?php if ($moedadigital_status) { ?>
										<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
										<option value="0"><?php echo $text_disabled; ?></option>
										<?php } else { ?>
										<option value="1"><?php echo $text_enabled; ?></option>
										<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="tab-status">
							<div class="form-group">
								<label class="col-sm-2 control-label"
								for="input-canceled-reversal-status"><?php echo $entry_canceled_reversal_status; ?></label>
								<div class="col-sm-10">
									<select name="moedadigital_status_cancelado_id"
										id="input-canceled-reversal-status" class="form-control">
										<?php foreach ($order_statuses as $order_status) { ?>
											<?php if ($order_status['order_status_id'] == $moedadigital_status_cancelado_id) { ?>
												<option
												value="<?php echo $order_status['order_status_id']; ?>"
												selected="selected"><?php echo $order_status['name']; ?></option>
											<?php } else { ?>
												<option
												value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"
								for="input-approved-status"><?php echo $entry_approved_status; ?></label>
								<div class="col-sm-10">
									<select name="moedadigital_status_aprovado_id"
										id="input-approved-status" class="form-control">
										<?php foreach ($order_statuses as $order_status) { ?>
											<?php if ($order_status['order_status_id'] == $moedadigital_status_aprovado_id) { ?>
												<option
												value="<?php echo $order_status['order_status_id']; ?>"
												selected="selected"><?php echo $order_status['name']; ?></option>
											<?php } else { ?>
												<option
												value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"
								for="input-deny-status"><?php echo $entry_deny_status; ?></label>
								<div class="col-sm-10">
									<select name="moedadigital_status_negado_id"
										id="input-deny-status" class="form-control">
										<?php foreach ($order_statuses as $order_status) { ?>
											<?php if ($order_status['order_status_id'] == $moedadigital_status_negado_id) { ?>
												<option
												value="<?php echo $order_status['order_status_id']; ?>"
												selected="selected"><?php echo $order_status['name']; ?></option>
											<?php } else { ?>
												<option
												value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"
								for="input-pending-status"><?php echo $entry_pending_status; ?></label>
								<div class="col-sm-10">
									<select name="moedadigital_status_pendente_id"
										id="input-pending-status" class="form-control">
										<?php foreach ($order_statuses as $order_status) { ?>
											<?php if ($order_status['order_status_id'] == $moedadigital_status_pendente_id) { ?>
												<option
												value="<?php echo $order_status['order_status_id']; ?>"
												selected="selected"><?php echo $order_status['name']; ?></option>
											<?php } else { ?>
												<option
												value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"
								for="input-invalid-status"><?php echo $entry_invalid_status; ?></label>
								<div class="col-sm-10">
									<select name="moedadigital_status_invalido_id"
										id="input-invalid-status" class="form-control">
										<?php foreach ($order_statuses as $order_status) { ?>
											<?php if ($order_status['order_status_id'] == $moedadigital_status_invalido_id) { ?>
												<option
												value="<?php echo $order_status['order_status_id']; ?>"
												selected="selected"><?php echo $order_status['name']; ?></option>
											<?php } else { ?>
												<option
												value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"
								for="input-analise-status">Analise</label>
								<div class="col-sm-10">
									<select name="moedadigital_status_analise_id"
										id="input-analise-status" class="form-control">
										<?php foreach ($order_statuses as $order_status) { ?>
											<?php if ($order_status['order_status_id'] == $moedadigital_status_analise_id) { ?>
												<option
												value="<?php echo $order_status['order_status_id']; ?>"
												selected="selected"><?php echo $order_status['name']; ?></option>
											<?php } else { ?>
												<option
												value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"
								for="input-estorno-status">Estorno</label>
								<div class="col-sm-10">
									<select name="moedadigital_status_estorno_id"
										id="input-estorno-status" class="form-control">
										<?php foreach ($order_statuses as $order_status) { ?>
											<?php if ($order_status['order_status_id'] == $moedadigital_status_estorno_id) { ?>
												<option
												value="<?php echo $order_status['order_status_id']; ?>"
												selected="selected"><?php echo $order_status['name']; ?></option>
											<?php } else { ?>
												<option
												value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"
								for="input-agendado-status">Agendado</label>
								<div class="col-sm-10">
									<select name="moedadigital_status_agendado_id"
										id="input-agendado-status" class="form-control">
										<?php foreach ($order_statuses as $order_status) { ?>
											<?php if ($order_status['order_status_id'] == $moedadigital_status_agendado_id) { ?>
												<option
												value="<?php echo $order_status['order_status_id']; ?>"
												selected="selected"><?php echo $order_status['name']; ?></option>
											<?php } else { ?>
												<option
												value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"
								for="input-chargeback-status"><?php echo $entry_chargeback_status; ?></label>
								<div class="col-sm-10">
									<select name="moedadigital_status_chargeback_id"
										id="input-chargeback-status" class="form-control">
										<?php foreach ($order_statuses as $order_status) { ?>
											<?php if ($order_status['order_status_id'] == $moedadigital_status_chargeback_id) { ?>
												<option
												value="<?php echo $order_status['order_status_id']; ?>"
												selected="selected"><?php echo $order_status['name']; ?></option>
											<?php } else { ?>
												<option
												value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php echo $footer; ?>