<div class="portlet light">
    <div class="portlet-title">
        <div class="caption caption-md">
            <i class="icon-globe font-green-sharp"></i>
            <span class="caption-subject font-green-sharp bold uppercase">Form Pertanyaan</span>
        </div>
    </div>
    <div class="portlet-body form">
        <form role="form" id="frm_fr" class="form-horizontal form-bordered form-row-stripped">
            <div class="form-body">
            	<h3 class="form-section">Silahkan pilih sesuai kebutuhan anda</h3>
				<div class="form-group">
                    <label class="col-md-3 control-label">Budget</label>
                    <div class="col-md-9">
						<div class="col-md-1" style="margin-top:8px">Antara</div>
                    	<div class="col-md-5" style="width:150px" >
                        	<input type="text" name="inp[price][start]" class="form-control col-md-5 mask_currency" />
                        </div>
                        <div class="col-md-1" style="margin-top:8px">Sampai</div>
                        <div class="col-md-5" style="width:150px">
                        	<input type="text" name="inp[price][end]" class="form-control col-md-5 mask_currency" />
                        </div>
						</br>
						</br>
                        <span class="help-block">Kosongkan pilihan budget jika tidak mempunyai budget khusus</span>
                    </div>
                </div>
				
				<!--<div class="form-group">
                    <label class="col-md-3 control-label">Budget</label>
                    <div class="col-md-9">
                    	<div class="col-md-5">
                        	<input type="text" name="inp[price][start]" class="form-control col-md-5 mask_currency" />
                        </div>
                        <div class="col-md-1" style="margin-top:8px">Sampai</div>
                        <div class="col-md-5">
                        	<input type="text" name="inp[price][end]" class="form-control col-md-5 mask_currency" />
                        </div>
                        <span class="help-block">Kosongkan pilihan budget jika tidak mempunyai budget khusus</span>
                    </div>
                </div>-->
				
                <div class="form-group">
                    <label class="col-md-3 control-label">Merk Kamera</label>
                    <div class="col-md-9">
                        <div class="checkbox-list">
                            <?php foreach($brand as $b) { ?>
                                <label class="checkbox-inline col-md-3" style="margin-left: 0; padding: 0; text-align: center;">
                                    <input type="checkbox" name="inp[brand][]" id="" value="<?= $b ?>">
                                    <img src="cdn/icons/<?= $b ?>.svg" style="width: 100px; height: 75px; object-fit: contain;" alt="<?= str_replace('_', ' ', $b) ?>"> 
                                </label>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Jenis Kamera</label>
                    <div class="col-md-9">
                        <div class="checkbox-list">
                        	<?php foreach($type as $id => $name) { ?>
                            <label class="checkbox-inline col-md-6" style="margin-left:0px; padding-left:0px">
                            <input type="checkbox" name="inp[type][]" id="" value="<?= $id ?>"> <img src="cdn/icons/<?= $id ?>.png" height="40"> &nbsp; <?= $name ?>
                            </label>
                            <?php } ?>
                            <span class="help-block">Jangan dicentang jika ingin memilih semua jenis Kamera</span>
                        </div>
                    </div>
                </div>
            	<h3 class="form-section">Silahkan pilih satu atau lebih pilihan dibawah ini sesuai kebutuhan anda</h3>
                <?php foreach($fr as $f) { ?>                    
                <div class="form-group">
                <?php
                    $this->load->driver('crs');
                    $com = $this->crs->ontology->get_fr_comment($f);
                    if (isset($com[0])) $c = $com[0];
                    else $c = '';
                    ?>

                    <label for="inputEmail1" class="col-md-3 control-label" title="<?= $c ?>">
                        <div style="display: flex; flex-direction: column; align-items: center;">
                            <img src="cdn/icons/<?= $f ?>.png" style="width: 60px; height: 40px; object-fit: contain;" alt="<?= $c ?>">
                            <?= str_replace('_', ' ', $f) ?>
                        </div>
                    </label>
                    <div class="col-md-9">
                        <div class="radio-list">
                        	<label class="radio-inline">
                                <input type="radio" name="inp[usermodel][<?= $f ?>]" value="fh" checked="checked"> 
                                Wajib Dipenuhi
                            </label>
                        	<label class="radio-inline">
                                <input type="radio" name="inp[usermodel][<?= $f ?>]" value="fs"> 
                                Lebih baik dipenuhi
                            </label>
                        	<label class="radio-inline">
                                <input type="radio" name="inp[usermodel][<?= $f ?>]" value="fx"> 
                                Tidak Diperlukan
                            </label>
                        </div>
                    </div>
                </div>
            	<?php } ?>
            </div>
            <div class="form-actions">
                <button type="button" onclick="recommend_u1()" class="btn purple">Rekomendasikan &nbsp; <i class="fa fa-long-arrow-right"></i></button>
            </div>
        </form>
    </div>
</div>