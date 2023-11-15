<div class="col-md-12" style="background:url(https://wallpapers.com/images/featured/camera-95u5meqmzcsp76z9.jpg); opacity:0.7; height:500px; background-size: cover;">
    <div class="note note-bordered note-success" style="text-align:center">
        <h4>Aplikasi ini akan membantu anda dalam menemukan kamera yang sesuai dengan kebutuhan anda</h4>
        <h4>Silahkan mengisi informasi user terlebih dahulu</h4>
        <form action="home/start" role="form" method="post" id="inf" class="form-horizontal form-bordered form-row-stripped">
            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-4 control-label">Nama</label>
                    <div class="col-md-7">
                       	<input type="text" name="inp[u_name]" class="form-control" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label">Usia</label>
                    <div class="col-md-2">
                       	<input type="text" name="inp[u_old]" class="form-control" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label">Familiar terhadap fitur teknis Kamera</label>
                    <div class="col-md-7">
                       	<div class="radio-list" style="text-align:left">
                        	<label class="radio-inline">
                                <input type="radio" name="inp[u_kategori]" value="Y" checked="checked"> 
                                Ya
                            </label>
                        	<label class="radio-inline">
                                <input type="radio" name="inp[u_kategori]" value="S"> 
                                Agak / Sedang
                            </label>
                        	<label class="radio-inline">
                                <input type="radio" name="inp[u_kategori]" value="T"> 
                                Tidak
                            </label>
                    	</div>
                    </div>
                </div>
        	</div>
            <div class="form-actions">
            	<button type="submit" class="btn btn-primary wrap12">Start &nbsp; &nbsp; <i class="fa fa-caret-right"></i></button>
        	</div>   
        </form>       
    </div>
</div>